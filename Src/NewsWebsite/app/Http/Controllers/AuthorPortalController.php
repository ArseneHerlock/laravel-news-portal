<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Language;
use App\Models\Media;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthorPortalController extends Controller
{
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        $postsQuery = Post::where('user_id', $user->id);

        $stats = [
            'total_posts' => (clone $postsQuery)->count(),
            'published_posts' => (clone $postsQuery)->where('status', 'published')->count(),
            'pending_posts' => (clone $postsQuery)->where('status', 'pending')->count(),
            'draft_posts' => (clone $postsQuery)->where('status', 'draft')->count(),
            'rejected_posts' => (clone $postsQuery)->where('status', 'rejected')->count(),
            'total_views' => (int) (clone $postsQuery)->sum('views_count'),
            'total_comments' => Comment::whereHas('post', fn ($q) => $q->where('user_id', $user->id))->count(),
        ];

        $recentPosts = Post::with('category')
            ->where('user_id', $user->id)
            ->latest()
            ->take(8)
            ->get();

        $recentComments = Comment::with(['post', 'user'])
            ->whereHas('post', fn ($q) => $q->where('user_id', $user->id))
            ->latest()
            ->take(6)
            ->get();

        return view('portal.author.dashboard', compact('user', 'stats', 'recentPosts', 'recentComments'));
    }

    public function posts(Request $request): View
    {
        $user = $request->user();
        $statusFilter = $request->input('status', 'all');
        $search = trim((string) $request->input('search', ''));

        $query = Post::with(['category', 'language', 'tags'])
            ->where('user_id', $user->id);

        if (in_array($statusFilter, ['published', 'pending', 'draft', 'rejected'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where('title', 'like', "%{$search}%");
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        $baseQuery = Post::where('user_id', $user->id);
        $counts = [
            'all' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];

        return view('portal.author.posts', compact('posts', 'counts', 'statusFilter', 'search'));
    }

    public function create(): View
    {
        $categories = Category::where('status', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $languages = Language::where('status', true)->get();

        return view('portal.author.create-post', [
            'post' => null,
            'categories' => $categories,
            'tags' => $tags,
            'languages' => $languages,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'language_id' => ['required', 'exists:languages,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'post_type' => ['nullable', 'in:article,video,audio'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,pending,published'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $user = $request->user();

        // Enforce editorial workflow: Authors submit as 'draft' or 'pending'; only Admins can directly set 'published'
        $status = $validated['status'];
        if (! $user->isAdmin() && $status === 'published') {
            $status = 'pending';
        }

        $slug = $this->generateUniqueSlug(($validated['slug'] ?? null) ?: $validated['title']);

        $imagePath = 'assets/img/post-tech-1.svg';
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $storedPath = $file->store('uploads/posts', 'public');
            $imagePath = 'storage/'.$storedPath;

            Media::create([
                'user_id' => $user->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $imagePath,
                'file_size' => $file->getSize(),
                'file_type' => 'image',
                'mime_type' => $file->getMimeType() ?? 'image/jpeg',
                'alt_text' => $validated['title'],
            ]);
        }

        $hasVideo = ! empty($validated['video_url']);

        $post = Post::create([
            'language_id' => $validated['language_id'],
            'user_id' => $user->id,
            'category_id' => $validated['category_id'],
            'post_type' => $hasVideo ? 'video' : ($validated['post_type'] ?? 'article'),
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image' => $imagePath,
            'video_type' => $hasVideo ? 'embed' : 'none',
            'video_url' => $validated['video_url'] ?? null,
            'is_featured' => $user->isAdmin() ? $request->boolean('is_featured') : false,
            'is_breaking' => $user->isAdmin() ? $request->boolean('is_breaking') : false,
            'is_slider' => $user->isAdmin() ? $request->boolean('is_slider') : false,
            'is_recommended' => $request->boolean('is_recommended'),
            'status' => $status,
            'published_at' => $status === 'published' ? now() : null,
            'meta_title' => ($validated['meta_title'] ?? null) ?: $validated['title'],
            'meta_description' => ($validated['meta_description'] ?? null) ?: ($validated['summary'] ?? ''),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
        ]);

        if (! empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        $message = $status === 'pending'
            ? 'Article submitted to the Editorial Queue (Pending Admin Approval).'
            : ($status === 'published' ? 'Article published live!' : 'Article saved as Draft.');

        return redirect()->route($user->isAdmin() ? 'admin.posts' : 'author.posts')
            ->with('success', $message);
    }

    public function edit(Request $request, Post $post): View
    {
        $user = $request->user();
        if (! $user->isAdmin() && $post->user_id !== $user->id) {
            abort(403, 'You can only edit your own articles.');
        }

        $categories = Category::where('status', true)->orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();
        $languages = Language::where('status', true)->get();

        return view('portal.author.create-post', compact('post', 'categories', 'tags', 'languages'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && $post->user_id !== $user->id) {
            abort(403, 'You can only update your own articles.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'language_id' => ['required', 'exists:languages,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'post_type' => ['nullable', 'in:article,video,audio'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'status' => ['required', 'in:draft,pending,published'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'featured_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $status = $validated['status'];
        if (! $user->isAdmin() && $status === 'published') {
            $status = $post->status === 'published' ? 'published' : 'pending';
        }

        $slug = $this->generateUniqueSlug(($validated['slug'] ?? null) ?: $validated['title'], $post->id);

        $imagePath = $post->image;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $storedPath = $file->store('uploads/posts', 'public');
            $imagePath = 'storage/'.$storedPath;
        }

        $hasVideo = ! empty($validated['video_url']);

        $post->update([
            'language_id' => $validated['language_id'],
            'category_id' => $validated['category_id'],
            'post_type' => $hasVideo ? 'video' : ($validated['post_type'] ?? 'article'),
            'title' => $validated['title'],
            'slug' => $slug,
            'summary' => $validated['summary'] ?? Str::limit(strip_tags($validated['content']), 180),
            'content' => $validated['content'],
            'image' => $imagePath,
            'video_type' => $hasVideo ? 'embed' : 'none',
            'video_url' => $validated['video_url'] ?? null,
            'is_featured' => $user->isAdmin() ? $request->boolean('is_featured') : $post->is_featured,
            'is_breaking' => $user->isAdmin() ? $request->boolean('is_breaking') : $post->is_breaking,
            'is_slider' => $user->isAdmin() ? $request->boolean('is_slider') : $post->is_slider,
            'is_recommended' => $request->boolean('is_recommended'),
            'status' => $status,
            'rejection_reason' => $status === 'pending' ? null : $post->rejection_reason,
            'published_at' => ($status === 'published' && ! $post->published_at) ? now() : $post->published_at,
            'meta_title' => ($validated['meta_title'] ?? null) ?: $validated['title'],
            'meta_description' => ($validated['meta_description'] ?? null) ?: ($validated['summary'] ?? ''),
            'meta_keywords' => $validated['meta_keywords'] ?? null,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()->route($user->isAdmin() ? 'admin.posts' : 'author.posts')
            ->with('success', 'Article "'.$post->title.'" updated successfully.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();
        if (! $user->isAdmin() && $post->user_id !== $user->id) {
            abort(403);
        }

        $post->delete();

        return back()->with('success', 'Article deleted.');
    }

    private function generateUniqueSlug(string $source, ?int $ignoreId = null): string
    {
        $base = Str::slug($source) ?: 'article-'. strtolower(Str::random(5));
        $slug = $base;
        $counter = 2;

        while (
            Post::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$counter++;
        }

        return $slug;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AuthorProfile;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Language;
use App\Models\Newsletter;
use App\Models\Post;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminPortalController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'pending_posts' => Post::where('status', 'pending')->count(),
            'total_views' => (int) Post::sum('views_count'),
            'total_users' => User::count(),
            'total_authors' => User::whereIn('role_id', [1, 2])->count(),
            'total_comments' => Comment::count(),
            'pending_comments' => Comment::where('status', 'pending')->count(),
            'subscribers_count' => Newsletter::where('status', true)->count(),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
        ];

        $pendingPosts = Post::with(['author', 'category'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get();

        $recentPosts = Post::with(['author', 'category'])
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::withCount('posts')->orderBy('order')->get();
        $tags = Tag::withCount('posts')->get();
        $recentComments = Comment::with(['post', 'user'])->latest()->take(8)->get();
        $contactMessages = ContactMessage::latest()->take(6)->get();
        $users = User::with(['role', 'authorProfile'])->withCount('posts')->latest()->get();

        return view('portal.admin.dashboard', compact(
            'stats',
            'pendingPosts',
            'recentPosts',
            'categories',
            'tags',
            'recentComments',
            'contactMessages',
            'users'
        ));
    }

    public function posts(Request $request): View
    {
        $statusFilter = $request->input('status', 'all');
        $categoryFilter = $request->input('category_id');
        $search = trim((string) $request->input('search', ''));

        $query = Post::with(['author', 'category', 'language', 'tags']);

        if (in_array($statusFilter, ['published', 'pending', 'draft', 'rejected'], true)) {
            $query->where('status', $statusFilter);
        }

        if ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('author', fn ($aq) => $aq->where('name', 'like', "%{$search}%"));
            });
        }

        $posts = $query->latest()->paginate(10)->withQueryString();

        $counts = [
            'all' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'pending' => Post::where('status', 'pending')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'rejected' => Post::where('status', 'rejected')->count(),
        ];

        $categories = Category::orderBy('order')->get();
        $tags = Tag::orderBy('name')->get();

        return view('portal.admin.posts', compact(
            'posts',
            'counts',
            'statusFilter',
            'categoryFilter',
            'search',
            'categories',
            'tags'
        ));
    }

    public function moderatePost(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:published,pending,draft,rejected'],
            'rejection_reason' => ['nullable', 'string', 'max:1000'],
            'is_featured' => ['nullable', 'boolean'],
            'is_breaking' => ['nullable', 'boolean'],
        ]);

        $status = $validated['status'];

        $post->update([
            'status' => $status,
            'published_at' => ($status === 'published' && ! $post->published_at) ? now() : $post->published_at,
            'rejection_reason' => $status === 'rejected'
                ? ($validated['rejection_reason'] ?: 'Requires editorial revision before publication.')
                : null,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : $post->is_featured,
            'is_breaking' => $request->has('is_breaking') ? $request->boolean('is_breaking') : $post->is_breaking,
        ]);

        return back()->with('success', 'Post "'.$post->title.'" status updated to '.strtoupper($status).'.');
    }

    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'in:publish,draft,pending,reject,delete'],
            'ids' => ['required', 'string'],
        ]);

        $ids = array_filter(array_map('intval', explode(',', $validated['ids'])));
        if (empty($ids)) {
            return back()->with('error', 'No articles selected.');
        }

        $query = Post::whereIn('id', $ids);

        match ($validated['action']) {
            'publish' => $query->update(['status' => 'published', 'published_at' => now()]),
            'draft' => $query->update(['status' => 'draft']),
            'pending' => $query->update(['status' => 'pending']),
            'reject' => $query->update(['status' => 'rejected']),
            'delete' => $query->delete(),
        };

        return back()->with('success', 'Bulk action ('.$validated['action'].') applied to '.count($ids).' article(s).');
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'parent_id' => ['nullable', 'exists:categories,id'],
            'order' => ['nullable', 'integer'],
        ]);

        $defaultLang = Language::where('is_default', true)->first() ?? Language::first();
        $baseSlug = Str::slug(($validated['slug'] ?? null) ?: $validated['name']) ?: 'category-'.strtolower(Str::random(4));
        $slug = $baseSlug;
        $counter = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter++;
        }

        Category::create([
            'language_id' => $defaultLang->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'show_on_menu' => $request->boolean('show_on_menu', true),
            'show_on_home' => $request->boolean('show_on_home', true),
            'status' => true,
        ]);

        return back()->with('success', 'Category "'.$validated['name'].'" created.');
    }

    public function destroyCategory(Category $category): RedirectResponse
    {
        if ($category->posts()->exists()) {
            return back()->with('error', 'Cannot delete a category that still contains articles. Reassign its articles first.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function storeTag(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $defaultLang = Language::where('is_default', true)->first() ?? Language::first();
        $slug = Str::slug($validated['name']) ?: 'tag-'.strtolower(Str::random(4));

        Tag::updateOrCreate(
            ['slug' => $slug],
            ['language_id' => $defaultLang->id, 'name' => $validated['name']]
        );

        return back()->with('success', 'Tag "'.$validated['name'].'" saved.');
    }

    public function destroyTag(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return back()->with('success', 'Tag removed.');
    }

    public function moderateComment(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,pending,rejected'],
        ]);

        $comment->update(['status' => $validated['status']]);

        return back()->with('success', 'Comment status updated to '.$validated['status'].'.');
    }

    public function destroyComment(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    public function updateUserRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'status' => ['required', 'in:active,pending,banned'],
        ]);

        // Prevent demoting or banning oneself
        if ($user->id === $request->user()->id && ((int) $validated['role_id'] !== 1 || $validated['status'] !== 'active')) {
            return back()->with('error', 'You cannot demote or ban your own administrator account.');
        }

        $user->update($validated);

        if ((int) $validated['role_id'] === 2) {
            AuthorProfile::updateOrCreate(
                ['user_id' => $user->id],
                ['is_approved' => true, 'designation' => $user->authorProfile?->designation ?? 'Contributing Editor']
            );
        }

        return back()->with('success', 'Updated role & status for '.$user->name.'.');
    }

    public function markMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Contact inquiry marked as read.');
    }
}

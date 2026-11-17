<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\CustomPage;
use App\Models\Language;
use App\Models\Newsletter;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicNewsController extends Controller
{
    public function index(): View
    {
        $postsQuery = Post::with(['category', 'author', 'tags'])->published();

        $featuredPosts = (clone $postsQuery)->where('is_featured', true)->latest('published_at')->take(5)->get();
        if ($featuredPosts->isEmpty()) {
            $featuredPosts = (clone $postsQuery)->latest('published_at')->take(5)->get();
        }

        $breakingPosts = (clone $postsQuery)->where('is_breaking', true)->latest('published_at')->take(5)->get();
        $latestPosts = (clone $postsQuery)->latest('published_at')->take(8)->get();
        $popularPosts = (clone $postsQuery)->orderByDesc('views_count')->take(6)->get();
        $recommendedPosts = (clone $postsQuery)->where('is_recommended', true)->latest('published_at')->take(6)->get();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->where('status', true)
            ->orderBy('order')
            ->get();

        $tags = Tag::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();
        $bannerAd = Advertisement::where('status', true)->first();

        return view('public.index', compact(
            'featuredPosts',
            'breakingPosts',
            'latestPosts',
            'popularPosts',
            'recommendedPosts',
            'categories',
            'tags',
            'bannerAd'
        ));
    }

    public function blogs(Request $request): View
    {
        $query = Post::with(['category', 'author', 'tags'])->published();

        $activeCategory = null;
        if ($request->filled('category')) {
            $activeCategory = Category::where('slug', $request->string('category'))->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        $activeTag = null;
        if ($request->filled('tag')) {
            $activeTag = Tag::where('slug', $request->string('tag'))->first();
            if ($activeTag) {
                $query->whereHas('tags', fn ($q) => $q->where('tags.id', $activeTag->id));
            }
        }

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->orderByDesc('views_count');
        } else {
            $query->latest('published_at')->latest('id');
        }

        $posts = $query->paginate(6)->withQueryString();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->where('status', true)
            ->orderBy('order')
            ->get();
        $tags = Tag::all();
        $recentPosts = Post::with('category')->published()->latest('published_at')->take(4)->get();

        return view('public.blogs', compact(
            'posts',
            'categories',
            'tags',
            'recentPosts',
            'activeCategory',
            'activeTag',
            'search',
            'sort'
        ));
    }

    public function show(Request $request, ?string $slug = null): View
    {
        $postQuery = Post::with(['category', 'author.authorProfile', 'tags', 'approvedComments.user', 'approvedComments.replies.user']);

        if ($slug) {
            $post = $postQuery->where('slug', $slug)->firstOrFail();
        } else {
            $post = $postQuery->published()->latest('published_at')->firstOrFail();
        }

        // Only allow viewing unpublished posts if the user is the author or an admin
        if ($post->status !== 'published') {
            $user = $request->user();
            if (! $user || (! $user->isAdmin() && $user->id !== $post->user_id)) {
                abort(404);
            }
        } else {
            $post->increment('views_count');
        }

        $previousPost = Post::published()->where('id', '<', $post->id)->orderByDesc('id')->first();
        $nextPost = Post::published()->where('id', '>', $post->id)->orderBy('id')->first();
        $relatedPosts = Post::with(['category', 'author'])
            ->published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->where('status', true)
            ->orderBy('order')
            ->get();
        $recentPosts = Post::with('category')->published()->where('id', '!=', $post->id)->latest('published_at')->take(4)->get();

        $isBookmarked = $request->user()
            ? Bookmark::where('user_id', $request->user()->id)->where('post_id', $post->id)->exists()
            : false;

        return view('public.blog-details', compact(
            'post',
            'previousPost',
            'nextPost',
            'relatedPosts',
            'categories',
            'recentPosts',
            'isBookmarked'
        ));
    }

    public function author(?string $username = null): View
    {
        if ($username) {
            $author = User::with('authorProfile')->where('username', $username)->firstOrFail();
        } else {
            $author = User::with('authorProfile')->whereIn('role_id', [1, 2])->firstOrFail();
        }

        $posts = Post::with(['category', 'tags'])
            ->published()
            ->where('user_id', $author->id)
            ->latest('published_at')
            ->paginate(6);

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->where('status', true)
            ->orderBy('order')
            ->get();
        $recentPosts = Post::with('category')->published()->latest('published_at')->take(4)->get();

        return view('public.author', compact('author', 'posts', 'categories', 'recentPosts'));
    }

    public function about(): View
    {
        $teamMembers = User::with(['authorProfile', 'role'])
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
            ->whereIn('role_id', [1, 2])
            ->get();

        $stats = [
            'published_posts' => Post::published()->count(),
            'total_views' => Post::published()->sum('views_count'),
            'categories_count' => Category::where('status', true)->count(),
            'authors_count' => $teamMembers->count(),
        ];

        return view('public.about', compact('teamMembers', 'stats'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $subject = $validated['subject'] ?: 'General Inquiry'.($validated['phone'] ? ' ('.$validated['phone'].')' : '');

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $subject,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Thank you! Your message has been sent to our editorial team.');
    }

    public function subscribeNewsletter(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:150'],
        ]);

        Newsletter::updateOrCreate(
            ['email' => $validated['email']],
            ['token' => Str::random(32), 'status' => true]
        );

        return back()->with('success', 'You have successfully subscribed to the Zaira Daily Newsletter!');
    }

    public function storeComment(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'min:3', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $user = $request->user();
        // Auto-approve comments from Admin/Author or active verified users for smooth interaction, while allowing Admin moderation
        $status = ($user->isAdmin() || $user->isAuthor() || $user->status === 'active') ? 'approved' : 'pending';

        Comment::create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'comment' => $validated['comment'],
            'status' => $status,
        ]);

        return back()->with('success', $status === 'approved'
            ? 'Your comment has been published!'
            : 'Your comment has been submitted and is awaiting moderation.');
    }

    public function toggleBookmark(Request $request, Post $post): RedirectResponse
    {
        $user = $request->user();
        $existing = Bookmark::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($existing) {
            $existing->delete();

            return back()->with('success', 'Article removed from your reading list.');
        }

        Bookmark::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        return back()->with('success', 'Article saved to your reading bookmarks!');
    }

    public function privacyPolicy(): View
    {
        $page = CustomPage::where('slug', 'privacy-policy')->first();

        return view('public.privacy-policy', compact('page'));
    }

    public function switchLanguage(Request $request, string $code): RedirectResponse
    {
        $lang = Language::where('code', $code)->where('status', true)->first();
        if ($lang) {
            $request->session()->put('locale', $lang->code);
            $request->session()->put('direction', $lang->direction);
        }

        return back()->with('success', 'Language preference updated to '.($lang->name ?? strtoupper($code)).'.');
    }

    public function rssFeed(): Response
    {
        $posts = Post::with(['category', 'author'])->published()->latest('published_at')->take(20)->get();

        $xml = view('public.rss', compact('posts'))->render();

        return response($xml, 200, ['Content-Type' => 'application/rss+xml; charset=UTF-8']);
    }
}

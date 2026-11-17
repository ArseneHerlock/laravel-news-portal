<?php

namespace Tests\Feature;

use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\Newsletter;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ZairaNewsPortalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_public_homepage_blog_archive_search_and_detail_pages_work(): void
    {
        // Homepage
        $home = $this->get(route('home'));
        $home->assertStatus(200)
            ->assertSee('Breaking News')
            ->assertSee('Latest Stories')
            ->assertDontSee('zaira.themedox.com');

        // Blog listing & Category filtering
        $blogs = $this->get(route('blogs.index', ['category' => 'technology']));
        $blogs->assertStatus(200)
            ->assertSee('Technology');

        // Search
        $search = $this->get(route('blogs.index', ['search' => 'Accessible Carousels']));
        $search->assertStatus(200)
            ->assertSee('A Step-By-Step Guide To Building Accessible Carousels');

        // Article detail increments views_count
        $post = Post::where('slug', 'a-step-by-step-guide-to-building-accessible-carousels')->firstOrFail();
        $initialViews = $post->views_count;

        $detail = $this->get(route('blogs.show', $post->slug));
        $detail->assertStatus(200)
            ->assertSee($post->title)
            ->assertSee('min read');

        $this->assertEquals($initialViews + 1, $post->fresh()->views_count);

        // Public Author profile, About, Contact, Privacy, and RSS feed
        $this->get(route('blogs.author', 'sarah-jenkins'))->assertStatus(200)->assertSee('Sarah Jenkins');
        $this->get(route('pages.about'))->assertStatus(200)->assertSee('Meet Our Editorial Team');
        $this->get(route('pages.contact'))->assertStatus(200)->assertSee('Drop Us a Message');
        $this->get(route('pages.privacy'))->assertStatus(200)->assertSee('Privacy Policy');
        $this->get(route('rss.feed'))->assertStatus(200)->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');
    }

    public function test_authentication_registration_and_role_based_access_control(): void
    {
        // Login pages render cleanly without hardcoded is-invalid or external form actions
        $this->get(route('login'))->assertStatus(200)->assertDontSee('zaira.themedox.com');
        $this->get(route('author.login'))->assertStatus(200)->assertDontSee('zaira.themedox.com');
        $this->get(route('admin.login'))->assertStatus(200)->assertDontSee('zaira.themedox.com');

        // Registration creates user with unique username and logs in
        $registerResponse = $this->post(route('register.submit'), [
            'name' => 'Tran Quoc Tuan',
            'email' => 'tuan.test@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $registerResponse->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticated();

        $reader = User::where('email', 'tuan.test@example.com')->firstOrFail();
        $this->assertEquals('tran-quoc-tuan', $reader->username);

        // Reader cannot access Author Studio or Admin Console
        $this->actingAs($reader)->get(route('author.dashboard'))->assertRedirect(route('user.dashboard'));
        $this->actingAs($reader)->get(route('admin.dashboard'))->assertRedirect(route('user.dashboard'));

        // Author can access Author Studio but NOT Admin Console
        $author = User::where('email', 'author@gmail.com')->firstOrFail();
        $this->actingAs($author)->get(route('author.dashboard'))
            ->assertStatus(200)
            ->assertSee('Published Articles')
            ->assertDontSee('Total Sale')
            ->assertDontSee('backend::translate');

        $this->actingAs($author)->get(route('admin.dashboard'))->assertRedirect(route('author.dashboard'));

        // Admin can access Admin Console
        $admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertStatus(200)
            ->assertSee('Editorial Moderation Queue')
            ->assertDontSee('Total Sale')
            ->assertDontSee('backend::translate');
    }

    public function test_author_post_submission_and_admin_editorial_moderation_workflow(): void
    {
        $author = User::where('email', 'author@gmail.com')->firstOrFail();
        $admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $category = Category::firstOrFail();
        $tag = Tag::firstOrFail();

        // 1. Author submits a new article -> even if 'published' is sent, non-admin is enforced as 'pending'
        $storeResponse = $this->actingAs($author)->post(route('author.posts.store'), [
            'title' => 'Open Source Security Best Practices 2026',
            'language_id' => 1,
            'category_id' => $category->id,
            'summary' => 'A comprehensive guide to securing open-source web applications.',
            'content' => '<h2>Introduction</h2><p>Full article body content for testing.</p>',
            'status' => 'published',
            'tags' => [$tag->id],
        ]);

        $storeResponse->assertRedirect(route('author.posts'));

        $post = Post::where('title', 'Open Source Security Best Practices 2026')->firstOrFail();
        $this->assertEquals('pending', $post->status);
        $this->assertTrue($post->tags->contains($tag->id));

        // Unpublished pending post is hidden from public guest readers (404)
        auth()->logout();
        $this->get(route('blogs.show', $post->slug))->assertStatus(404);

        // 2. Admin rejects the post with an Editorial Review Note
        $this->actingAs($admin)->patch(route('admin.posts.moderate', $post), [
            'status' => 'rejected',
            'rejection_reason' => 'Please add benchmark references in section 2.',
        ])->assertRedirect();

        $this->assertEquals('rejected', $post->fresh()->status);
        $this->assertEquals('Please add benchmark references in section 2.', $post->fresh()->rejection_reason);

        // 3. Admin approves and publishes the post
        $this->actingAs($admin)->patch(route('admin.posts.moderate', $post), [
            'status' => 'published',
        ])->assertRedirect();

        $this->assertEquals('published', $post->fresh()->status);
        $this->assertNotNull($post->fresh()->published_at);

        // Now public guest readers can read the published article
        auth()->logout();
        $this->get(route('blogs.show', $post->slug))->assertStatus(200)->assertSee('Open Source Security Best Practices 2026');
    }

    public function test_reader_comments_bookmarks_contact_and_newsletter_workflows(): void
    {
        $reader = User::where('email', 'user@gmail.com')->firstOrFail();
        $post = Post::published()->firstOrFail();

        // Reader posts a comment
        $this->actingAs($reader)->post(route('blogs.comments.store', $post), [
            'comment' => 'Great open-source implementation with Laravel 13!',
        ])->assertRedirect();

        $this->assertTrue(Comment::where('post_id', $post->id)->where('user_id', $reader->id)->where('comment', 'Great open-source implementation with Laravel 13!')->exists());

        // Reader toggles bookmark
        Bookmark::where('user_id', $reader->id)->where('post_id', $post->id)->delete();
        $this->actingAs($reader)->post(route('blogs.bookmark.toggle', $post))->assertRedirect();
        $this->assertTrue(Bookmark::where('user_id', $reader->id)->where('post_id', $post->id)->exists());

        // Contact form submission
        $this->post(route('pages.contact.submit'), [
            'name' => 'Le Van B',
            'email' => 'levanb@example.com',
            'subject' => 'Feedback on Zaira UI',
            'message' => 'The new responsive layout and dark mode work great.',
        ])->assertRedirect();
        $this->assertTrue(ContactMessage::where('email', 'levanb@example.com')->exists());

        // Newsletter subscription
        $this->post(route('newsletter.subscribe'), [
            'email' => 'newreader@example.com',
        ])->assertRedirect();
        $this->assertTrue(Newsletter::where('email', 'newreader@example.com')->exists());
    }

    public function test_admin_category_tag_comment_and_user_management_workflows(): void
    {
        $admin = User::where('email', 'admin@gmail.com')->firstOrFail();
        $reader = User::where('email', 'user@gmail.com')->firstOrFail();

        // Admin creates a new category
        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Open Source Hardware',
            'language_id' => 1,
            'order' => 9,
            'show_on_menu' => 1,
        ])->assertRedirect();
        $this->assertTrue(Category::where('slug', 'open-source-hardware')->exists());

        // Admin creates a new tag
        $this->actingAs($admin)->post(route('admin.tags.store'), [
            'name' => 'RISC-V',
        ])->assertRedirect();
        $this->assertTrue(Tag::where('slug', 'risc-v')->exists());

        // Admin updates user status (active -> banned)
        $this->actingAs($admin)->patch(route('admin.users.role', $reader), [
            'role_id' => 3,
            'status' => 'banned',
        ])->assertRedirect();
        $this->assertEquals('banned', $reader->fresh()->status);

        // Banned user cannot log in
        auth()->logout();
        $this->post(route('login.submit'), [
            'email' => $reader->email,
            'password' => 'password123',
        ])->assertSessionHasErrors('email');

        // Admin re-activates user and promotes to Author (role_id = 2)
        $this->actingAs($admin)->patch(route('admin.users.role', $reader), [
            'role_id' => 2,
            'status' => 'active',
        ])->assertRedirect();
        $this->assertEquals('active', $reader->fresh()->status);
        $this->assertEquals(2, $reader->fresh()->role_id);
    }
}

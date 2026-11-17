<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\AuthorProfile;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use App\Models\ContactMessage;
use App\Models\CustomPage;
use App\Models\Language;
use App\Models\Newsletter;
use App\Models\Post;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the Zaira News & Magazine application database.
     */
    public function run(): void
    {
        // 1. Roles
        $adminRole = Role::updateOrCreate(
            ['id' => 1],
            ['name' => 'admin', 'display_name' => 'Administrator', 'permissions' => ['*']]
        );
        $authorRole = Role::updateOrCreate(
            ['id' => 2],
            ['name' => 'author', 'display_name' => 'Author / Editor', 'permissions' => ['posts.create', 'posts.edit', 'posts.delete', 'comments.view']]
        );
        $userRole = Role::updateOrCreate(
            ['id' => 3],
            ['name' => 'user', 'display_name' => 'Normal User / Reader', 'permissions' => ['comments.create', 'bookmarks.manage']]
        );

        // 2. Languages
        $en = Language::updateOrCreate(
            ['code' => 'en'],
            ['name' => 'English', 'direction' => 'ltr', 'is_default' => true, 'status' => true]
        );
        Language::updateOrCreate(
            ['code' => 'ar'],
            ['name' => 'Arabic', 'direction' => 'rtl', 'is_default' => false, 'status' => true]
        );
        Language::updateOrCreate(
            ['code' => 'vi'],
            ['name' => 'Vietnamese', 'direction' => 'ltr', 'is_default' => false, 'status' => true]
        );

        // 3. Users & Author Profiles
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Alex Morgan (Editor-in-Chief)',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'phone' => '+1 (555) 234-5678',
                'avatar' => 'assets/img/avatar-admin.svg',
                'bio' => 'Editor-in-Chief & Full Stack Web Architect at Zaira News Portal. Leading editorial quality, open-source publishing standards, and digital journalism.',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        AuthorProfile::updateOrCreate(
            ['user_id' => $admin->id],
            [
                'designation' => 'Editor-in-Chief & Systems Architect',
                'about' => $admin->bio,
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://www.x.com/',
                'linkedin' => 'https://www.linkedin.com/',
                'instagram' => 'https://www.instagram.com/',
                'is_approved' => true,
            ]
        );

        $author = User::updateOrCreate(
            ['email' => 'author@gmail.com'],
            [
                'role_id' => $authorRole->id,
                'name' => 'Sarah Jenkins',
                'username' => 'sarah-jenkins',
                'password' => Hash::make('password'),
                'phone' => '+1 (555) 876-5432',
                'avatar' => 'assets/img/avatar-author.svg',
                'bio' => 'Senior Technology & Open-Source Correspondent covering web engineering, cloud databases, cybersecurity, and modern software architecture.',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        AuthorProfile::updateOrCreate(
            ['user_id' => $author->id],
            [
                'designation' => 'Senior Technology Correspondent',
                'about' => $author->bio,
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://www.x.com/',
                'linkedin' => 'https://www.linkedin.com/',
                'instagram' => 'https://www.instagram.com/',
                'is_approved' => true,
            ]
        );

        $author2 = User::updateOrCreate(
            ['email' => 'david.tran@zaira.dev'],
            [
                'role_id' => $authorRole->id,
                'name' => 'David Tran',
                'username' => 'david-tran',
                'password' => Hash::make('password'),
                'phone' => '+84 912 345 678',
                'avatar' => 'assets/img/avatar-author2.svg',
                'bio' => 'Digital Culture, Science & Global Markets Editor with 8+ years of investigative reporting experience.',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        AuthorProfile::updateOrCreate(
            ['user_id' => $author2->id],
            [
                'designation' => 'Science & Global Markets Editor',
                'about' => $author2->bio,
                'facebook' => 'https://www.facebook.com/',
                'twitter' => 'https://www.x.com/',
                'linkedin' => 'https://www.linkedin.com/',
                'instagram' => 'https://www.instagram.com/',
                'is_approved' => true,
            ]
        );

        $reader = User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'role_id' => $userRole->id,
                'name' => 'Minh Tuan (Reader)',
                'username' => 'minh-tuan',
                'password' => Hash::make('password'),
                'phone' => '+84 987 654 321',
                'avatar' => 'assets/img/avatar-user.svg',
                'bio' => 'Open-source enthusiast and avid reader of technology, science, and editorial insights.',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        // 4. Categories
        $categoriesData = [
            ['name' => 'Technology', 'slug' => 'technology', 'order' => 1, 'description' => 'Software engineering, AI, open-source ecosystems, and cloud infrastructure.'],
            ['name' => 'Featured', 'slug' => 'featured', 'order' => 2, 'description' => 'In-depth cover stories and investigative reports curated by our editors.'],
            ['name' => 'Trending', 'slug' => 'trending', 'order' => 3, 'description' => 'Fast-moving stories and breaking developments across the globe.'],
            ['name' => 'Insights', 'slug' => 'insights', 'order' => 4, 'description' => 'Deep technical analyses, architectural patterns, and industry benchmarks.'],
            ['name' => 'Community', 'slug' => 'community', 'order' => 5, 'description' => 'Developer communities, open-source summits, and collaborative innovation.'],
            ['name' => 'Opinion', 'slug' => 'opinion', 'order' => 6, 'description' => 'Editorial perspectives, columns, and thought leadership.'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'language_id' => $en->id,
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'order' => $cat['order'],
                    'show_on_menu' => true,
                    'show_on_home' => true,
                    'status' => true,
                    'meta_title' => $cat['name'].' News | Zaira Magazine',
                    'meta_description' => $cat['description'],
                ]
            );
        }

        // 5. Tags
        $tagsData = [
            'open-source' => 'Open Source',
            'laravel' => 'Laravel',
            'cloud-database' => 'Cloud Database',
            'cybersecurity' => 'Cybersecurity',
            'web-accessibility' => 'Web Accessibility',
            'artificial-intelligence' => 'Artificial Intelligence',
            'ui-ux-design' => 'UI/UX Design',
            'devops' => 'DevOps',
            'fintech' => 'FinTech',
            'science' => 'Science',
        ];
        $tags = [];
        foreach ($tagsData as $slug => $tagName) {
            $tags[$slug] = Tag::updateOrCreate(
                ['slug' => $slug],
                ['language_id' => $en->id, 'name' => $tagName]
            );
        }

        // 6. Posts (Published, Pending, Draft, Rejected)
        $postsData = [
            [
                'title' => 'A Step-By-Step Guide To Building Accessible Carousels & Modern Web Interfaces',
                'slug' => 'a-step-by-step-guide-to-building-accessible-carousels',
                'category' => 'technology',
                'user_id' => $author->id,
                'image' => 'assets/img/post-tech-1.svg',
                'views_count' => 1420,
                'is_featured' => true,
                'is_breaking' => true,
                'is_slider' => true,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'tags' => ['web-accessibility', 'ui-ux-design', 'open-source'],
                'summary' => 'Learn how to architect WAI-ARIA compliant interactive components that remain fast, keyboard-friendly, and resilient across all devices.',
                'content' => '<h2>Why Web Accessibility Matters in Open-Source Publishing</h2><p>Modern news portals serve millions of readers across diverse devices and assistive technologies. Designing accessible carousels, navigation drawers, and article layouts ensures every reader can consume journalism without barriers.</p><h3>1. Semantic HTML Structure</h3><p>Always start with native landmarks such as <code>&lt;article&gt;</code>, <code>&lt;nav&gt;</code>, and <code>&lt;section&gt;</code>. Screen readers rely on heading hierarchy (<code>h1</code> through <code>h3</code>) to generate a navigable outline of the story.</p><blockquote><p>"Accessibility is not an afterthought or a checklist—it is the foundation of high-quality software engineering."</p></blockquote><h3>2. Keyboard Navigation & Focus Management</h3><p>Interactive widgets must support Tab, Shift+Tab, and arrow keys while maintaining visible focus rings and respecting <code>prefers-reduced-motion</code> preferences.</p>',
            ],
            [
                'title' => 'Scaling Distributed SQL with TiDB Cloud and Laravel 13 in Team Environments',
                'slug' => 'scaling-distributed-sql-with-tidb-cloud-and-laravel',
                'category' => 'insights',
                'user_id' => $admin->id,
                'image' => 'assets/img/post-cloud-2.svg',
                'views_count' => 980,
                'is_featured' => true,
                'is_breaking' => true,
                'is_slider' => true,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'tags' => ['laravel', 'cloud-database', 'devops'],
                'summary' => 'How distributed MySQL-compatible cloud databases enable university and enterprise engineering teams to collaborate on live synchronized schemas.',
                'content' => '<h2>Collaborative Database Engineering</h2><p>When multiple developers work on Authentication, Public News Feeds, Author Studios, and Admin Consoles simultaneously, local database drift quickly becomes a bottleneck.</p><h3>TLS Security & Connection Pooling</h3><p>Connecting Laravel to TiDB Cloud over TLS using verified CA certificates guarantees encrypted data in transit while preserving full Eloquent ORM compatibility.</p><h3>Indexing High-Traffic News Feeds</h3><p>Composite indexes on <code>(status, language_id, published_at)</code> allow homepage queries to resolve in milliseconds even as the article archive grows.</p>',
            ],
            [
                'title' => 'Designing Resilient Cybersecurity Strategies for Modern Digital Newsrooms',
                'slug' => 'the-building-your-security-strategy',
                'category' => 'trending',
                'user_id' => $author->id,
                'image' => 'assets/img/post-security-3.svg',
                'views_count' => 865,
                'is_featured' => true,
                'is_breaking' => false,
                'is_slider' => true,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'tags' => ['cybersecurity', 'open-source', 'devops'],
                'summary' => 'From role-based access control (RBAC) and CSRF protection to secret management in Git repositories, here is how modern publishing platforms stay secure.',
                'content' => '<h2>Zero-Trust Principles for Publishing Platforms</h2><p>Publishing systems bring together public readers, external contributors, and privileged administrators. Enforcing strict role middleware and sanitizing rich-text submissions are essential safeguards.</p><h3>Protecting Credentials in Version Control</h3><p>Never commit plaintext database passwords or API keys to Git. Use environment variables (<code>.env</code>) and strict <code>.gitignore</code> rules across all branches.</p>',
            ],
            [
                'title' => 'Open-Source AI Models Are Transforming Real-Time Editorial Fact-Checking',
                'slug' => 'open-source-ai-models-transforming-editorial-fact-checking',
                'category' => 'featured',
                'user_id' => $author2->id,
                'image' => 'assets/img/post-ai-4.svg',
                'views_count' => 1210,
                'is_featured' => true,
                'is_breaking' => true,
                'is_slider' => false,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'tags' => ['artificial-intelligence', 'open-source', 'science'],
                'summary' => 'Newsrooms are adopting open-weights language models to assist journalists with source verification, automated summaries, and multilingual translation.',
                'content' => '<h2>Human-in-the-Loop Journalism</h2><p>While AI can cross-reference citations and generate initial metadata in seconds, editorial judgment remains irreplaceable. The combination of open-source tooling and human review sets a new standard for accuracy.</p>',
            ],
            [
                'title' => 'Target Sizes & Typography Cheatsheet for High-Readability Digital Magazines',
                'slug' => 'target-sizes-cheatsheet-for-digital-magazines',
                'category' => 'technology',
                'user_id' => $author->id,
                'image' => 'assets/img/post-ux-5.svg',
                'views_count' => 640,
                'is_featured' => false,
                'is_breaking' => false,
                'is_slider' => false,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => ['ui-ux-design', 'web-accessibility'],
                'summary' => 'Practical guidelines on touch target sizing (44x44px minimum), line height, and dark-mode contrast ratios for long-form reading.',
                'content' => '<h2>Optimizing Long-Form Reading Comfort</h2><p>Balancing Manrope headings with Inter body copy at a comfortable 65–75 character line width dramatically reduces eye strain on both desktop and mobile viewports.</p>',
            ],
            [
                'title' => 'How Global Developer Communities Sustain Critical Open-Source Infrastructure',
                'slug' => 'how-global-developer-communities-sustain-open-source',
                'category' => 'community',
                'user_id' => $author2->id,
                'image' => 'assets/img/post-community-6.svg',
                'views_count' => 530,
                'is_featured' => false,
                'is_breaking' => false,
                'is_slider' => false,
                'is_recommended' => true,
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'tags' => ['open-source', 'devops', 'laravel'],
                'summary' => 'Behind every modern web application lies a foundation of community-maintained packages, transparent governance, and shared standards.',
                'content' => '<h2>The Power of Collaborative Engineering</h2><p>From Linux and MySQL to Laravel and Bootstrap, open-source ecosystems thrive when developers contribute bug fixes, documentation, and security audits back upstream.</p>',
            ],
            [
                'title' => 'Why Clean Architecture Beats Over-Engineering in University & Startup Projects',
                'slug' => 'why-clean-architecture-beats-over-engineering',
                'category' => 'opinion',
                'user_id' => $admin->id,
                'image' => 'assets/img/post-opinion-7.svg',
                'views_count' => 790,
                'is_featured' => false,
                'is_breaking' => false,
                'is_slider' => false,
                'is_recommended' => false,
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'tags' => ['laravel', 'open-source'],
                'summary' => 'Focusing on clear MVC separation, well-indexed relational tables, and cohesive user workflows delivers far more value than unnecessary complexity.',
                'content' => '<h2>Pragmatic Software Design</h2><p>A well-structured monolith with clean controllers, Eloquent relationships, and reusable Blade layouts is easier to test, audit, and maintain than premature microservices.</p>',
            ],
            [
                'title' => 'Upcoming Web Standards in 2027: Native CSS Grid Subgrid & View Transitions',
                'slug' => 'upcoming-web-standards-native-css-and-view-transitions',
                'category' => 'technology',
                'user_id' => $author->id,
                'image' => 'assets/img/post-tech-1.svg',
                'views_count' => 45,
                'is_featured' => false,
                'is_breaking' => false,
                'is_slider' => false,
                'is_recommended' => false,
                'status' => 'pending',
                'published_at' => null,
                'tags' => ['ui-ux-design', 'open-source'],
                'summary' => 'An awaiting-moderation submission exploring how native browser APIs eliminate heavy JavaScript dependencies.',
                'content' => '<h2>Awaiting Editorial Approval</h2><p>This article was submitted by Sarah Jenkins through the Author Studio and is currently in the <strong>pending</strong> queue for Admin moderation.</p>',
            ],
            [
                'title' => 'Draft: Benchmarking Full-Text Search Strategies in Relational Databases',
                'slug' => 'draft-benchmarking-full-text-search-strategies',
                'category' => 'insights',
                'user_id' => $author->id,
                'image' => 'assets/img/post-cloud-2.svg',
                'views_count' => 0,
                'is_featured' => false,
                'is_breaking' => false,
                'is_slider' => false,
                'is_recommended' => false,
                'status' => 'draft',
                'published_at' => null,
                'tags' => ['cloud-database', 'laravel'],
                'summary' => 'Work-in-progress benchmarks comparing SQL LIKE queries, FULLTEXT indexes, and external search engines.',
                'content' => '<p>Draft notes and benchmark tables in progress...</p>',
            ],
        ];

        foreach ($postsData as $p) {
            $post = Post::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'language_id' => $en->id,
                    'user_id' => $p['user_id'],
                    'category_id' => $categories[$p['category']]->id,
                    'post_type' => 'article',
                    'title' => $p['title'],
                    'summary' => $p['summary'],
                    'content' => $p['content'],
                    'image' => $p['image'],
                    'views_count' => $p['views_count'],
                    'is_featured' => $p['is_featured'],
                    'is_breaking' => $p['is_breaking'],
                    'is_slider' => $p['is_slider'],
                    'is_recommended' => $p['is_recommended'],
                    'status' => $p['status'],
                    'published_at' => $p['published_at'],
                    'meta_title' => $p['title'],
                    'meta_description' => $p['summary'],
                    'meta_keywords' => implode(', ', $p['tags']),
                ]
            );

            $tagIds = collect($p['tags'])->map(fn ($t) => $tags[$t]->id)->all();
            $post->tags()->sync($tagIds);
        }

        // 7. Sample Comments & Bookmarks
        $firstPost = Post::where('slug', 'a-step-by-step-guide-to-building-accessible-carousels')->first();
        $secondPost = Post::where('slug', 'scaling-distributed-sql-with-tidb-cloud-and-laravel')->first();

        if ($firstPost) {
            $parentComment = Comment::updateOrCreate(
                ['post_id' => $firstPost->id, 'user_id' => $reader->id, 'parent_id' => null],
                [
                    'comment' => 'Excellent breakdown! Adding keyboard navigation and proper ARIA labels made a huge difference in our university project demo.',
                    'status' => 'approved',
                ]
            );

            Comment::updateOrCreate(
                ['post_id' => $firstPost->id, 'user_id' => $author->id, 'parent_id' => $parentComment->id],
                [
                    'comment' => 'Thank you Minh Tuan! Glad the accessibility patterns were helpful for your team.',
                    'status' => 'approved',
                ]
            );

            Bookmark::updateOrCreate(['user_id' => $reader->id, 'post_id' => $firstPost->id]);
        }

        if ($secondPost) {
            Bookmark::updateOrCreate(['user_id' => $reader->id, 'post_id' => $secondPost->id]);
        }

        // 8. Sample Newsletter, Contact Message, Custom Page, Settings
        Newsletter::updateOrCreate(
            ['email' => 'subscriber@zaira.dev'],
            ['token' => 'demo-token-123', 'status' => true]
        );

        ContactMessage::updateOrCreate(
            ['email' => 'partner@university.edu.vn', 'subject' => 'Open Source Course Project Inquiry'],
            [
                'name' => 'Dr. Nguyen Van A',
                'message' => 'Great work on the Zaira News Portal architecture and TiDB Cloud integration.',
                'is_read' => false,
            ]
        );

        CustomPage::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'language_id' => $en->id,
                'title' => 'Privacy Policy & Terms of Use',
                'content' => '<p><strong>Last Updated:</strong> November 2026</p><p>Welcome to Zaira News & Magazine Portal. We respect your privacy and are committed to protecting your personal data across our open-source publishing platform.</p>',
                'status' => true,
                'meta_title' => 'Privacy Policy & Terms | Zaira News',
                'meta_description' => 'Privacy Policy and Terms of Service for Zaira News & Magazine System.',
            ]
        );

        Advertisement::updateOrCreate(
            ['slot_key' => 'home_middle_banner'],
            [
                'title' => 'Zaira Open-Source Publishing Summit 2026',
                'banner_image' => 'assets/img/ad-banner.svg',
                'redirect_url' => '/blogs',
                'status' => true,
            ]
        );

        $defaultSettings = [
            'site_name' => 'Zaira — News & Magazine Portal',
            'site_tagline' => 'Modern Open-Source Journalism, Technology & Editorial Insights',
            'contact_email' => 'editorial@zaira-news.org',
            'contact_phone' => '+84 (028) 3899-2026',
            'contact_address' => 'Open Source Software Lab, Ho Chi Minh City, Vietnam',
            'footer_description' => 'Zaira is a multi-role News & Magazine publishing platform built with Laravel, TiDB Cloud (MySQL), and Bootstrap 5 for the Open Source Programming course.',
        ];

        foreach ($defaultSettings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
    }
}

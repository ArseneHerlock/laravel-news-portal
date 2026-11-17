@extends('layouts.public')

@section('title', $post->title . ' | Zaira News')
@section('meta_description', strip_tags($post->summary ?: $post->meta_description))

@section('reading_progress')
  <div id="reading-progress-bar"></div>
@endsection

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('blogs.index', ['category' => $post->category?->slug]) }}">{{ $post->category?->name ?? 'Articles' }}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 60) }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-50 pb-60">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-8">
          @if($post->status !== 'published')
            <div class="alert alert-warning d-flex justify-content-between align-items-center">
              <div>
                <strong>Editorial Preview Mode:</strong> This article is currently marked as <span class="badge bg-dark text-uppercase">{{ $post->status }}</span>.
              </div>
              <a href="{{ route('author.posts.edit', $post) }}" class="btn btn-sm btn-dark">Edit Article</a>
            </div>
          @endif

          <article class="card border-0 shadow-sm p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
              <div class="d-flex align-items-center gap-2">
                <a href="{{ route('blogs.index', ['category' => $post->category?->slug]) }}" class="badge bg-primary text-uppercase px-3 py-2">
                  {{ $post->category?->name ?? 'Editorial' }}
                </a>
                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                  <i class="far fa-clock me-1"></i> {{ $post->reading_time }} min read
                </span>
                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                  <i class="far fa-eye me-1"></i> {{ number_format($post->views_count) }} views
                </span>
              </div>

              <!-- Interactive Reader Actions: Listen (TTS) + Bookmark -->
              <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleArticleSpeech(this)">
                  <i class="fas fa-headphones me-1"></i> Listen to Article
                </button>
                @auth
                  <form action="{{ route('blogs.bookmark.toggle', $post) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $isBookmarked ? 'btn-primary' : 'btn-outline-primary' }}">
                      <i class="{{ $isBookmarked ? 'fas' : 'far' }} fa-bookmark me-1"></i>
                      {{ $isBookmarked ? 'Saved' : 'Bookmark' }}
                    </button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">
                    <i class="far fa-bookmark me-1"></i> Bookmark
                  </a>
                @endauth
              </div>
            </div>

            <h1 class="h2 fw-bold mb-3">{{ $post->title }}</h1>

            <div class="d-flex flex-wrap align-items-center gap-3 pb-3 mb-4 border-bottom text-muted small">
              <div class="d-flex align-items-center gap-2">
                <img src="{{ asset($post->author?->avatar ?: 'assets/img/avatar-author.svg') }}" alt="{{ $post->author?->name }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" />
                <span>By <a href="{{ route('blogs.author', $post->author?->username) }}" class="fw-bold text-body">{{ $post->author?->name }}</a></span>
              </div>
              <span>•</span>
              <span><i class="far fa-calendar-alt me-1"></i> {{ ($post->published_at ?? $post->created_at)->format('d M Y, H:i') }}</span>
              <span>•</span>
              <span><i class="far fa-comments me-1"></i> {{ $post->approvedComments->count() }} Comments</span>
            </div>

            <div class="mb-4">
              <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="img-fluid rounded w-100" style="max-height: 440px; object-fit: cover;" />
            </div>

            @if($post->summary)
              <div class="p-3 mb-4 rounded bg-body-tertiary border-start border-4 border-primary fst-italic">
                {{ $post->summary }}
              </div>
            @endif

            <!-- Dynamic Table of Contents (Auto-populated by zaira-lite.js if headings exist) -->
            <div id="article-toc-box" class="d-none p-3 mb-4 rounded border bg-body-tertiary">
              <div class="fw-bold mb-2"><i class="fas fa-list-ul text-primary me-2"></i>Table of Contents</div>
              <ul id="article-toc-list" class="mb-0 ps-3 small"></ul>
            </div>

            @if($post->video_url)
              <div class="mb-4 p-3 border rounded bg-dark text-white d-flex justify-content-between align-items-center">
                <span><i class="fab fa-youtube text-danger me-2"></i>Associated Video Stream</span>
                <a href="{{ $post->video_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-danger">Watch Video</a>
              </div>
            @endif

            <div id="article-content-body" class="blog-content lh-lg fs-6 mb-4">
              {!! $post->content !!}
            </div>

            <!-- Tags & Social Share -->
            <div class="d-flex flex-wrap justify-content-between align-items-center pt-3 border-top gap-3">
              <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="fw-bold small me-1">Tags:</span>
                @forelse($post->tags as $t)
                  <a href="{{ route('blogs.index', ['tag' => $t->slug]) }}" class="btn btn-sm btn-outline-secondary">#{{ $t->name }}</a>
                @empty
                  <span class="text-muted small">General News</span>
                @endforelse
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="fw-bold small me-1">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary"><i class="fab fa-facebook-f"></i></a>
                <a href="https://x.com/share?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info"><i class="fab fa-twitter"></i></a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->fullUrl()) }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </article>

          <!-- Author Biography Card -->
          @if($post->author)
            <div class="card border-0 shadow-sm p-4 mb-4">
              <div class="d-flex flex-column flex-sm-row gap-3 align-items-center align-items-sm-start">
                <a href="{{ route('blogs.author', $post->author->username) }}">
                  <img src="{{ asset($post->author->avatar ?: 'assets/img/avatar-author.svg') }}" alt="{{ $post->author->name }}" class="rounded-circle" style="width: 88px; height: 88px; object-fit: cover;" />
                </a>
                <div class="text-center text-sm-start flex-grow-1">
                  <div class="d-flex flex-wrap justify-content-center justify-content-sm-between align-items-center mb-1">
                    <h4 class="h5 fw-bold mb-0">
                      <a href="{{ route('blogs.author', $post->author->username) }}">{{ $post->author->name }}</a>
                    </h4>
                    <span class="badge bg-zaira-navy">{{ $post->author->authorProfile?->designation ?? 'Editorial Contributor' }}</span>
                  </div>
                  <p class="text-muted small mb-2">{{ $post->author->authorProfile?->about ?? $post->author->bio }}</p>
                  <a href="{{ route('blogs.author', $post->author->username) }}" class="small fw-semibold text-primary">
                    View all articles by {{ $post->author->name }} <i class="fas fa-arrow-right ms-1"></i>
                  </a>
                </div>
              </div>
            </div>
          @endif

          <!-- Previous / Next Navigation -->
          <div class="row g-3 mb-4">
            <div class="col-md-6">
              @if($previousPost)
                <div class="card border-0 shadow-sm p-3 h-100">
                  <span class="text-muted small"><i class="fas fa-arrow-left me-1"></i> Previous Article</span>
                  <h6 class="fw-bold mb-0 mt-1">
                    <a href="{{ route('blogs.show', $previousPost->slug) }}">{{ Str::limit($previousPost->title, 65) }}</a>
                  </h6>
                </div>
              @endif
            </div>
            <div class="col-md-6">
              @if($nextPost)
                <div class="card border-0 shadow-sm p-3 h-100 text-md-end">
                  <span class="text-muted small">Next Article <i class="fas fa-arrow-right ms-1"></i></span>
                  <h6 class="fw-bold mb-0 mt-1">
                    <a href="{{ route('blogs.show', $nextPost->slug) }}">{{ Str::limit($nextPost->title, 65) }}</a>
                  </h6>
                </div>
              @endif
            </div>
          </div>

          <!-- Comments & Nested Discussion Section -->
          <div class="card border-0 shadow-sm p-4" id="comments-area">
            <h3 class="h5 fw-bold mb-4">
              <i class="far fa-comments text-primary me-2"></i>Reader Discussion ({{ $post->approvedComments->count() }})
            </h3>

            @forelse($post->approvedComments as $c)
              <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($c->user?->avatar ?: 'assets/img/avatar-user.svg') }}" alt="{{ $c->user?->name }}" class="rounded-circle" style="width: 32px; height: 32px;" />
                    <strong>{{ $c->user?->name ?? 'Reader' }}</strong>
                    @if($c->user?->isAdmin() || $c->user?->isAuthor())
                      <span class="badge bg-primary" style="font-size: 10px;">Editorial Team</span>
                    @endif
                  </div>
                  <span class="text-muted small">{{ $c->created_at->diffForHumans() }}</span>
                </div>
                <p class="mb-2 small">{{ $c->comment }}</p>

                <!-- Nested Replies -->
                @if($c->replies->isNotEmpty())
                  <div class="ms-4 mt-2 ps-3 border-start border-2 border-primary">
                    @foreach($c->replies as $reply)
                      <div class="py-2 {{ ! $loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                          <strong class="small">{{ $reply->user?->name ?? 'Author' }}</strong>
                          <span class="text-muted" style="font-size: 11px;">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="mb-0 small text-muted">{{ $reply->comment }}</p>
                      </div>
                    @endforeach
                  </div>
                @endif

                @auth
                  <details class="mt-2">
                    <summary class="small text-primary fw-semibold" style="cursor: pointer;">Reply to this comment</summary>
                    <form action="{{ route('blogs.comments.store', $post) }}" method="POST" class="mt-2">
                      @csrf
                      <input type="hidden" name="parent_id" value="{{ $c->id }}" />
                      <div class="input-group input-group-sm">
                        <input type="text" name="comment" class="form-control" placeholder="Write a reply..." required />
                        <button type="submit" class="btn btn-primary">Reply</button>
                      </div>
                    </form>
                  </details>
                @endauth
              </div>
            @empty
              <p class="text-muted small mb-4">No comments yet. Be the first reader to share your thoughts!</p>
            @endforelse

            <div class="mt-3 pt-3 border-top">
              <h4 class="h6 fw-bold mb-3">Post a Comment</h4>
              @auth
                <form action="{{ route('blogs.comments.store', $post) }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <textarea name="comment" rows="3" class="form-control" placeholder="Share your perspective on this story..." required>{{ old('comment') }}</textarea>
                  </div>
                  <button type="submit" class="btn btn-primary">
                    <i class="far fa-paper-plane me-1"></i> Publish Comment
                  </button>
                </form>
              @else
                <div class="p-3 rounded bg-body-tertiary d-flex justify-content-between align-items-center">
                  <span class="small">Please sign in to join the conversation or bookmark this article.</span>
                  <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Sign In</a>
                </div>
              @endauth
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Search</h4>
              </div>
              <form action="{{ route('blogs.index') }}" method="GET">
                <div class="input-group">
                  <input value="" name="search" type="text" class="form-control" placeholder="Search articles..." />
                  <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>

          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Hot Categories</h4>
              </div>
              <div class="list-group list-group-flush">
                @foreach($categories as $cat)
                  <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}"
                     class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                    <span>{{ $cat->name }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Recent News</h4>
              </div>
              <div class="d-flex flex-column gap-3">
                @foreach($recentPosts as $rp)
                  <div class="d-flex gap-3 align-items-center">
                    <img src="{{ $rp->image_url }}" alt="{{ $rp->title }}" class="horizontal-thumb" style="width: 76px; height: 60px;" />
                    <div>
                      <h6 class="small fw-bold mb-1">
                        <a href="{{ route('blogs.show', $rp->slug) }}">{{ Str::limit($rp->title, 55) }}</a>
                      </h6>
                      <span class="text-muted" style="font-size: 11px;">{{ ($rp->published_at ?? $rp->created_at)->format('d M Y') }}</span>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

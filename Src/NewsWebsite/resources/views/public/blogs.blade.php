@extends('layouts.public')

@section('title', ($activeCategory ? $activeCategory->name . ' News' : ($activeTag ? '#' . $activeTag->name . ' Articles' : 'Article Archive')) . ' | Zaira News')

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Articles</a></li>
          @if($activeCategory)
            <li class="breadcrumb-item active" aria-current="page">{{ $activeCategory->name }}</li>
          @elseif($activeTag)
            <li class="breadcrumb-item active" aria-current="page">#{{ $activeTag->name }}</li>
          @elseif($search !== '')
            <li class="breadcrumb-item active" aria-current="page">Search: "{{ $search }}"</li>
          @else
            <li class="breadcrumb-item active" aria-current="page">All Archives</li>
          @endif
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-50 pb-60">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-8">
          <!-- Filter & Search Summary Bar -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
              <div>
                <h1 class="h4 mb-1">
                  @if($activeCategory)
                    Category: <span class="text-primary">{{ $activeCategory->name }}</span>
                  @elseif($activeTag)
                    Topic Tag: <span class="text-primary">#{{ $activeTag->name }}</span>
                  @elseif($search !== '')
                    Search Results for: <span class="text-primary">"{{ $search }}"</span>
                  @else
                    All Published Articles
                  @endif
                </h1>
                @if($activeCategory && $activeCategory->description)
                  <p class="text-muted small mb-0">{{ $activeCategory->description }}</p>
                @endif
              </div>

              <form action="{{ route('blogs.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                @if($activeCategory)
                  <input type="hidden" name="category" value="{{ $activeCategory->slug }}" />
                @endif
                @if($activeTag)
                  <input type="hidden" name="tag" value="{{ $activeTag->slug }}" />
                @endif
                @if($search !== '')
                  <input type="hidden" name="search" value="{{ $search }}" />
                @endif
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                  <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Sort by: Latest</option>
                  <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Sort by: Most Viewed</option>
                </select>
                @if($activeCategory || $activeTag || $search !== '')
                  <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-outline-secondary text-nowrap">Clear Filter</a>
                @endif
              </form>
            </div>
          </div>

          @if($posts->isEmpty())
            <div class="card border-0 shadow-sm p-5 text-center">
              <div class="mb-3 text-muted"><i class="far fa-newspaper fa-3x"></i></div>
              <h4>No matching articles found</h4>
              <p class="text-muted mb-4">Try clearing your search filter or exploring another news category.</p>
              <div>
                <a href="{{ route('blogs.index') }}" class="btn btn-primary">Reset All Filters</a>
              </div>
            </div>
          @else
            <div class="row g-4">
              @foreach($posts as $post)
                <div class="col-md-6">
                  <article class="card h-100 border-0 shadow-sm">
                    <div class="post-thumb-wrap">
                      <a href="{{ route('blogs.show', $post->slug) }}">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" />
                      </a>
                    </div>
                    <div class="card-body d-flex flex-column">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <a href="{{ route('blogs.index', ['category' => $post->category?->slug]) }}" class="badge bg-primary">
                          {{ $post->category?->name ?? 'News' }}
                        </a>
                        <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $post->reading_time }} min read</span>
                      </div>
                      <h3 class="h5 fw-bold mb-2">
                        <a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a>
                      </h3>
                      <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($post->summary, 130) }}</p>
                      <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                        <a href="{{ route('blogs.author', $post->author?->username) }}">
                          <i class="far fa-user me-1"></i>{{ $post->author?->name }}
                        </a>
                        <span><i class="far fa-calendar-alt me-1"></i>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                      </div>
                    </div>
                  </article>
                </div>
              @endforeach
            </div>

            <div class="mt-4 d-flex justify-content-center">
              {{ $posts->links() }}
            </div>
          @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Search Archive</h4>
              </div>
              <form action="{{ route('blogs.index') }}" method="GET">
                <div class="input-group">
                  <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Keyword, title, topic..." />
                  <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
              </form>
            </div>
          </div>

          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Categories</h4>
              </div>
              <div class="list-group list-group-flush">
                @foreach($categories as $cat)
                  <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}"
                     class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 {{ $activeCategory?->id === $cat->id ? 'fw-bold text-primary' : '' }}">
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

          <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Tags</h4>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @foreach($tags as $t)
                  <a href="{{ route('blogs.index', ['tag' => $t->slug]) }}"
                     class="btn btn-sm {{ $activeTag?->id === $t->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                    #{{ $t->name }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

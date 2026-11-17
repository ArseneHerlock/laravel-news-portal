@extends('layouts.public')

@section('title', 'Zaira — Multi-Role News & Magazine Publishing Platform')

@section('content')
  <!-- Breaking News Ticker -->
  @if($breakingPosts->isNotEmpty())
    <div class="bg-zaira-navy py-2 border-bottom border-secondary">
      <div class="container d-flex align-items-center gap-3 overflow-hidden">
        <span class="badge bg-danger text-uppercase px-3 py-1 flex-shrink-0">
          <i class="fas fa-bolt me-1"></i> Breaking News
        </span>
        <div class="d-flex gap-4 overflow-auto text-nowrap small">
          @foreach($breakingPosts as $bp)
            <a href="{{ route('blogs.show', $bp->slug) }}" class="text-white text-decoration-none">
              <span class="text-warning me-1">•</span> {{ $bp->title }}
            </a>
          @endforeach
        </div>
      </div>
    </div>
  @endif

  <!-- Hero Featured Editorial Section -->
  <section class="pt-40 pb-40">
    <div class="container">
      @php
        $mainHero = $featuredPosts->first();
        $subFeatured = $featuredPosts->slice(1, 4);
      @endphp

      <div class="row g-4">
        @if($mainHero)
          <div class="col-lg-7">
            <div class="overlay-card tall shadow-sm">
              <img src="{{ $mainHero->image_url }}" alt="{{ $mainHero->title }}" />
              <div class="overlay-card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <a href="{{ route('blogs.index', ['category' => $mainHero->category?->slug]) }}" class="badge bg-primary text-uppercase">
                    {{ $mainHero->category?->name ?? 'Featured' }}
                  </a>
                  <span class="badge bg-dark bg-opacity-75"><i class="far fa-clock me-1"></i>{{ $mainHero->reading_time }} min read</span>
                </div>
                <h2 class="h3 fw-bold mb-2">
                  <a href="{{ route('blogs.show', $mainHero->slug) }}" class="text-white">{{ $mainHero->title }}</a>
                </h2>
                <p class="text-light opacity-75 small mb-3 d-none d-sm-block">{{ Str::limit($mainHero->summary, 150) }}</p>
                <div class="d-flex align-items-center gap-3 small text-light opacity-75">
                  <span><i class="far fa-user me-1"></i> <a href="{{ route('blogs.author', $mainHero->author?->username) }}" class="text-white">{{ $mainHero->author?->name }}</a></span>
                  <span><i class="far fa-calendar-alt me-1"></i> {{ ($mainHero->published_at ?? $mainHero->created_at)->format('d M Y') }}</span>
                  <span><i class="far fa-eye me-1"></i> {{ number_format($mainHero->views_count) }} views</span>
                </div>
              </div>
            </div>
          </div>
        @endif

        <div class="col-lg-5">
          <div class="row g-3">
            @foreach($subFeatured as $sub)
              <div class="col-sm-6 col-lg-12">
                <div class="card border-0 shadow-sm h-100">
                  <div class="card-body p-3 d-flex gap-3 align-items-center">
                    <a href="{{ route('blogs.show', $sub->slug) }}" class="flex-shrink-0">
                      <img src="{{ $sub->image_url }}" alt="{{ $sub->title }}" class="horizontal-thumb" />
                    </a>
                    <div>
                      <a href="{{ route('blogs.index', ['category' => $sub->category?->slug]) }}" class="badge bg-primary bg-opacity-10 text-primary mb-1">
                        {{ $sub->category?->name ?? 'News' }}
                      </a>
                      <h5 class="fs-6 fw-bold mb-1">
                        <a href="{{ route('blogs.show', $sub->slug) }}">{{ Str::limit($sub->title, 68) }}</a>
                      </h5>
                      <div class="text-muted" style="font-size: 12px;">
                        <i class="far fa-calendar me-1"></i>{{ ($sub->published_at ?? $sub->created_at)->format('d M Y') }}
                        <span class="ms-2"><i class="far fa-eye me-1"></i>{{ number_format($sub->views_count) }}</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Latest Stories & Sidebar -->
  <section class="pb-60">
    <div class="container">
      <div class="row g-4">
        <!-- Main Column: Latest Articles + Recommended -->
        <div class="col-lg-8">
          <div class="d-flex justify-content-between align-items-center section-heading">
            <h3 class="h4 mb-0">Latest Stories &amp; Analysis</h3>
            <a href="{{ route('blogs.index') }}" class="small fw-semibold text-primary">View All Archives <i class="fas fa-arrow-right ms-1"></i></a>
          </div>

          <div class="row g-4">
            @foreach($latestPosts as $post)
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
                        {{ $post->category?->name ?? 'General' }}
                      </a>
                      <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $post->reading_time }} min read</span>
                    </div>
                    <h4 class="h5 fw-bold mb-2">
                      <a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a>
                    </h4>
                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($post->summary, 115) }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                      <a href="{{ route('blogs.author', $post->author?->username) }}" class="fw-medium">
                        <i class="far fa-user-circle me-1"></i>{{ $post->author?->name }}
                      </a>
                      <span><i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }}</span>
                    </div>
                  </div>
                </article>
              </div>
            @endforeach
          </div>

          <!-- Banner Advertisement Slot -->
          @if($bannerAd)
            <div class="my-5">
              <a href="{{ $bannerAd->redirect_url ?: route('blogs.index') }}">
                <img src="{{ asset($bannerAd->banner_image ?: 'assets/img/ad-banner.svg') }}" alt="{{ $bannerAd->title }}" class="img-fluid rounded shadow-sm w-100" />
              </a>
            </div>
          @endif

          <!-- Editor's Picks / Recommended -->
          <div class="section-heading mt-4">
            <h3 class="h4 mb-0">Editor's Recommendations</h3>
          </div>
          <div class="row g-3">
            @foreach($recommendedPosts->take(4) as $rec)
              <div class="col-md-6">
                <div class="p-3 border rounded bg-body-tertiary d-flex gap-3 align-items-center h-100">
                  <img src="{{ $rec->image_url }}" alt="{{ $rec->title }}" class="horizontal-thumb" />
                  <div>
                    <span class="badge bg-secondary mb-1">{{ $rec->category?->name }}</span>
                    <h5 class="fs-6 fw-bold mb-1">
                      <a href="{{ route('blogs.show', $rec->slug) }}">{{ Str::limit($rec->title, 60) }}</a>
                    </h5>
                    <span class="text-muted" style="font-size: 12px;">{{ ($rec->published_at ?? $rec->created_at)->format('d M Y') }}</span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <!-- Right Sidebar: Trending / Most Viewed, Categories, Tags, Newsletter -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0"><i class="fas fa-fire text-danger me-2"></i>Most Read This Week</h4>
              </div>
              <div class="d-flex flex-column gap-3">
                @foreach($popularPosts as $index => $pop)
                  <div class="d-flex gap-3 align-items-start pb-3 {{ ! $loop->last ? 'border-bottom' : '' }}">
                    <span class="badge rounded-pill bg-zaira-navy fs-6 px-2.5 py-2">{{ sprintf('%02d', $index + 1) }}</span>
                    <div>
                      <a href="{{ route('blogs.index', ['category' => $pop->category?->slug]) }}" class="text-primary small fw-bold text-uppercase">
                        {{ $pop->category?->name }}
                      </a>
                      <h6 class="fw-bold mb-1">
                        <a href="{{ route('blogs.show', $pop->slug) }}">{{ Str::limit($pop->title, 65) }}</a>
                      </h6>
                      <div class="text-muted" style="font-size: 12px;">
                        <span><i class="far fa-eye me-1"></i>{{ number_format($pop->views_count) }} reads</span>
                        <span class="ms-2">• {{ $pop->reading_time }} min</span>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Categories Directory -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Explore Categories</h4>
              </div>
              <div class="list-group list-group-flush">
                @foreach($categories as $cat)
                  <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}"
                     class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0">
                    <span class="fw-medium">{{ $cat->name }}</span>
                    <span class="badge bg-primary rounded-pill">{{ $cat->posts_count }}</span>
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Popular Tags Cloud -->
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Trending Topics</h4>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @foreach($tags as $tag)
                  <a href="{{ route('blogs.index', ['tag' => $tag->slug]) }}" class="btn btn-sm btn-outline-secondary">
                    #{{ $tag->name }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Sidebar Newsletter Box -->
          <div class="card border-0 shadow-sm bg-zaira-navy text-white">
            <div class="card-body p-4 text-center">
              <i class="fas fa-paper-plane fa-2x text-warning mb-3"></i>
              <h4 class="h5 text-white">Daily Editorial Briefing</h4>
              <p class="small text-light opacity-75 mb-3">Get top open-source software and technology stories delivered straight to your inbox.</p>
              <form action="{{ route('newsletter.subscribe') }}" method="POST">
                @csrf
                <input type="email" name="email" class="form-control mb-2" placeholder="Enter your email..." required />
                <button type="submit" class="btn btn-primary w-100">Subscribe Now</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

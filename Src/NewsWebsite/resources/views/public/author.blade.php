@extends('layouts.public')

@section('title', $author->name . ' — Author Profile | Zaira News')

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('pages.about') }}">Editorial Team</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $author->name }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-50 pb-60">
    <div class="container">
      <!-- Author Profile Header Card -->
      <div class="card border-0 shadow-sm p-4 mb-5">
        <div class="row align-items-center g-4">
          <div class="col-md-3 text-center">
            <img src="{{ asset($author->avatar ?: 'assets/img/avatar-author.svg') }}" alt="{{ $author->name }}" class="rounded-circle shadow-sm" style="width: 140px; height: 140px; object-fit: cover;" />
          </div>
          <div class="col-md-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
              <div>
                <h1 class="h3 fw-bold mb-1">{{ $author->name }}</h1>
                <span class="badge bg-primary">{{ $author->authorProfile?->designation ?? 'Editorial Journalist' }}</span>
              </div>
              <div class="d-flex gap-2">
                @if($author->authorProfile?->facebook)
                  <a href="{{ $author->authorProfile->facebook }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fab fa-facebook-f"></i></a>
                @endif
                @if($author->authorProfile?->twitter)
                  <a href="{{ $author->authorProfile->twitter }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fab fa-twitter"></i></a>
                @endif
                @if($author->authorProfile?->linkedin)
                  <a href="{{ $author->authorProfile->linkedin }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fab fa-linkedin-in"></i></a>
                @endif
              </div>
            </div>
            <p class="text-muted mb-3">{{ $author->authorProfile?->about ?? $author->bio }}</p>
            <div class="d-flex gap-4 small text-muted">
              <span><i class="far fa-newspaper text-primary me-1"></i> <strong>{{ $posts->total() }}</strong> Published Articles</span>
              <span><i class="far fa-check-circle text-success me-1"></i> Verified Zaira Contributor</span>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-8">
          <div class="section-heading">
            <h3 class="h4 mb-0">Articles by {{ $author->name }}</h3>
          </div>

          <div class="row g-4">
            @forelse($posts as $post)
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
                      <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $post->reading_time }} min</span>
                    </div>
                    <h4 class="h5 fw-bold mb-2">
                      <a href="{{ route('blogs.show', $post->slug) }}">{{ $post->title }}</a>
                    </h4>
                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($post->summary, 120) }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top small text-muted">
                      <span><i class="far fa-calendar-alt me-1"></i>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                      <span><i class="far fa-eye me-1"></i>{{ number_format($post->views_count) }} views</span>
                    </div>
                  </div>
                </article>
              </div>
            @empty
              <div class="col-12">
                <div class="alert alert-light border">No published articles by this author yet.</div>
              </div>
            @endforelse
          </div>

          <div class="mt-4">
            {{ $posts->links() }}
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <div class="section-heading">
                <h4 class="h5 mb-0">Categories</h4>
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
        </div>
      </div>
    </div>
  </section>
@endsection

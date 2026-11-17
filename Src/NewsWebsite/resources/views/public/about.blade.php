@extends('layouts.public')

@section('title', 'About Zaira Newsroom & Editorial Team | Zaira News')

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">About Us</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-60 pb-60">
    <div class="container">
      <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
          <span class="badge bg-primary text-uppercase mb-2">Open-Source Digital Journalism</span>
          <h1 class="h2 fw-bold mb-3">Empowering Readers, Authors, and Editors Through Open-Source Publishing</h1>
          <p class="text-muted mb-3">
            <strong>Zaira News &amp; Magazine System</strong> is a modern, multi-role publishing platform engineered with <strong>PHP / Laravel 13</strong>, <strong>TiDB Cloud (MySQL-compatible)</strong>, and <strong>Bootstrap 5.3</strong>.
          </p>
          <p class="text-muted mb-4">
            Our architecture separates Public Readers, Contributing Authors, and Editorial Administrators into dedicated workspaces backed by a real-time relational database, ensuring rigorous editorial moderation, fast full-text discovery, and accessible reading across desktop and mobile devices.
          </p>
          <div class="row g-3 text-center">
            <div class="col-6 col-sm-3">
              <div class="p-3 border rounded bg-body-tertiary">
                <div class="h4 fw-bold text-primary mb-0">{{ $stats['published_posts'] }}</div>
                <div class="small text-muted">Published Articles</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="p-3 border rounded bg-body-tertiary">
                <div class="h4 fw-bold text-primary mb-0">{{ number_format($stats['total_views']) }}</div>
                <div class="small text-muted">Total Reads</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="p-3 border rounded bg-body-tertiary">
                <div class="h4 fw-bold text-primary mb-0">{{ $stats['categories_count'] }}</div>
                <div class="small text-muted">Categories</div>
              </div>
            </div>
            <div class="col-6 col-sm-3">
              <div class="p-3 border rounded bg-body-tertiary">
                <div class="h4 fw-bold text-primary mb-0">{{ $stats['authors_count'] }}</div>
                <div class="small text-muted">Editors &amp; Authors</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <img src="{{ asset('assets/img/post-tech-1.svg') }}" alt="Zaira Newsroom" class="img-fluid rounded shadow" />
        </div>
      </div>

      <!-- Editorial Team Section -->
      <div class="section-heading mt-5">
        <h2 class="h3 mb-0">Meet Our Editorial Team</h2>
      </div>
      <div class="row g-4">
        @foreach($teamMembers as $member)
          <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 text-center p-4">
              <div class="mx-auto mb-3">
                <a href="{{ route('blogs.author', $member->username) }}">
                  <img src="{{ asset($member->avatar ?: 'assets/img/avatar-author.svg') }}" alt="{{ $member->name }}" class="rounded-circle shadow-sm" style="width: 108px; height: 108px; object-fit: cover;" />
                </a>
              </div>
              <h4 class="h5 fw-bold mb-1">
                <a href="{{ route('blogs.author', $member->username) }}">{{ $member->name }}</a>
              </h4>
              <span class="badge bg-primary bg-opacity-10 text-primary mb-2">
                {{ $member->authorProfile?->designation ?? $member->role?->display_name }}
              </span>
              <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($member->authorProfile?->about ?? $member->bio, 120) }}</p>
              <div class="pt-3 border-top d-flex justify-content-between align-items-center small">
                <span class="text-muted"><i class="far fa-newspaper me-1"></i>{{ $member->posts_count }} Articles</span>
                <a href="{{ route('blogs.author', $member->username) }}" class="fw-semibold text-primary">View Profile <i class="fas fa-arrow-right ms-1"></i></a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endsection

@extends('layouts.public')

@section('title', 'Privacy Policy & Terms of Use | Zaira News')

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Privacy Policy &amp; Terms</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-50 pb-60">
    <div class="container">
      <div class="card border-0 shadow-sm p-4 p-md-5">
        <h1 class="h3 fw-bold mb-3">{{ $page?->title ?? 'Privacy Policy & Terms of Use' }}</h1>
        <p class="text-muted small pb-3 border-bottom">Last Updated: November 2026 • Open Source Programming Course Project (MIT License)</p>

        <div class="lh-lg">
          {!! $page?->content !!}

          <h4 class="h5 fw-bold mt-4">1. Information We Collect</h4>
          <p>We collect basic account information (name, username, email address, and optional profile biography) when you register as a Reader, Author, or Administrator, or when you submit comments and contact messages.</p>

          <h4 class="h5 fw-bold mt-4">2. How We Use Your Information</h4>
          <ul>
            <li>To provide role-based access to the Reader Hub, Author Studio, and Admin Console.</li>
            <li>To attribute published articles and comments to their respective authors.</li>
            <li>To deliver subscribed newsletters and respond to support inquiries.</li>
          </ul>

          <h4 class="h5 fw-bold mt-4">3. Open-Source License &amp; Academic Attribution</h4>
          <p>Zaira News &amp; Magazine System is developed for the university course <strong>Open Source Programming</strong> using open-source software including PHP, Laravel Framework (MIT), TiDB / MySQL, Bootstrap 5 (MIT), and Font Awesome.</p>

          <h4 class="h5 fw-bold mt-4">4. Contact Us</h4>
          <p>For questions regarding this Privacy Policy or data handling practices, please contact our editorial desk at <a href="mailto:{{ $globalSettings['contact_email'] ?? 'editorial@zaira-news.org' }}" class="text-primary fw-semibold">{{ $globalSettings['contact_email'] ?? 'editorial@zaira-news.org' }}</a>.</p>
        </div>
      </div>
    </div>
  </section>
@endsection

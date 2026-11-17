@extends('layouts.public')

@section('title', 'Contact the Editorial Newsroom | Zaira News')

@section('content')
  <div class="breadcrumb-area py-3 bg-body-tertiary border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">Contact With Us</li>
        </ol>
      </nav>
    </div>
  </div>

  <section class="pt-60 pb-60">
    <div class="container">
      <div class="row g-4 mb-5">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center gap-3">
            <div class="logo-mark flex-shrink-0" style="width: 48px; height: 48px;"><i class="fas fa-map-marker-alt"></i></div>
            <div>
              <h5 class="fw-bold mb-1">Newsroom Location</h5>
              <p class="text-muted small mb-0">{{ $globalSettings['contact_address'] ?? 'Open Source Software Lab, Ho Chi Minh City, Vietnam' }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center gap-3">
            <div class="logo-mark flex-shrink-0" style="width: 48px; height: 48px;"><i class="fas fa-envelope"></i></div>
            <div>
              <h5 class="fw-bold mb-1">Editorial Desk Email</h5>
              <p class="text-muted small mb-0">{{ $globalSettings['contact_email'] ?? 'editorial@zaira-news.org' }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card border-0 shadow-sm p-4 h-100 d-flex flex-row align-items-center gap-3">
            <div class="logo-mark flex-shrink-0" style="width: 48px; height: 48px;"><i class="fas fa-phone-alt"></i></div>
            <div>
              <h5 class="fw-bold mb-1">Press Hotline</h5>
              <p class="text-muted small mb-0">{{ $globalSettings['contact_phone'] ?? '+84 (028) 3899-2026' }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 align-items-center">
        <div class="col-lg-5">
          <img src="{{ asset('assets/img/post-cloud-2.svg') }}" alt="Contact Zaira Newsroom" class="img-fluid rounded shadow-sm" />
        </div>
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm p-4 p-md-5">
            <h3 class="h4 fw-bold mb-2">Drop Us a Message</h3>
            <p class="text-muted small mb-4">Have a news tip, press inquiry, or feedback on our open-source platform? Send a message directly to our administrators.</p>

            <form action="{{ route('pages.contact.submit') }}" method="POST">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Your Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" value="{{ old('name', auth()->user()?->name) }}" class="form-control @error('name') is-invalid @enderror" placeholder="Full Name" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                  <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="you@example.com" required />
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Subject</label>
                  <input type="text" name="subject" value="{{ old('subject') }}" class="form-control" placeholder="News Tip / Partnership / Support" />
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold">Phone (Optional)</label>
                  <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+84 ..." />
                </div>
                <div class="col-12">
                  <label class="form-label small fw-semibold">Message <span class="text-danger">*</span></label>
                  <textarea name="message" rows="4" class="form-control @error('message') is-invalid @enderror" placeholder="Write your message here..." required>{{ old('message') }}</textarea>
                </div>
                <div class="col-12">
                  <button type="submit" class="btn btn-primary px-4 py-2">
                    <i class="far fa-paper-plane me-1"></i> Send Message
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

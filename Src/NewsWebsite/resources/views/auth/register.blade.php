@extends('layouts.public')

@section('title', 'Create Account — Zaira News Portal')

@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-8">
        <div class="card border-0 shadow-sm p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="h3 fw-bold">Create Your Account</h2>
            <p class="text-muted small">Join our open-source news community as a Reader or Contributing Author.</p>
          </div>

          <form method="POST" action="{{ route('register.submit') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-semibold" for="name">Full Name <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" value="{{ old('name') }}"
                     class="form-control @error('name') is-invalid @enderror"
                     placeholder="Enter your full name" required />
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold" for="username">Username (URL Handle)</label>
                <input type="text" name="username" id="username" value="{{ old('username') }}"
                       class="form-control @error('username') is-invalid @enderror"
                       placeholder="e.g. minh-tuan (optional)" />
                @error('username')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold" for="email">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" required />
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label small fw-semibold" for="password">Password <span class="text-danger">*</span></label>
                <div class="password-input-wrapper position-relative">
                  <input type="password" name="password" id="password"
                         class="form-control @error('password') is-invalid @enderror"
                         placeholder="Minimum 6 characters" required />
                  <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                    <svg class="eye-icon" id="eyeIcon-password" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                    <svg class="eye-off-icon" id="eyeOffIcon-password" width="18" height="18" viewBox="0 0 24 24" fill="none" style="display: none;"><path d="m1 1 22 22" stroke="currentColor" stroke-width="2"/></svg>
                  </button>
                </div>
              </div>
              <div class="col-md-6">
                <label class="form-label small fw-semibold" for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                <div class="password-input-wrapper position-relative">
                  <input type="password" name="password_confirmation" id="password_confirmation"
                         class="form-control"
                         placeholder="Retype password" required />
                  <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmation')">
                    <svg class="eye-icon" id="eyeIcon-password_confirmation" width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                    <svg class="eye-off-icon" id="eyeOffIcon-password_confirmation" width="18" height="18" viewBox="0 0 24 24" fill="none" style="display: none;"><path d="m1 1 22 22" stroke="currentColor" stroke-width="2"/></svg>
                  </button>
                </div>
              </div>
            </div>

            <div class="p-3 mb-4 rounded border bg-body-tertiary">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="register_as_author" value="1" id="register_as_author" {{ old('register_as_author') ? 'checked' : '' }} />
                <label class="form-check-label fw-semibold small" for="register_as_author">
                  Also activate my Author Studio profile (allows writing and submitting articles for review)
                </label>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Create Account</button>

            <div class="text-center small">
              Already have an account? <a href="{{ route('login') }}" class="fw-bold text-primary">Sign In</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@extends('layouts.public')

@section('title', 'Sign In to Zaira News Portal')

@section('content')
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-8">
        <div class="card border-0 shadow-sm p-4 p-md-5">
          <div class="text-center mb-4">
            <h2 class="h3 fw-bold">Sign In to Continue</h2>
            <p class="text-muted small">Access your bookmarks, join article discussions, or manage your editorial studio.</p>
          </div>

          <!-- 1-Click Demo Credentials Box (Replaces the red error-like text in raw HTML) -->
          <div class="p-3 mb-4 rounded border bg-body-tertiary">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="small fw-bold text-uppercase"><i class="fas fa-key text-primary me-1"></i> Quick Demo Credentials (Click to auto-fill)</span>
              <span class="badge bg-secondary">Password: password</span>
            </div>
            <div class="d-flex flex-wrap gap-2">
              <button type="button" class="btn btn-sm btn-outline-primary" onclick="fillDemoCredentials('user@gmail.com', 'password')">
                <i class="fas fa-user me-1"></i> Reader (user@gmail.com)
              </button>
              <button type="button" class="btn btn-sm btn-outline-success" onclick="fillDemoCredentials('author@gmail.com', 'password')">
                <i class="fas fa-pen-nib me-1"></i> Author (author@gmail.com)
              </button>
              <button type="button" class="btn btn-sm btn-outline-dark" onclick="fillDemoCredentials('admin@gmail.com', 'password')">
                <i class="fas fa-shield-alt me-1"></i> Admin (admin@gmail.com)
              </button>
            </div>
          </div>

          <form method="POST" action="{{ route('login.submit') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label small fw-semibold" for="email">Email Address</label>
              <input type="email" name="email" id="email" value="{{ old('email') }}"
                     class="form-control form-control-lg @error('email') is-invalid @enderror"
                     placeholder="Enter your email" required autofocus />
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label small fw-semibold" for="password">Password</label>
              <div class="password-input-wrapper position-relative">
                <input type="password" name="password" id="password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror"
                       placeholder="Enter your password" required />
                <button type="button" class="password-toggle-btn" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                  <svg class="eye-icon" id="eyeIcon-password" width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2"/></svg>
                  <svg class="eye-off-icon" id="eyeOffIcon-password" width="20" height="20" viewBox="0 0 24 24" fill="none" style="display: none;"><path d="m1 1 22 22" stroke="currentColor" stroke-width="2"/><path d="m6.71 6.71c-1.82 1.31-3.71 3.64-3.71 5.29s4 8 11 8c1.75 0 3.35-.39 4.71-1.07" stroke="currentColor" stroke-width="2"/></svg>
                </button>
              </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" />
                <label class="form-check-label small" for="remember">Remember me</label>
              </div>
              <div class="small">
                <a href="{{ route('author.login') }}" class="text-muted me-2">Author Login</a> •
                <a href="{{ route('admin.login') }}" class="text-muted ms-2">Admin Login</a>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">Sign In</button>

            <div class="text-center small">
              Don't have an account yet? <a href="{{ route('register') }}" class="fw-bold text-primary">Create an account here</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

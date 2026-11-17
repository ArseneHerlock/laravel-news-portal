<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>{{ $portalTitle }} - Zaira</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="{{ asset('assets/img/logo-dark.svg') }}" type="image/svg+xml">
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/app-V2aRcIkD.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/custom.css') }}">
</head>
<body class="!overflow-y-auto bg-white sm:bg-bg">
  <div class="min-h-screen sm:p-6 grid lg:grid-cols-2 gap-6 overflow-y-auto">
    <div class="hidden lg:flex bg-headline flex-col justify-between rounded-2xl overflow-hidden p-8">
      <div>
        <a href="{{ route('home') }}">
          <img src="{{ asset('assets/img/logo-light.svg') }}" alt="Zaira" style="height: 42px;" />
        </a>
      </div>
      <div class="my-auto text-center px-6">
        <span class="badge bg-danger mb-3 px-3 py-1 text-uppercase">{{ $portalRole === 'admin' ? 'Admin Moderation Console' : 'Author Publishing Studio' }}</span>
        <h1 class="text-[34px] leading-[120%] text-white font-semibold mb-4">{{ $portalTitle }}</h1>
        <p class="text-white/75 mb-6">Manage articles, editorial workflows, categories, and community discussions on the Zaira News &amp; Magazine platform.</p>
        <img src="{{ asset($portalRole === 'admin' ? 'assets/img/post-cloud-2.svg' : 'assets/img/post-tech-1.svg') }}" alt="Portal Preview" class="rounded-xl shadow-lg mx-auto" style="max-height: 260px;" />
      </div>
      <div class="text-white/60 text-sm text-center">
        Open Source Programming Course • Laravel 13 &amp; TiDB Cloud
      </div>
    </div>

    <div class="w-full flex items-center justify-center sm:p-6">
      <div class="w-full max-w-[540px] h-fit bg-white p-6 sm:p-10 rounded-2xl shadow-sm border">
        <div class="mb-6">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <h2 class="h3 fw-bold mb-0">{{ $portalTitle }}</h2>
            <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary"><i class="fas fa-home me-1"></i>Public Site</a>
          </div>
          <p class="text-muted small mb-0">Sign in with your {{ $portalRole }} credentials or use the 1-click demo button below.</p>
        </div>

        @if($errors->any())
          <div class="alert alert-danger py-2 small">
            @foreach($errors->all() as $err)
              <div>{{ $err }}</div>
            @endforeach
          </div>
        @endif

        <!-- 1-Click Auto-Fill Demo Box -->
        <div class="p-3 mb-4 rounded border bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div class="small">
            <strong>Demo {{ ucfirst($portalRole) }}:</strong> <code>{{ $demoEmail }}</code> / <code>{{ $demoPassword }}</code>
          </div>
          <button type="button" class="btn btn-sm btn-primary" onclick="fillDemoCredentials('{{ $demoEmail }}', '{{ $demoPassword }}')">
            Auto-Fill Credentials
          </button>
        </div>

        <form method="POST" action="{{ route('login.submit') }}">
          @csrf
          <div class="mb-4">
            <label class="form-label fw-semibold" for="email">Email Address</label>
            <input name="email" id="email" type="email" class="form-control form-control-lg"
                   placeholder="Enter your email" value="{{ old('email', $demoEmail) }}" required />
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold" for="password">Password</label>
            <div class="position-relative">
              <input type="password" name="password" id="password"
                     placeholder="Enter your password" value="{{ $demoPassword }}" class="form-control form-control-lg pe-5" required />
              <button type="button" class="btn btn-sm btn-link position-absolute top-50 end-0 translate-middle-y me-2 text-secondary"
                      onclick="togglePassword('password')">
                <i class="far fa-eye"></i>
              </button>
            </div>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input id="remember" name="remember" type="checkbox" class="form-check-input" checked />
              <label for="remember" class="form-check-label small">Remember session</label>
            </div>
            <div class="small">
              @if($portalRole === 'admin')
                <a href="{{ route('author.login') }}" class="text-primary">Switch to Author Login</a>
              @else
                <a href="{{ route('admin.login') }}" class="text-primary">Switch to Admin Login</a>
              @endif
            </div>
          </div>

          <button type="submit" class="btn btn-primary btn-lg w-100">Sign In to {{ ucfirst($portalRole) }} Portal</button>
        </form>

        <div class="mt-4 pt-3 border-top text-center small">
          Want to test immediately?
          <a href="{{ route('demo.switch', $portalRole) }}" class="fw-bold text-danger ms-1">
            Instant 1-Click Login as {{ ucfirst($portalRole) }} <i class="fas fa-bolt"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/zaira-lite.js') }}"></script>
</body>
</html>

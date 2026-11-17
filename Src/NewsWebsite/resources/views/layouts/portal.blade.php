<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>@yield('title', 'Editorial Portal — Zaira News')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="{{ asset('assets/img/logo-dark.svg') }}" type="image/svg+xml">
  <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/flowbite.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/app-V2aRcIkD.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/backend/css/custom.css') }}">
  <style>
    :root {
      --zaira-primary: #F4796C;
      --zaira-navy: #183354;
    }
    .portal-badge {
      display: inline-flex;
      align-items: center;
      padding: 3px 10px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 600;
    }
    .portal-badge.published, .portal-badge.approved, .portal-badge.active { background: rgba(23, 186, 58, 0.12); color: #15803d; }
    .portal-badge.pending { background: rgba(245, 158, 11, 0.15); color: #b45309; }
    .portal-badge.draft { background: rgba(107, 114, 128, 0.14); color: #374151; }
    .portal-badge.rejected, .portal-badge.banned { background: rgba(220, 53, 69, 0.14); color: #b91c1c; }
    .kpi-card {
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 14px;
      padding: 20px;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }
    a { text-decoration: none; }
  </style>
  @stack('styles')
</head>
<body class="flex flex-col-reverse sm:flex-row max-h-screen overflow-hidden bg-slate-50">
  @php
    $currentUser = auth()->user();
  @endphp

  <!-- Left Editorial Sidebar (Cleaned of all E-Commerce clutter) -->
  <section id="main-sidebar"
    class="left-side-bar h-screen group absolute xl:static bg-gray z-50 -left-[266px] collapse-sidebar:left-0 transition-all duration-300">
    <div class="relative bg-gray z-20 flex flex-col w-[265px] items-start h-full border-r border-border transition-all duration-300">
      <div class="flex justify-between items-center p-5 border-b border-border w-full">
        <a href="{{ route('home') }}" class="d-flex align-items-center gap-2">
          <img src="{{ asset('assets/img/logo-dark.svg') }}" alt="Zaira" style="height: 36px;">
        </a>
        <button id="sidebar-toggler-2" class="text-headline hover:text-main xl:hidden" aria-label="Close Sidebar">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <div class="py-4 px-3 flex flex-col gap-2 w-full flex-1 thin-scrollbar overflow-y-auto">
        <p class="text-xs px-3 text-gray-500 uppercase tracking-wider font-semibold mb-1">
          {{ $currentUser->isAdmin() ? 'Admin Console' : ($currentUser->isAuthor() ? 'Author Studio' : 'Reader Hub') }}
        </p>

        @if($currentUser->isAdmin())
          <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie w-5 text-center"></i>
            <span>Editorial Overview</span>
          </a>
          <a href="{{ route('admin.posts') }}" class="nav-item {{ request()->routeIs('admin.posts') ? 'active' : '' }}">
            <i class="fas fa-newspaper w-5 text-center"></i>
            <span>All Articles &amp; Moderation</span>
          </a>
          <a href="{{ route('author.posts.create') }}" class="nav-item {{ request()->routeIs('author.posts.create') ? 'active' : '' }}">
            <i class="fas fa-plus-circle w-5 text-center"></i>
            <span>Write New Article</span>
          </a>
          <a href="{{ route('admin.dashboard') }}#categories-section" class="nav-item">
            <i class="fas fa-folder-tree w-5 text-center"></i>
            <span>Categories &amp; Tags</span>
          </a>
          <a href="{{ route('admin.dashboard') }}#comments-section" class="nav-item">
            <i class="fas fa-comments w-5 text-center"></i>
            <span>Comment Moderation</span>
          </a>
          <a href="{{ route('admin.dashboard') }}#users-section" class="nav-item">
            <i class="fas fa-users-cog w-5 text-center"></i>
            <span>Users &amp; Roles</span>
          </a>
          <a href="{{ route('admin.dashboard') }}#messages-section" class="nav-item">
            <i class="fas fa-envelope-open-text w-5 text-center"></i>
            <span>Contact Inquiries</span>
          </a>
        @elseif($currentUser->isAuthor())
          <a href="{{ route('author.dashboard') }}" class="nav-item {{ request()->routeIs('author.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line w-5 text-center"></i>
            <span>Author Dashboard</span>
          </a>
          <a href="{{ route('author.posts') }}" class="nav-item {{ request()->routeIs('author.posts') ? 'active' : '' }}">
            <i class="fas fa-file-alt w-5 text-center"></i>
            <span>My Articles</span>
          </a>
          <a href="{{ route('author.posts.create') }}" class="nav-item {{ request()->routeIs('author.posts.create') ? 'active' : '' }}">
            <i class="fas fa-pen-nib w-5 text-center"></i>
            <span>Create New Article</span>
          </a>
          <a href="{{ route('blogs.author', $currentUser->username) }}" class="nav-item" target="_blank">
            <i class="fas fa-id-badge w-5 text-center"></i>
            <span>Public Author Profile</span>
          </a>
        @endif

        <p class="text-xs px-3 text-gray-500 uppercase tracking-wider font-semibold mt-4 mb-1">Personal Account</p>
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
          <i class="fas fa-bookmark w-5 text-center"></i>
          <span>Bookmarks &amp; Profile</span>
        </a>
        <a href="{{ route('home') }}" class="nav-item">
          <i class="fas fa-globe w-5 text-center"></i>
          <span>Back to Public News</span>
        </a>

        <div class="mt-auto pt-4 border-t border-border">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item w-full text-red-600">
              <i class="fas fa-sign-out-alt w-5 text-center"></i>
              <span>Sign Out</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content Container -->
  <div class="main-container flex flex-col h-screen flex-1 overflow-hidden">
    <div class="header-wrapper w-full bg-white border-b border-border py-3 px-6 flex gap-4 justify-between items-center print:hidden">
      <div class="flex items-center gap-3">
        <button id="sidebar-toggler" class="text-headline hover:text-main cursor-pointer btn btn-sm btn-light" title="Toggle Menu">
          <i class="fas fa-bars"></i>
        </button>
        <h5 class="mb-0 fw-bold text-dark d-none d-md-block">@yield('page_heading', 'Zaira Publishing Workspace')</h5>
      </div>

      <div class="flex items-center gap-3 flex-wrap">
        <div class="btn-group btn-group-sm d-none d-lg-inline-flex" role="group" aria-label="Demo Switcher">
          <a href="{{ route('demo.switch', 'user') }}" class="btn {{ $currentUser->isUser() ? 'btn-primary' : 'btn-outline-secondary' }}">Reader</a>
          <a href="{{ route('demo.switch', 'author') }}" class="btn {{ $currentUser->isAuthor() ? 'btn-primary' : 'btn-outline-secondary' }}">Author</a>
          <a href="{{ route('demo.switch', 'admin') }}" class="btn {{ $currentUser->isAdmin() ? 'btn-primary' : 'btn-outline-secondary' }}">Admin</a>
        </div>

        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-dark">
          <i class="fas fa-external-link-alt me-1"></i> View Live Portal
        </a>

        <div class="d-flex align-items-center gap-2 border-start ps-3">
          <img src="{{ asset($currentUser->avatar ?: 'assets/img/avatar-user.svg') }}" class="rounded-circle" style="width: 36px; height: 36px; object-fit: cover;" alt="{{ $currentUser->name }}">
          <div class="d-none d-sm-block lh-sm">
            <div class="fw-bold small text-dark">{{ $currentUser->name }}</div>
            <div class="text-muted" style="font-size: 11px;">{{ $currentUser->email }}</div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex-1 thin-scrollbar overflow-y-auto p-4 sm:p-6">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
          <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('assets/js/zaira-lite.js') }}"></script>
  @stack('scripts')
</body>
</html>

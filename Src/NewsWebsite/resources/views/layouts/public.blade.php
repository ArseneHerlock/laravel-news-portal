<!doctype html>
<html class="no-js" lang="{{ session('locale', 'en') }}" dir="{{ session('direction', 'ltr') }}">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/img/logo-dark.svg') }}" type="image/svg+xml" />
    <title>@yield('title', $globalSettings['site_name'] ?? 'Zaira — News & Magazine Portal')</title>
    <meta name="description" content="@yield('meta_description', $globalSettings['site_tagline'] ?? 'Zaira is a modern multi-role News & Magazine publishing platform built with Laravel and TiDB Cloud.')" />
    <link rel="alternate" type="application/rss+xml" title="Zaira News RSS Feed" href="{{ route('rss.feed') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome-all.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/zaira.css') }}" />
    <style>
      :root {
        --tg-primary-color: #f4796c;
        --tg-secondary-color: #183354;
      }
      #reading-progress-bar {
        position: fixed;
        top: 0;
        left: 0;
        height: 4px;
        width: 0%;
        background: linear-gradient(90deg, #f4796c, #ff9f43);
        z-index: 99999;
        transition: width 0.12s ease-out;
      }
      .zaira-demo-bar {
        background: #0c1622;
        color: #e2e8f0;
        font-size: 12px;
        padding: 6px 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      }
      .zaira-demo-bar a.demo-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 10px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 11px;
        background: rgba(244, 121, 108, 0.15);
        color: #f4796c;
        border: 1px solid rgba(244, 121, 108, 0.35);
      }
      .zaira-demo-bar a.demo-pill:hover,
      .zaira-demo-bar a.demo-pill.active {
        background: #f4796c;
        color: #fff;
      }
      .article-card-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 8px;
      }
      .hero-card-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
      }
    </style>
    @stack('styles')
  </head>
  <body class="theme_1">
    @yield('reading_progress')

    <!-- Quick Demo Role Switcher Bar for University Defense & Evaluation -->
    <div class="zaira-demo-bar">
      <div class="container d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          <span class="badge bg-danger">OPEN-SOURCE DEMO</span>
          <span class="d-none d-md-inline">1-Click Role Switcher:</span>
          <a href="{{ route('demo.switch', 'user') }}" class="demo-pill {{ auth()->check() && auth()->user()->isUser() ? 'active' : '' }}">
            <i class="fas fa-user"></i> Reader
          </a>
          <a href="{{ route('demo.switch', 'author') }}" class="demo-pill {{ auth()->check() && auth()->user()->isAuthor() ? 'active' : '' }}">
            <i class="fas fa-pen-nib"></i> Author Studio
          </a>
          <a href="{{ route('demo.switch', 'admin') }}" class="demo-pill {{ auth()->check() && auth()->user()->isAdmin() ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i> Admin Console
          </a>
        </div>
        <div class="d-flex align-items-center gap-3">
          <a href="{{ route('rss.feed') }}" target="_blank" class="text-light opacity-75"><i class="fas fa-rss text-warning me-1"></i>RSS Feed</a>
          @auth
            <span>Signed in as <strong class="text-white">{{ auth()->user()->name }}</strong> ({{ strtoupper(auth()->user()->role?->name ?? 'user') }})</span>
          @else
            <span>Guest Reader Mode</span>
          @endauth
        </div>
      </div>
    </div>

    <div class="darkmode-trigger" title="Toggle Dark / Light Mode">
      <label class="modeSwitch">
        <input type="checkbox" />
        <span class="icon"></span>
      </label>
    </div>

    <button class="scroll-top scroll-to-target" data-target="html" aria-label="Scroll to top">
      <i class="fas fa-angle-up"></i>
    </button>

    <header>
      <div id="header-fixed-height"></div>
      <div class="header-top-wrap">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-md-6">
              <div class="header-top-menu">
                <ul class="list-wrap">
                  <li><a href="{{ route('blogs.index') }}">All Articles</a></li>
                  <li><a href="{{ route('pages.about') }}">Editorial Team</a></li>
                  <li><a href="{{ route('pages.contact') }}">Contact Newsroom</a></li>
                  <li><a href="{{ route('pages.privacy') }}">Privacy &amp; Terms</a></li>
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="d-flex gap-3 justify-content-end align-items-center">
                <div class="header-top-social d-none d-sm-flex align-items-center gap-2">
                  <h5 class="title mb-0">Follow Us:</h5>
                  <ul class="list-wrap">
                    <li><a href="https://www.facebook.com/" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://www.x.com" target="_blank" rel="noopener"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="https://www.linkedin.com" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a></li>
                    <li><a href="{{ route('rss.feed') }}" target="_blank" title="RSS Feed"><i class="fas fa-rss"></i></a></li>
                  </ul>
                </div>
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-light dropdown-toggle py-1 px-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-globe me-1"></i> {{ strtoupper(session('locale', 'en')) }}
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">
                    @foreach($globalLanguages as $lang)
                      <li>
                        <a class="dropdown-item d-flex justify-content-between align-items-center {{ session('locale', 'en') === $lang->code ? 'active' : '' }}"
                           href="{{ route('language.switch', $lang->code) }}">
                          <span>{{ $lang->name }}</span>
                          <span class="badge bg-secondary ms-2">{{ strtoupper($lang->direction) }}</span>
                        </a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div id="sticky-header" class="menu-area">
        <div class="container">
          <div class="row g-0">
            <div class="col-12">
              <div class="menu-wrap">
                <div class="row align-items-center g-0">
                  <div class="col-xl-5">
                    <div class="header-left-side">
                      <div class="offcanvas-toggle">
                        <a href="javascript:void(0)" class="menu-tigger" aria-label="Open Offcanvas Info">
                          <span></span><span></span><span></span>
                        </a>
                      </div>
                      <div class="navbar-wrap main-menu d-none d-xl-flex">
                        <ul id="menu-primary-menu" class="navigation">
                          <li class="{{ request()->routeIs('home') ? 'active current-menu-item' : '' }}">
                            <a href="{{ route('home') }}">Home</a>
                          </li>
                          <li class="menu-item-has-children {{ request()->routeIs('blogs.*') ? 'active current-menu-item' : '' }}">
                            <a href="{{ route('blogs.index') }}">Categories</a>
                            <ul class="sub-menu">
                              @foreach($globalMenuCategories as $menuCat)
                                <li>
                                  <a href="{{ route('blogs.index', ['category' => $menuCat->slug]) }}">{{ $menuCat->name }}</a>
                                </li>
                              @endforeach
                              <li><a href="{{ route('blogs.index') }}">View All Archives</a></li>
                            </ul>
                          </li>
                          <li class="{{ request()->routeIs('pages.about') ? 'active current-menu-item' : '' }}">
                            <a href="{{ route('pages.about') }}">About</a>
                          </li>
                          <li class="{{ request()->routeIs('pages.contact') ? 'active current-menu-item' : '' }}">
                            <a href="{{ route('pages.contact') }}">Contact</a>
                          </li>
                          @auth
                            <li>
                              <a href="{{ auth()->user()->dashboardRoute() }}" class="text-danger fw-bold">
                                <i class="fas fa-th-large me-1"></i>Portal
                              </a>
                            </li>
                          @endauth
                        </ul>
                      </div>
                    </div>
                  </div>

                  <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <div class="logo text-center">
                      <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/img/logo-dark.svg') }}" alt="Zaira News" />
                      </a>
                    </div>
                  </div>

                  <div class="col-xl-5 col-lg-9 col-md-8 col-6">
                    <div class="header-right-side">
                      <div class="header-search-wrap d-none d-md-block">
                        <form action="{{ route('blogs.index') }}" method="GET">
                          <input value="{{ request('search') }}" name="search" type="text" placeholder="Search news, topics..." />
                          <button type="submit" aria-label="Search"><i class="flaticon-search"></i></button>
                        </form>
                      </div>
                      <div class="header-action">
                        <ul class="list-wrap">
                          @auth
                            <li class="header-sine-in d-flex align-items-center gap-2">
                              <a href="{{ auth()->user()->dashboardRoute() }}" class="fw-semibold">
                                <i class="flaticon-user"></i> {{ Str::words(auth()->user()->name, 2, '') }}
                              </a>
                              <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger ms-1 py-1 px-2" title="Sign Out">
                                  <i class="fas fa-sign-out-alt"></i>
                                </button>
                              </form>
                            </li>
                          @else
                            <li class="header-sine-in">
                              <a href="{{ route('login') }}"><i class="flaticon-user"></i> Sign In</a>
                            </li>
                          @endauth
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
              </div>

              <div class="mobile-menu">
                <nav class="menu-box">
                  <div class="close-btn"><i class="fas fa-times"></i></div>
                  <div class="nav-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-dark.svg') }}" alt="Zaira" /></a>
                  </div>
                  <div class="mobile-search">
                    <form action="{{ route('blogs.index') }}" method="GET">
                      <input value="{{ request('search') }}" name="search" type="text" placeholder="Search articles..." />
                      <button type="submit"><i class="flaticon-search"></i></button>
                    </form>
                  </div>
                  <div class="menu-outer"></div>
                </nav>
              </div>
              <div class="menu-backdrop"></div>
            </div>
          </div>
        </div>
      </div>

      <div class="offCanvas-wrap">
        <div class="offCanvas-body">
          <div class="offCanvas-toggle"><span></span><span></span></div>
          <div class="offCanvas-content">
            <div class="offCanvas-logo logo">
              <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-dark.svg') }}" alt="Zaira" /></a>
            </div>
            <p>{{ $globalSettings['footer_description'] ?? 'Zaira is an open-source multi-role News & Magazine publishing platform built with Laravel 13, TiDB Cloud, and Bootstrap 5.' }}</p>
            <div class="mt-4">
              <h5 class="mb-2">Quick Portal Access</h5>
              <div class="d-grid gap-2">
                <a href="{{ route('user.login') }}" class="btn btn-sm btn-outline-secondary text-start"><i class="fas fa-book-reader me-2"></i>Reader Portal</a>
                <a href="{{ route('author.login') }}" class="btn btn-sm btn-outline-secondary text-start"><i class="fas fa-pen-fancy me-2"></i>Author Studio</a>
                <a href="{{ route('admin.login') }}" class="btn btn-sm btn-outline-secondary text-start"><i class="fas fa-user-shield me-2"></i>Admin Console</a>
              </div>
            </div>
          </div>
          <div class="offCanvas-contact">
            <h4 class="title">Editorial Newsroom</h4>
            <ul class="offCanvas-contact-list list-wrap">
              <li><i class="fas fa-envelope-open"></i><a href="mailto:{{ $globalSettings['contact_email'] ?? 'editorial@zaira-news.org' }}">{{ $globalSettings['contact_email'] ?? 'editorial@zaira-news.org' }}</a></li>
              <li><i class="fas fa-phone"></i><a href="tel:{{ $globalSettings['contact_phone'] ?? '+8402838992026' }}">{{ $globalSettings['contact_phone'] ?? '+84 (028) 3899-2026' }}</a></li>
              <li><i class="fas fa-map-marker-alt"></i> {{ $globalSettings['contact_address'] ?? 'Ho Chi Minh City, Vietnam' }}</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="offCanvas-overlay"></div>
    </header>

    <!-- Flash Notifications -->
    <div class="container mt-3">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
          <div class="fw-bold mb-1"><i class="fas fa-exclamation-circle me-1"></i>Please check the form for errors:</div>
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
    </div>

    <main class="fix">
      @yield('content')
    </main>

    <footer>
      <div class="footer-area">
        <div class="footer-top">
          <div class="container">
            <div class="row">
              <div class="col-lg-4 col-md-7">
                <div class="footer-widget">
                  <div class="fw-logo">
                    <a href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-light.svg') }}" alt="Zaira" /></a>
                  </div>
                  <div class="footer-content">
                    <p>{{ $globalSettings['footer_description'] ?? 'Zaira is a modern multi-role News & Magazine publishing system built with Laravel, TiDB Cloud, and Bootstrap 5.' }}</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-2 col-md-5 col-sm-6">
                <div class="footer-widget">
                  <h4 class="fw-title">Categories</h4>
                  <div class="footer-link-wrap">
                    <ul class="list-wrap">
                      @foreach($globalMenuCategories->take(5) as $cat)
                        <li><a href="{{ route('blogs.index', ['category' => $cat->slug]) }}">{{ $cat->name }}</a></li>
                      @endforeach
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-widget">
                  <h4 class="fw-title">Role Portals</h4>
                  <div class="footer-link-wrap">
                    <ul class="list-wrap">
                      <li><a href="{{ route('user.login') }}">Reader Sign In</a></li>
                      <li><a href="{{ route('register') }}">Create Reader / Author Account</a></li>
                      <li><a href="{{ route('author.login') }}">Author Studio</a></li>
                      <li><a href="{{ route('admin.login') }}">Admin Moderation Console</a></li>
                      <li><a href="{{ route('rss.feed') }}">RSS 2.0 Syndication</a></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="footer-widget">
                  <h4 class="fw-title">Daily Newsletter</h4>
                  <p class="text-light opacity-75 small mb-3">Subscribe to receive breaking stories and open-source engineering digests.</p>
                  <form action="{{ route('newsletter.subscribe') }}" method="POST">
                    @csrf
                    <div class="input-group">
                      <input type="email" name="email" class="form-control form-control-sm" placeholder="Your email address" required />
                      <button class="btn btn-primary btn-sm" type="submit">Join</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="footer-bottom-menu">
                  <ul class="list-wrap">
                    <li><a href="{{ route('pages.privacy') }}">Privacy Policy &amp; Terms</a></li>
                    <li><a href="{{ route('pages.about') }}">About Us</a></li>
                    <li><a href="{{ route('pages.contact') }}">Contact</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-md-6">
                <div class="copyright-text text-md-end">
                  <p>© {{ date('Y') }} Zaira News Portal — Open Source Programming Course (MIT License).</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/js/zaira-lite.js') }}"></script>
    @stack('scripts')
  </body>
</html>

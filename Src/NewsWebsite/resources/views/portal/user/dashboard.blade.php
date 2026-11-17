@extends('layouts.portal')

@section('title', 'Reader Dashboard — Zaira News')
@section('page_heading', 'Reader Hub — Bookmarks, Comments & Account Settings')

@section('content')
  <!-- Reader KPI Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Saved Bookmarks</div>
          <div class="h3 fw-bold mb-0 mt-1">{{ $stats['bookmarks_count'] }}</div>
        </div>
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-bookmark fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Total Comments</div>
          <div class="h3 fw-bold mb-0 mt-1">{{ $stats['comments_count'] }}</div>
        </div>
        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-comments fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Approved Comments</div>
          <div class="h3 fw-bold mb-0 mt-1">{{ $stats['approved_comments'] }}</div>
        </div>
        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-check-double fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Support Tickets</div>
          <div class="h3 fw-bold mb-0 mt-1">{{ $stats['tickets_count'] }}</div>
        </div>
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-headset fa-lg"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column: Saved Bookmarks & My Comments -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
          <h5 class="mb-0 fw-bold"><i class="fas fa-bookmark text-primary me-2"></i>Saved Reading List (Bookmarks)</h5>
          <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-outline-primary">Browse More Articles</a>
        </div>
        <div class="card-body">
          @if($bookmarks->isEmpty())
            <div class="text-center py-4 text-muted">
              <i class="far fa-bookmark fa-2x mb-2"></i>
              <p class="mb-2">You haven't bookmarked any articles yet.</p>
              <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-primary">Explore News Archive</a>
            </div>
          @else
            <div class="table-responsive">
              <table class="table align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th>Article</th>
                    <th>Category</th>
                    <th>Saved On</th>
                    <th class="text-end">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($bookmarks as $bm)
                    @if($bm->post)
                      <tr>
                        <td>
                          <a href="{{ route('blogs.show', $bm->post->slug) }}" class="fw-semibold text-dark">
                            {{ Str::limit($bm->post->title, 55) }}
                          </a>
                          <div class="text-muted small">By {{ $bm->post->author?->name }}</div>
                        </td>
                        <td>
                          <span class="badge bg-primary bg-opacity-10 text-primary">{{ $bm->post->category?->name ?? 'News' }}</span>
                        </td>
                        <td class="small text-muted">{{ $bm->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                          <a href="{{ route('blogs.show', $bm->post->slug) }}" class="btn btn-sm btn-outline-secondary me-1">Read</a>
                          <form action="{{ route('blogs.bookmark.toggle', $bm->post) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Bookmark"><i class="fas fa-trash-alt"></i></button>
                          </form>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <!-- Recent Comments -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="far fa-comments text-success me-2"></i>My Recent Comments</h5>
        </div>
        <div class="card-body">
          @forelse($comments as $c)
            <div class="border-bottom pb-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <a href="{{ $c->post ? route('blogs.show', $c->post->slug) : '#' }}" class="fw-semibold small text-primary">
                  {{ $c->post?->title ?? 'Deleted Article' }}
                </a>
                <span class="portal-badge {{ $c->status }}">{{ ucfirst($c->status) }}</span>
              </div>
              <p class="small mb-1">{{ $c->comment }}</p>
              <span class="text-muted" style="font-size: 11px;">Posted {{ $c->created_at->diffForHumans() }}</span>
            </div>
          @empty
            <p class="text-muted small mb-0">You have not posted any comments yet.</p>
          @endforelse
        </div>
      </div>
    </div>

    <!-- Right Column: Profile Settings, Password Change & Author Upgrade -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit text-primary me-2"></i>My Profile</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label class="form-label small fw-semibold">Full Name</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required />
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Phone Number</label>
              <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control" />
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Short Bio</label>
              <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Profile Changes</button>
          </form>
        </div>
      </div>

      @if($user->isUser())
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-pen-nib text-warning me-2"></i>Apply to Become an Author</h5>
          </div>
          <div class="card-body">
            <p class="text-muted small">Want to publish articles on Zaira? Submit your editorial bio for Admin approval.</p>
            <form action="{{ route('user.request-author') }}" method="POST">
              @csrf
              <div class="mb-2">
                <label class="form-label small fw-semibold">Editorial Designation / Specialty</label>
                <input type="text" name="designation" value="{{ old('designation', $user->authorProfile?->designation) }}" class="form-control form-control-sm" placeholder="e.g. Tech & AI Columnist" required />
              </div>
              <div class="mb-3">
                <label class="form-label small fw-semibold">About Your Writing Experience</label>
                <textarea name="about" rows="2" class="form-control form-control-sm" required>{{ old('about', $user->authorProfile?->about) }}</textarea>
              </div>
              <button type="submit" class="btn btn-sm btn-outline-primary w-100">Submit Author Application</button>
            </form>
          </div>
        </div>
      @endif

      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-lock text-secondary me-2"></i>Change Password</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('user.password.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-2">
              <label class="form-label small fw-semibold">Current Password</label>
              <input type="password" name="current_password" class="form-control form-control-sm" required />
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">New Password</label>
              <input type="password" name="password" class="form-control form-control-sm" required />
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Confirm New Password</label>
              <input type="password" name="password_confirmation" class="form-control form-control-sm" required />
            </div>
            <button type="submit" class="btn btn-sm btn-secondary w-100">Update Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

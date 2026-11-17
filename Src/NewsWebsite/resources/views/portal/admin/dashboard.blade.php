@extends('layouts.portal')

@section('title', 'Admin Editorial Console — Zaira News')
@section('page_heading', 'Admin Console — System-Wide Publishing, Moderation & Access Control')

@section('content')
  <!-- Top System-Wide Editorial KPI Cards (Replacing E-Commerce Orders/Sales) -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Published / Total Articles</div>
          <div class="h3 fw-bold mb-0 mt-1 text-primary">{{ $stats['published_posts'] }} <span class="text-muted fs-6">/ {{ $stats['total_posts'] }}</span></div>
          <div class="text-warning small fw-semibold mt-1"><i class="fas fa-clock me-1"></i>{{ $stats['pending_posts'] }} awaiting review</div>
        </div>
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-newspaper fa-lg"></i>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Total Portal Reads</div>
          <div class="h3 fw-bold mb-0 mt-1 text-success">{{ number_format($stats['total_views']) }}</div>
          <div class="text-muted small mt-1"><i class="fas fa-rss me-1"></i>Live syndicated feed</div>
        </div>
        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-chart-bar fa-lg"></i>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Registered Users &amp; Authors</div>
          <div class="h3 fw-bold mb-0 mt-1 text-dark">{{ $stats['total_users'] }}</div>
          <div class="text-muted small mt-1">{{ $stats['total_authors'] }} Editorial Contributors</div>
        </div>
        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-users fa-lg"></i>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Community &amp; Newsletter</div>
          <div class="h3 fw-bold mb-0 mt-1 text-danger">{{ $stats['total_comments'] }} <span class="fs-6 text-muted">Comments</span></div>
          <div class="text-muted small mt-1">{{ $stats['subscribers_count'] }} Newsletter Subscribers</div>
        </div>
        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-bullhorn fa-lg"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- 1. Pending Moderation Queue -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
      <div>
        <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-check text-warning me-2"></i>Editorial Moderation Queue (Pending Author Submissions)</h5>
      </div>
      <div class="d-flex gap-2">
        <a href="{{ route('admin.posts') }}" class="btn btn-sm btn-outline-primary">Manage All Articles ({{ $stats['total_posts'] }})</a>
        <a href="{{ route('author.posts.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>New Article</a>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Title</th>
              <th>Author</th>
              <th>Category</th>
              <th>Submitted</th>
              <th class="text-end pe-4">Quick Editorial Decision</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pendingPosts as $pp)
              <tr>
                <td class="ps-4">
                  <a href="{{ route('blogs.show', $pp->slug) }}" class="fw-bold text-dark">{{ $pp->title }}</a>
                  <div class="text-muted small">{{ Str::limit($pp->summary, 80) }}</div>
                </td>
                <td class="small fw-semibold">{{ $pp->author?->name }}</td>
                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $pp->category?->name }}</span></td>
                <td class="small text-muted">{{ $pp->created_at->diffForHumans() }}</td>
                <td class="text-end pe-4">
                  <div class="d-inline-flex gap-1 align-items-center">
                    <a href="{{ route('blogs.show', $pp->slug) }}" class="btn btn-sm btn-outline-secondary" title="Preview"><i class="far fa-eye"></i></a>

                    <!-- Approve & Publish -->
                    <form action="{{ route('admin.posts.moderate', $pp) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="published" />
                      <button type="submit" class="btn btn-sm btn-success">
                        <i class="fas fa-check me-1"></i>Approve &amp; Publish
                      </button>
                    </form>

                    <!-- Reject with Editorial Note -->
                    <form action="{{ route('admin.posts.moderate', $pp) }}" method="POST" class="d-inline"
                          onsubmit="const note = prompt('Enter editorial review reason for rejecting this article:', 'Please expand citations and formatting.'); if(note === null) return false; this.querySelector('[name=rejection_reason]').value = note;">
                      @csrf
                      @method('PATCH')
                      <input type="hidden" name="status" value="rejected" />
                      <input type="hidden" name="rejection_reason" value="" />
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-times me-1"></i>Reject
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                  <i class="fas fa-check-circle text-success me-1"></i> All caught up! No articles are waiting in the pending queue.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 2. Categories & Tags Management (Member 4 Scope) -->
  <div class="row g-4 mb-4" id="categories-section">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-folder-open text-primary me-2"></i>Categories Management ({{ $categories->count() }})</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.categories.store') }}" method="POST" class="row g-2 mb-3 pb-3 border-bottom">
            @csrf
            <div class="col-sm-4">
              <input type="text" name="name" class="form-control form-control-sm" placeholder="New Category Name *" required />
            </div>
            <div class="col-sm-5">
              <input type="text" name="description" class="form-control form-control-sm" placeholder="Short description..." />
            </div>
            <div class="col-sm-3">
              <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-plus me-1"></i>Add Category</button>
            </div>
          </form>

          <div class="table-responsive" style="max-height: 260px;">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Articles</th>
                  <th class="text-end">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($categories as $cat)
                  <tr>
                    <td class="fw-semibold">{{ $cat->name }}</td>
                    <td><code>{{ $cat->slug }}</code></td>
                    <td><span class="badge bg-secondary">{{ $cat->posts_count }}</span></td>
                    <td class="text-end">
                      <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" class="btn btn-sm btn-link py-0" target="_blank">View</a>
                      @if($cat->posts_count === 0)
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-link text-danger py-0"><i class="fas fa-trash-alt"></i></button>
                        </form>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-tags text-info me-2"></i>Topic Tags ({{ $tags->count() }})</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.tags.store') }}" method="POST" class="input-group input-group-sm mb-3 pb-3 border-bottom">
            @csrf
            <input type="text" name="name" class="form-control" placeholder="New tag name (e.g. DevSecOps)..." required />
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i>Add Tag</button>
          </form>

          <div class="d-flex flex-wrap gap-2">
            @foreach($tags as $tag)
              <div class="badge bg-light text-dark border d-inline-flex align-items-center gap-2 px-2.5 py-2">
                <span>#{{ $tag->name }} ({{ $tag->posts_count }})</span>
                <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-link btn-sm text-danger p-0 lh-1" title="Delete Tag">&times;</button>
                </form>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- 3. Comment Moderation & Contact Inquiries -->
  <div class="row g-4 mb-4" id="comments-section">
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-comments text-success me-2"></i>Recent Reader Comments</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">Reader &amp; Comment</th>
                  <th>Article</th>
                  <th>Status</th>
                  <th class="text-end pe-3">Moderation</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentComments as $c)
                  <tr>
                    <td class="ps-3">
                      <div class="fw-semibold small">{{ $c->user?->name ?? 'Reader' }}</div>
                      <div class="text-muted small">{{ Str::limit($c->comment, 70) }}</div>
                    </td>
                    <td class="small">
                      @if($c->post)
                        <a href="{{ route('blogs.show', $c->post->slug) }}">{{ Str::limit($c->post->title, 32) }}</a>
                      @endif
                    </td>
                    <td><span class="portal-badge {{ $c->status }}">{{ ucfirst($c->status) }}</span></td>
                    <td class="text-end pe-3 text-nowrap">
                      @if($c->status !== 'approved')
                        <form action="{{ route('admin.comments.moderate', $c) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <input type="hidden" name="status" value="approved" />
                          <button type="submit" class="btn btn-sm btn-outline-success py-0 px-2">Approve</button>
                        </form>
                      @endif
                      <form action="{{ route('admin.comments.destroy', $c) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2"><i class="fas fa-trash-alt"></i></button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center py-3 text-muted">No comments found.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5" id="messages-section">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold"><i class="fas fa-envelope-open-text text-warning me-2"></i>Contact Form Inquiries</h5>
        </div>
        <div class="card-body">
          @forelse($contactMessages as $msg)
            <div class="border rounded p-3 mb-2 {{ $msg->is_read ? 'bg-light' : 'border-warning' }}">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="small">{{ $msg->name }} ({{ $msg->email }})</strong>
                @if(! $msg->is_read)
                  <form action="{{ route('admin.messages.read', $msg) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-warning py-0 px-2" style="font-size: 11px;">Mark Read</button>
                  </form>
                @else
                  <span class="badge bg-secondary">Read</span>
                @endif
              </div>
              <div class="fw-semibold small text-primary">{{ $msg->subject }}</div>
              <p class="small text-muted mb-0">{{ Str::limit($msg->message, 120) }}</p>
            </div>
          @empty
            <p class="text-muted small mb-0">No contact messages received yet.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  <!-- 4. Users & Role Management -->
  <div class="card border-0 shadow-sm" id="users-section">
    <div class="card-header bg-white py-3">
      <h5 class="mb-0 fw-bold"><i class="fas fa-users-cog text-primary me-2"></i>Users &amp; Role Access Control ({{ $users->count() }})</h5>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">User</th>
              <th>Email / Username</th>
              <th>Articles</th>
              <th>Role &amp; Account Status</th>
            </tr>
          </thead>
          <tbody>
            @foreach($users as $u)
              <tr>
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-2">
                    <img src="{{ asset($u->avatar ?: 'assets/img/avatar-user.svg') }}" class="rounded-circle" style="width: 34px; height: 34px;" alt="{{ $u->name }}" />
                    <div>
                      <div class="fw-bold small">{{ $u->name }}</div>
                      @if($u->authorProfile && ! $u->authorProfile->is_approved)
                        <span class="badge bg-warning text-dark">Requested Author Approval</span>
                      @endif
                    </div>
                  </div>
                </td>
                <td class="small">
                  <div>{{ $u->email }}</div>
                  <code class="text-muted">{{ '@' . $u->username }}</code>
                </td>
                <td><span class="badge bg-secondary">{{ $u->posts_count }}</span></td>
                <td>
                  <form action="{{ route('admin.users.role', $u) }}" method="POST" class="d-flex gap-2 align-items-center">
                    @csrf
                    @method('PATCH')
                    <select name="role_id" class="form-select form-select-sm" style="max-width: 150px;">
                      <option value="1" {{ (int) $u->role_id === 1 ? 'selected' : '' }}>Admin</option>
                      <option value="2" {{ (int) $u->role_id === 2 ? 'selected' : '' }}>Author / Editor</option>
                      <option value="3" {{ (int) $u->role_id === 3 ? 'selected' : '' }}>Reader (User)</option>
                    </select>
                    <select name="status" class="form-select form-select-sm" style="max-width: 120px;">
                      <option value="active" {{ $u->status === 'active' ? 'selected' : '' }}>Active</option>
                      <option value="pending" {{ $u->status === 'pending' ? 'selected' : '' }}>Pending</option>
                      <option value="banned" {{ $u->status === 'banned' ? 'selected' : '' }}>Banned</option>
                    </select>
                    <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection

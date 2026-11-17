@extends('layouts.portal')

@section('title', 'Author Studio Dashboard — Zaira News')
@section('page_heading', 'Author Studio — Editorial Analytics & Article Management')

@section('content')
  {{-- Author Editorial KPI Cards --}}
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Published Articles</div>
          <div class="h3 fw-bold mb-0 mt-1 text-success">{{ $stats['published_posts'] }}</div>
          <div class="text-muted" style="font-size: 11px;">Out of {{ $stats['total_posts'] }} total submissions</div>
        </div>
        <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-check-circle fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Awaiting Moderation</div>
          <div class="h3 fw-bold mb-0 mt-1 text-warning">{{ $stats['pending_posts'] }}</div>
          <div class="text-muted" style="font-size: 11px;">{{ $stats['draft_posts'] }} saved in Drafts</div>
        </div>
        <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-hourglass-half fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Total Article Reads</div>
          <div class="h3 fw-bold mb-0 mt-1 text-primary">{{ number_format($stats['total_views']) }}</div>
          <div class="text-muted" style="font-size: 11px;">Cumulative reader views</div>
        </div>
        <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-eye fa-lg"></i>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="kpi-card d-flex align-items-center justify-content-between">
        <div>
          <div class="text-muted small fw-semibold">Reader Comments</div>
          <div class="h3 fw-bold mb-0 mt-1 text-info">{{ $stats['total_comments'] }}</div>
          <div class="text-muted" style="font-size: 11px;">Across your articles</div>
        </div>
        <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 52px; height: 52px;">
          <i class="fas fa-comments fa-lg"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Recent Articles by Author -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
          <h5 class="mb-0 fw-bold">My Recent Articles</h5>
          <div class="d-flex gap-2">
            <a href="{{ route('author.posts') }}" class="btn btn-sm btn-outline-secondary">Manage All Articles</a>
            <a href="{{ route('author.posts.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i>Write New Article</a>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-4">Article Title</th>
                  <th>Category</th>
                  <th>Status</th>
                  <th>Views</th>
                  <th>Updated</th>
                  <th class="text-end pe-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($recentPosts as $p)
                  <tr>
                    <td class="ps-4">
                      <a href="{{ route('blogs.show', $p->slug) }}" class="fw-semibold text-dark d-block">{{ Str::limit($p->title, 52) }}</a>
                      @if($p->status === 'rejected' && $p->rejection_reason)
                        <div class="text-danger small mt-1">
                          <i class="fas fa-info-circle me-1"></i>Editor Note: {{ $p->rejection_reason }}
                        </div>
                      @endif
                    </td>
                    <td><span class="badge bg-light text-dark border">{{ $p->category?->name ?? 'News' }}</span></td>
                    <td><span class="portal-badge {{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    <td>{{ number_format($p->views_count) }}</td>
                    <td class="small text-muted">{{ $p->updated_at->format('d M Y') }}</td>
                    <td class="text-end pe-4">
                      <a href="{{ route('blogs.show', $p->slug) }}" class="btn btn-sm btn-outline-secondary" title="Preview"><i class="far fa-eye"></i></a>
                      <a href="{{ route('author.posts.edit', $p) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="far fa-edit"></i></a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No articles yet. Click "Write New Article" to begin.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Reader Feedback on Author's Posts -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold">Latest Reader Comments</h5>
        </div>
        <div class="card-body">
          @forelse($recentComments as $rc)
            <div class="border-bottom pb-3 mb-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <strong class="small">{{ $rc->user?->name ?? 'Reader' }}</strong>
                <span class="text-muted" style="font-size: 11px;">{{ $rc->created_at->diffForHumans() }}</span>
              </div>
              <p class="small text-muted mb-1">{{ Str::limit($rc->comment, 90) }}</p>
              <a href="{{ $rc->post ? route('blogs.show', $rc->post->slug) : '#' }}" class="small text-primary">
                On: {{ Str::limit($rc->post?->title, 40) }}
              </a>
            </div>
          @empty
            <p class="text-muted small mb-0">No comments on your articles yet.</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection

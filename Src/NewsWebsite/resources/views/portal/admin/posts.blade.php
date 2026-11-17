@extends('layouts.portal')

@section('title', 'All Articles & Moderation — Admin Console | Zaira News')
@section('page_heading', 'Admin Console — Article Management & Moderation')

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
      <!-- Status Filter Tabs -->
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.posts', ['status' => 'all']) }}" class="btn btn-sm {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
          All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.posts', ['status' => 'published']) }}" class="btn btn-sm {{ $statusFilter === 'published' ? 'btn-success' : 'btn-outline-secondary' }}">
          Published ({{ $counts['published'] }})
        </a>
        <a href="{{ route('admin.posts', ['status' => 'pending']) }}" class="btn btn-sm {{ $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">
          Pending Review ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('admin.posts', ['status' => 'draft']) }}" class="btn btn-sm {{ $statusFilter === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' }}">
          Draft ({{ $counts['draft'] }})
        </a>
        <a href="{{ route('admin.posts', ['status' => 'rejected']) }}" class="btn btn-sm {{ $statusFilter === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">
          Rejected ({{ $counts['rejected'] }})
        </a>
      </div>

      <!-- Search & Category Filter -->
      <div class="d-flex flex-wrap gap-2 align-items-center">
        <form action="{{ route('admin.posts') }}" method="GET" class="d-flex gap-2">
          <input type="hidden" name="status" value="{{ $statusFilter }}" />
          <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 160px;">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
              <option value="{{ $cat->id }}" {{ (int) $categoryFilter === $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
          </select>
          <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search title or author..." />
          <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('author.posts.create') }}" class="btn btn-sm btn-primary text-nowrap">
          <i class="fas fa-plus me-1"></i> Create Article
        </a>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Cover</th>
              <th>Title &amp; Flags</th>
              <th>Author</th>
              <th>Category</th>
              <th>Status</th>
              <th>Views</th>
              <th class="text-end pe-4">Moderation &amp; Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($posts as $post)
              <tr>
                <td class="ps-4">
                  <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="rounded" style="width: 64px; height: 44px; object-fit: cover;" />
                </td>
                <td>
                  <a href="{{ route('blogs.show', $post->slug) }}" class="fw-bold text-dark d-block">{{ $post->title }}</a>
                  <div class="d-flex flex-wrap gap-1 mt-1">
                    @if($post->is_featured)
                      <span class="badge bg-primary" style="font-size: 10px;">Featured</span>
                    @endif
                    @if($post->is_breaking)
                      <span class="badge bg-danger" style="font-size: 10px;">Breaking</span>
                    @endif
                    @if($post->is_recommended)
                      <span class="badge bg-info text-dark" style="font-size: 10px;">Recommended</span>
                    @endif
                    <span class="text-muted small">• {{ $post->reading_time }} min read</span>
                  </div>
                  @if($post->status === 'rejected' && $post->rejection_reason)
                    <div class="text-danger small mt-1">
                      <strong>Reason:</strong> {{ $post->rejection_reason }}
                    </div>
                  @endif
                </td>
                <td class="small">
                  <div class="fw-semibold">{{ $post->author?->name }}</div>
                  <div class="text-muted" style="font-size: 11px;">{{ $post->created_at->format('d M Y') }}</div>
                </td>
                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $post->category?->name }}</span></td>
                <td><span class="portal-badge {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                <td>{{ number_format($post->views_count) }}</td>
                <td class="text-end pe-4 text-nowrap">
                  <div class="d-inline-flex gap-1 align-items-center">
                    @if($post->status !== 'published')
                      <form action="{{ route('admin.posts.moderate', $post) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="published" />
                        <button type="submit" class="btn btn-sm btn-success" title="Approve & Publish">
                          <i class="fas fa-check"></i> Publish
                        </button>
                      </form>
                    @endif

                    @if($post->status !== 'rejected')
                      <form action="{{ route('admin.posts.moderate', $post) }}" method="POST" class="d-inline"
                            onsubmit="const note = prompt('Editorial reason for rejecting this article:', 'Needs revision.'); if(note === null) return false; this.querySelector('[name=rejection_reason]').value = note;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="rejected" />
                        <input type="hidden" name="rejection_reason" value="" />
                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Reject with Editorial Note">
                          <i class="fas fa-ban"></i>
                        </button>
                      </form>
                    @endif

                    <a href="{{ route('blogs.show', $post->slug) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="far fa-eye"></i></a>
                    <a href="{{ route('author.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="far fa-edit"></i></a>

                    <form action="{{ route('author.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Permanently delete this article?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="far fa-trash-alt"></i></button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">No articles match your filter criteria.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @if($posts->hasPages())
      <div class="card-footer bg-white py-3">
        {{ $posts->links() }}
      </div>
    @endif
  </div>
@endsection

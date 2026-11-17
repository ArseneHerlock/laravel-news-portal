@extends('layouts.portal')

@section('title', 'My Articles — Author Studio | Zaira News')
@section('page_heading', 'Author Studio — Manage Articles')

@section('content')
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
      <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('author.posts', ['status' => 'all']) }}" class="btn btn-sm {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
          All ({{ $counts['all'] }})
        </a>
        <a href="{{ route('author.posts', ['status' => 'published']) }}" class="btn btn-sm {{ $statusFilter === 'published' ? 'btn-success' : 'btn-outline-secondary' }}">
          Published ({{ $counts['published'] }})
        </a>
        <a href="{{ route('author.posts', ['status' => 'pending']) }}" class="btn btn-sm {{ $statusFilter === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">
          Pending Review ({{ $counts['pending'] }})
        </a>
        <a href="{{ route('author.posts', ['status' => 'draft']) }}" class="btn btn-sm {{ $statusFilter === 'draft' ? 'btn-secondary' : 'btn-outline-secondary' }}">
          Draft ({{ $counts['draft'] }})
        </a>
        <a href="{{ route('author.posts', ['status' => 'rejected']) }}" class="btn btn-sm {{ $statusFilter === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">
          Rejected ({{ $counts['rejected'] }})
        </a>
      </div>

      <div class="d-flex gap-2 align-items-center">
        <form action="{{ route('author.posts') }}" method="GET" class="d-flex gap-2">
          <input type="hidden" name="status" value="{{ $statusFilter }}" />
          <input type="text" name="search" value="{{ $search }}" class="form-control form-control-sm" placeholder="Search my articles..." />
          <button type="submit" class="btn btn-sm btn-outline-primary"><i class="fas fa-search"></i></button>
        </form>
        <a href="{{ route('author.posts.create') }}" class="btn btn-sm btn-primary text-nowrap">
          <i class="fas fa-plus me-1"></i> Add New Article
        </a>
      </div>
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Cover</th>
              <th>Title &amp; Summary</th>
              <th>Category</th>
              <th>Language</th>
              <th>Status</th>
              <th>Views</th>
              <th>Created</th>
              <th class="text-end pe-4">Actions</th>
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
                  <span class="text-muted small">{{ Str::limit($post->summary, 75) }}</span>
                  @if($post->status === 'rejected' && $post->rejection_reason)
                    <div class="text-danger small mt-1">
                      <strong>Editor Review Note:</strong> {{ $post->rejection_reason }}
                    </div>
                  @endif
                </td>
                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $post->category?->name }}</span></td>
                <td class="small">{{ $post->language?->name ?? 'English' }}</td>
                <td><span class="portal-badge {{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                <td>{{ number_format($post->views_count) }}</td>
                <td class="small text-muted">{{ $post->created_at->format('d M Y') }}</td>
                <td class="text-end pe-4 text-nowrap">
                  <a href="{{ route('blogs.show', $post->slug) }}" class="btn btn-sm btn-outline-secondary" title="View"><i class="far fa-eye"></i></a>
                  <a href="{{ route('author.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary" title="Edit"><i class="far fa-edit"></i></a>
                  <form action="{{ route('author.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this article?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"><i class="far fa-trash-alt"></i></button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="text-center py-5">
                  <h5 class="text-muted mb-2">No articles found</h5>
                  <p class="text-muted small mb-3">No articles match your current filter or search query.</p>
                  <a href="{{ route('author.posts.create') }}" class="btn btn-sm btn-primary">Create Your First Article</a>
                </td>
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

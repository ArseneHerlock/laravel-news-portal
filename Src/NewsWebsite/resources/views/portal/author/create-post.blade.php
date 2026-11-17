@extends('layouts.portal')

@section('title', ($post ? 'Edit Article' : 'Create New Article') . ' — Zaira News')
@section('page_heading', $post ? 'Edit Article: ' . Str::limit($post->title, 45) : 'Create New Article')

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ $post ? 'Edit Article' : 'Compose New Article' }}</h4>
      <p class="text-muted small mb-0">All fields are mapped directly to the relational <code>posts</code> and <code>post_tag</code> tables.</p>
    </div>
    <a href="{{ auth()->user()->isAdmin() ? route('admin.posts') : route('author.posts') }}" class="btn btn-outline-secondary btn-sm">
      <i class="fas fa-arrow-left me-1"></i> Back to Articles
    </a>
  </div>

  <div class="card border-0 shadow-sm p-4">
    <form action="{{ $post ? route('author.posts.update', $post) : route('author.posts.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      @if($post)
        @method('PUT')
      @endif

      <div class="row g-4">
        <!-- Main Content & SEO Column -->
        <div class="col-lg-8 border-end-lg pe-lg-4">
          <h5 class="fw-bold mb-3 pb-2 border-bottom">1. Editorial Content</h5>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="title">Article Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $post?->title) }}" class="form-control form-control-lg" placeholder="Enter a compelling headline..." required />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="slug">URL Slug (Optional)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $post?->slug) }}" class="form-control" placeholder="Leave empty to auto-generate unique slug from title" />
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold" for="summary">Executive Summary / Excerpt</label>
            <textarea name="summary" id="summary" rows="3" class="form-control" placeholder="Short summary displayed on homepage cards and search results...">{{ old('summary', $post?->summary) }}</textarea>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold" for="content">Full Article Body (Supports HTML headings <code>&lt;h2&gt;</code>, <code>&lt;h3&gt;</code> for Auto-TOC) <span class="text-danger">*</span></label>
            <textarea name="content" id="content" rows="12" class="form-control font-monospace" placeholder="Write your full article content here..." required>{{ old('content', $post?->content) }}</textarea>
          </div>

          <h5 class="fw-bold mb-3 pb-2 border-bottom">2. Search Engine Optimization (SEO Metadata)</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Meta Title</label>
              <input type="text" name="meta_title" value="{{ old('meta_title', $post?->meta_title) }}" class="form-control" placeholder="Defaults to article title" />
            </div>
            <div class="col-md-6">
              <label class="form-label small fw-semibold">Meta Keywords (Comma-separated)</label>
              <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $post?->meta_keywords) }}" class="form-control" placeholder="laravel, open-source, tidb, news" />
            </div>
            <div class="col-12">
              <label class="form-label small fw-semibold">Meta Description</label>
              <textarea name="meta_description" rows="2" class="form-control" placeholder="Defaults to article summary">{{ old('meta_description', $post?->meta_description) }}</textarea>
            </div>
          </div>
        </div>

        <!-- Right Publishing & Taxonomy Column -->
        <div class="col-lg-4">
          <h5 class="fw-bold mb-3 pb-2 border-bottom">3. Publishing &amp; Workflow</h5>

          <div class="mb-3">
            <label class="form-label fw-semibold">Workflow Status <span class="text-danger">*</span></label>
            <select name="status" class="form-select" required>
              <option value="pending" {{ old('status', $post?->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Submit for Review (Pending)</option>
              <option value="draft" {{ old('status', $post?->status) === 'draft' ? 'selected' : '' }}>Save as Draft</option>
              @if(auth()->user()->isAdmin())
                <option value="published" {{ old('status', $post?->status) === 'published' ? 'selected' : '' }}>Publish Immediately (Admin)</option>
              @endif
            </select>
            @if(! auth()->user()->isAdmin())
              <div class="form-text small">Authors submit articles to the <strong>Pending</strong> editorial queue for Admin approval.</div>
            @endif
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Language <span class="text-danger">*</span></label>
              <select name="language_id" class="form-select" required>
                @foreach($languages as $lang)
                  <option value="{{ $lang->id }}" {{ (int) old('language_id', $post?->language_id ?? 1) === $lang->id ? 'selected' : '' }}>
                    {{ $lang->name }} ({{ strtoupper($lang->code) }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                  <option value="{{ $cat->id }}" {{ (int) old('category_id', $post?->category_id) === $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Editorial Highlight Flags (Aligned with Database/zaira_schema.sql) -->
          <div class="p-3 rounded border bg-light mb-3">
            <div class="fw-semibold small mb-2">Editorial Placement Flags</div>
            @if(auth()->user()->isAdmin())
              <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured" {{ old('is_featured', $post?->is_featured) ? 'checked' : '' }} />
                <label class="form-check-label small" for="is_featured">Featured Cover Story (<code>is_featured</code>)</label>
              </div>
              <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="is_breaking" value="1" id="is_breaking" {{ old('is_breaking', $post?->is_breaking) ? 'checked' : '' }} />
                <label class="form-check-label small" for="is_breaking">Breaking News Ticker (<code>is_breaking</code>)</label>
              </div>
              <div class="form-check mb-1">
                <input class="form-check-input" type="checkbox" name="is_slider" value="1" id="is_slider" {{ old('is_slider', $post?->is_slider) ? 'checked' : '' }} />
                <label class="form-check-label small" for="is_slider">Homepage Slider (<code>is_slider</code>)</label>
              </div>
            @endif
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_recommended" value="1" id="is_recommended" {{ old('is_recommended', $post?->is_recommended) ? 'checked' : '' }} />
              <label class="form-check-label small" for="is_recommended">Editor's Recommendation (<code>is_recommended</code>)</label>
            </div>
          </div>

          <!-- Video Stream Option -->
          <div class="mb-3">
            <div class="form-check mb-2">
              <input type="checkbox" id="is_video_blog" class="form-check-input" {{ old('video_url', $post?->video_url) ? 'checked' : '' }} />
              <label for="is_video_blog" class="form-check-label small fw-semibold">Include Video Stream URL</label>
            </div>
            <div id="video_url" class="{{ old('video_url', $post?->video_url) ? '' : 'hidden' }}">
              <input type="url" name="video_url" value="{{ old('video_url', $post?->video_url) }}" class="form-control form-control-sm" placeholder="https://www.youtube.com/watch?v=..." />
            </div>
          </div>

          <!-- Many-to-Many Tags (post_tag) -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Topic Tags (<code>post_tag</code>)</label>
            @php
              $selectedTags = old('tags', $post ? $post->tags->pluck('id')->all() : []);
            @endphp
            <div class="border rounded p-2 bg-white" style="max-height: 165px; overflow-y: auto;">
              @foreach($tags as $tag)
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}"
                         {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }} />
                  <label class="form-check-label small" for="tag_{{ $tag->id }}">#{{ $tag->name }}</label>
                </div>
              @endforeach
            </div>
          </div>

          <!-- Featured Cover Image Upload + Live Preview -->
          <div class="mb-4">
            <label class="form-label fw-semibold" for="cover-image">Featured Cover Image</label>
            <input type="file" name="featured_image" id="cover-image" accept="image/*" class="form-control form-control-sm mb-2" />
            <img id="cover-image-preview"
                 src="{{ $post ? $post->image_url : '' }}"
                 alt="Cover Preview"
                 class="img-fluid rounded border {{ $post ? '' : 'd-none' }}"
                 style="max-height: 150px; width: 100%; object-fit: cover;" />
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-save me-1"></i> {{ $post ? 'Save Article Changes' : 'Submit Article' }}
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
@endsection

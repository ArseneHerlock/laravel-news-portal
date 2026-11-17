<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>{{ $globalSettings['site_name'] ?? 'Zaira — News & Magazine Portal' }}</title>
    <link>{{ route('home') }}</link>
    <description>{{ $globalSettings['site_tagline'] ?? 'Open-Source News & Magazine Publishing System' }}</description>
    <language>en-us</language>
    <atom:link href="{{ route('rss.feed') }}" rel="self" type="application/rss+xml" />
    @foreach($posts as $post)
      <item>
        <title><![CDATA[{{ $post->title }}]]></title>
        <link>{{ route('blogs.show', $post->slug) }}</link>
        <guid isPermaLink="true">{{ route('blogs.show', $post->slug) }}</guid>
        <category><![CDATA[{{ $post->category?->name ?? 'News' }}]]></category>
        <description><![CDATA[{{ $post->summary }}]]></description>
        <pubDate>{{ ($post->published_at ?? $post->created_at)->toRssString() }}</pubDate>
      </item>
    @endforeach
  </channel>
</rss>

@extends('layouts.app')

@section('title', $post->title.' - '.$siteName)
@section('description', $post->excerpt ?? Str::limit(strip_tags($post->safe_content), 160))
@section('og_type', 'article')
@if($post->featured_image_url)
    @section('og_image', $post->featured_image_url)
@endif

@push('json-ld')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $post->title,
    'description' => $post->excerpt ?? Str::limit(strip_tags($post->safe_content), 160),
    'image' => $post->featured_image_url ?? asset('favicon.ico'),
    'author' => [
        '@type' => 'Person',
        'name' => $siteName,
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => $siteName,
        'logo' => [
            '@type' => 'ImageObject',
            'url' => $faviconSetting ? asset('storage/' . $faviconSetting) : asset('favicon.ico'),
        ],
    ],
    'datePublished' => $post->published_at?->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'articleSection' => $post->category,
]) !!}
</script>
@endpush

@section('content')

<!-- Blog Header -->
<section class="pt-24 pb-12 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('blog.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 mb-6 font-medium">
            <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> Back to Blog
        </a>

        @if($post->category)
            <span class="text-xs font-semibold text-primary-600 bg-primary-100 px-3 py-1 rounded-full">{{ $post->category }}</span>
        @endif

        <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mt-4 mb-6">{{ $post->title }}</h1>

        <div class="flex items-center text-gray-500 text-sm space-x-4">
            <span><i class="far fa-calendar mr-1" aria-hidden="true"></i> {{ optional($post->published_at)->format('d M Y') ?? '-' }}</span>
            <span><i class="far fa-eye mr-1" aria-hidden="true"></i> {{ $post->views_count }} views</span>
            <span><i class="far fa-clock mr-1" aria-hidden="true"></i> {{ max(1, (int) ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
        </div>
    </div>
</section>

<!-- Featured Image -->
@if($post->featured_image_url)
<section class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-64 lg:h-96 bg-gradient-to-br from-primary-100 to-purple-100 rounded-2xl overflow-hidden -mt-4">
            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
        </div>
    </div>
</section>
@endif

<!-- Blog Content -->
<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <article class="prose prose-lg max-w-none prose-headings:text-gray-900 prose-p:text-gray-600 prose-a:text-primary-600">
            {{-- safe_content is sanitized in App\Models\BlogPost; never echo raw content here. --}}
            {!! $post->safe_content !!}
        </article>
    </div>
</section>

<!-- Share & Navigation -->
<section class="py-12 bg-gray-50 border-t">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <a href="{{ route('blog.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                <i class="fas fa-arrow-left mr-2" aria-hidden="true"></i> All Posts
            </a>
            <div class="flex space-x-3">
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}"
                   target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke Twitter"
                   class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all text-gray-600">
                    <i class="fab fa-twitter" aria-hidden="true"></i>
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $post->slug)) }}"
                   target="_blank" rel="noopener noreferrer" aria-label="Bagikan ke LinkedIn"
                   class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all text-gray-600">
                    <i class="fab fa-linkedin" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

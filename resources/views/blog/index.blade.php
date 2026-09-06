@extends('layouts.app')

@section('title', 'Blog - '.$siteName)
@section('description', 'Articles, tutorials, and thoughts on web development')

@section('content')

<!-- Blog Header -->
<section class="pt-24 pb-16 bg-gradient-to-br from-gray-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">Latest Articles</p>
            <h1 class="text-5xl font-bold text-gray-900 mb-4">My Blog</h1>
            <p class="text-gray-500 max-w-2xl mx-auto">Thoughts, tutorials, and insights on web development and technology.</p>
        </div>
    </div>
</section>

<!-- Blog Posts -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($posts->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="bg-gray-50 rounded-2xl overflow-hidden card-hover border border-gray-100">
                <div class="h-48 bg-gradient-to-br from-primary-100 to-purple-100 flex items-center justify-center">
                    @if($post->featured_image_url)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <i class="fas fa-newspaper text-4xl text-primary-300" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                        <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full">{{ $post->category }}</span>
                    @endif
                    <h2 class="text-xl font-bold mt-3 mb-2 text-gray-800">{{ $post->title }}</h2>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-400">
                        <span><i class="far fa-calendar mr-1" aria-hidden="true"></i> {{ optional($post->published_at)->format('d M Y') ?? '-' }}</span>
                        <span><i class="far fa-eye mr-1" aria-hidden="true"></i> {{ $post->views_count }} views</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $posts->links() }}
        </div>
        @else
        <div class="text-center py-20">
            <i class="fas fa-newspaper text-6xl text-gray-200 mb-6" aria-hidden="true"></i>
            <h2 class="text-xl font-bold text-gray-400 mb-2">No blog posts yet</h2>
            <p class="text-gray-400">Stay tuned for upcoming articles!</p>
        </div>
        @endif
    </div>
</section>

@endsection

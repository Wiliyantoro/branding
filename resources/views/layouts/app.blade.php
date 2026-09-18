<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteName.' - '.$siteTagline)</title>
    <meta name="description" content="@yield('description', $siteDescription)">
    <meta name="keywords" content="web developer, vibe coding, fullstack, laravel, javascript">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Brand favicon: KW hex-keystone mark (generated in logo_work/) --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') . '?v='.config('app.key') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="@yield('title', $siteName.' - '.$siteTagline)">
    <meta property="og:description" content="@yield('description', $siteDescription)">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="@yield('title', $siteName.' - '.$siteTagline)">
    <meta name="twitter:description" content="@yield('description', $siteDescription)">

    {{-- Font Awesome: icon font only, no build step needed. --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased font-sans">

    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-[60] focus:bg-white focus:px-4 focus:py-2 focus:rounded-lg focus:shadow">
        Lewati ke konten utama
    </a>

    <!-- Navigation -->
    <nav class="fixed w-full bg-white/80 backdrop-blur-md z-50 border-b border-gray-100" id="navbar" aria-label="Navigasi utama">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-xl font-bold gradient-text" aria-label="{{ $siteName }} - beranda">
                    KW<span class="text-gray-400">.</span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}#about" class="text-gray-600 hover:text-primary-600 transition-colors font-medium">About</a>
                    <a href="{{ route('home') }}#portfolio" class="text-gray-600 hover:text-primary-600 transition-colors font-medium">Portfolio</a>
                    <a href="{{ route('home') }}#services" class="text-gray-600 hover:text-primary-600 transition-colors font-medium">Services</a>
                    <a href="{{ route('blog.index') }}" class="text-gray-600 hover:text-primary-600 transition-colors font-medium">Blog</a>
                    <a href="{{ route('home') }}#contact" class="bg-primary-600 text-white px-5 py-2 rounded-lg hover:bg-primary-700 transition-colors font-medium">Contact</a>
                </div>

                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2" id="mobileMenuButton"
                        aria-label="Buka menu navigasi" aria-expanded="false" aria-controls="mobileMenu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="md:hidden hidden" id="mobileMenu">
            <div class="px-4 py-4 space-y-3 bg-white border-t">
                <a href="{{ route('home') }}#about" class="block py-2 text-gray-600 hover:text-primary-600">About</a>
                <a href="{{ route('home') }}#portfolio" class="block py-2 text-gray-600 hover:text-primary-600">Portfolio</a>
                <a href="{{ route('home') }}#services" class="block py-2 text-gray-600 hover:text-primary-600">Services</a>
                <a href="{{ route('blog.index') }}" class="block py-2 text-gray-600 hover:text-primary-600">Blog</a>
                <a href="{{ route('home') }}#contact" class="block py-2 text-primary-600 font-medium">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Brand -->
                <div>
                    <h2 class="text-2xl font-bold gradient-text mb-4">{{ $siteName }}<span class="text-gray-400">.</span></h2>
                    <p class="text-gray-400">{{ $siteTagline }} - {{ $siteDescription }}</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h2 class="font-semibold mb-4">Quick Links</h2>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('home') }}#about" class="hover:text-white transition-colors">About</a></li>
                        <li><a href="{{ route('home') }}#portfolio" class="hover:text-white transition-colors">Portfolio</a></li>
                        <li><a href="{{ route('home') }}#services" class="hover:text-white transition-colors">Services</a></li>
                        <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h2 class="font-semibold mb-4">Contact</h2>
                    <ul class="space-y-2 text-gray-400">
                        @if($siteEmail)
                            <li><a href="mailto:{{ $siteEmail }}" class="hover:text-white transition-colors">{{ $siteEmail }}</a></li>
                        @endif
                        @if($siteLocation)
                            <li>{{ $siteLocation }}</li>
                        @endif
                    </ul>
                    @include('partials.social', ['classes' => 'text-gray-400 hover:text-white transition-colors', 'wrapper' => 'flex space-x-4 mt-4'])
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

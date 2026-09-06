@extends('layouts.app')

@section('title', $siteName.' - '.$siteTagline.' | Personal Branding')
@section('description', $siteDescription)

@section('content')

<!-- Hero Section -->
<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-blue-50 pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <p class="text-primary-600 font-semibold mb-4 tracking-wide uppercase">Hello, I'm</p>
                <h1 class="text-5xl lg:text-7xl font-bold mb-6">
                    <span class="gradient-text">{{ $siteName }}</span>
                </h1>
                <h2 class="text-2xl lg:text-3xl text-gray-600 mb-6">Web Developer | <span class="font-bold text-primary-600">{{ $siteTagline }}</span></h2>
                <p class="text-lg text-gray-500 mb-8 leading-relaxed max-w-lg">
                    Passionate about creating beautiful, functional, and user-centered digital experiences.
                    I turn ideas into reality through clean code and creative solutions.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#portfolio" class="bg-primary-600 text-white px-8 py-3 rounded-lg hover:bg-primary-700 transition-all font-semibold shadow-lg shadow-primary-200">
                        View My Work
                    </a>
                    <a href="#contact" class="border-2 border-primary-600 text-primary-600 px-8 py-3 rounded-lg hover:bg-primary-50 transition-all font-semibold">
                        Get In Touch
                    </a>
                </div>
            </div>
            <div class="hidden lg:flex justify-center">
                <div class="relative">
                    <div class="w-80 h-80 bg-gradient-to-br from-primary-400 to-purple-500 rounded-full opacity-20 absolute -top-10 -right-10"></div>
                    <div class="w-64 h-64 bg-gradient-to-br from-primary-500 to-purple-600 rounded-2xl shadow-2xl relative z-10 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white">KW</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">Get To Know Me</p>
            <h2 class="text-4xl font-bold text-gray-900">About Me</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-bold mb-6 text-gray-800">A Passionate Developer Who Loves to Code</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    I'm a web developer with a passion for creating beautiful and functional websites.
                    With expertise in modern web technologies, I build solutions that make a difference.
                </p>
                <p class="text-gray-600 leading-relaxed mb-6">
                    My philosophy is "Vibe Coding" - writing code that not only works but also feels right.
                    I believe great software comes from a combination of technical skill and creative thinking.
                </p>
                <div class="grid grid-cols-2 gap-6 mt-8">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-primary-600">5+</div>
                        <div class="text-gray-500 text-sm mt-1">Years Experience</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-3xl font-bold text-primary-600">50+</div>
                        <div class="text-gray-500 text-sm mt-1">Projects Completed</div>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-primary-50 to-purple-50 p-8 rounded-2xl">
                <h4 class="font-bold text-lg mb-4 text-gray-800">Tech Stack</h4>
                <div class="flex flex-wrap gap-3">
                    @php
                    $techStack = ['PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Node.js', 'MySQL', 'PostgreSQL', 'Git', 'Docker', 'Tailwind CSS', 'REST API'];
                    @endphp
                    @foreach($techStack as $tech)
                        <span class="px-4 py-2 bg-white rounded-lg text-sm font-medium text-gray-700 shadow-sm border border-gray-100">
                            {{ $tech }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Portfolio Section -->
<section id="portfolio" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">My Recent Work</p>
            <h2 class="text-4xl font-bold text-gray-900">Portfolio</h2>
        </div>

        @if($portfolios->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($portfolios as $portfolio)
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm card-hover">
                <div class="h-48 bg-gradient-to-br from-primary-100 to-purple-100 flex items-center justify-center">
                    @if($portfolio->image_url)
                        <img src="{{ $portfolio->image_url }}" alt="{{ $portfolio->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <span class="text-4xl font-bold text-primary-300">{{ substr($portfolio->title, 0, 2) }}</span>
                    @endif
                </div>
                <div class="p-6">
                    @if($portfolio->category)
                        <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full">{{ $portfolio->category }}</span>
                    @endif
                    <h3 class="text-xl font-bold mt-3 mb-2 text-gray-800">{{ $portfolio->title }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($portfolio->description, 100) }}</p>
                    @if($portfolio->tech_stack)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach(explode(',', $portfolio->tech_stack) as $tech)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ trim($tech) }}</span>
                            @endforeach
                        </div>
                    @endif
                    @if($portfolio->url)
                        <a href="{{ $portfolio->url }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium text-sm">
                            View Project <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">Portfolio coming soon...</p>
        </div>
        @endif
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">What I Offer</p>
            <h2 class="text-4xl font-bold text-gray-900">Services</h2>
        </div>

        @if($services->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
            <div class="p-8 bg-gray-50 rounded-2xl card-hover border border-gray-100">
                <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center mb-6">
                    <i class="fas fa-{{ $service->icon ?? 'code'}} text-primary-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3 text-gray-800">{{ $service->title }}</h3>
                <p class="text-gray-500 mb-4">{{ Str::limit($service->description, 150) }}</p>
                @if($service->price)
                    <p class="text-primary-600 font-bold">
                        {{ $service->price_label ?? 'Mulai dari' }} Rp {{ number_format($service->price, 0, ',', '.') }}
                    </p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">Services coming soon...</p>
        </div>
        @endif
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">What Clients Say</p>
            <h2 class="text-4xl font-bold text-gray-900">Testimonials</h2>
        </div>

        @if($testimonials->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-white p-8 rounded-2xl shadow-sm card-hover">
                <div class="flex mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                    @endfor
                </div>
                <p class="text-gray-600 italic mb-6">"{{ $testimonial->content }}"</p>
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mr-4">
                        @if($testimonial->client_avatar_url)
                            <img src="{{ $testimonial->client_avatar_url }}" alt="{{ $testimonial->client_name }}" class="w-full h-full rounded-full object-cover" loading="lazy">
                        @else
                            <span class="text-primary-600 font-bold">{{ strtoupper(substr($testimonial->client_name, 0, 2)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $testimonial->client_name }}</h4>
                        <p class="text-gray-500 text-sm">{{ $testimonial->client_title }}{{ $testimonial->client_company ? ' @ ' . $testimonial->client_company : '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">Testimonials coming soon...</p>
        </div>
        @endif
    </div>
</section>

<!-- Blog Preview Section -->
<section id="blog" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">Latest Articles</p>
            <h2 class="text-4xl font-bold text-gray-900">From My Blog</h2>
        </div>

        @if($posts->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}" class="bg-gray-50 rounded-2xl overflow-hidden card-hover border border-gray-100">
                <div class="h-48 bg-gradient-to-br from-primary-100 to-purple-100 flex items-center justify-center">
                    @if($post->featured_image_url)
                        <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover" loading="lazy">
                    @else
                        <i class="fas fa-newspaper text-4xl text-primary-300"></i>
                    @endif
                </div>
                <div class="p-6">
                    @if($post->category)
                        <span class="text-xs font-semibold text-primary-600 bg-primary-50 px-3 py-1 rounded-full">{{ $post->category }}</span>
                    @endif
                    <h3 class="text-xl font-bold mt-3 mb-2 text-gray-800">{{ $post->title }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 120) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-400">
                        <span><i class="far fa-calendar mr-1" aria-hidden="true"></i> {{ optional($post->published_at)->format('d M Y') ?? '-' }}</span>
                        <span><i class="far fa-eye mr-1" aria-hidden="true"></i> {{ $post->views_count }} views</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                View All Posts <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">Blog posts coming soon...</p>
        </div>
        @endif
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <p class="text-primary-600 font-semibold mb-2 uppercase tracking-wide">Get In Touch</p>
            <h2 class="text-4xl font-bold text-gray-900">Contact Me</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div>
                <h3 class="text-2xl font-bold mb-6 text-gray-800">Let's Work Together</h3>
                <p class="text-gray-600 mb-8">
                    Have a project in mind? Let's discuss how I can help you achieve your goals.
                    Feel free to reach out anytime!
                </p>

                <div class="space-y-6">
                    @if($siteEmail)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-primary-600" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <a href="mailto:{{ $siteEmail }}" class="font-medium text-gray-800 hover:text-primary-600">{{ $siteEmail }}</a>
                        </div>
                    </div>
                    @endif

                    @if($siteLocation)
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-map-marker-alt text-primary-600" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Location</p>
                            <p class="font-medium text-gray-800">{{ $siteLocation }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-primary-600" aria-hidden="true"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Availability</p>
                            <p class="font-medium text-gray-800">Open for freelance projects</p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                @include('partials.social', [
                    'wrapper' => 'flex space-x-4 mt-8',
                    'classes' => 'w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all text-gray-600',
                ])
            </div>

            <!-- Contact Form -->
            <div class="bg-white p-8 rounded-2xl shadow-sm">
                @if(session('status'))
                    <div role="status" class="mb-6 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                <form action="{{ route('contact.send') }}" method="POST" novalidate>
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                            <input type="text" name="name" id="contact-name" required maxlength="100"
                                value="{{ old('name') }}"
                                @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                                placeholder="John Doe">
                            @error('name')
                                <p id="contact-name-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                            <input type="email" name="email" id="contact-email" required maxlength="255"
                                value="{{ old('email') }}"
                                @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                                placeholder="john@example.com">
                            @error('email')
                                <p id="contact-email-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact-subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <input type="text" name="subject" id="contact-subject" required maxlength="150"
                                value="{{ old('subject') }}"
                                @error('subject') aria-invalid="true" aria-describedby="contact-subject-error" @enderror
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none"
                                placeholder="Project Inquiry">
                            @error('subject')
                                <p id="contact-subject-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea name="message" id="contact-message" rows="5" required maxlength="5000"
                                @error('message') aria-invalid="true" aria-describedby="contact-message-error" @enderror
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none resize-none"
                                placeholder="Tell me about your project...">{{ old('message') }}</textarea>
                            @error('message')
                                <p id="contact-message-error" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full bg-primary-600 text-white py-3 rounded-lg hover:bg-primary-700 transition-all font-semibold shadow-lg shadow-primary-200">
                            Send Message <i class="fas fa-paper-plane ml-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

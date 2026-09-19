<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandingSeeder extends Seeder
{
    public function run(): void
    {
        // Settings
        $settings = [
            // Site Identity
            ['key' => 'site_name', 'value' => 'KANG WILLY', 'group' => 'general'],
            ['key' => 'site_tagline', 'value' => 'Vibe Coding', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Web Developer | Building digital experiences with passion and precision.', 'group' => 'general'],
            ['key' => 'footer_text', 'value' => '', 'group' => 'general'],
            ['key' => 'meta_keywords', 'value' => '', 'group' => 'general'],
            ['key' => 'og_image', 'value' => null, 'group' => 'general'],
            ['key' => 'favicon', 'value' => null, 'group' => 'general'],
            
            // Contact
            ['key' => 'email', 'value' => 'ketutwiliyantoro@gmail.com', 'group' => 'contact'],
            ['key' => 'location', 'value' => 'Indonesia', 'group' => 'contact'],
            
            // Social
            ['key' => 'github', 'value' => 'https://github.com/', 'group' => 'social'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com/', 'group' => 'social'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/', 'group' => 'social'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        // Services
        $services = [
            [
                'icon' => 'code',
                'title' => 'Web Development',
                'description' => 'Building modern, responsive websites and web applications using the latest technologies.',
                'price' => 5000000,
                'price_label' => 'Mulai dari',
                'sort_order' => 1,
            ],
            [
                'icon' => 'mobile-alt',
                'title' => 'Mobile App Development',
                'description' => 'Creating cross-platform mobile applications that work seamlessly on iOS and Android.',
                'price' => 15000000,
                'price_label' => 'Mulai dari',
                'sort_order' => 2,
            ],
            [
                'icon' => 'paint-brush',
                'title' => 'UI/UX Design',
                'description' => 'Designing intuitive and beautiful user interfaces that enhance user experience.',
                'price' => 3000000,
                'price_label' => 'Mulai dari',
                'sort_order' => 3,
            ],
            [
                'icon' => 'server',
                'title' => 'Backend Development',
                'description' => 'Building robust and scalable backend systems and RESTful APIs.',
                'price' => 7000000,
                'price_label' => 'Mulai dari',
                'sort_order' => 4,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Portfolios
        $portfolios = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'A full-featured e-commerce platform with payment integration, inventory management, and admin dashboard.',
                'url' => 'https://example.com',
                'category' => 'Web App',
                'tech_stack' => 'Laravel, Vue.js, MySQL, Stripe',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Task Management System',
                'description' => 'A collaborative task management tool with real-time updates and team features.',
                'url' => 'https://example.com',
                'category' => 'SaaS',
                'tech_stack' => 'React, Node.js, MongoDB, Socket.io',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Company Profile Website',
                'description' => 'A modern company profile website with CMS and SEO optimization.',
                'url' => 'https://example.com',
                'category' => 'Website',
                'tech_stack' => 'Laravel, Blade, Tailwind CSS',
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create($portfolio);
        }

        // Testimonials
        $testimonials = [
            [
                'client_name' => 'Ahmad Rizky',
                'client_title' => 'CEO',
                'client_company' => 'PT Maju Jaya',
                'content' => 'Kang Willy delivered an exceptional e-commerce platform that exceeded our expectations. The attention to detail and code quality is outstanding.',
                'rating' => 5,
                'sort_order' => 1,
            ],
            [
                'client_name' => 'Sari Dewi',
                'client_title' => 'Marketing Director',
                'client_company' => 'PT Berkah Digital',
                'content' => 'Professional, responsive, and delivers on time. Our new website has significantly improved our online presence and lead generation.',
                'rating' => 5,
                'sort_order' => 2,
            ],
            [
                'client_name' => 'Budi Santoso',
                'client_title' => 'Founder',
                'client_company' => 'Startup Teknologi',
                'content' => 'Great collaboration and technical expertise. The mobile app Kang Willy built for us has received excellent user feedback.',
                'rating' => 5,
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Blog Posts
        $blogPosts = [
            [
                'title' => 'Getting Started with Laravel 11',
                'slug' => 'getting-started-with-laravel-11',
                'excerpt' => 'A comprehensive guide to getting started with the latest version of Laravel framework.',
                'content' => '<p>Laravel 11 brings exciting new features and improvements that make building web applications even more enjoyable. In this guide, we will explore the key features and how to get started.</p><h2>Installation</h2><p>To install Laravel 11, you can use the Laravel installer or Composer directly.</p><pre><code>composer create-project laravel/laravel my-app</code></pre><h2>Key Features</h2><ul><li>Simplified directory structure</li><li>Improved performance</li><li>Better debugging tools</li></ul><p>Laravel 11 continues to be the best choice for building modern PHP applications.</p>',
                'category' => 'Tutorial',
                'is_published' => true,
                'published_at' => now()->subDays(5),
                'views_count' => 150,
            ],
            [
                'title' => 'The Art of Vibe Coding',
                'slug' => 'the-art-of-vibe-coding',
                'excerpt' => 'What is vibe coding and why it matters in modern software development.',
                'content' => '<p>Vibe coding is more than just writing code - it is about creating software that feels right. It is a philosophy that combines technical excellence with creative intuition.</p><h2>What is Vibe Coding?</h2><p>Vibe coding is about writing code that is not only functional but also elegant and maintainable. It is the intersection of art and engineering.</p><h2>Principles of Vibe Coding</h2><ul><li>Write clean, readable code</li><li>Focus on user experience</li><li>Embrace simplicity</li><li>Think about the future maintainer</li></ul><p>When you vibe code, you create software that not only works but also brings joy to both developers and users.</p>',
                'category' => 'Opinion',
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'views_count' => 89,
            ],
            [
                'title' => 'Modern CSS Tips for 2026',
                'slug' => 'modern-css-tips-2026',
                'excerpt' => 'Essential CSS tips and tricks for building modern responsive websites.',
                'content' => '<p>CSS has evolved significantly. Here are some modern tips to level up your CSS game in 2026.</p><h2>CSS Container Queries</h2><p>Container queries allow you to style elements based on their container size, not the viewport.</p><h2>CSS Nesting</h2><p>Native CSS nesting is now supported in all major browsers, making your stylesheets more organized.</p><h2>Modern Layout Techniques</h2><p>Use CSS Grid and Flexbox together for powerful layouts that are both flexible and maintainable.</p>',
                'category' => 'Tutorial',
                'is_published' => true,
                'published_at' => now()->subDays(1),
                'views_count' => 234,
            ],
        ];

        foreach ($blogPosts as $post) {
            BlogPost::create($post);
        }
    }
}
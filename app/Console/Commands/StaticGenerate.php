<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class StaticGenerate extends Command
{
    protected $signature = 'static:generate {--url=http://localhost:8099 : Base URL for generation}';
    protected $description = 'Generate static HTML files from routes';

    protected array $routes = [
        '/',
        '/blog',
        '/sitemap.xml',
        '/robots.txt',
    ];

    public function handle(): int
    {
        $baseUrl = $this->option('url');
        $outputDir = base_path('public_static');

        if (File::isDirectory($outputDir)) {
            File::deleteDirectory($outputDir);
        }
        File::ensureDirectoryExists($outputDir);

        $this->info('Building static files from: ' . $baseUrl);

        // Generate main pages
        foreach ($this->routes as $path) {
            $this->generatePage($baseUrl.$path, $path, $outputDir);
        }

        // Generate blog item pages (dynamically fetch first 50 from DB)
        $blogSlugs = \App\Models\BlogPost::published()
            ->limit(50)
            ->pluck('slug')
            ->toArray();

        foreach ($blogSlugs as $slug) {
            $path = "/blog/{$slug}";
            $this->generatePage($baseUrl.$path, $path, $outputDir);
        }

        $this->info('✅ Static site generated in: ' . $outputDir);
        $this->info('📄 Files: ' . File::allFiles($outputDir)->count());

        return self::SUCCESS;
    }

    protected function generatePage(string $url, string $path, string $outputDir): void
    {
        $this->line("  Generating: {$path}");

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->status() === 200) {
                $filename = $path === '/' ? 'index.html' : rtrim($path, '/').'.html';
                $filepath = $outputDir . '/' . $filename;

                // Ensure subdirectory exists for paths like /blog/slug
                File::ensureDirectoryExists(dirname($filepath));

                File::put($filepath, $response->body());
            } else {
                $this->warn("  ⚠️ Status {$response->status()}: {$path}");
            }
        } catch (\Exception $e) {
            $this->error("  ❌ Error: {$e->getMessage()}");
        }
    }
}
<?php

use App\Console\Commands\StaticGenerate;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register custom static:generate command
Artisan::command('static:generate {--url=http://localhost:8099 : Base URL for generation}', function () {
    $baseUrl = $this->option('url');
    $outputDir = base_path('public_static');

    if (\Illuminate\Support\Facades\File::isDirectory($outputDir)) {
        \Illuminate\Support\Facades\File::deleteDirectory($outputDir);
    }
    \Illuminate\Support\Facades\File::ensureDirectoryExists($outputDir);

    $this->info('Building static files from: ' . $baseUrl);

    // Simple route generation
    $routes = ['/', '/blog', '/sitemap.xml', '/robots.txt'];
    foreach ($routes as $path) {
        $this->line("  Generating: {$path}");
        $response = \Illuminate\Support\Facades\Http::timeout(30)->get($baseUrl . $path);
        if ($response->status() === 200) {
            $filename = $path === '/' ? 'index.html' : rtrim($path, '/') . '.html';
            \Illuminate\Support\Facades\File::put($outputDir . '/' . $filename, $response->body());
        }
    }

    $this->info('✅ Static generation complete');
})->purpose('Generate static HTML files from routes');

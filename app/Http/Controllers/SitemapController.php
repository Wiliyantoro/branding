<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        // Cache selama 1 jam — sitemap hanya berubah saat ada post baru/diperbarui.
        $urls = Cache::remember('sitemap.urls', 3600, function (): array {
            $urls = [
                ['loc' => route('home'), 'priority' => '1.0'],
                ['loc' => route('blog.index'), 'priority' => '0.8'],
            ];

            foreach (BlogPost::published()->ordered()->get(['slug', 'updated_at']) as $post) {
                $urls[] = [
                    'loc' => route('blog.show', $post->slug),
                    'lastmod' => $post->updated_at?->toAtomString(),
                    'priority' => '0.6',
                ];
            }

            return $urls;
        });

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $body = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /storage/',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}

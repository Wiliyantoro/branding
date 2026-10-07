<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));

        $posts = BlogPost::published()
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($query) use ($q): void {
                    $query->where('title', 'like', "%{$q}%")
                        ->orWhere('excerpt', 'like', "%{$q}%")
                        ->orWhere('category', 'like', "%{$q}%");
                });
            })
            ->ordered()
            ->paginate(9)
            ->withQueryString();

        return view('blog.index', compact('posts', 'q'));
    }

    public function show(Request $request, string $slug)
    {
        // Scoped lookup: unpublished drafts must 404, not render.
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        // One view per session per post; incrementQuietly keeps updated_at intact.
        $seen = $request->session()->get('seen_posts', []);

        if (! in_array($post->id, $seen, true)) {
            $post->incrementQuietly('views_count');
            $request->session()->put('seen_posts', [...$seen, $post->id]);
        }

        return view('blog.show', compact('post'));
    }
}

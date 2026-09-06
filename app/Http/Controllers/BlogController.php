<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->ordered()->paginate(9);

        return view('blog.index', compact('posts'));
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

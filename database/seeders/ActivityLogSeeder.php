<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Factories\Sequence;
use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        // Generate recent activity from existing posts, portfolios, services
        $activities = [];

        // Recent blog posts
        foreach (BlogPost::latest()->limit(6)->get() as $post) {
            $activities[] = [
                'type' => 'Blog Post',
                'title' => $post->title,
                'record_id' => $post->id,
                'action' => 'update',
                'updated_at' => $post->updated_at,
            ];
        }

        // Recent portfolios
        foreach (Portfolio::latest()->limit(5)->get() as $p) {
            $activities[] = [
                'type' => 'Portofolio',
                'title' => $p->title,
                'record_id' => $p->id,
                'action' => 'update',
                'updated_at' => $p->updated_at,
            ];
        }

        // Recent services
        foreach (Service::latest()->limit(4)->get() as $s) {
            $activities[] = [
                'type' => 'Layanan',
                'title' => $s->title,
                'record_id' => $s->id,
                'action' => 'update',
                'updated_at' => $s->updated_at,
            ];
        }

        // Seed system activity
        ActivityLog::insert(
            collect($activities)->sortByDesc('updated_at')->take(10)->toArray()
        );
    }
}
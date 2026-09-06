<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

// Home page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/contact', [HomeController::class, 'contact'])
    ->middleware('throttle:5,1')
    ->name('contact.send');

// Blog routes
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// SEO
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

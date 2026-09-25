<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Services\RecaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'portfolios' => Portfolio::published()->ordered()->take(6)->get(),
            'services' => Service::published()->ordered()->get(),
            'testimonials' => Testimonial::published()->ordered()->take(3)->get(),
            'posts' => BlogPost::published()->ordered()->take(3)->get(),
        ]);
    }

    public function contact(Request $request, RecaptchaService $recaptcha)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        // Verify reCAPTCHA token (fails gracefully in dev when keys not configured).
        if (! $recaptcha->verify($request->input('g_recaptcha_response', ''))) {
            return back()
                ->withInput()
                ->withErrors(['g_recaptcha_response' => 'Terima kasih atas verifikasi keamanan.'])
                ->withFragment('contact');
        }

        $to = Setting::get('email') ?: config('mail.from.address');

        Mail::raw($data['message'], fn ($mail) => $mail
            ->to($to)
            ->replyTo($data['email'], $data['name'])
            ->subject('[Kontak] '.$data['subject']));

        return back()
            ->with('status', 'Pesan terkirim. Terima kasih!')
            ->withFragment('contact');
    }
}

<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    /**
     * phpunit.xml sets MAIL_MAILER=array, so sent messages land in the
     * array transport. Mail::fake() only records Mailables, and the
     * controller sends a raw message — read the transport instead.
     */
    private function sentMessages(): \Illuminate\Support\Collection
    {
        return Mail::mailer()->getSymfonyTransport()->messages();
    }

    protected function setUp(): void
    {
        parent::setUp();
        Mail::mailer()->getSymfonyTransport()->flush();
    }

    public function test_valid_submission_sends_mail_to_configured_address(): void
    {
        Setting::create(['key' => 'email', 'value' => 'owner@example.com', 'group' => 'contact']);

        $response = $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Proyek',
            'message' => 'Mau bikin web',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $messages = $this->sentMessages();
        $this->assertCount(1, $messages);

        $email = $messages->first()->getOriginalMessage();
        $this->assertSame('owner@example.com', $email->getTo()[0]->getAddress());
        $this->assertSame('budi@example.com', $email->getReplyTo()[0]->getAddress());
        $this->assertSame('[Kontak] Proyek', $email->getSubject());
        $this->assertStringContainsString('Mau bikin web', $email->getTextBody());
    }

    public function test_invalid_submission_is_rejected_and_sends_nothing(): void
    {
        $response = $this->from('/')->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'subject' => 'x',
            'message' => '',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertCount(0, $this->sentMessages());
    }

    public function test_oversized_fields_are_rejected(): void
    {
        $response = $this->from('/')->post('/contact', [
            'name' => str_repeat('a', 101),
            'email' => 'budi@example.com',
            'subject' => str_repeat('b', 151),
            'message' => str_repeat('c', 5001),
        ]);

        $response->assertSessionHasErrors(['name', 'subject', 'message']);
        $this->assertCount(0, $this->sentMessages());
    }

    public function test_rate_limiting_triggers_after_max_attempts(): void
    {
        // throttle:5,1 — 5 attempts per minute allowed, 6th must be blocked.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', [
                'name' => 'Budi',
                'email' => 'budi@example.com',
                'subject' => 'Proyek',
                'message' => 'Pesan ke-'.$i,
            ])->assertRedirect()->assertSessionHas('status');
        }

        // Clear mailbox to confirm 6th attempt is blocked before sending.
        Mail::mailer()->getSymfonyTransport()->flush();

        $response = $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Proyek',
            'message' => 'Pesan ke-6',
        ]);

        $response->assertStatus(429);
        $this->assertCount(0, $this->sentMessages());
    }

    public function test_rate_limit_resets_after_window(): void
    {
        // Simulate time passing one full throttle window (60 seconds).
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contact', [
                'name' => 'Budi',
                'email' => 'budi@example.com',
                'subject' => 'Proyek',
                'message' => 'Reset window test',
            ]);
        }

        // Block until throttle window passes.
        $this->travel(1)->hours();

        $response = $this->post('/contact', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'subject' => 'Proyek',
            'message' => 'After reset',
        ]);

        $response->assertRedirect()->assertSessionHas('status');
    }
}

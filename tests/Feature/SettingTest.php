<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_returns_value_and_default(): void
    {
        Setting::set('site_name', 'KANG WILLY');

        $this->assertSame('KANG WILLY', Setting::get('site_name'));
        $this->assertSame('fallback', Setting::get('missing_key', 'fallback'));
    }

    public function test_cache_is_invalidated_on_write(): void
    {
        Setting::set('site_name', 'Lama');
        $this->assertSame('Lama', Setting::get('site_name'));

        Setting::set('site_name', 'Baru');
        $this->assertSame('Baru', Setting::get('site_name'));
    }

    public function test_delete_invalidates_cache(): void
    {
        $setting = Setting::set('temp_key', 'nilai');
        $this->assertSame('nilai', Setting::get('temp_key'));

        $setting->delete();
        $this->assertNull(Setting::get('temp_key'));
    }
}

<?php

namespace Tests\Unit;

use App\Support\ResponsiveImage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ResponsiveImageTest extends TestCase
{
    public static function htmlProvider(): array
    {
        return [
            'wraps_local_image_in_picture_with_webp_source' => [
                '<figure><img src="/storage/blog/ai-circuit.jpg" alt="x" class="w-full rounded"></figure>',
                function (string $out): bool {
                    return str_contains($out, '<picture>')
                        && str_contains($out, '<source srcset="/storage/blog/ai-circuit.webp" type="image/webp">')
                        && str_contains($out, 'src="/storage/blog/ai-circuit.jpg"')
                        && str_contains($out, '</picture>');
                },
            ],
            'leaves_external_images_untouched' => [
                '<img src="https://example.com/x.jpg" alt="y">',
                fn (string $out): bool => $out === '<img src="https://example.com/x.jpg" alt="y">',
            ],
            'handles_img_without_slash_storage' => [
                '<img src="/img/local.jpg" alt="z">',
                fn (string $out): bool => $out === '<img src="/img/local.jpg" alt="z">',
            ],
            'keeps_src_when_no_webp_variant' => [
                '<img src="/storage/blog/not-on-disk.jpg" alt="q" loading="lazy">',
                fn (string $out): bool => str_contains($out, 'not-on-disk.jpg"') && str_contains($out, 'loading=') && ! str_contains($out, 'webp'),
            ],
        ];
    }

    #[DataProvider('htmlProvider')]
    #[Test]
    public function picture(string $html, \Closure $assert): void
    {
        $this->assertTrue($assert(ResponsiveImage::picture($html)));
    }

    #[Test]
    public function webp_variant_url_handles_absolute_and_relative(): void
    {
        $this->assertSame(
            '/storage/blog/ai-circuit.webp',
            ResponsiveImage::webpVariantUrl('/storage/blog/ai-circuit.jpg')
        );
        $this->assertSame(
            'https://example.test/storage/blog/ai-circuit.webp',
            ResponsiveImage::webpVariantUrl('https://example.test/storage/blog/ai-circuit.jpg')
        );
        $this->assertNull(ResponsiveImage::webpVariantUrl('https://example.com/x.jpg'));
        $this->assertNull(ResponsiveImage::webpVariantUrl('/storage/blog/not-on-disk.jpg'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $blogDir = storage_path('app/public/blog');
        if (! is_dir($blogDir)) {
            @mkdir($blogDir, 0755, true);
        }

        // Create tiny real image files so the helper's is_file() checks resolve.
        $img = imagecreatetruecolor(20, 20);
        imagejpeg($img, $blogDir.'/ai-circuit.jpg', 80);
        imagewebp($img, $blogDir.'/ai-circuit.webp', 80);
        imagedestroy($img);

        $this->beforeApplicationDestroyed(function () use ($blogDir): void {
            @unlink($blogDir.'/ai-circuit.jpg');
            @unlink($blogDir.'/ai-circuit.webp');
        });
    }
}

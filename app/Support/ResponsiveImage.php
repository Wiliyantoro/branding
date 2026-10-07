<?php

namespace App\Support;

class ResponsiveImage
{
    /**
     * Wrap each local <img src="/storage/..."> in a <picture> with a WebP <source> when a
     * .webp variant exists (same name, .webp extension), so modern browsers fetch the
     * smaller file. External images and images without a WebP variant pass through unchanged.
     */
    public static function picture(string $safeHtml): string
    {
        return (string) preg_replace_callback('#<img\b(?P<img>[^>]*(?:\s+src="(?P<src>/storage/[^"]+)")[^>]*)>#i', function (array $m): string {
            $webpUrl = self::webpSrcUrl($m['src'] ?? '');
            if ($webpUrl === null) {
                return $m[0];
            }

            $img = $m['img'];
            $img = preg_replace('#\ssrc="[^"]*"#i', ' src="'.e($m['src']).'"', $img, 1);

            return '<picture>'
                .'<source srcset="'.e($webpUrl).'" type="image/webp">'
                .'<img'.$img.'>'
                .'</picture>';
        }, $safeHtml);
    }

    /**
     * Return the public URL of the WebP twin for a /storage/ path, or null when the
     * original is not on disk or has no .webp sibling.
     */
    protected static function webpSrcUrl(string $storageUrl): ?string
    {
        if (! str_starts_with($storageUrl, '/storage/')) {
            return null;
        }
        $relative = substr($storageUrl, strlen('/storage/'));   // blog/foo.jpg
        $file = storage_path('app/public/'.$relative);
        if (! is_file($file)) {
            return null;
        }
        $dir = dirname($file);
        $name = pathinfo($file, PATHINFO_FILENAME);
        $webp = $dir.'/'.$name.'.webp';

        if (! is_file($webp)) {
            return null;
        }

        $relativeDir = str_replace(DIRECTORY_SEPARATOR, '/', $relative);
        $baseDir = dirname($relativeDir);
        return '/storage/'.$baseDir.'/'.$name.'.webp';
    }
}


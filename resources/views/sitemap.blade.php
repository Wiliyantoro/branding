{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
@isset($url['lastmod'])
        <lastmod>{{ $url['lastmod'] }}</lastmod>
@endisset
        <priority>{{ $url['priority'] }}</priority>
    </url>
@endforeach
</urlset>

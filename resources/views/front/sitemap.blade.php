{!! '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    @foreach($cms as $page)
        <url>
            <loc>{{ url($page->seo_url) }}</loc>
            <lastmod>{{ $page->updated_at?->toW3cString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.8</priority>
        </url>
    @endforeach

    @foreach($blogs as $blog)
        <url>
            <loc>{{ url($blog->seo_url) }}</loc>
            <lastmod>{{ $blog->updated_at?->toW3cString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.7</priority>
        </url>
    @endforeach

    @foreach($blogcategories as $category)
        <url>
            <loc>{{ url($category->seo_url) }}</loc>
            <lastmod>{{ $category->updated_at?->toW3cString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach
</urlset>

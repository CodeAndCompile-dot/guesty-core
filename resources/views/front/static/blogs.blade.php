@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->meta_title ?? 'Blogs' }}</title>
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->name ?? 'Blogs' }}</h1>

    <div class="row">
        @forelse($blogs as $blog)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($blog->image)
                        <img src="{{ asset('uploads/blogs/'.$blog->image) }}" class="card-img-top" alt="{{ $blog->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $blog->title }}</h5>
                        <p class="card-text">{{ Str::limit(strip_tags($blog->description ?? ''), 120) }}</p>
                        <a href="{{ url($blog->seo_url ?? '#') }}" class="btn btn-sm btn-primary">Read More</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No blog posts found.</p>
        @endforelse
    </div>

    {{ $blogs->links() }}
</div>
@endsection

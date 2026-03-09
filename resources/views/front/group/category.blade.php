{{-- Blog category listing --}}
@extends('front.layouts.master')

@section('container')
<div class="container py-4">
    <h1>{{ $data->name ?? '' }}</h1>

    @if($blogs->count())
        <div class="row">
            @foreach($blogs as $blog)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if(!empty($blog->image))
                            <img src="{{ asset('uploads/blogs/'.$blog->image) }}" class="card-img-top" alt="{{ $blog->title ?? '' }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog->title ?? '' }}</h5>
                            <p class="card-text">{{ Str::limit(strip_tags($blog->description ?? ''), 120) }}</p>
                            <a href="{{ url('blog/'.$blog->seo_url) }}" class="btn btn-sm btn-primary">Read More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $blogs->links() }}
    @else
        <p>No blog posts found in this category.</p>
    @endif
</div>
@endsection

{{-- Blog single post --}}
@extends('front.layouts.master')

@section('container')
<div class="container py-4">
    <h1>{{ $data->title ?? $data->name ?? '' }}</h1>

    @if(!empty($data->image))
        <img src="{{ asset('uploads/blogs/'.$data->image) }}" alt="{{ $data->title ?? '' }}" class="img-fluid mb-3">
    @endif

    <div class="blog-content">
        {!! $data->description ?? '' !!}
    </div>

    @if(!empty($category))
        <p class="mt-3"><strong>Category:</strong> {{ $category->name ?? '' }}</p>
    @endif
</div>
@endsection

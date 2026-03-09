@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->meta_title ?? $data->name ?? 'Page' }}</title>
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $data->meta_keywords ?? '' }}">
    @if(!empty($ogimage))
        <meta property="og:image" content="{{ asset($ogimage) }}">
    @endif
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->name ?? '' }}</h1>
    {!! $data->description ?? '' !!}
</div>
@endsection

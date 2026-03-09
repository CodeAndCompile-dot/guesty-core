@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->meta_title ?? $data->name ?? 'Home' }}</title>
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
    <meta name="keywords" content="{{ $data->meta_keywords ?? '' }}">
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->name ?? 'Home' }}</h1>
    {!! $data->description ?? '' !!}
</div>
@endsection

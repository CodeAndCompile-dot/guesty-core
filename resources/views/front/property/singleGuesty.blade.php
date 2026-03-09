@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->title ?? $data->name ?? 'Property' }}</title>
    @if(!empty($ogimage))
        <meta property="og:image" content="{{ asset($ogimage) }}">
    @endif
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->title ?? $data->name ?? '' }}</h1>
    {!! $data->description ?? '' !!}
</div>
@endsection

@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->meta_title ?? $data->name ?? '' }}</title>
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->name ?? '' }}</h1>
    {!! $data->description ?? '' !!}
</div>
@endsection

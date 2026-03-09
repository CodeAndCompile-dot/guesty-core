@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->name ?? 'Team Member' }}</title>
@endsection

@section("container")
<div class="container py-5">
    @if($data->image)
        <img src="{{ asset('uploads/our-teams/'.$data->image) }}" alt="{{ $data->name }}" class="img-fluid rounded mb-4" style="max-width:300px;">
    @endif
    <h1>{{ $data->name ?? '' }}</h1>
    <p class="text-muted">{{ $data->designation ?? '' }}</p>
    {!! $data->description ?? '' !!}
</div>
@endsection

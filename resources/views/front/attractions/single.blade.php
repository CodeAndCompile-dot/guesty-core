{{-- Attraction detail --}}
@extends('front.layouts.master')

@section('container')
<div class="container py-4">
    <h1>{{ $data->name ?? $data->title ?? '' }}</h1>

    @if(!empty($data->image))
        <img src="{{ asset('uploads/attractions/'.$data->image) }}" alt="{{ $data->name ?? '' }}" class="img-fluid mb-3">
    @endif

    <div class="attraction-content">
        {!! $data->description ?? '' !!}
    </div>
</div>
@endsection

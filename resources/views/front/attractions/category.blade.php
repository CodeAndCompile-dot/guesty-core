{{-- Attraction category listing --}}
@extends('front.layouts.master')

@section('container')
<div class="container py-4">
    <h1>{{ $data->name ?? '' }}</h1>

    <div class="attraction-category-content">
        {!! $data->description ?? '' !!}
    </div>
</div>
@endsection

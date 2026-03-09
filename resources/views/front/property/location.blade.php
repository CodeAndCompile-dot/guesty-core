{{-- Properties by location --}}
@extends('front.layouts.master')

@section('container')
<div class="container py-4">
    <h1>{{ $data->name ?? '' }}</h1>

    <div class="property-location-content">
        {!! $data->description ?? '' !!}
    </div>
</div>
@endsection

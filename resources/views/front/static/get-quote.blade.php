@extends("front.layouts.master")

@section("header-section")
    <title>{{ $data->meta_title ?? 'Get Quote' }}</title>
    <meta name="description" content="{{ $data->meta_description ?? '' }}">
@endsection

@section("container")
<div class="container py-5">
    <h1>{{ $data->name ?? 'Get a Quote' }}</h1>

    @if(session('danger'))
        <div class="alert alert-danger">{{ session('danger') }}</div>
    @endif

    @if(isset($mainData) && isset($property))
        <div class="row">
            <div class="col-md-8">
                <h3>{{ $property->title ?? $property->name ?? '' }}</h3>
                <p><strong>Check-in:</strong> {{ $mainData['start_date'] ?? '' }}</p>
                <p><strong>Check-out:</strong> {{ $mainData['end_date'] ?? '' }}</p>
                <p><strong>Guests:</strong> {{ $mainData['total_guests'] ?? '' }}
                    (Adults: {{ $mainData['adults'] ?? 0 }}, Children: {{ $mainData['child'] ?? 0 }})</p>

                @if(!empty($mainData['guestyapi']['data']))
                    <h4>Pricing</h4>
                    <pre>{{ json_encode($mainData['guestyapi']['data'], JSON_PRETTY_PRINT) }}</pre>
                @endif
            </div>
        </div>
    @else
        {!! $data->description ?? '' !!}
    @endif
</div>
@endsection

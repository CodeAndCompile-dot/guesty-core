@extends('front.layouts.master')

@section('title', 'Preview Booking')

@section('container')
<section class="preview-booking-section py-5">
    <div class="container">
        <h2 class="mb-4">Booking Preview</h2>

        @if(isset($property))
        <div class="row">
            {{-- Property Summary --}}
            <div class="col-md-5 mb-4">
                <div class="card">
                    @if(!empty($property->picture))
                        <img src="{{ $property->picture }}" class="card-img-top" alt="{{ $property->title ?? 'Property' }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $property->title ?? '' }}</h5>
                        @if(!empty($property->address?->full))
                            <p class="text-muted">{{ $property->address->full }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Booking Details --}}
            <div class="col-md-7 mb-4">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Booking Details</h5></div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th>Check-in</th>
                                <td>{{ $booking['checkin'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Check-out</th>
                                <td>{{ $booking['checkout'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Guests</th>
                                <td>{{ $booking['guests'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <th>Nights</th>
                                <td>{{ $booking['nights'] ?? '' }}</td>
                            </tr>
                        </table>

                        @if(isset($data))
                        <hr>
                        <h6>Payment Summary</h6>
                        <table class="table table-borderless">
                            @if(!empty($data->fareAccommodation))
                            <tr>
                                <td>Accommodation</td>
                                <td class="text-end">${{ number_format($data->fareAccommodation, 2) }}</td>
                            </tr>
                            @endif
                            @if(!empty($data->cleaning))
                            <tr>
                                <td>Cleaning Fee</td>
                                <td class="text-end">${{ number_format($data->cleaning, 2) }}</td>
                            </tr>
                            @endif
                            @if(!empty($data->tax))
                            <tr>
                                <td>Tax</td>
                                <td class="text-end">${{ number_format($data->tax, 2) }}</td>
                            </tr>
                            @endif
                            @if(!empty($data->totalPrice))
                            <tr class="fw-bold border-top">
                                <td>Total</td>
                                <td class="text-end">${{ number_format($data->totalPrice, 2) }}</td>
                            </tr>
                            @endif
                        </table>
                        @endif
                    </div>
                </div>

                {{-- Guest Info --}}
                <div class="card mt-3">
                    <div class="card-header"><h5 class="mb-0">Guest Information</h5></div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $booking['first_name'] ?? '' }} {{ $booking['last_name'] ?? '' }}</p>
                        <p><strong>Email:</strong> {{ $booking['email'] ?? '' }}</p>
                        <p><strong>Phone:</strong> {{ $booking['mobile'] ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="alert alert-warning">Booking data not available.</div>
        @endif
    </div>
</section>
@endsection

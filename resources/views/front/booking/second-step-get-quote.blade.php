@extends('front.layouts.master')

@section('title', 'Complete Your Booking')

@section('container')
<section class="get-quote-after-section py-5">
    <div class="container">
        <h2 class="mb-4">Complete Your Booking</h2>

        <div class="row">
            {{-- Property & Booking Summary --}}
            <div class="col-md-5 mb-4">
                @if(isset($property))
                <div class="card">
                    @if(!empty($property->picture))
                        <img src="{{ $property->picture }}" class="card-img-top" alt="{{ $property->title ?? 'Property' }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $property->title ?? '' }}</h5>
                    </div>
                </div>
                @endif

                @if(isset($booking))
                <div class="card mt-3">
                    <div class="card-header"><h5 class="mb-0">Stay Details</h5></div>
                    <div class="card-body">
                        <p><strong>Check-in:</strong> {{ $booking->checkin ?? '' }}</p>
                        <p><strong>Check-out:</strong> {{ $booking->checkout ?? '' }}</p>
                        <p><strong>Guests:</strong> {{ $booking->guests ?? '' }}</p>
                        <p><strong>Nights:</strong> {{ $booking->nights ?? '' }}</p>
                    </div>
                </div>
                @endif

                @if(isset($data))
                <div class="card mt-3">
                    <div class="card-header"><h5 class="mb-0">Price Breakdown</h5></div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless mb-0">
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
                            @if(!empty($data->subTotal))
                            <tr>
                                <td>Subtotal</td>
                                <td class="text-end">${{ number_format($data->subTotal, 2) }}</td>
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
                    </div>
                </div>
                @endif
            </div>

            {{-- Payment Form --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Payment Information</h5></div>
                    <div class="card-body">
                        @if(isset($booking))
                        <form action="{{ url('update-payment-booking-data/' . $booking->id) }}" method="POST" id="payment-form">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Cardholder Name</label>
                                <input type="text" name="card_name" class="form-control" required
                                       value="{{ old('card_name', ($booking->first_name ?? '') . ' ' . ($booking->last_name ?? '')) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Card Number</label>
                                <input type="text" name="card_number" class="form-control" required
                                       maxlength="19" placeholder="1234 5678 9012 3456">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expiry Month</label>
                                    <select name="card_month" class="form-select" required>
                                        <option value="">Month</option>
                                        @for($m = 1; $m <= 12; $m++)
                                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Expiry Year</label>
                                    <select name="card_year" class="form-select" required>
                                        <option value="">Year</option>
                                        @for($y = date('Y'); $y <= date('Y') + 10; $y++)
                                            <option value="{{ $y }}">{{ $y }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CVV</label>
                                <input type="text" name="card_cvv" class="form-control" required
                                       maxlength="4" placeholder="123">
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                Pay ${{ isset($data) ? number_format($data->totalPrice ?? 0, 2) : '0.00' }}
                            </button>
                        </form>
                        @else
                            <div class="alert alert-warning">Booking not found.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

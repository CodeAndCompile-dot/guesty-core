@extends("front.layouts.master")
@section("title",$data->meta_title ?? 'Payment Confirmation')
@section("keywords",$data->meta_keywords ?? '')
@section("description",$data->meta_description ?? '')

@section("container")

    @php
        $name = $data->name ?? 'Payment Confirmation';
        $bannerImage = asset('front/images/internal-banner.webp');
        $payment_currency = $setting_data['payment_currency'] ?? '$';
    @endphp

    @include("front.layouts.banner")

    <!-- Confirmation Section -->
    <section class="payment">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success text-center">
                        <h3>Congratulations, {{ $booking['first_name'] ?? '' }} {{ $booking['last_name'] ?? '' }}!</h3>
                        <p>Your payment has been received successfully.</p>
                    </div>
                </div>

                {{-- Property Detail --}}
                <div class="pro-detail">
                    <div class="head-area">
                        <h6>Property Detail</h6>
                    </div>
                    <div class="body-area">
                        <div class="row">
                            <div class="col-3 col-md-3 col-sm-12 pdl">
                                <p>Property Name</p>
                            </div>
                            <div class="col-9 col-md-9 col-sm-12 amt">
                                <p>{{ $property->title ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Booking Detail --}}
                <h3 class="quote-head">Booking Detail</h3>
                <div class="quote">
                    <div class="head-area">
                        <div class="row">
                            <div class="col-2 col-md-2 col-sm-12 check-in">
                                <h6>Check In</h6>
                            </div>
                            <div class="col-2 col-md-2 col-sm-12 check-out">
                                <h6>Check Out</h6>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 guest">
                                <h6>Total Guests</h6>
                            </div>
                            <div class="col-2 col-md-2 col-sm-12 nights">
                                <h6>Total Nights</h6>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <h6>Gross Amount</h6>
                            </div>
                        </div>
                    </div>
                    <div class="body-area">
                        <div class="row">
                            <div class="col-2 col-md-2 col-sm-12 check-in">
                                <p>{{ $booking['checkin'] }}</p>
                            </div>
                            <div class="col-2 col-md-2 col-sm-12 check-out">
                                <p>{{ $booking['checkout'] }}</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 guest">
                                <p>{{ $booking['total_guests'] ?? 0 }} ({{ $booking['adults'] ?? 0 }} Adults, {{ $booking['child'] ?? 0 }} Child)</p>
                            </div>
                            <div class="col-2 col-md-2 col-sm-12 nights">
                                <p>{{ $booking['total_night'] ?? 0 }}</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['gross_amount'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Before-total fees --}}
                    @isset($booking['before_total_fees'])
                        @foreach(json_decode($booking['before_total_fees'] ?? '[]') as $c)
                        <div class="misc">
                            <div class="row">
                                <div class="col-9 col-md-9 col-sm-12 mis">
                                    <p>{{ $c->title ?? $c->name ?? '' }}</p>
                                </div>
                                <div class="col-3 col-md-3 col-sm-12 amt">
                                    <p>{!! $payment_currency !!}{{ number_format($c->amount ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endisset

                    {{-- Taxes (hidden matching legacy) --}}
                    @if(($booking['tax'] ?? 0) > 0)
                    <div class="taxes" style="display:none">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 tax">
                                <p>Taxes</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['tax'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Total --}}
                    <div class="total">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 tl">
                                <p>Total</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['total_amount'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@stop

@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front') }}/css/get-quote.css" />
    <link rel="stylesheet" href="{{ asset('front') }}/css/get-quote-responsive.css" />
@stop

@section("js")
    @parent
    <script src="{{ asset('front') }}/js/get-quote.js"></script>
@stop

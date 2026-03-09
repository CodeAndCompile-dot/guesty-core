@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)

@section("container")

    @php
        $name=$data->name;
        $bannerImage=asset('front/images/internal-banner.webp');
        $payment_currency= $setting_data['payment_currency'] ?? '$';
        $amount_data = json_decode($booking['amount_data'] ?? '[]') ?? [];
    @endphp

    @include("front.layouts.banner")

    <!-- Payment Section -->
    <section class="payment">
        <div class="container">
            <div class="row">
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

                    {{-- Guest fee --}}
                    @if(($booking['guest_fee'] ?? 0) > 0)
                    <div class="misc">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 mis">
                                <p>Guest Fee</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['guest_fee'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Pet fee --}}
                    @if(($booking['pet_fee'] ?? 0) > 0)
                    <div class="misc">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 mis">
                                <p>Pet Fee ({{ $booking['pet_fee_type'] ?? 'taxable' }})</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['pet_fee'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Heating/Pool fee --}}
                    @if(($booking['heating_pool_fee'] ?? 0) > 0)
                    <div class="misc">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 mis">
                                <p>Heating/Pool Fee</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['heating_pool_fee'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

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

                    {{-- Tax --}}
                    @if(($booking['tax'] ?? 0) > 0)
                    <div class="taxes">
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

                    {{-- Sub-total (when after_total_fees exist) --}}
                    @if(($booking['sub_amount'] ?? 0) != ($booking['gross_amount'] ?? 0))
                        @if(count(json_decode($booking['after_total_fees'] ?? '[]')) > 0)
                        <div class="total">
                            <div class="row">
                                <div class="col-9 col-md-9 col-sm-12 tl">
                                    <p>Sub Total</p>
                                </div>
                                <div class="col-3 col-md-3 col-sm-12 amt">
                                    <p>{!! $payment_currency !!}{{ number_format($booking['sub_amount'] ?? 0, 2) }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endif

                    {{-- After-total fees --}}
                    @foreach(json_decode($booking['after_total_fees'] ?? '[]') as $c)
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

                    {{-- Discount --}}
                    @if(!empty($booking['discount']) && $booking['discount'] != 0)
                    <div class="total">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 tl">
                                <p>Discount ({{ $booking['discount'] }}%)</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['after_discount_total'] ?? 0, 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Extra discount --}}
                    @if(!empty($booking['extra_discount']) && $booking['extra_discount'] != 0)
                    <div class="total">
                        <div class="row">
                            <div class="col-9 col-md-9 col-sm-12 tl">
                                <p>Extra Discount</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($booking['extra_discount'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Instalment schedule --}}
                    @if(is_array($amount_data) && count($amount_data) > 0)
                    <div class="total">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mt-3 mb-2">Payment Schedule</h6>
                            </div>
                        </div>
                        @foreach($amount_data as $key => $a)
                        <div class="row mb-1">
                            <div class="col-6 col-md-6 col-sm-12 tl">
                                <p>
                                    Instalment {{ $key + 1 }}
                                    @if(($a->status ?? '') == 'complete')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unpaid</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{!! $payment_currency !!}{{ number_format($a->amount ?? 0, 2) }}</p>
                            </div>
                            <div class="col-3 col-md-3 col-sm-12 amt">
                                <p>{{ $a->date ?? '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                </div>

                {{-- PayPal button --}}
                <div class="row card-detail mt-4">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Pay with PayPal</h3>
                            </div>
                            <div class="panel-body">
                                @if (Session::has('success'))
                                    <div class="alert alert-success text-center">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <p>{{ Session::get('success') }}</p>
                                    </div>
                                @endif
                                @if (Session::has('danger'))
                                    <div class="alert alert-danger text-center">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        <p>{{ Session::get('danger') }}</p>
                                    </div>
                                @endif
                                <div id="paypal-button-container"></div>
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

    @php
        $paypal_token = ModelHelper::getDataFromSetting('paypal_access_token');
        $pay_amount = $booking['total_amount'] ?? 0;
        if(!empty($booking['discount']) && $booking['discount'] != 0) {
            $pay_amount = $booking['after_discount_total'] ?? $pay_amount;
        }
        // Determine next unpaid instalment amount
        if(is_array($amount_data) && count($amount_data) > 0) {
            foreach($amount_data as $a) {
                if(($a->status ?? '') != 'complete') {
                    $pay_amount = $a->amount ?? $pay_amount;
                    break;
                }
            }
        }
    @endphp

    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal_token }}&currency=USD"></script>
    <script>
    var paypal_sdk = paypal;
    paypal_sdk.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '{{ round($pay_amount, 2) }}'
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                var url = "{{ route('paypal.submit', [$booking['id']]) }}";
                url += "?tx=" + details.id;
                url += "&st=" + details.status;
                url += "&amt=" + details.purchase_units[0].amount.value;
                url += "&item_number={{ $booking['id'] }}";
                window.location.href = url;
            });
        },
        onCancel: function(data) {
            console.log('PayPal payment cancelled');
        },
        onError: function(err) {
            console.error('PayPal error:', err);
            alert('An error occurred with PayPal. Please try again.');
        }
    }).render('#paypal-button-container');
    </script>
@stop

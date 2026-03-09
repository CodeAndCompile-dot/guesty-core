@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)

@section("container")

   @php
        $name=$data->name;
        $bannerImage=asset('front/images/internal-banner.webp');
        $amount=$booking['total_amount'] ?? 0;
        $payment_currency= $setting_data['payment_currency'] ?? '$';
    @endphp
    @if(!empty($booking['discount']) && $booking['discount'] != 0)
        @php $amount=$booking['after_discount_total'] ?? $amount; @endphp
    @endif

        @include("front.layouts.banner")

    <!-- start about section -->

      <!-- About Section -->

      <section class="payment">

        <div class="container">

            <div class="row">
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
                    <p>{{$property->title }}</p>
                </div>
                        </div>
                    </div>
                </div>
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
                    <p>{{$booking['checkin'] }}</p>
                </div>
                <div class="col-2 col-md-2 col-sm-12 check-out">
                    <p>{{$booking['checkout'] }}</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 guest">
                    <p>{{$booking['total_guests'] ?? 0 }} ({{$booking['adults'] ?? 0}} Adults, {{$booking['child'] ?? 0}} Child)</p>
                </div>
                <div class="col-2 col-md-2 col-sm-12 nights">
                    <p>{{$booking['total_night'] ?? 0 }}</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 amt">
                    <p>{!! $payment_currency !!}{{number_format($booking['gross_amount'] ?? 0,2) }}</p>
                </div>
                </div>
            </div>
            @isset($booking['before_total_fees'])
             @foreach(json_decode($booking['before_total_fees'] ?? '[]') as $c)
            <div class="misc">
                <div class="row">
                <div class="col-9 col-md-9 col-sm-12 mis">
                    <p>{{$c->title ?? $c->name ?? ''}}</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 amt">
                    <p>{!! $payment_currency !!}{{number_format($c->amount ?? 0,2) }}</p>
                </div>
                </div>
            </div>
            @endforeach
            @endisset
            @if($booking['tax'] ?? 0)
            <div class="taxes">
                <div class="row">
                <div class="col-9 col-md-9 col-sm-12 tax">
                    <p>Taxes</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 amt">
                    <p>{!! $payment_currency !!}{{number_format($booking['tax'],2) }}</p>
                </div>
                </div>
            </div>
            @endif
            @if(($booking['sub_amount'] ?? 0) != ($booking['gross_amount'] ?? 0))
            @if(count(json_decode($booking['after_total_fees'] ?? '[]'))>0)
            <div class="total">
                <div class="row">
                <div class="col-9 col-md-9 col-sm-12 tl">
                    <p>Sub Total</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 amt">
                    <p>{!! $payment_currency !!}{{number_format($booking['sub_amount'] ?? 0,2) }}</p>
                </div>
                </div>
            </div>
             @endif
            @endif
            <div class="total">
                <div class="row">
                <div class="col-9 col-md-9 col-sm-12 tl">
                    <p>Total</p>
                </div>
                <div class="col-3 col-md-3 col-sm-12 amt">
                    <p>{!! $payment_currency !!}{{number_format($booking['total_amount'] ?? 0,2) }}</p>
                </div>
                </div>
            </div>
            </div>


    <div class="row card-detail">
        <div class="col-md-12 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Card Details</h3>
                    </div>
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
                    <style>
                        .hide{
                            display:none;
                        }
                    </style>
                    <div class="error hide" >
                        <div class="alert alert-danger"></div>
                    </div>

                    <form role="form" action="{{ route('stripe.post',$booking['id']) }}" method="post" class="require-validation"
                                                     data-cc-on-file="false"
                                                    data-stripe-publishable-key="{{ ModelHelper::getDataFromSetting('stripe_publish_key') }}"
                                                    id="payment-form">
                        @csrf

                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> <input
                                    class='form-control' size='4' type='text'>
                            </div>
                        </div>

                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Card Number</label> <input
                                    autocomplete='off' class='form-control card-number' size='20'
                                    type='text'>
                            </div>
                        </div>

                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> <input autocomplete='off'
                                    class='form-control card-cvc' placeholder='ex. 311' size='4'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label> <input
                                    class='form-control card-expiry-month' placeholder='MM' size='2'
                                    type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label> <input
                                    class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                    type='text'>
                            </div>
                        </div>

                        <input type="hidden" name="amount" value="{{ round($amount,2) }}">

                        <div class="row mt-4">
                            <div class="col-xs-12">
                                <label>&nbsp;<br></label>
                                <button class="main-btn" type="submit">Pay Now ({!! $payment_currency !!}{{ $amount }})</button>
                            </div>
                        </div>

                    </form>
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
    <link rel="stylesheet" href="{{ asset('front')}}/css/get-quote.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/get-quote-responsive.css" />
@stop
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/get-quote.js" ></script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
$(function() {
    var $form         = $(".require-validation");
  $('form.require-validation').bind('submit', function(e) {
    var $form         = $(".require-validation"),
        inputSelector = ['input[type=email]', 'input[type=password]',
                         'input[type=text]', 'input[type=file]',
                         'textarea'].join(', '),
        $inputs       = $form.find('.required').find(inputSelector),
        $errorMessage = $form.find('div.error'),
        valid         = true;
        $errorMessage.addClass('hide');

        $('.has-error').removeClass('has-error');
    $inputs.each(function(i, el) {
      var $input = $(el);
      if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorMessage.removeClass('hide');
        e.preventDefault();
      }
    });

    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-number').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeResponseHandler);
    }

  });

  function stripeResponseHandler(status, response) {
      console.log(status);
      console.log(response);
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
        } else {
            var $form = $("#payment-form");
            var token = response['id'];
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $form.get(0).submit();
        }
    }

});
</script>
@stop

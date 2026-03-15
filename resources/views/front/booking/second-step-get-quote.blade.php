@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)
@section("container")
    @php
        $name='Get Quote';
        $bannerImage=asset('front/images/breadcrumb.webp');
    @endphp
   
    @php
        $before=json_decode($booking->before_total_fees,true);
        $new_result_booking_object=json_decode($booking->new_result_booking_object,true);
        $all_data=json_decode($property->all_data,true);
        if(isset($new_result_booking_object['money'])){
            $money=$new_result_booking_object['money'];
        }
        //dd($new_result_booking_object);
    @endphp
   <section class="get-quote-sec">
       <div class="container">
           <div class="quote-header">
               <h2>{{$property->title ?? ''}} : Booking Quote</h2>
           </div>
           
           <div class="quote-card">
               <div class="booking-summary">
                   <div class="booking-details-grid">
                       <div class="booking-detail-item">
                           <div class="detail-label">Check In</div>
                           <div class="detail-value">{{date('F jS, Y',strtotime($booking->checkin))}}</div>
                       </div>
                       <div class="booking-detail-item">
                           <div class="detail-label">Check Out</div>
                           <div class="detail-value">{{date('F jS, Y',strtotime($booking->checkout))}}</div>
                       </div>
                       <div class="booking-detail-item">
                           <div class="detail-label">Total Guests</div>
                           <div class="detail-value">{{$booking->total_guests}} Guests ({{ $booking->adults }} Adults, {{ $booking->child }} Child)</div>
                       </div>
                       <div class="booking-detail-item">
                           <div class="detail-label">Total Nights</div>
                           <div class="detail-value">{{$booking->total_night}}</div>
                       </div>
                   </div>
               </div>
               
               <div class="price-breakdown">
                   <table class="price-table">
                       <tbody>
                           @foreach($money['invoiceItems'] as $c)
                               <tr class="fee-row">
                                   <td class="fee-name">{{str_replace("_"," ",$c['title']) }}</td>
                                   <td class="fee-amount">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($c['amount'],2)}}</td>
                               </tr>
                           @endforeach
                           
                           <tr class="total-row">
                               <td class="total-label">Total</td>
                               <td class="total-amount">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($money['netIncome'],2)}}</td>
                           </tr>
                       </tbody>
                   </table>
               </div>
           </div>

           <div class="payment-card">
               <div class="payment-header">
                   <h3>Payment Information</h3>
               </div>
               
               {!! Form::open(["route"=>["update-payment-booking-data",$booking->new_reservation_id],"class"=>"payment-form","id"=>"paymentFrm"]) !!}
               <input type="hidden" value="{{  $money['netIncome'] }}" name="total_amount">
               
               <div class="form-section card-details">
                   <h4>Card Details</h4>
                   <div class="form-row">
                       <div class="form-group ">
                           <label for="card_name">Card Name <span class="required">*</span></label>
                           {!! Form::text("card_name",null,["class"=>"form-control","required","placeholder"=>"Enter Card Name"])!!}
                       </div>
                         <div class="form-group">
                           <label for="card_number">Card Number <span class="required">*</span></label>
                           {!! Form::text("card_number",null,["class"=>"form-control","required","placeholder"=>"Enter Card Number"])!!}
                       </div>
                   </div>
                   
                   <!--<div class="form-row">-->
                     
                   <!--</div>-->
                   
                   <div class="form-row">
                       <div class="form-group">
                           <label for="card_cvv">Card CVV <span class="required">*</span></label>
                           {!! Form::text("card_cvv",null,["class"=>"form-control","placeholder"=>"Enter Card CVV","required"])!!}
                       </div>
                       
                       <div class="form-group">
                           <label for="card_expiry_month">Expiry Month <span class="required">*</span></label>
                           {!! Form::select("card_expiry_month",Helper::getMonthListArray(),null,["class"=>"form-control","required"])!!}
                       </div>
                       
                       <div class="form-group">
                           <label for="card_expiry_year">Expiry Year <span class="required">*</span></label>
                           {!! Form::selectRange("card_expiry_year",date('Y'),2050,null,["class"=>"form-control","required"])!!}
                       </div>
                   </div>
               </div>
               
               <div class="form-section billing-details">
                   <h4>Billing Address</h4>
                   <div class="form-row">
                       <div class="form-group full-width">
                           <label for="address_line_1">Address <span class="required">*</span></label>
                           {!! Form::text("address_line_1",null,["class"=>"form-control","required","placeholder"=>"Enter Address Line 1"])!!}
                       </div>
                   </div>
                   
                   <div class="form-row">
                       <div class="form-group">
                           <label for="city">City <span class="required">*</span></label>
                           {!! Form::text("city",null,["class"=>"form-control","required","placeholder"=>"Enter City"])!!}
                       </div>
                       
                       <div class="form-group">
                           <label for="zipcode">Zipcode <span class="required">*</span></label>
                           {!! Form::text("zipcode",null,["class"=>"form-control","required","placeholder"=>"Enter Zipcode"])!!}
                       </div>
                       
                       <div class="form-group">
                           <label for="country">Country <span class="required">*</span></label>
                           {!! Form::select("country",Helper::getCountryListArray(),'US',["class"=>"form-control","placeholder"=>"Enter country","required"])!!}
                       </div>
                   </div>
               </div>
               
               <div class="form-actions">
                   <button type="submit" id="submitBtn" class="btn-primary" name="operation" value="send-quote">
                       <div class="spinner hidden" id="spinner"></div>
                       <span id="buttonText">Pay Now</span>
                   </button>
               </div>
               
               {!! Form::close() !!}
           </div>
       </div>
   </section>
@stop
@section("css")
    @parent
    <style>
        /* Modern Black & White Theme Variables */
        :root {
            /* Color Palette */
            --primary-accent: #000000; /* Pure Black */
            --background-light: #F5F5F5; /* Off White */
            --grey-light: #E0E0E0; /* Light Grey */
            --grey-mid: #B0B0B0; /* Medium Grey */
            --grey-dark: #707070; /* Dark Grey */
            --highlight-bg: #1A1A1A; /* Charcoal */
            --text-light: #FFFFFF; /* White */
            --text-dark: #000000; /* Black */
            --accent-success: #28a745; /* Success Green */
            --accent-error: #dc3545; /* Error Red */
        }

        /* Base Styles */
        .get-quote-sec {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            padding: 40px 0;
            background-color: var(--background-light);
            color: var(--text-dark);
        }

        .container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Quote Header */
        .quote-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .quote-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary-accent);
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }

        .quote-header h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 25%;
            width: 50%;
            height: 3px;
            background-color: var(--primary-accent);
        }

        /* Card Styles */
        .quote-card, 
        .payment-card {
            background-color: var(--text-light);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        /* Enhanced Booking Summary Section */
        .booking-summary {
            padding: 25px;
            border-bottom: 1px solid var(--grey-light);
        }

        .booking-details-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 15px;
            justify-content: space-between;
        }

        .booking-detail-item {
            flex: 1;
            padding: 15px;
            background-color: var(--background-light);
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .booking-detail-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
            background-color: #f8f8f8;
        }
        
        .detail-label {
            font-weight: 600;
            color: var(--highlight-bg);
            margin-right: 8px;
            white-space: nowrap;
            font-size: 0.9rem;
        }
        
        .detail-label::after {
            content: ":";
            margin-left: 2px;
            margin-right: 8px;
        }
        
        .detail-value {
            font-weight: 500;
            color: var(--text-dark);
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Icon styling for each detail item */
        .booking-detail-item::before {
            content: "";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            margin-right: 12px;
            font-size: 18px;
            color: var(--primary-accent);
            width: 24px;
            text-align: center;
        }
        
        .booking-detail-item:nth-child(1)::before {
            content: "\f133"; /* Calendar icon for Check In */
        }
        
        .booking-detail-item:nth-child(2)::before {
            content: "\f133"; /* Calendar for Check Out */
        }
        
        .booking-detail-item:nth-child(3)::before {
            content: "\f0c0"; /* Users icon for Total Guests */
        }
        
        .booking-detail-item:nth-child(4)::before {
            content: "\f186"; /* Moon icon for Total Nights */
        }
        
        /* Responsive styles */
        @media (max-width: 1200px) {
            .booking-details-grid {
                flex-wrap: wrap;
            }
            
            .booking-detail-item {
                flex: 1 1 calc(50% - 15px);
                min-width: calc(50% - 15px);
            }
        }
        
        @media (max-width: 767px) {
            .booking-detail-item {
                flex: 1 1 100%;
                min-width: 100%;
            }
            
            .booking-details-grid {
                flex-direction: column;
                gap: 10px;
            }
            
            .booking-detail-item {
                padding: 12px;
            }
            
            .detail-label, .detail-value {
                font-size: 14px;
            }
        }
        
        @media (max-width: 480px) {
            .booking-detail-item::before {
                font-size: 16px;
                margin-right: 8px;
            }
            
            .detail-value {
                font-size: 13px;
            }
        }

        .detail-label {
            font-size: 14px;
            font-weight: 600;
            color: var(--grey-dark);
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Price Breakdown */
        .price-breakdown {
            padding: 20px;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-table tr {
            border-bottom: 1px solid var(--grey-light);
        }

        .price-table tr:last-child {
            border-bottom: none;
        }

        .fee-row td {
            padding: 12px 15px;
        }

        .fee-name {
            color: var(--text-dark);
            font-weight: 400;
            width: 70%;
        }

        .fee-amount {
            text-align: right;
            font-weight: 500;
            width: 30%;
            color: var(--text-dark);
        }

        .total-row {
            border-top: 2px solid var(--grey-mid);
            background-color: rgba(0, 0, 0, 0.02);
        }

        .total-row td {
            padding: 15px;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-accent);
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            text-align: right;
            color: var(--primary-accent);
        }

        /* Payment Form */
        .payment-card {
            padding-bottom: 20px;
        }

        .payment-header {
            background-color: var(--primary-accent);
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .payment-header h3 {
            color: var(--text-light);
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .payment-form {
            padding: 0 20px;
        }

        .form-section {
            margin-bottom: 25px;
            
            border-bottom: 1px solid var(--grey-light);
        }

        .form-section h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--highlight-bg);
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px 15px;
        }

        .form-group {
            padding: 0 10px;
            margin-bottom: 15px;
            flex: 1;
        }

        .full-width {
            flex: 0 0 100%;
        }

        .three-columns .form-group {
            flex: 0 0 calc(33.33% - 20px);
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .required {
            color: var(--accent-error);
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--grey-mid);
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-accent);
            outline: none;
            box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' fill='none'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23000' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 30px;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-primary {
            border-radius: 50px;
    padding: 15px;
    height: auto;
            background-color: var(--primary-accent);
            color: var(--text-light);
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 200px;
        }

        .btn-primary:hover {
            background-color: var(--highlight-bg);
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: var(--text-light);
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        .hidden {
            display: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 1600px) {
             .booking-details-grid {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .three-columns .form-group {
                flex: 0 0 calc(50% - 20px);
            }
            
            .booking-details-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 767px) {
            .three-columns .form-group {
                flex: 0 0 100%;
            }
            
            .booking-details-grid {
                grid-template-columns: 1fr;
            }
            
            .quote-header h2 {
                font-size: 24px;
            }
            
            .fee-name, .fee-amount {
                padding: 10px;
            }
            
            .total-label, .total-amount {
                font-size: 16px;
            }
            
            .form-row {
                flex-direction: column;
            }
        }

        @media (max-width: 576px) {
            .booking-detail-item {
                padding: 8px;
            }
            
            .detail-label {
                font-size: 13px;
            }
            
            .detail-value {
                font-size: 15px;
            }
            
            .btn-primary {
                width: 100%;
            }
        }
    </style>
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/get-quote.js" ></script>

@stop
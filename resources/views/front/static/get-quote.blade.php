@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)
@section("logo",$data->image)
@section("header-section")
{!! $data->header_section !!}
@stop
@section("footer-section")
{!! $data->footer_section !!}
@stop
@section("container")
    @php
        $name=$data->name;
        $bannerImage=asset('front/images/breadcrumb.webp');
        if($data->bannerImage){
            $bannerImage=asset($data->bannerImage);
        }
    @endphp
    
      @php
        $guestyapi=$main_data['guestyapi'];
        $start_date=$main_data["start_date"];
        $end_date=$main_data["end_date"];
        $adults=$main_data["adults"];
        $child=$main_data["child"];
        $total_guests=$adults+$child;
        if(isset($guestyapi['data']['rates']['ratePlans'])){
            if(isset($guestyapi['data']['rates']['ratePlans'][0])){
                if(isset($guestyapi['data']['rates']['ratePlans'][0]['money'])){
                    if(isset($guestyapi['data']['rates']['ratePlans'][0]['money']['money'])){
                        $moneyData=$guestyapi['data']['rates']['ratePlans'][0]['money']['money'];
                        $rate_api_id=$guestyapi['data']['rates']['ratePlans'][0]['money']['_id'];
                        $gross_amount=$moneyData['fareAccommodation'];
                        $sub_total=$moneyData['subTotalPrice'];
                        $total_amount=$moneyData['hostPayout'];
                        $taxes=$moneyData['totalTaxes'];
                        $before_total_fees=$moneyData['invoiceItems'];
                        $quote_id=$guestyapi['data']['_id'];
                    }else{
                        @endphp <script>window.location = "{{ url($property->seo_url) }}";</script> @php
                    }
                }else{
                    @endphp <script>window.location = "{{ url($property->seo_url) }}";</script> @php
                }
            }else{
                @endphp <script>window.location = "{{ url($property->seo_url) }}";</script> @php
            }
        }else{
            @endphp <script>window.location = "{{ url($property->seo_url) }}";</script> @php
        }
        $total_guests=$main_data["adults"]+$main_data["childs"];
        $gross_amount=$gross_amount;
        $tax=0;
        $now = strtotime($start_date); 
        $your_date = strtotime($end_date);
        $datediff =  $your_date-$now;
        $day= ceil($datediff / (60 * 60 * 24));
        $total_night=$day;
        $after_total_fees=[];
        $pet_fee=0;
        $total_pets=0;
        $amount_data=[];
        $total_payment=$total_amount;
        $after_total_fees=[];
        $define_tax=0;
    @endphp
   <section class="get-quote-sec">
       <div class="container">
           <div class="row">
              <div class="col-md-12 text-center">
                  <h2 class="booking-title">{{$property->title ?? ''}} : Booking Quote</h2>
              </div>
            </div>
            
            <!-- Desktop Quote Table -->
            <div class="quote-container">
                <div class="quote-card">
                    <!-- Reservation Summary -->
                    <div class="reservation-summary">
                        <div class="summary-header">
                            <h3>Reservation Summary</h3>
                        </div>
                        <div class="summary-grid">
                            <div class="summary-item">
                                <div class="summary-label">Check In</div>
                                <div class="summary-value">{{date('F jS, Y',strtotime($start_date))}}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Check Out</div>
                                <div class="summary-value">{{date('F jS, Y',strtotime($end_date))}}</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Total Guests</div>
                                <div class="summary-value">{{$total_guests}} Guests ({{ $adults }} Adults, {{ $child }} Child)</div>
                            </div>
                            <div class="summary-item">
                                <div class="summary-label">Total Nights</div>
                                <div class="summary-value">{{$day}}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="price-breakdown">
                        <div class="breakdown-header">
                            <h3>Price Breakdown</h3>
                        </div>
                        
                        <table class="breakdown-table">
                            <tbody>
                                @foreach($before_total_fees as $c)
                                <tr class="breakdown-item">
                                    <td class="item-name">{{str_replace("_"," ",$c['title']) }}</td>
                                    <td class="item-price">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($c['amount'],2)}}</td>
                                </tr>
                                @endforeach
                                
                                <!-- Coupon Section -->
                                @php $apply_button_name="Apply";$apply=0;$error=0; @endphp
                                <tr class="coupon-section">
                                    <td colspan="2">
                                        <div class="coupon-container" id="coupon-form">
                                            <div class="coupon-label">Do you have a coupon code?</div>
                                            <div class="coupon-form">
                                                @if(Request::get("coupon"))
                                                <form method="get" action="{{ url('get-quote') }}">
                                                    @foreach(Request::except(["coupon"]) as $key=>$c_gaurav)
                                                        <input type="hidden" name="{{  $key }}" value="{{ $c_gaurav }}" />
                                                    @endforeach
                                                    <div class="input-group">
                                                        <input type="text" disabled name="coupon" value="{{Request::get('coupon')}}" placeholder="Enter Coupon Code" />
                                                        <button type="submit" class="btn-remove">
                                                            <i class="fa fa-times"></i> Remove
                                                        </button>
                                                    </div>
                                                </form>
                                                @else
                                                <form method="get" action="{{ url('get-quote') }}">
                                                    <div class="input-group">
                                                        <input type="text" name="coupon" value="{{Request::get('coupon')}}" placeholder="Enter Coupon Code" />
                                                        <button type="submit" {{ $apply==1?'disabled':'' }} class="btn-apply {{ $apply==1?'d-none':'' }}">
                                                            {{ $apply_button_name }}
                                                        </button>
                                                    </div>
                                                    @if($apply==0)
                                                        @foreach(Request::except(["coupon"]) as $key=>$c_gaurav)
                                                            <input type="hidden" name="{{  $key }}" value="{{ $c_gaurav }}" />
                                                        @endforeach
                                                    @endif
                                                </form>
                                                @endif
                                                
                                                @if($apply==1)
                                                    @if($error==1)
                                                        <div class="message error">Invalid Coupon</div>
                                                    @endif
                                                    @if($apply==1)
                                                    <div class="message success">Coupon code applied successfully</div>
                                                    @endif
                                                    <div class="discount-amount">
                                                        {!! ModelHelper::getDataFromSetting('payment_currency') !!} {{number_format($discount,2)}}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Hidden taxes row -->
                                <tr class="taxes-row" style="display:none;">
                                    <td class="taxes-label">Total Taxes</td>
                                    <td class="taxes-value">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($taxes,2)}}</td>
                                </tr>
                                
                                <!-- Total row -->
                                <tr class="total-row">
                                    <td class="total-label">Total</td>
                                    <td class="total-value">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($total_amount,2)}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Mobile Quote View -->
                <div class="quote-mobile">
                    <!-- Reservation Details -->
                    <div class="mobile-section">
                        <h3>Reservation Details</h3>
                        <div class="mobile-item">
                            <div class="mobile-label">Check In</div>
                            <div class="mobile-value">{{date('F jS, Y',strtotime($start_date))}}</div>
                        </div>
                        <div class="mobile-item">
                            <div class="mobile-label">Check Out</div>
                            <div class="mobile-value">{{date('F jS, Y',strtotime($end_date))}}</div>
                        </div>
                        <div class="mobile-item">
                            <div class="mobile-label">Total Guests</div>
                            <div class="mobile-value">{{$total_guests}} Guests ({{ $adults }} Adults, {{ $child }} Child)</div>
                        </div>
                        <div class="mobile-item">
                            <div class="mobile-label">Total Nights</div>
                            <div class="mobile-value">{{$day}}</div>
                        </div>
                    </div>
                    
                    <!-- Price Breakdown -->
                    <div class="mobile-section">
                        <h3>Price Breakdown</h3>
                        @foreach($before_total_fees as $c)
                        <div class="mobile-item">
                            <div class="mobile-label">{{str_replace("_"," ",$c['title']) }}</div>
                            <div class="mobile-value">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($c['amount'],2)}}</div>
                        </div>
                        @endforeach
                        
                        <div class="mobile-subtotal mobile-item">
                            <div class="mobile-label">Sub Total</div>
                            <div class="mobile-value">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($sub_total,2)}}</div>
                        </div>
                        
                        <div class="mobile-total mobile-item">
                            <div class="mobile-label">Total</div>
                            <div class="mobile-value">{!! ModelHelper::getDataFromSetting('payment_currency') !!}{{number_format($total_amount,2)}}</div>
                        </div>
                    </div>
                </div>
            </div>

    {!! Form::open(["url"=>"save-booking-data","class"=>"booking-form","id"=>"paymentFrm"]) !!}
        <!-- Hidden form fields - preserved functionality -->
        <input type="hidden" name="discount" value="{{ 0 }}" />
        <input type="hidden" name="discount_coupon" value="{{ Request::get('coupon') }}" />
        <input type="hidden" name="total_pets" value="{{ 0 }}">
        <input type="hidden" name="pet_fee" value="{{ 0 }}">
        <input type="hidden" name="guest_fee" value="{{ 0 }}">
        <input type="hidden" name="rest_guests" value="{{ 0 }}">
        <input type="hidden" name="single_guest_fee" value="{{ 0 }}">
        <input type="hidden" name="total_payment" value="{{ $total_payment }}">
        <input type="hidden" name="amount_data" value="{{ json_encode($amount_data) }}">
        <input type="hidden" name="property_id" value="{{ $property->id }}">
        <input type="hidden" name="checkin" value="{{ $start_date }}" >
        <input type="hidden" name="checkout" value="{{ $end_date }}" >
        <input type="hidden" name="total_guests" value="{{ $total_guests }}" >
        <input type="hidden" name="adults" value="{{ $adults }}" >
        <input type="hidden" name="child" value="{{ $child }}" >
        <input type="hidden" name="gross_amount" value="{{ $gross_amount }}" >
        <input type="hidden" name="total_night" value="{{ $day }}" >
        <input type="hidden" name="sub_amount" value="{{ $sub_total }}" >
        <input type="hidden" name="total_amount" value="{{ $total_amount }}" >
        <input type="hidden" name="after_total_fees" value="{{ json_encode($after_total_fees) }}">
        <input type="hidden" name="before_total_fees" value="{{ json_encode($before_total_fees) }}">
        <input type="hidden" name="request_id" value="{{ uniqid() }}" >
        <input type="hidden" name="tax" value="{{ $taxes }}" >
        <input type="hidden" name="define_tax" value="{{ 0 }}" >
        <input type="hidden" name="rate_api_id" value="{{ $rate_api_id }}">
        <input type="hidden" name="stripe_intent_data_id" value="" id="stripe_intent_data_id">
        <input type="hidden" name="stripe_main_payment_method" value="" id="stripe_main_payment_method">
        <input type="hidden" name="quote_id" value="{{ $quote_id }}" >
        
        <div class="form-container">
            <div class="form-header">
                <h3>Guest Information</h3>
            </div>
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">First Name <span class="required">*</span></label>
                    {!! Form::text("firstname",null,["class"=>"form-input","required","placeholder"=>"Enter First Name","id"=>"first_name"])!!}
                </div>
                
                <div class="form-group">
                    <label for="last_name">Last Name <span class="required">*</span></label>
                    {!! Form::text("lastname",null,["class"=>"form-input","required","placeholder"=>"Enter Last Name","id"=>"last_name"])!!}
                </div>
                
                <div class="form-group">
                    <label for="email">Email ID <span class="required">*</span></label>
                    {!! Form::email("email",null,["class"=>"form-input","required","placeholder"=>"Enter email","id"=>"email"])!!}
                </div>
                
                <div class="form-group">
                    <label for="mobile">Mobile Number <span class="required">*</span></label>
                    {!! Form::number("mobile",null,["class"=>"form-input","placeholder"=>"Enter mobile","required"])!!}
                </div>
                
                @php
                $all_data= GuestyApi::getAdditionalFeeData($property->_id);
                if(isset($all_data['data'])){
                    if(isset($all_data['data']['isPetsAllowed'])){
                        if($all_data['data']['isPetsAllowed']==true){
                                @endphp
                                <div class="form-group pet-select">
                                    <label for="total_pets">Pet</label>
                                    {!! Form::selectRange("total_pets",0,2,null,["class"=>"form-input","placeholder"=>"Choose Pet"])!!}
                                </div>
                                @php
                        }      
                    }
                }
                @endphp
            </div>
            
            <div class="form-actions">
                <button type="submit" id="submitBtn" class="btn-book-now" name="operation" value="send-quote">
                    <div class="spinner hidden" id="spinner"></div>
                    <span id="buttonText">Book Now</span>
                </button>
            </div>
        </div>
       {!! Form::close() !!}
       </div>
   </section>
@stop
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
        --cta-button: #707070; /* Mid Grey */
        --cta-text: #FFFFFF; /* White */
        
        /* Legacy variables mapped to new palette */
        --red-cottage-red: #000000; /* Changed to pure black */
        --red-cottage-dark: #1A1A1A; /* Changed to charcoal */
        --red-cottage-light: #F5F5F5; /* Changed to off white */
        --red-cottage-beige: #E0E0E0; /* Changed to light grey */
        
        /* Spacing */
        --spacing-xs: 0.25rem;
        --spacing-sm: 0.5rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
        --spacing-xxl: 3rem;
        
        /* Typography */
       
        --font-size-sm: 0.875rem;
        --font-size-base: 1rem;
        --font-size-md: 1.125rem;
        --font-size-lg: 1.25rem;
        --font-size-xl: 1.5rem;
        --font-size-xxl: 2rem;
        
        /* Border Radius */
        --border-radius-sm: 0.125rem;
        --border-radius-md: 0.25rem;
        --border-radius-lg: 0.5rem;
        
        /* Box Shadows */
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.05);
    }
    
    /* Global Styles */
    section.get-quote-sec {
        padding: var(--spacing-xl) 0;
        background-color: var(--background-light);
        color: var(--text-dark);
        font-family: var(--font-family);
    }
    
    /* Title Styles */
    .booking-title {
        font-size: var(--font-size-xxl);
        margin-bottom: var(--spacing-xl);
        color: var(--primary-accent);
        font-weight: 700;
        text-align: center;
    }
    
    /* Quote Container */
    .quote-container {
       
        margin: 0 auto;
        padding: 0 var(--spacing-md);
    }
    
    /* Quote Card */
    .quote-card {
        background-color: var(--text-light);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--spacing-xl);
    }
    
    /* Reservation Summary */
    .reservation-summary {
        margin-bottom: var(--spacing-lg);
    }
    
    .summary-header {
        background-color: var(--primary-accent);
        color: var(--text-light);
        padding: var(--spacing-md);
        border-top-left-radius: var(--border-radius-lg);
        border-top-right-radius: var(--border-radius-lg);
    }
    
    .summary-header h3 {
        margin: 0;
        font-size: var(--font-size-lg);
        font-weight: 600;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 0;
        border-bottom: 1px solid var(--grey-light);
    }
    
    .summary-item {
        padding: var(--spacing-md);
        border-right: 1px solid var(--grey-light);
    }
    
    .summary-item:last-child {
        border-right: none;
    }
    
    .summary-label {
        font-size: var(--font-size-sm);
        color: var(--grey-dark);
        margin-bottom: var(--spacing-xs);
        font-weight: 600;
    }
    
    .summary-value {
        font-size: var(--font-size-base);
        color: var(--text-dark);
        font-weight: 500;
    }
    
    /* Price Breakdown */
    .price-breakdown {
        padding: var(--spacing-md);
    }
    
    .breakdown-header {
        margin-bottom: var(--spacing-md);
    }
    
    .breakdown-header h3 {
        font-size: var(--font-size-lg);
        color: var(--primary-accent);
        margin: 0;
        font-weight: 600;
    }
    
    /* Table Styles */
    .breakdown-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .breakdown-item td {
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--grey-light);
    }
    
    .item-name {
        font-size: var(--font-size-base);
        color: var(--grey-dark);
        width: 75%;
    }
    
    .item-price {
        font-size: var(--font-size-base);
        color: var(--text-dark);
        text-align: right;
        font-weight: 600;
        width: 25%;
    }
    
    /* Coupon Section */
    .coupon-section td {
        padding: var(--spacing-lg) var(--spacing-md);
        border-bottom: 1px solid var(--grey-light);
    }
    
    .coupon-container {
        width: 100%;
    }
    
    .coupon-label {
        font-size: var(--font-size-base);
        color: var(--text-dark);
        margin-bottom: var(--spacing-sm);
        font-weight: 600;
    }
    
    .coupon-form {
        display: flex;
        flex-direction: column;
    }
    
    .input-group {
        display: flex;
        align-items: center;
        margin-top: var(--spacing-xs);
    }
    
    .input-group input {
        flex: 1;
        padding: var(--spacing-sm) var(--spacing-md);
        border: 1px solid var(--grey-light);
        border-radius: var(--border-radius-sm);
        font-size: var(--font-size-sm);
        margin-right: var(--spacing-sm);
    }
    
    .btn-apply, .btn-remove {
        padding: var(--spacing-sm) var(--spacing-md);
        border: none;
        border-radius: var(--border-radius-sm);
        font-size: var(--font-size-sm);
        cursor: pointer;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    
    .btn-apply {
        background-color: var(--primary-accent);
        color: var(--text-light);
    }
    
    .btn-apply:hover {
        background-color: var(--highlight-bg);
    }
    
    .btn-remove {
        background-color: #ff3b30;
        color: var(--text-light);
    }
    
    .btn-remove:hover {
        background-color: #e02e24;
    }
    
    .message {
        margin-top: var(--spacing-xs);
        font-size: var(--font-size-sm);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: var(--border-radius-sm);
    }
    
    .message.success {
        color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .message.error {
        color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    .discount-amount {
        text-align: right;
        font-weight: 600;
        color: #28a745;
        font-size: var(--font-size-base);
        margin-top: var(--spacing-xs);
    }
    
    /* Total Row */
    .total-row td {
        padding: var(--spacing-md);
        border-top: 2px solid var(--grey-dark);
    }
    
    .total-label {
        font-size: var(--font-size-lg);
        color: var(--primary-accent);
        font-weight: 700;
    }
    
    .total-value {
        font-size: var(--font-size-lg);
        color: var(--primary-accent);
        text-align: right;
        font-weight: 700;
    }
    
    /* Form Styles */
    .form-container {
        background-color: var(--text-light);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-top: var(--spacing-xl);
    }
    
    .form-header {
        background-color: var(--primary-accent);
        color: var(--text-light);
        padding: var(--spacing-md);
    }
    
    .form-header h3 {
        margin: 0;
        font-size: var(--font-size-lg);
        font-weight: 600;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 2fr));
        gap: var(--spacing-md);
        padding: var(--spacing-lg);
    }
    
    .form-group {
        margin-bottom: var(--spacing-md);
    }
    
    .form-group label {
        display: block;
        font-size: var(--font-size-sm);
        margin-bottom: var(--spacing-xs);
        color: var(--grey-dark);
        font-weight: 500;
    }
    
    .required {
        color: #dc3545;
    }
    
    .form-input {
        width: 100%;
        padding: var(--spacing-sm) var(--spacing-md);
        border: 1px solid var(--grey-light);
        border-radius: var(--border-radius-sm);
        font-size: var(--font-size-base);
        transition: border-color 0.2s ease;
    }
    
    .form-input:focus {
        border-color: var(--primary-accent);
        outline: none;
    }
    
    .form-actions {
        padding: var(--spacing-md) var(--spacing-lg) var(--spacing-xl);
        text-align: center;
    }
    
    .btn-book-now {
        border-radius: 50px;
    padding: 15px;
    height: auto;
  
    border: none;
   

        background-color: var(--primary-accent);
        color: var(--text-light);
        border: none;
        
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease;
        min-width: 200px;
    }
    
    .btn-book-now:hover {
        background-color: var(--highlight-bg);
    }
    
    .spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border-radius: 50%;
        border: 2px solid var(--text-light);
        border-top-color: transparent;
        animation: spinner 0.8s linear infinite;
    }
    
    @keyframes spinner {
        to {
            transform: rotate(360deg);
        }
    }
    
    .hidden {
        display: none;
    }
    
    /* Mobile Quote View */
    .quote-mobile {
        display: none;
        background-color: var(--text-light);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
        overflow: hidden;
        margin-bottom: var(--spacing-xl);
    }
    
    .mobile-section {
        margin-bottom: var(--spacing-md);
    }
    
    .mobile-section h3 {
        background-color: var(--primary-accent);
        color: var(--text-light);
        padding: var(--spacing-md);
        margin: 0;
        font-size: var(--font-size-md);
        font-weight: 600;
    }
    
    .mobile-item {
        display: flex;
        justify-content: space-between;
        padding: var(--spacing-md);
        border-bottom: 1px solid var(--grey-light);
    }
    
    .mobile-label {
        font-size: var(--font-size-base);
        color: var(--grey-dark);
        flex: 0 0 50%;
    }
    
    .mobile-value {
        font-size: var(--font-size-base);
        color: var(--text-dark);
        text-align: right;
        font-weight: 500;
        flex: 0 0 50%;
    }
    
    .mobile-subtotal {
        background-color: var(--grey-light);
    }
    
    .mobile-total {
        background-color: var(--grey-light);
    }
    
    .mobile-total .mobile-label,
    .mobile-total .mobile-value {
        font-weight: 700;
        font-size: var(--font-size-md);
        color: var(--primary-accent);
    }
    
    /* Pet Select Field */
    .pet-select {
        grid-column: span 2;
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .summary-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .summary-item:nth-child(2n) {
            border-right: none;
        }
        
        .summary-item:nth-child(-n+2) {
            border-bottom: 1px solid var(--grey-light);
        }
    }
    
    @media (max-width: 768px) {
        .quote-card {
            display: none;
        }
        
        .quote-mobile {
            display: block;
        }
        
        .form-grid {
            grid-template-columns: 2fr 2fr;
        }
        
        .pet-select {
            grid-column: span 1;
        }
        
        .booking-title {
            font-size: var(--font-size-xl);
            margin-bottom: var(--spacing-lg);
        }
    }
    
    @media (max-width: 576px) {
        .input-group {
            flex-direction: column;
            align-items: stretch;
        }
        
        .input-group input {
            margin-right: 0;
            margin-bottom: var(--spacing-sm);
        }
        
        .btn-book-now {
            width: 100%;
        }
    }
</style>
@section("css")
    @parent
    <!--<link rel="stylesheet" href="{{ asset('front')}}/css/get-quote.css" />-->
    <!--<link rel="stylesheet" href="{{ asset('front')}}/css/get-quote-responsive.css" />-->
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/get-quote.js" ></script>
    <script>
    $(document).ready(function() {
        // Form validation
        $("#paymentFrm").on("submit", function() {
            const firstName = $("#first_name").val().trim();
            const lastName = $("#last_name").val().trim();
            const email = $("#email").val().trim();
            
            if (!firstName || !lastName || !email) {
                return false;
            }
            
            $("#submitBtn").prop("disabled", true);
            $("#spinner").removeClass("hidden");
            $("#buttonText").text("Processing...");
        });
        
        // Coupon handling (preserved functionality)
        $(document).on("change", "#is_coupon", function() {
            if($(this).prop("checked") == true) {
                $("#coupon-form").show();
            } else {
                $("#coupon-form").hide();
            }
        });
        
        @if(Request::get("coupon"))
            $("#is_coupon").prop("checked", "true");
            $("#coupon-form").show();
        @endif
    });
</script>
@stop
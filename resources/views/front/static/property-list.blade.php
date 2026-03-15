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
   
<div class="search-bar mt-md-5" id="check">
    <div class="container">
        <form method="get" action="{{ url('properties')}}">
            <div class="row">
               <div class="col-6 md-12 sm-12 icns mb-lg-0 position-relative  datepicker-section datepicker-common-2 main-check">
                            <div class="row">
                                <div class="check left icns mb-lg-0 position-relative datepicker-common-2 col-md-6">
                               {!! Form::text("start_date",null,["required","autocomplete"=>"off","inputmode"=>"none","id"=>"start_date","placeholder"=>"Check in","title"=>"Select Check In Dates","class"=>"form-control"]) !!}
                               <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <div class="check right icns mb-lg-0 position-relative datepicker-common-2 check-out col-md-6">
                               {!! Form::text("end_date",null,["required","autocomplete"=>"off","inputmode"=>"none","id"=>"end_date","placeholder"=>"Check Out","title"=>"Select Check Out Dates","class"=>"form-control lst" ]) !!}
                               <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <div class="col-12 md-12 sm-12 datepicker-common-2 datepicker-main">
                                    <input type="text" id="demo17" value="" aria-label="Check-in and check-out dates" aria-describedby="demo17-input-description" readonly />
                                </div>
                            </div>
                    </div>


            <div class="col-3 md-12 sm-12 guest">
                    <input type="text" name="Guests"   value="{{ Request::get('Guests') ?? '' }}" readonly="" class="form-control gst" id="show-target-data" placeholder="Guests">
                    <i class="fa-solid fa-users "></i>
                    <input type="hidden" value="{{ Request::get('adults') ?? '0' }}"  name="adults" id="adults-data" />
                    <input type="hidden" value="{{ Request::get('child') ?? '0' }}"  name="child" id="child-data" />
                    <div class="adult-popup" id="guestsss">
                        <i class="fa fa-times close1"></i>
                        <div class="adult-box pt-3">
                            <p id="adults-data-show"><span>@if(Request::get('adults'))
                                                                @if(Request::get('adults')>1)
                                                                    {{ Request::get('adults') }} Adults
                                                                @else
                                                                    {{ Request::get('adults') }} Adult
                                                                @endif
                                                             @else
                                                             Adult
                                                             @endif</span> 18+</p>
                            <div class="adult-btn">
                                <button class="button1"  type="button" onclick="functiondec('#adults-data','#show-target-data','#child-data')" value="Decrement Value">-</button>
                                <button class="button11 button1" type="button"  onclick="functioninc('#adults-data','#show-target-data','#child-data')" value="Increment Value">+</button>
                            </div>
                        </div>
                        <div class="adult-box">
                            <p id="child-data-show"><span>@if(Request::get('child'))
                                                                @if(Request::get('child')>1)
                                                                    {{ Request::get('child') }} Children
                                                                @else
                                                                    {{ Request::get('child') }} Child
                                                                @endif
                                                             @else
                                                             Child
                                                             @endif</span> (0-17)</p>
                            <div class="adult-btn">
                                <button class="button1" type="button"  onclick="functiondec('#child-data','#show-target-data','#adults-data')" value="Decrement Value">-</button>
                                <button class="button11 button1" type="button"  onclick="functioninc('#child-data','#show-target-data','#adults-data')" value="Increment Value">+</button>
                            </div>
                        </div>
                        <button class="main-btn  close111" type="button" onclick="">Apply</button>
                    </div>
                </div>
                <div class="col-3 md-12 sm-12 srch-btn">
                    <button type="submit" class="main-btn w-100">
                        <div class="search-button-content">
                            <i class="fa-solid fa-search"></i>
                            <span>Search</span>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>
 </div>
@php
 $currency=$setting_data['payment_currency'];
    $list=App\Models\Guesty\GuestyProperty::where(["status"=>"true"]);
    $total_sleep=0;
    if(Request::get("Guests")){
        if(Request::get("adults")!=""){
            if(is_int((int)Request::get("adults"))){
                $total_sleep+=Request::get("adults");
            }
        }
        if(Request::get("child")!=""){
            if(is_int((int)Request::get("child"))){
                $total_sleep+=Request::get("child");
            }
        }
    }
    if(Request::get("start_date")){
        if(Request::get("end_date")){
            $ids=Helper::getPropertyListNew(Request::get("start_date"),Request::get("end_date"),$total_sleep);
            #dd($ids);
            $list->whereIn("_id",$ids);
        }
    }
    if(Request::get("location_id")){
        $list->where('location_id', (int)Request::get("location_id"));
    }
    if($total_sleep>0){
        $list->where("accommodates",">=",$total_sleep);
    }
    $list->where("active","1");
    $list=$list->orderBy("id","desc")->paginate(1000);
@endphp
<a href="#check" class="sticky main-btn book1 check"><span class="button-text">Book Now</span></a>
<section class="home-list properties-section">
   <div class="container">
      <div class="section-header my-5">
         <h2 class="section-title">Our Properties</h2>
         <p class="section-subtitle">Where you stay matters; enjoy our hand-picked selection of places to stay in and around Bentonville, AR,</p>
         <p class="section-subtitle">that gives you access to world-class Mountain Biking, incredible dining, small-town charm, and more! From luxury to affordability find your base camp in Northwest Arkansas today!</p>
      </div>
      <div class="row">
         @forelse($list as $c)
         @php
             $picture=json_decode($c->picture,true);
             $pictures=json_decode($c->pictures,true);
             if($pictures){
                 if(count($pictures)>0){
                    $picture=$pictures[0];
                 }
             }
             $prices=json_decode($c->prices,true);
             $address=json_decode($c->address,true);
         @endphp
         <div class="col-lg-4 col-md-6">
            <div class="property-card">
               <div class="property-image">
                  @isset($picture['original'])
                     <a href="{{ url($c->seo_url).'?'.http_build_query(request()->all()) }}">
                        <img src="{{$picture['original']}}" alt="{{$picture['caption'] ?? $c->title}}">
                     </a>
                  @endisset
                  <div class="featured">
                     <span>{{$c->propertyType}}</span>
                  </div>
               </div>
               <div class="property-content">
                <h3 class="property-title">
    <a href="{{ url($c->seo_url) . '?' . http_build_query(request()->all()) }}">
                {{ \Illuminate\Support\Str::limit($c->title, 40) }}
       
    </a>
</h3>
                  <p class="property-description">  {{ \Illuminate\Support\Str::limit($c->summary, 40) }}</p>
                  @isset($prices['basePrice'])
                     <p class="property-price">Starting at {{ $currency }} {{$prices['basePrice']}} / night</p>
                  @endisset
                  <div class="property-details">
                     @isset($address['full'])
                     <div class="property-detail">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <span>{{$address['full']}}</span>
                     </div>
                     @endisset
                  </div>
                  <div class="property-details mt-3" style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 15px;">
                     <div class="property-detail">
                        <i class="fa-solid fa-users"></i>
                        <span>{{$c->accommodates}} {{$c->accommodates>1?'guests':'guest'}}</span>
                     </div>
                     <div class="property-detail">
                        <i class="fa-solid fa-bed"></i>
                        <span>{{$c->bedrooms}} {{$c->bedrooms>1?'beds':'bed'}}</span>
                     </div>
                     <div class="property-detail">
                        <i class="fa-solid fa-bath"></i>
                        <span>{{$c->bathrooms}} {{$c->bathrooms>1?'baths':'bath'}}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @empty
         <div class="col-12 col-md-12 col-sm-12">
            <div class="alert alert-info">
               No properties found matching your criteria.
            </div>
         </div>
         @endforelse
      </div>
   </div>
</section>

@stop

@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-list.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-list-responsive.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('datepicker') }}/dist/css/hotel-datepicker.css"/>
    <link rel="stylesheet" href="{{ asset('front')}}/css/datepicker.css" />
    
    <style>
    .search-bar {
        position: relative;
        z-index: 10;
    }
    .search-bar .col-3  {
        width: 30%;
    }
    .search-bar form {
        position: sticky;
        background: white;
        border-radius: 10px;
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 10px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    div#guestsss {
        background: white;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        padding: 15px;
    }
    
    .main-btn {
        background: #000;
        color: white;
        border-radius: 10px !important;
        height: 46px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .main-btn:hover {
        background: #000;
        color: white;
        
    }
    
    .search-bar button.main-btn.close111 {
        background: black;
        color: white;
        border-radius: 50px;
        width: 100%;
        margin-top: 10px;
    }
    
    .search-bar .srch-btn {
        display: flex;
        align-items: center;
    }
    
    .search-button-content {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
    }
    
    .search-button-content i {
        margin-right: 10px;
    }
    
    .search-button-content span {
        font-weight: 500;
    }
    
    .search-bar .col-6 {
        padding-right: 10px;
    }
    
    .search-bar .col-3.guest {
        padding-right: 10px;
    }
    
    @media (max-width: 991px) {
        .search-bar form {
            max-width: 95%;
            padding: 15px;
        }
        
        .col-6.md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }
        
        .col-3.md-12 {
            flex: 0 0 100%;
            max-width: 100%;
            margin-bottom: 10px;
        }
    }
    
    @media (max-width: 768px) {
        .search-bar form {
            padding: 12px;
        }
        
        .search-bar .row > div {
            margin-bottom: 12px;
        }
        
        .search-bar .srch-btn {
            padding-top: 5px;
        }
        
        .main-btn {
            height: 42px;
        }
    }
        /* Property Card Styles */
        .property-card {
            background: #FFFFFF;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            position: relative;
            opacity: 1;
            transform: translateY(0);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }

        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .property-image {
            height: 375px;
            position: relative;
            overflow: hidden;
        }

        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .property-card:hover .property-image img {
            transform: scale(1.05);
        }

        .property-content {
            padding: 20px;
            position: relative;
        }

        .property-title {
            font-size: 1.5rem;
            font-weight: 500;
            color: #000000;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .property-title a {
            color: inherit;
            text-decoration: none;
        }

        .property-description {
            color: #707070;
            margin-bottom: 10px;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        .property-price {
            font-weight: 600;
            color: #000000;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .property-details {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 0.9rem;
            justify-content: space-between;
            margin-top: 15px;
        }

        .property-detail {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .featured {
            position: absolute;
            top: 20px;
            left: 0;
            background: #000000;
            color: #FFFFFF;
            padding: 8px 16px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 5;
        }

        /* Properties Section */
        .properties-section {
            padding-bottom: 80px;
            padding-top: 40px;
            background: #F5F5F5;
        }

        .section-header {
            margin-bottom: 20px;
            text-align: center;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            color: #000000;
            font-weight: 400;
            margin-bottom: 8px;
        }

        .section-subtitle {
            color: #707070;
            font-size: 1rem;
            max-width: 100%;
            margin: 0 auto;
        }
         @media (min-width: 980px) {
            .section-subtitle {
                width: 70% !important;
            }
               
        }
        @media (max-width: 980px) {
             .search-bar {
                    position: absolute ;
                    height: 200px;
                }
        }
        a.sticky.main-btn {
            color: white !important;
        }
    </style>
@stop
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/properties-list.js" ></script>
    
    <script>
    // Add animations and hover effects to property cards
    document.addEventListener('DOMContentLoaded', function() {
        const propertyCards = document.querySelectorAll('.property-card');
        
        // Add hover effects
        propertyCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = '';
            });
        });
        
        // Add staggered reveal animation to property cards
        setTimeout(() => {
            propertyCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 150);
            });
        }, 300);
    });
    </script>
    <script>
    var val=0;
    function functiondec($getter_setter,$show,$cal){
        val=parseInt($($getter_setter).val());
        if(val>0){
            val=val-1;
        }
        $($getter_setter).val(val);
        person1=val;
        person2=parseInt($($cal).val());
        $show_data=person1+person2;
        $show_actual_data=$show_data+" Guests";
        if($getter_setter=="#adults-data"){
            $($getter_setter+'-show').html(val +" Adults");
            if(val<=1){
               $($getter_setter+'-show').html(val +" Adult");
            }
        }else{
             $($getter_setter+'-show').html(val +" Children");
            if(val<=1){
               $($getter_setter+'-show').html(val +" Child");
            }
        }
        $($show).val($show_actual_data);
    }
    function functioninc($getter_setter,$show,$cal){
        val=parseInt($($getter_setter).val());
        val=(val*1)+1;
        $($getter_setter).val(val);
        person1=val;
        person2=parseInt($($cal).val());
        $show_data=person1+person2;
        $show_actual_data=$show_data+" Guests";
        $($show).val($show_actual_data);
        if($getter_setter=="#adults-data"){
            $($getter_setter+'-show').html(val +" Adults");
            if(val<=1){
               $($getter_setter+'-show').html(val +" Adult");
            }
        }else{
             $($getter_setter+'-show').html(val +" Children");
            if(val<=1){
               $($getter_setter+'-show').html(val +" Child");
            }
        }
    }
</script>
<script src="{{ asset('datepicker') }}/node_modules/fecha/dist/fecha.min.js"></script>
<script src="{{ asset('datepicker') }}/dist/js/hotel-datepicker.js"></script>
<script>
	@php
	    $new_data_blocked=LiveCart::iCalDataCheckInCheckOutCheckinCheckout(0);
	    $checkin=json_encode($new_data_blocked['checkin']);
	    $checkout=json_encode($new_data_blocked['checkout']);
	    $blocked=json_encode($new_data_blocked['blocked']);
	@endphp
	var checkin = <?php echo $checkin;  ?>;
	var checkout = <?php echo ($checkout);  ?>;
	var blocked= <?php echo ($blocked);  ?>;
    function clearDataForm(){
        $("#start_date").val('');
        $("#end_date").val('');
    }
    (function () {
        @if(Request::get("start_date"))
            @if(Request::get("end_date"))
                $("#demo17").val("{{ request()->start_date }} - {{ request()->end_date }}");
            @endif
        @endif
        abc=document.getElementById("demo17");
        var demo17 = new HotelDatepicker(
            abc,
            {   endDate: "{{date('Y-m-d', strtotime('+30 months'))}}",
                @if($checkin)
                   noCheckInDates: checkin,
                @endif
                @if($checkout)
                   noCheckOutDates: checkout,
                @endif
                @if($blocked)
                   disabledDates: blocked,
                @endif
                onDayClick: function() {
                    d = new Date();
                    d.setTime(demo17.start);
                    document.getElementById("start_date").value = getDateData(d);
                    d = new Date();
                    console.log(demo17.end)
                    if(Number.isNaN(demo17.end)){
                        document.getElementById("end_date").value = '';
                    }else{
                        d.setTime(demo17.end);
                        document.getElementById("end_date").value = getDateData(d);
                    }
                },
                clearButton:function(){
                    return true;
                }
            }
        );
        @if(Request::get("start_date"))
            @if(Request::get("end_date"))
                setTimeout(function(){$("#demo17").val("{{ request()->start_date }} - {{ request()->end_date }}");document.getElementById("start_date").value ="{{ request()->start_date }}";document.getElementById("end_date").value ="{{ request()->end_date }}";},1000);
            @endif
        @endif
    })();

    $(document).on("click","#clear",function(){
        $("#clear-demo17").click();
    })
    x=document.getElementById("month-2-demo17");
    x.querySelector(".datepicker__month-button--next").addEventListener("click", function(){
        y=document.getElementById("month-1-demo17");
        y.querySelector(".datepicker__month-button--next").click();
    })  ;


    x=document.getElementById("month-1-demo17");
    x.querySelector(".datepicker__month-button--prev").addEventListener("click", function(){
        y=document.getElementById("month-2-demo17");
        y.querySelector(".datepicker__month-button--prev").click();
    })  ;
    function getDateData(objectDate){let day = objectDate.getDate();let month = objectDate.getMonth()+1;let year = objectDate.getFullYear();if (day < 10) {day = '0' + day;}if (month < 10) {month = `0${month}`;}format1 = `${year}-${month}-${day}`;return  format1 ;}
</script>
@stop

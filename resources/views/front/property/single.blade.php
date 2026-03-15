@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)
@section("header-section")
    {!! $data->header_section !!}
@stop
@section("footer-section")
    {!! $data->footer_section !!}
@stop
@section("container")
@php
    $currency=$setting_data['payment_currency'];
    $name=$data->name;
    $bannerImage=asset('front/images/internal-banner.webp');;
    if($data->banner_image){
        $bannerImage=asset($data->banner_image);
    }
@endphp
        <!-- header End Here -->
    <div class="banner">
        <div class="c-hero__background">
            <img class="img-fluid" src="{{ $bannerImage }}" title="{{ $name }}" alt="{{ $name }}">    
        </div>
        <div class="guides">
            <h1 class="c-hero__title">{{$name}}</h1>
        </div>
    </div>
   <div class="breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb single-breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow"><i class="fa-solid fa-house"></i>Home</a>
                <a href="{{ url('/properties') }}" rel="nofollow"><span><i class="fa-solid fa-chevron-right"></i></span> Properties</a>
               
                <span><i class="fa-solid fa-chevron-right"></i></span> {{$name}}
            </div>
        </div>
    </div>

<section class="property-detail">
    <section class="main">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-8">
                    <div class="row header-name">
                        <div class="col-10">
                            <h4>{{$data->name}}</h4>
                            @if($data->address)
                                <h6><i class="bi bi-geo-alt-fill"></i> {{$data->address}}</h6>
                            @endif
                            <ul class="ammenity-home">
                                @if($data->sleeps)
                                    <li><i class="fa fa-users"></i> Sleeps {{$data->sleeps}}</li>
                                @endif
                                @if($data->bedroom)
                                    <li><i class="fa fa-bed"></i> {{$data->bedroom}} bedrooms</li>
                                @endif
                                @if($data->bathroom)
                                    <li><i class="fa fa-bath"></i> {{$data->bathroom}} bathrooms</li>
                                @endif
                            </ul>
                        </div>
                        <div class="col-2 prop-price">
                              @if($data->price)
                                    <h5>{{ $currency }}  {{$data->price}}</h5>
                              @endif
                        </div>
                    </div>
                    <section class="gallery1">
                        <div class="row gallery">
                            <div class="col-9 big-img">
                                <div class="img-main">
                                    <a href="{{ asset($data->feature_image) }}" data-fancybox="gallery">
                                       <img src="{{ asset($data->feature_image) }}" class="img-fluid" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-3 sidebar" >
                              @foreach(App\Models\PropertyGallery::where("property_id",$data->id)->orderBy("sorting","asc")->limit(3)->skip(0)->get() as $c)
                                <div class="img-active">
                                    <a href="{{asset($c->image)}}" data-fancybox="gallery"> <img src="{{asset($c->image)}}" class="img-fluid"  alt="{{$c->caption}}"  title="{{$c->caption}}"></a>
                                </div>
                                @endforeach
                            </div>
                            <div class="hidden-gallery">
                                @foreach(App\Models\PropertyGallery::where("property_id",$data->id)->orderBy("sorting","asc")->limit(100)->skip(3)->get() as $c)
                                <div class="img-active">
                                    <a href="{{asset($c->image)}}" data-fancybox="gallery"> <img src="{{asset($c->image)}}" class="img-fluid"  alt="{{$c->caption}}"  title="{{$c->caption}}"></a>
                                </div>
                                @endforeach
                            </div>
                    </section>
                    <section class="description">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <h2>Description</h2>
                                </div>
                                <div class="col-9">
                                    {!! $data->long_description !!}
                                    <a href="#" class="read-more">Read More</a>
                                </div>
                            </div>
                        </div>
                    </section>
                  @if(App\Models\PropertyAmenityGroup::where("property_id",$data->id)->orderBy("sorting","asc")->count()>0)
                    <section class="ammenities">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <h2>Amenities</h2>
                                </div>
                                <div class="col-9">
                                    @foreach(App\Models\PropertyAmenityGroup::where("property_id",$data->id)->orderBy("sorting","asc")->get() as $c)
                                       <div class="row">
                                           <h6>{{$c->name}}</h6>
                                            @foreach(App\Models\PropertyAmenity::where("property_amenity_id",$c->id)->where("status","true")->orderBy("sorting","asc")->get() as $c1)
                                           <div class="col-4"><i class="fa-solid fa-check"></i> {{ $c1->name}}</div>
                                           @endforeach
                                       </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                  @endif
                    <section class="policies">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <h2>Policies</h2>
                                </div>
                                <div class="col-9">
                                 @if($data->cancellation_policy)
                                    <div class="policy-ctg">
                                        <h6>Cancellation Policies</h6>
                                        {!! $data->cancellation_policy !!}
                                    </div>
                                 @endif
                                 @if($data->booking_policy)
                                    <div class="policy-ctg">
                                        <h6>Booking Policies</h6>
                                        {!! $data->booking_policy !!}
                                    </div>
                                 @endif
                                 @if($data->short_description)
                                    <div class="policy-ctg">
                                        <h6>Damage And Incidentals</h6>
                                        {!! $data->short_description !!}
                                    </div>
                                 @endif
                                 @if($data->notes)
                                    <div class="policy-ctg">
                                        <h6>Notes</h6>
                                        {!! $data->notes !!}
                                    </div>
                                 @endif
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="policies">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <h2>Availability</h2>
                                </div>
                                <div class="col-9">
                                 <iframe src="{{ url('fullcalendar-demo/'.$data->id) }}"  width="100%" height="400" style="border:0;" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                </div>
                            </div>
                        </div>
                    </section>
                  @if(App\Models\Testimonial::where("property_id",$data->id)->where("status","true")->orderBy("id","desc")->count()>0)
                    <section class="reviews">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-3">
                                    <h2>Reviews</h2>
                                </div>
                                <div class="col-9">
                                    @foreach(App\Models\Testimonial::where("property_id",$data->id)->where("status","true")->orderBy("id","desc")->get() as $c)
                                       <div class="user">
                                           <div class="row">
                                               <div class="review-img">
                                                   <img src="https://www.transparentpng.com/thumb/user/gray-user-profile-icon-png-fP8Q1P.png">
                                               </div>
                                               <div class="col-review">
                                                   <h6>{{$c->name}}</h6>
                                                   <p>{{date('F-d Y',strtotime($c->stay_date))}}</p>
                                                   <p>{{$c->message}}</p>
                                               </div>
                                           </div>
                                       </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section> 
                  @endif         
                    <section class="contact-form">
                        <h2>Leave a Review</h2>
                        {!! Form::open(["autocomplete"=>"off","route"=>"reviewSubmit","data-aos"=>"fade-up", "data-aos-duration"=>"1500"]) !!}
                        <div class="main-form">
                            <div class ="row">
                                <div class="col-6">
                                    <label for="exampleFormControlInput1" class="form-label">Name *</label>
                                    <input type="text" name="name" required class="form-control"  placeholder="Email">
                                </div>
                                <div class="col-6">
                                    <label for="exampleFormControlInput1" class="form-label">Email *</label>
                                    <input type="email" name="email" required class="form-control"  placeholder="Email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <label for="exampleFormControlInput1" class="form-label">Caption *</label>
                                    <input type="text" name="profile" required  class="form-control"  placeholder="Caption">
                                </div>
                                <div class="col-4">
                                    <label for="exampleFormControlInput1" class="form-label">Stay Date *</label>
                                    <input type="date" class="form-control datepicker123"  placeholder="Stay Date">
                                     <input type="hidden" name="property_id" value="{{ $data->id }}">
                                     <input type="hidden" name="score" value="5">
                                </div>
                                <div class="col-4">
                                    <label for="exampleFormControlInput1" class="form-label">Rating *</label>
                                    <fieldset class="score">
                                        <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i> <i class="fa-solid fa-star"></i>  <i class="fa-solid fa-star"></i>  <i class="fa-solid fa-star"></i>
                                    </fieldset>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label for="exampleFormControlTextarea1" class="form-label">Review *</label>
                                        <textarea class="form-control textarea" name="message" required id="exampleFormControlTextarea1" rows="3"></textarea>
                                        <div class="col-12 submit">
                                            <button type="submit" class="submit-btn" name="reviewsubmit"><span class="txt">Submit Review</span></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </section>
                </div>
                <div class="col-lg-4" id="book">
                    <div class="get-quote">
                        <div class="forms-booking-tab">
                            <ul class="tabs">
                                <li class="item booking active" data-form="ovabrw_booking_form">Request A Quote</li>
                            </ul>
                            <div class="ovabrw_booking_form" id="ovabrw_booking_form" style="">
                                <form class="form booking_form" id="booking_form" action="{{url('get-quote')}}" method="get">
                                    <input type="hidden" name="property_id" value="{{ $data->id }}">
                                    <div class="ovabrw_datetime_wrapper">
                                        {!! Form::text("start_date",Request::get("start_date"),["required","autocomplete"=>"off","inputmode"=>"none","id"=>"txtFrom","placeholder"=>"Check in"]) !!}
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                    <div class="ovabrw_datetime_wrapper">
                                        {!! Form::text("end_date",Request::get("end_date"),["required","autocomplete"=>"off","inputmode"=>"none","id"=>"txtTo","placeholder"=>"Check Out"]) !!}
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                 
                                    <div class="ovabrw_service_select rental_item">
                                            <input type="text" name="Guests"   value="{{ Request::get('Guests') ?? '' }}" readonly="" class="form-control gst" id="show-target-data" placeholder="Guests">
                                             <i class="fa-solid fa-users "></i>
                                             <input type="hidden" value="{{ Request::get('adults') ?? '0' }}"  name="adults" id="adults-data" />
                                             <input type="hidden" value="{{ Request::get('child') ?? '0' }}"  name="child" id="child-data" />
                                             <div class="adult-popup" id="guestsss">
                                                 <i class="fa fa-times close1"></i>
                                                 <div class="adult-box">
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
                                    <div id="gaurav-new-data-area">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="ovabrw-book-now" id="submit-button-gaurav-data" style="display: none;">
                                                <button type="submit" class="main-btn">
                                                <span> Reserve</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Or<br>Contact Owner</p>
                                    <p><a href="mailto:{{$data->email ?? $setting_data['email'] }}"><i class="fa-solid fa-envelope"></i> {{$data->email ?? $setting_data['email'] }}</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
@if($data->map)
<iframe src="{!! $data->map !!}" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
@endif

@stop
@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/assets/fancybox/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-detail.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-detail-responsive.css" />
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/assets/fancybox/jquery.fancybox.min.js" ></script>
    <script src="{{ asset('front')}}/js/properties-detail.js" ></script>
    <script>
$(function(){
    $(".datepicker").datepicker();
});
</script>
    <script>
    function functiondec($getter_setter,$show,$cal){
        $("#submit-button-gaurav-data").hide();
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
        ajaxCallingData();
    }
    function functioninc($getter_setter,$show,$cal){
        $("#submit-button-gaurav-data").hide();
        val=parseInt($($getter_setter).val());
        val=val+1;
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
        ajaxCallingData();
    }
</script>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Days</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body" id="gaurav-new-modal-days-area">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- The Modal -->
<div class="modal" id="myModal1">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Additional Fee</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body" id="gaurav-new-modal-service-area">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@php
    $new_data_blocked=LiveCart::iCalDataCheckInCheckOut($data->id);
    $checkin=$new_data_blocked['checkin'];
    $checkout=$new_data_blocked['checkout'];
@endphp
<script type="text/javascript">
    var checkin = <?php echo json_encode($checkin);  ?>;
    var checkout = <?php echo json_encode($checkout);  ?>;
    $(function() {
        $("#txtFrom").datepicker({
            numberOfMonths: 1,
            minDate: '@minDate',
            dateFormat: 'yy-mm-dd',
            beforeShowDay: function(date) {
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [checkin.indexOf(string) == -1];
            },
            onSelect: function(selected) {
                $("#submit-button-gaurav-data").hide();
                var dt = new Date(selected);
                dt.setDate(dt.getDate() + 1);
                $("#txtTo").datepicker("option", "minDate", dt);
                $("#txtTo").val('');
            },
            onClose: function() {
                $("#txtTo").datepicker("show");
            }
        });
        $("#txtTo").datepicker({
            numberOfMonths: 1,
            dateFormat: 'yy-mm-dd', 
            beforeShowDay: function(date) {
                var string = jQuery.datepicker.formatDate('yy-mm-dd', date);
                return [checkout.indexOf(string) == -1];
            },
            onSelect: function(selected) {
                var dt = new Date(selected);
                dt.setDate(dt.getDate() - 1);
                $("#txtFrom").datepicker("option", "maxDate", dt);
                ajaxCallingData();
            },
            onClose: function() {
                $('.popover-1').addClass('opened');
            }
        });
    });
    $("#reset-button-gaurav-data").click(function(){
        $("#txtFrom").val('');
        $("#txtTo").val('');
        $("#adults-area").val('');
        $("#child-area").val('');
        $("#adults-data").val(0);
        $("#child-data").val(0);
        $("#show-target-data").val(null);
        $("#submit-button-gaurav-data").hide();
        $("#gaurav-new-modal-days-area").html('');
        $("#gaurav-new-modal-service-area").html('');
        $("#gaurav-new-data-area").html('');
        $("#adults-data-show").html("Adult");
        $("#child-data-show").html("Child");
        $("#pet_fee_data_guarav").val(0)
        $("#txtFrom").datepicker("option", "maxDate",  '2043-10-10');
    });
    @php
        if(Request::get("start_date")){
            if(Request::get("end_date")){
    @endphp
                $(document).ready(function(){
                    ajaxCallingData();
                });
    @php
            }
       }
    @endphp
    $(document).on("change","#pet_fee_data_guarav",function(){
        ajaxCallingData();
    });
    $(document).on("change","#heating_pool_fee_data_guarav",function(){
        ajaxCallingData();
    });
    function ajaxCallingData(){
        pet_fee_data_guarav=$("#pet_fee_data_guarav").val();
        heating_pool_fee_data_guarav=$("#heating_pool_fee_data_guarav").val();
        adults=$("#adults-data").val();
        childs=$("#child-data").val();
        total_guests=parseInt(adults)+parseInt(childs);
        if(total_guests>0){
            if($("#txtFrom").val()!=""){
                if($("#txtTo").val()!=""){
                     $.post("{{route('checkajax-get-quote')}}",{start_date:$("#txtFrom").val(),end_date:$("#txtTo").val(),heating_pool_fee_data_guarav:heating_pool_fee_data_guarav,pet_fee_data_guarav:pet_fee_data_guarav,adults:adults,childs:childs,book_sub:true,property_id:{{ $data->id }}},function(data){
                        if(data.status==400){
                            $("#gaurav-new-modal-days-area").html(null);
                            $("#gaurav-new-modal-service-area").html(null);
                            $("#gaurav-new-data-area").html(null);
                            $("#submit-button-gaurav-data").hide();
                            toastr.error(data.message);
                        }else{
                            $("#submit-button-gaurav-data").show();
                            $("#gaurav-new-modal-days-area").html(data.modal_day_view);
                            $("#gaurav-new-modal-service-area").html(data.modal_service_view);
                            $("#gaurav-new-data-area").html(data.data_view);
                        }
                    });
                }
            }
        }else{
            $("#gaurav-new-modal-days-area").html(null);
            $("#gaurav-new-modal-service-area").html(null);
            $("#gaurav-new-data-area").html(null);
            $("#submit-button-gaurav-data").hide();
        }
    }
</script>
@stop 
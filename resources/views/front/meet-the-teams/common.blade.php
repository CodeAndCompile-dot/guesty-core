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
<!-- start banner sec -->
  <!-- header End Here -->
    <div class="banner">
        <div class="c-hero__background">
            <img class="img-fluid" src="{{ $bannerImage }}" title="{{ $name }}" alt="{{ $name }}">    
        </div>
        <div class="guides">
            <h1 class="c-hero__title">{{$name}}</h1>
        </div>
    </div>
<!-- start about section -->
<section class="meet">
    <div class="row">
        <div class="col-5">
            @if($data->image)
            <div class="meet-img">
                <img src="{{ asset($data->image)}}" class="img-fluid" alt="">
            </div>
            @endif
        </div> 
        <div class="col-7">
            <div class="head">
                <h4>ABOUT</h4>
                <h2>{{$data->first_name}}</h2>
                <h3>{{$data->last_name}}</h3>
                <h6>{{$data->profile}}</h6>
            </div>
            <div class="content">
					{!! $data->longDescription !!}
					</div>
        </div>

    </div>
</section>


<div class="contact">
    <div class="-cont-img" style="width:50%;">
        @php
            $image=asset('front/images/cont.jpg');
            if($data->contactImage){
                $image=asset($data->contactImage);
            }
        @endphp
        
    <img src="{{ $image }}" alt=""  style="width:100%;">
    </div>
    <div class="contact-us">
        <h4>CONTACT {{$data->first_name}}</h4>
        @if($data->email)
        <a href="mailto:{{$data->email}}"><i class="fas fa-envelope"></i> {{$data->email}}</a>
        @endif
        @if($data->mobile)
        <a href="tel:{{$data->mobile}}"><i class="fas fa-phone-square-alt"></i> {{$data->mobile}}</a>
        @endif
        <a class="main-btn" href="{{ url('contact-us') }}">Contact {{$data->first_name}}</a>
    </div>
</div>



@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/faq-1.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/faq-responsive.css" />
@stop 
@section("js")
@parent
<script src="{{ asset('front')}}/js/faq.js" ></script>
@stop
       
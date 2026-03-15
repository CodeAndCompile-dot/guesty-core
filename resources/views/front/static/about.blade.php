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
@include("front.layouts.banner")
<section class="about-us">
   <div class="container">
      <div class="abt-info">
      <div class=" about-image-sec">
            <div class="abt-image">
               <div class="abt-img1 fadeInDown">
                    <img src="{{ asset($data->section_image)}}" alt="">
               </div>
               <div class="abt-img2 fadeInUp">
                    <img src="{{ asset($data->image)}}" alt="">
               </div>
            </div>
         </div>
         <div class=" about-content-sec">
            <div class="abt-content">
               <div class="abt-para">
                {!! $data->mediumDescription !!}
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/about.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/about-responsive.css" />
@stop 
@section("js")
@parent
<script src="{{ asset('front')}}/js/about.js" ></script>
@stop
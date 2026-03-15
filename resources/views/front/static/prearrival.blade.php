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

<!-- section about us -->
<section class="about_section section-b-space">
    <div class="container">
            <div class="image-sec" data-aos="fade-up" data-aos-duration="1500">
                <div class="about_img">
                    <div class="side-effect"><span></span></div>
                    <img src="{{ asset($data->image)}}" class="img-fluid" alt="">
                </div>
            </div>
            <div class="content-sec" data-aos="fade-up" data-aos-duration="1500">
                <div class="about_content">
                    <div>
                        <h5>{!! $data->shortDescription !!}</h5>
                        {!! $data->mediumDescription !!}
                       
                    </div>
                </div>
            </div>
    </div>
</section>
@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/prearrival.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/prearrival-responsive.css" />
@stop 
@section("js")
@parent
<script src="{{ asset('front')}}/js/prearrival.js" ></script>
@stop
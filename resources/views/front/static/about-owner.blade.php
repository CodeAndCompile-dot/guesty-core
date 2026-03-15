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
<section class="about-owner">
    <div class="container px-0 lg-px-2">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 img">
                <div class="abt-owner">
                    <div class="abt-img mb-2">
                        <img src="{{ asset($data->image)}}" class="img-fluid" alt="">
                    </div>
                    <div class="svg-img">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 540 540">
                            <style type="text/css">
                                .st0 {
                                    fill-rule: evenodd;
                                    clip-rule: evenodd;
                                }
                            </style>
                            <path class="rhea_mask" d="M0 0v540h540V0H0zM268.5 538C121.3 538 2 418 2 270S121.3 2 268.5 2c72.6 0 38 76.3 56.5 141.3 20.3 71.1 193.5 112.6 199 183.3C535.4 474.2 415.7 538 268.5 538zM522.4 192.1c-42.3 17.4-113.7 5.9-147.8-45.4 -15.8-23.8-16.7-60.2-15.6-81.1 1.3-23.2 13.3-42.4 35.5-51.4C416.3 5.4 434.6 1.8 462 10c27 8.1 38.4 43.6 41.6 80.9C508.8 151.2 564.4 174.9 522.4 192.1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-sm-12 cont">
            {!! $data->mediumDescription !!}
                <div class="abt-detail d-flex flex-wrap">
                    <div class="call-us">
                        <div class="icon-area">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="call-area">
                            Phone:
                            <a href="tel:{!! $setting_data['mobile'] ?? '#' !!}">{!! $setting_data['mobile'] ?? '#' !!}</a>
                        </div>
                    </div>
                    <div class="email-us">
                        <div class="icon-area">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <div class="call-area">
                            Email:
                            <a href="mailto:{!! $setting_data['email'] ?? '#' !!}">{!! $setting_data['email'] ?? '#' !!}</a>
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
<link rel="stylesheet" href="{{ asset('front')}}/css/about.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/about-responsive.css" />
@stop
@section("js")
@parent
<script src="{{ asset('front')}}/js/about.js"></script>

@stop
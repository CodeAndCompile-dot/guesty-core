
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
    <!-- start banner sec -->
  
<section class="Blog-details">
    <div class="container">
        <h1>{{$data->title}}</h1>
        <img src="{{ asset($data->image)}}" class="img-fluid" alt="" />
        <div>
            <div>{!! $data->description !!}</div>
        </div>
    </div>
</section>





@stop



@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/attraction-detail.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/attraction-detail-responsive.css" />
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/properties-list.js" ></script>
    
@stop 
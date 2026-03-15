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
<section class="about-home">
	<div class="container">
		<div class="head-sec">
			<h2>Meet The Team</h2>
		</div>
		<div class="row">
		    @foreach(App\Models\OurTeam::orderBy("id","desc")->get() as $c)
			<div class="col-4 " style="
    padding: 0 10px;
">
				<a href="{{ route('meet-the-team',$c->seo_url) }}">
					<div class="img">
						<img src="{{ asset($c->image)}}" class="img-fluid" alt="{{$c->first_name}} {{$c->last_name}} {{$c->profile}}">
					</div>
					<div class="name">
						<h2>{{$c->first_name}} {{$c->last_name}}</h2>
						<h6>{{$c->profile}}</h6>
					</div>
				</a>
			</div>
			@endforeach
		</div>
	</div>
</section>
{!! $data->seo_section !!}
@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/map.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/map-responsive.css" />
@stop 
@section("js")
@parent
<script src="{{ asset('front')}}/js/map.js" ></script>
@stop
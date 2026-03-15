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
            <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i>Home </a>
            <a href="{{ url('attractions') }}"> <i class="fa-solid fa-chevron-right"></i> Attractions </a>
            <span><i class="fa-solid fa-chevron-right"></i></span> {{$name}}
        </div>
    </div>
</div>
 
 @php
  $list=App\Models\Attraction::where("location_id",$data->id)->orderBy("ordering","asc")->paginate(10);
  @endphp
 
  <section class="memory-section">
       <div class="container">
         @php $i=1; @endphp
              @foreach($list as $c)
              @if($i%2==0)
          <div class="row rev">
             <div class="content">
                <div class="memory-item">
                   <div class="memory-content">
                      <h2><a href="{{ url('attractions/detail/'.$c->seo_url) }}">{{$c->name}}</a></h2>
                      <p>{{$c->description}}</p>
                   </div>
                </div>
                <div class="dot">
                   <img src="{{ asset('front') }}/images/dot-shape.png" alt="{{$c->name}}">
                </div>
             </div>
             <div class="img">
                <div class="memory-image">
                   <a href="{{ url('attractions/detail/'.$c->seo_url) }}"><img src="{{asset($c->image)}}" alt="{{$c->name}}" class="img-fluid"></a>
                </div>
             </div>
          </div>
          @else
          <div class="row">
               <div class="img">
                  <div class="memory-image">
                     <a href="{{ url('attractions/detail/'.$c->seo_url) }}"><img src="{{asset($c->image)}}" alt="{{$c->name}}"  class="img-fluid"></a>
                  </div>
               </div>
               <div class="content">
                  <div class="memory-item">
                     <div class="memory-content" >
                        <h2><a href="{{ url('attractions/detail/'.$c->seo_url) }}">{{$c->name}}</a></h2>
                        <p style="">{{$c->description}}</p>
                     </div>
                  </div>
                  <div class="dot">
                     <img src="{{ asset('front') }}/images/dot-shape.png" alt="{{$c->name}}">
                  </div>
               </div>
            </div>
            @endif
            @php $i++; @endphp
            @endforeach

            <div class="row align-items-center">
               {{ $list->links()}}
            </div>
       </div>
    </section>
{!! $data->seo_section !!}

@stop
@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/attraction.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/attraction-responsive.css" />
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/attraction.js" ></script>
    
@stop 
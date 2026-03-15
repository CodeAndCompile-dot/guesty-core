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
  
   @include("front.layouts.banner")



@if(App\Models\Faq::orderBy("id","desc")->count()>0)   
<section class="faq" >
   <div class="container">
      <div class="row">
         <div class="col-12 col-md-12 col-sm-4">
       
            <div class="accordion accordion-flush" id="accordionFlushExample" data-aos="fade-up">
               @php $i=1; @endphp
               @foreach(App\Models\Faq::orderBy("id","desc")->get() as $c) 
               @if($i==1)           
               <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingOne{{$i}}">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne{{$i}}" aria-expanded="false" aria-controls="flush-collapseOne{{$i}}">
                     <span class="list-faq">{{$i}}</span>{{$c->question}}
                     </button>
                  </h2>
                  <div id="flush-collapseOne{{$i}}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne{{$i}}" data-bs-parent="#accordionFlushExample">
                     <div class="accordion-body">{!! $c->answer !!}</div>
                  </div>
               </div>
               @else
               <div class="accordion-item">
                  <h2 class="accordion-header" id="flush-headingFive{{$i}}">
                     <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive{{$i}}" aria-expanded="false" aria-controls="flush-collapseFive{{$i}}">
                     <span class="list-faq">{{$i}}</span>{{$c->question}}
                     </button>
                  </h2>
                  <div id="flush-collapseFive{{$i}}" class="accordion-collapse collapse" aria-labelledby="flush-headingFive{{$i}}" data-bs-parent="#accordionFlushExample">
                     <div class="accordion-body">{!! $c->answer !!}</div>
                  </div>
               </div>
               @endif
               @php $i++; @endphp
               @endforeach
            </div>
         </div>
      </div>
   </div>
</section>
@endif
{!! $data->seo_section !!}
@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/faq.css" />
@stop

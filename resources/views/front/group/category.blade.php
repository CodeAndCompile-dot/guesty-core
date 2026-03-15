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
        $name=$data->title;
        $bannerImage='https://ga4prozbj7-flywheel.netdna-ssl.com/wp-content/themes/aspenhomes/dist/images/trees-bg-600x350.jpg';
        if($data->bannerImage){
            $bannerImage=asset($data->bannerImage);
        }
    @endphp


    @include("front.layouts.banner")


    

 
<section class="blog-wrapper">
  <div class="container">
    <div class="home-blog-sec">
      <div class="row  grid" >
         <div class="col-lg-4 col-md-6 col-12 grid-sizer"></div>
  @forelse($blogs as $b)
        <div class="col-lg-4 col-md-6 col-12 grid-item">
          <div class="blog-page">
             @php $date=$b->publish_date; if($date){}else{$date=$b->created_at;} @endphp

            @if($b->featureImage)
            <div class="home-blog-image">
               <img src="{{ asset($b->featureImage) }}" alt="{{ $b->title }}">
            </div>
            @endif
            <div class="blog-content">
              <h4><a href="{{ url('blog/'.$b->seo_url) }}/"> {{$b->title}} </a></h4>
                     <h6 class="blog-feat">
                <span class="blog-date"><i class="far fa-calendar-alt"></i>&nbsp; {{date('d-F-Y',strtotime($date))}}</span>
                  @php $category=App\Models\Blogs\BlogCategory::where("id",$b->blog_category_id)->first(); @endphp

                  @if($category)

          
<span class="blog-date"><i class="fas fa-list"></i>&nbsp;<a href="{{ url('blogs/category/'.$category->seo_url) }}/">{{$category->title}}</a></span>
                  @endif
                
              </h6>
              <p> {{ Str::limit($b->shortDescription,100)}}</p>
              <a href="{{ url('blog/'.$b->seo_url) }}/" class="blog-read">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        </div>
   

    @empty

         <div class="alert alert-danger">No any Blogs Found.</div>

         @endforelse

      </div>

      <div class="row">{{$blogs->links() }}</div>
    </div>
  </div>
</section>




@stop

@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/blog.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/blog-responsive.css" />
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/blog.js" ></script>
    <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.js"></script>
<script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.js"></script>
<script>
    // external js: masonry.pkgd.js, imagesloaded.pkgd.js

// init Masonry
var $grid = $('.grid').masonry({
  itemSelector: '.grid-item',
  percentPosition: true,
  columnWidth: '.grid-sizer'
});
// layout Masonry after each image loads
$grid.imagesLoaded().progress( function() {
  $grid.masonry();
});  
</script>
    
@stop



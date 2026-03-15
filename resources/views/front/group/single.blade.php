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
        if($data->image){
            $bannerImage=asset($data->image);
        }
    @endphp

      

    @include("front.layouts.banner")







<section class="blog-detail-wrapper mt-5">

     <div class="container">

      

        <div class="row">

           <div class="col-lg-8 col-xs-12 col-md-12">

              <div class="blog-detail-left">

                 <div class="blog-detail-image">

                    <img src="{{ asset($data->featureImage)}}">

              

                 </div>

                    <div class="blog-detail-title">

                       <h3>{{$data->title}}</h3>

                    </div>

                    <div class="feat_blog_con">

                        <p>

                        	<span><i class="fas fa-calendar-alt" aria-hidden="true"></i> {{ date('d M Y',strtotime($data->created_at)) }}</span>



                        	&nbsp;&nbsp;

							@php $category=App\Models\Blogs\BlogCategory::where("id",$data->blog_category_id)->first(); @endphp

			                @if($category)

                        	<span><i class="fas fa-globe" aria-hidden="true"></i><a href="{{ url('blogs/category/'.$category->seo_url) }}/"> {{$category->title}}</a></span>

                        	@endif

                        </p>

                      </div>

                    <div class="blod-detail-description mb-5">

                    	

                       {!! $data->longDescription !!}

                    </div>

              </div>

           </div>

           <div class="col-lg-4 col-xs-12 col-md-12">

             <section id="categories-4" class="widget widget_categories">

            <h2 class="widget-title">Categories</h2>

            <ul>

            	@foreach(App\Models\Blogs\BlogCategory::orderBy("id","desc")->get() as $category)

                <li class="cat-item cat-item-2"><a href="{{ url('blogs/category/'.$category->seo_url) }}/">{{$category->title}}</a> <span>({{ App\Models\Blogs\Blog::where("blog_category_id",$category->id)->count() }})</span></li>

                @endforeach

            </ul>

        </section>



        <section id="recent-posts-2" class="widget widget_recent_entries">

            <h2 class="widget-title"><span class="first">Recent</span> Posts</h2>

            <ul>

            	@foreach(App\Models\Blogs\Blog::where("id","!=",$data->id)->orderBy("id","desc")->take(5)->get() as $b)

                <li class="item-recent-post">

                    <div class="thumbnail-post">

                        <img src="{{asset($b->featureImage)}}" class="attachment-editech-thumbnail size-editech-thumbnail wp-post-image" alt="{{$b->title}}">

                    </div>

                    <div class="title-post"><a href="{{ url('blog/'.$b->seo_url) }}/">{{$b->title}}</a> <span class="post-date"><i class="far fa-calendar-check" aria-hidden="true"></i> {{ date('d M Y',strtotime($b->created_at)) }}</span></div>

                </li>

               @endforeach

            </ul>

        </section>

           </div>

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
@stop
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
        $bannerImage=asset('front/images/internal-banner.webp');
        if($data->bannerImage){
            $bannerImage=asset($data->bannerImage);
        }
    @endphp
	<!-- start banner sec -->
  <div class="banner d-none">
        <div class="c-hero__background">
            <img class="img-fluid" src="{{ $bannerImage }}" title="{{ $name }}" alt="{{ $name }}">
        </div>
        <div class="guides">
            <h1 class="c-hero__title fade-in-element">{{$name}}</h1>
        </div>
    </div>
   <div class="breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb single-breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow"><i class="fa-solid fa-house"></i>Home</a>
                <a href="{{ url('/attractions') }}" rel="nofollow"><span><i class="fa-solid fa-chevron-right"></i></span> Attractions</a>
                <span><i class="fa-solid fa-chevron-right"></i></span> {{$name}}
            </div>
        </div>
    </div>


  @php
  $list=App\Models\Attraction::where("category_id",$data->id)->orderBy("ordering","asc")->paginate(15);
  @endphp
  <!-- Attractions Content Sections -->
  @php $i=1; @endphp
  @foreach($list as $c)
      <!-- Section with alternating layout based on index -->
      <div class="container section-container">
          <div class="row align-items-center justify-content-center scale-container position-relative">
              @if($i % 2 != 0)
              <!-- Content Section (Left for odd items) -->
              <div class="col-12 col-lg-5 order-2 order-lg-1">
                  <div class="content-card">
                      <div>
                          <h2 class="section-title fade-in-element">
                              {{$c->name}}
                          </h2>
                          <p class="section-text fade-in-element">
                              @if($c->address)
                              <span><i class="fa-solid fa-location-dot"></i> {{ $c->address }}</span><br>
                              @endif
                              @if($c->mobile)
                              <span><i class="fa-solid fa-phone"></i> {{ $c->mobile }}</span><br><br>
                              @endif
                              {!!$c->longDescription!!}
                          </p>
                          <!--<a @if($c->type=="internal") href="{{ url('attractions/detail/'.$c->seo_url) }}" @else href="{{ $c->seo_url }}" target="_BLANK" @endif class="main-btn" id="atr">View More</a>-->
                      </div>
                  </div>
              </div>

              <!-- Image Section (Right for odd items) -->
              <div class="col-12 col-lg-6 order-1 order-lg-2">
                  <div class="image-container">
                      <img src="{{asset($c->image)}}" alt="{{$c->name}}" class="main-image fade-in-element">
                  </div>
              </div>

              <!-- Dots Pattern - Left Side -->
              <div class="dots-pattern dots-left d-none d-lg-block"></div>
              @else
              <!-- Image Section (Left for even items) -->
              <div class="col-12 col-lg-6 order-1">
                  <div class="image-container">
                      <img src="{{asset($c->image)}}" alt="{{$c->name}}" class="main-image fade-in-element">
                  </div>
              </div>

              <!-- Content Section (Right for even items) -->
              <div class="col-12 col-lg-5 order-2">
                  <div class="content-card">
                      <div>
                          <h2 class="section-title fade-in-element">
                              {{$c->name}}
                          </h2>
                          <p class="section-text fade-in-element">
                              @if($c->address)
                              <span><i class="fa-solid fa-location-dot"></i> {{ $c->address }}</span><br>
                              @endif
                              @if($c->mobile)
                              <span><i class="fa-solid fa-phone"></i> {{ $c->mobile }}</span><br><br>
                              @endif
                              {!!$c->longDescription!!}
                          </p>
                          <!--<a @if($c->type=="internal") href="{{ url('attractions/detail/'.$c->seo_url) }}" @else href="{{ $c->seo_url }}" target="_BLANK" @endif class="main-btn" id="atr">View More</a>-->
                      </div>
                  </div>
              </div>

              <!-- Dots Pattern - Right Side -->
              <div class="dots-pattern dots-right d-none d-lg-block"></div>
              @endif
          </div>
      </div>
      @php $i++; @endphp
  @endforeach

  <!-- Pagination -->
  <div class="container">
      <div class="row align-items-center justify-content-center">
          <div class="col-md-12 text-center">
              {{ $list->links() }}
          </div>
      </div>
  </div>
{!! $data->seo_section !!}
@stop
@section("css")
@parent
<link rel="stylesheet" href="{{ asset('front')}}/css/attraction.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/attraction-responsive.css" />
<style>
    /* Additional styles from attraction-list.html */
    .section-container {
      
        padding: 2rem 1rem;
    }

    @media (min-width: 992px) {
        .section-container {
            padding: 4rem 1rem;
        }
    }

    .content-card {
        background: var(--text-light);
        border: 1px solid var(--grey-light);
        padding: 2rem;
        position: relative;
        transform: scale(1.1);
        z-index: 2;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .image-container {
        position: relative;
        z-index: 1;
        height: 100%;
    }

    .main-image {
        width: 100%;
        height: 100%;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        object-fit: cover;
        min-height: 300px;
    }

    .section-title {
        color: var(--text-dark);
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        line-height: 1.3;
        font-family: "Playfair Display", serif;
    }

    @media (min-width: 992px) {
        .section-title {
            font-size: 2rem;
        }
    }

    .section-text {
        color: var(--grey-dark);
        font-size: 1rem;
        line-height: 1.75;
    }

    @media (min-width: 992px) {
        .section-text {
            font-size: 1.05rem;
            line-height: 1.8;
        }
    }

    .dots-pattern {
        position: absolute;
        bottom: 0;
        right: 20px;
        z-index: 0;
        width: 300px;
        height: 200px;
        background-image: url("{{ asset('front/images/dot-shape.png') }}");
        opacity: 0.3;
    }

    .dots-left {
        left: -1rem;
    }

    .dots-right {
        right: -1rem;
    }

    .fade-in-element {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .fade-in-element.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Custom scaling adjustments */
    .scale-container {
        transform: scale(1);
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
       

        .content-card {
            transform: none;
            padding-bottom: 2rem;
            margin-bottom: 2rem;
        }

        .main-image {
            margin-bottom: 2rem;
            min-height: 250px;
        }

        .scale-container {
            transform: none;
        }

        .dots-pattern {
            display: none;
        }
    }

    @media (max-width: 767px) {
        .section-title {
            font-size: 1.5rem;
        }
    }
    
    /* Button styling */
    .content-card .main-btn {
        display: inline-block;
        margin-top: 1.5rem;
        text-decoration: none;
        padding: 10px 20px;
    }
</style>
@stop
@section("js")
@parent
<script src="{{ asset('front')}}/js/attraction.js" ></script>
<script>
    // Animation handling
    let observer;

    document.addEventListener("DOMContentLoaded", function () {
        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = "smooth";

        // Initialize intersection observer
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                    }
                });
            },
            {
                threshold: 0.1,
                rootMargin: "0px 0px -50px 0px",
            }
        );

        // Apply animation to elements
        document.querySelectorAll(".fade-in-element").forEach((el) => {
            observer.observe(el);
        });
    });

    // Clean up the observer when page is unloaded
    window.addEventListener("beforeunload", function () {
        if (observer) {
            observer.disconnect();
        }
    });
</script>
@stop

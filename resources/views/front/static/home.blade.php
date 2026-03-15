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
$currency=$setting_data['payment_currency'];
@endphp
<!-- Hero Section -->
<section class="hero-section" style="background-image: url('{{$data->bannerImage}}')">
    <div class="container">
        <div class="hero-content text-center text-white">
            <div class="logo-container">
                <img src="{!! asset($setting_data['header_logo']) !!}" alt="{!! asset($setting_data['website']) !!}" class="hero-logo image-fluid">
            </div>
            <h1 class="hero-title">BENTONVILLE</h1>
            <h2 class="hero-subtitle dynalight-regular">Lodging Company</h2>
            <p class="hero-location">Bentonville, Arkansas</p>
        </div>
    </div>
</section>

<!-- Search Bar Section - Below Banner -->
<section class="search-bar-below-banner">
    <div class="container">
        <form method="get" action="{{ url('properties')}}">
            <div class="search-form-container">
                <div class="search-form-wrapper">
                    <div class="search-item date-picker-item position-relative">
                        <div class="date-picker-wrapper">
                            <div class="check-in position-relative mx-md-2">
                                {!! Form::text("start_date",null,["required","autocomplete"=>"off","inputmode"=>"none","id"=>"start_date","placeholder"=>"Check in","title"=>"Select Check In Dates","class"=>"form-control","readonly" => true]) !!}
                             
                            </div>
                           
                            <div class="check-out position-relative">
                                {!! Form::text("end_date",null,["required","autocomplete"=>"off","inputmode"=>"none","id"=>"end_date","placeholder"=>"Check Out","title"=>"Select Check Out Dates","class"=>"form-control lst","readonly" => true ]) !!}
                               
                            </div>
                            <div class="">
                                <input type="text" id="demo17" value="" aria-label="Check-in and check-out dates" aria-describedby="demo17-input-description" readonly />
                            </div>
                        </div>
                    </div>

                    <div class="search-item guest-item">
                        <div class="guest-container position-relative">
                            <input type="text" name="Guests" value="{{ Request::get('Guests') ?? '' }}" readonly="" class="form-control gst" id="show-target-data" placeholder="Guests">
                            
                            <input type="hidden" value="{{ Request::get('adults') ?? '0' }}" name="adults" id="adults-data" />
                            <input type="hidden" value="{{ Request::get('child') ?? '0' }}" name="child" id="child-data" />
                            <div class="adult-popup" id="guestsss">
                                <i class="fa fa-times close1"></i>
                                <div class="adult-box pt-3">
                                    <p id="adults-data-show"><span>@if(Request::get('adults'))
                                                                        @if(Request::get('adults')>1)
                                                                            {{ Request::get('adults') }} Adults
                                                                        @else
                                                                            {{ Request::get('adults') }} Adult
                                                                        @endif
                                                                    @else
                                                                        Adult
                                                                    @endif</span> 18+</p>
                                    <div class="adult-btn">
                                        <button class="button1" type="button" onclick="functiondec('#adults-data','#show-target-data','#child-data')" value="Decrement Value">-</button>
                                        <button class="button11 button1" type="button" onclick="functioninc('#adults-data','#show-target-data','#child-data')" value="Increment Value">+</button>
                                    </div>
                                </div>
                                <div class="adult-box">
                                    <p id="child-data-show"><span>@if(Request::get('child'))
                                                                        @if(Request::get('child')>1)
                                                                            {{ Request::get('child') }} Children
                                                                        @else
                                                                            {{ Request::get('child') }} Child
                                                                        @endif
                                                                    @else
                                                                        Child
                                                                    @endif</span> (0-17)</p>
                                    <div class="adult-btn">
                                        <button class="button1" type="button" onclick="functiondec('#child-data','#show-target-data','#adults-data')" value="Decrement Value">-</button>
                                        <button class="button11 button1" type="button" onclick="functioninc('#child-data','#show-target-data','#adults-data')" value="Increment Value">+</button>
                                    </div>
                                </div>
                                <button class="main-btn close111" type="button">Apply</button>
                            </div>
                        </div>
                    </div>
                    <div class="search-item search-button-item">
                        <button type="submit" class="search-btn">
                            <i class="fa-solid fa-search"></i>
                            <span>Search</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@php
$proList =  App\Models\Guesty\GuestyProperty::where(["status"=>"true","active"=>"1"])->orderBy("id","desc")->limit(3)->get();
@endphp
<!-- Properties Section -->
@if(count($proList)>0)
<section class="properties-section" id="properties">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Properties</h2>
            <p class="section-subtitle">Where you stay matters; enjoy our hand-picked selection of places to stay in and around Bentonville, AR,</p>
            <p class="section-subtitle">that gives you access to world-class Mountain Biking, incredible dining, small-town charm, and more! From luxury to affordability find your base camp in Northwest Arkansas today!</p>
        </div>
        <div class="row">
            @php $i=1;@endphp
            @foreach($proList as $c)
            @php
             $picture=json_decode($c->picture,true);
             $pictures=json_decode($c->pictures,true);
             if($pictures){
                 if(count($pictures)>0){
                    $picture=$pictures[0];
                 }
             }
             $prices=json_decode($c->prices,true);
             $address=json_decode($c->address,true);
            @endphp
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="property-card">
                    <div class="property-image">
                    @isset($picture['original'])
                        <a href="{{ url($c->seo_url).'?'.http_build_query(request()->all()) }}">
                            <img src="{{$picture['original']}}" alt="{{$picture['caption'] ?? ''}}" loading="lazy">
                        </a>
                    @endisset
                    </div>
                    <div class="property-content">
                       <h3 class="property-title">
    <a href="{{ url($c->seo_url) }}">
        {{ \Illuminate\Support\Str::limit($c->title, 40) }}
    </a>
</h3>
                        <p class="property-description">{{ $c->short_description ?? 'Modern & Stylish Woodland Escape with Hot Tub and Treetop Views' }}</p>
                        <p class="property-price">Starting at {{ $currency }} {{ $c->starting_price ?? '278' }} / night</p>
                        <div class="property-details">
                            @isset($address['full'])
                            <div class="property-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="text-truncate">{{$address['full']}}</span>
                            </div>
                            @endisset
                        </div>
                        <div class="property-details mt-3" style="border-top: 1px solid rgba(0,0,0,0.1); padding-top: 15px;">
                            <div class="property-detail">
                                <i class="fas fa-users"></i>
                                <span>{{ $c->accommodates ?? '6' }} guests</span>
                            </div>
                            <div class="property-detail">
                                <i class="fas fa-bed"></i>
                                <span>{{ $c->bedrooms ?? '3' }} beds</span>
                            </div>
                            <div class="property-detail">
                                <i class="fas fa-bath"></i>
                                <span>{{ $c->bedrooms ?? '2' }} baths</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @php $i++;@endphp
            @endforeach
        </div>
        <div class="text-center mt-4 mt-md-3">
            <a href="{{ url('properties') }}" class="btn btn-outline-dark py-2 py-md-3 px-3 px-md-4">
                View All Properties <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif
<!-- Testimonials Section -->
@php
$testimonials = App\Models\Testimonial::where("status","true")->orderBy("stay_date","desc")->take(8)->get();
@endphp
@if(count($testimonials))
<section class="reviews-section">
    <div class="container">
        <h2 class="reviews-title">What Our Guests Are Saying</h2>
        <div class="swiper-container reviews-swiper">
            <div class="swiper-wrapper">
                @foreach($testimonials as $c)
                <div class="swiper-slide">
                    <div class="review-card">
                        <div class="review-header">
                            <div class="d-none">
                                <i class="fas fa-home review-icon"></i>
                                <div class="review-title">{{ $c->property_name ?? 'Wonderful Property' }}</div>
                            </div>
                            <div style="width: 100%;">
                                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;">
                                    <div class="stars">
                                        @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star"></i>
                                        @endfor
                                    </div>
                                     <div class="review-date">
                                        <h4 class="text-capitalize"> {{$c->name}} </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="review-text">
                            {{ $c->message }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Add navigation buttons -->
            <div class="swiper-button-next review-nav-btn"></div>
            <div class="swiper-button-prev review-nav-btn"></div>
            
            <!-- Add pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>
@endif

<!-- Attractions Section -->
<section class="attraction-section">
    <div class="container">
        <h2 class="section-title py-4">Explore the Best of Bentonville</h2>

        <div class="row g-3 g-md-4">
            @foreach(App\Models\AttractionCategory::orderBy("id","desc")->take(3)->get() as $key => $c)
            <div class="col-lg-4 col-md-6">
                <div class="attraction-card hot-tub-card" data-aos="fade-up" @if($key > 0) data-aos-delay="{{ $key * 200 }}" @endif>
                    <div class="card-background">
                        <img src="{{ asset($c->image) }}" alt="{{ $c->name }}" loading="lazy">
                    </div>
                    <div class="card-overlay"></div>
                    <div class="design-card-content">
                        <h3>{{ $c->name }}</h3>
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">{{ $c->name }}</h3>
                        <p class="card-subtitle">{{ $c->short_description ?? 'Experience the best of Bentonville' }}</p>
                        <a href="{{ url('attractions/category/'.$c->seo_url) }}" class="explore-btn">
                            Explore <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-3 mt-md-4">
            <a href="{{ url('attractions') }}" class="btn btn-outline-dark py-2 py-md-3 px-3 px-md-4">
                View All Attractions <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <div style="font-weight: bold; font-size: 1.5rem;">About Us</div>
                  <h2 class="dynalight-regular text-black">{{$data->shortDescription}}</h2>

                {!! $data->longDescription !!}
            </div>
            <div class="about-illustration">
				<img src="https://assets.guesty.com/image/upload/v1711936015/production/64dce90d2c2f9ae2069eb6fc/hfe2dw9ae99xznkd3f6b.jpg" alt="About Bentonville Lodging" class="px-3" style=" object-fit: cover; border-radius: 15px;">
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content">
            <h2 class="newsletter-title">Get exclusive updates.<br class="d-none d-md-block">Sign up now!</h2>
            <form class="newsletter-form newsletter-data" action="{{route('newsletterPost')}}" method="POST">
                <div class="input-button-wrapper">
                    <input type="email" class="newsletter-input" name="email" placeholder="Email address" required>
                    <button type="submit" class="newsletter-btn">SUBSCRIBE</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Preserve any extra sections from original blade file that might be needed -->
<!-- Original CTA Section -->
<section class="cta" style="display: none; background-image:url({{ asset($data->strip_image) }})">
    <div class="container">
        <div class="content">
            <div class="head-sec">
                <h2>{{ $data->strip_title }}</h2>
            </div>
            <p>{{ $data->strip_desction }}</p>
            <a href="{{ url($data->strip_anchor ?? '#' ) }}" class="main-btn aos-init" data-aos="zoom-in" data-aos-duration="1500">Book Now</a>
        </div>
    </div>
    <div class="cta-banner"></div>
</section>

<!-- Original About Owner Section -->
<section class="about-owner" style="display: none;">
    <div class="container px-0 lg-px-2">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sm-12 img">
                <div class="abt-owner">
                    <div class="abt-img mb-2">
                        <img src="{{ asset($data->image_2) }}" class="img-fluid" alt="">
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
                <div class="abt-btn">
                    <a href="{{ url('about-owner') }}" class="main-btn">View More</a>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@section("css")
@parent
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dynalight&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('front')}}/assets/fancybox/jquery.fancybox.min.css" />
<link rel="stylesheet" href="{{ asset('front')}}/assets/owl/owl.carousel.min.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/home.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/home-responsive.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/header-footer-banner.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('datepicker') }}/dist/css/hotel-datepicker.css"/>
<link rel="stylesheet" href="{{ asset('front')}}/css/datepicker.css" />
<!-- Swiper CSS -->
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
<link rel="stylesheet" href="{{ asset('front')}}/css/testimonial-swiper.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- New styles for modern design -->
<style>
/* Hero Section */
.hero-section {
    position: relative;
    height: 80vh;
    background: url('{{ asset("WhatsApp Image 2025-08-07 at 7.38.40 PM.jpeg") }}');
    background-size: cover;
    background-position: center;
    display: flex;
    align-items: center;
    justify-content: center;
    filter: contrast(1.1);
}

.about-section .about-text p {
    font-size: 16px;
}
/* Responsive styles for search bar below banner */
@media (max-width: 991px) {
    .search-bar-below-banner {
        padding: 20px 0;
        border-radius: 20px !important;
    }
    
    .search-form-wrapper {
        padding: 15px;
    }
}

@media (max-width: 767px) {
     .search-bar-below-banner {
         border-radius: 20px !important;
     }
    .search-form-wrapper {
        flex-direction: column;
        width: 95%;
    }
    
    .search-item {
        width: 100%;
        margin-bottom: 15px;
    }
    
    .search-button-item {
        width: 100%;
    }
    
    .search-btn {
        width: 100%;
    }
    
    .adult-popup {
        width: 100%;
        left: 0;
        right: 0;
    }
}

@media (max-width: 576px) {
    .search-bar-below-banner {
        padding: 15px 0;
         border-radius: 20px !important;
    }
    
    .search-form-wrapper {
        padding: 12px;
    }
    
    .search-btn {
       
        justify-content: center;
    }
    
    .search-btn i {
        margin: 0;
        font-size: 18px;
    }
}
}

.hero-section:after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23000000' fill-opacity='0.05' fill-rule='evenodd'%3E%3Ccircle cx='3' cy='3' r='3'/%3E%3Ccircle cx='13' cy='13' r='3'/%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.3;
    pointer-events: none;
    z-index: 2;
}

.hero-section:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 10, 10, 0.7); /* Enhanced gradient overlay */
    opacity: 1.5;
}

.hero-content {
    position: relative;
    z-index: 10;
    margin-bottom: 60px;
}

.logo-container {
    margin-bottom: 40px;
}

.hero-logo {
    width: 300px;
    height: auto;
}

.hero-title {
    font-size: 4rem;
    font-weight: 700;
    letter-spacing: 6px;
    margin-bottom: 0;
}

.hero-subtitle {
    font-size: 3.5rem;
    margin-top: -15px;
    margin-bottom: 15px;
}

.hero-location {
    font-size: 1.2rem;
    letter-spacing: 2px;
    font-weight: 300;
}

.search-container {
    position: absolute;
    background: var(--text-light);
    padding: 20px;
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    max-width: 800px;
    bottom: -50px;
    width: 90%;
    z-index: 20;
    left: 50%;
    transform: translateX(-50%);
}

/* Responsive adjustment for search container on small screens */
@media (max-width: 768px) {
    .search-container {
        position: absolute;
        bottom: -250px;
        padding: 15px 10px;
        /*margin: 20px auto;*/
        border-radius: 50px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .hero-section {
        padding-bottom: 20px; /* Reduce padding at bottom */
    }
    
   
}

.search-form {
    display: flex;
    gap: 15px;
    align-items: end;
}

.form-group {
    flex: 1;
}

.btn-search {
    background-color: var(--cta-button);
    border: none;
    color: var(--text-light);
    padding: 17px 30px;
    border-radius: 3px;
    font-weight: 600;
    transition: all 0.3s ease;
    height: fit-content;
    letter-spacing: 1px;
    text-transform: uppercase;
    font-size: 0.85rem;
}

.btn-search:hover {
    background-color: var(--primary-accent);
}

/* Properties Section */
.properties-section {
    padding-bottom: 30px;
    margin-top: 20px; /* Default margin for desktop */
    background: var(--background-light);
}

/* Property card with animation */
.property-card {
    transform: translateY(2px);
    transition: opacity 0.6s ease, transform 0.6s ease;
    background: var(--text-light);
    border-radius: 12px;
    overflow: hidden;
    height: 100%;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    position: relative;
}

.section-header {
    margin-bottom: 60px;
    text-align: center;
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    color: var(--text-dark);
    font-weight: 400;
    margin-bottom: 8px;
}

.section-subtitle {
    color: var(--grey-dark);
    font-size: 1rem;
    max-width: 70%;
    margin: 0 auto;
}

.property-image {
    height: 375px;
    position: relative;
    overflow: hidden;
}

.property-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.property-content {
    padding: 20px;
    position: relative;
}

.property-title {
    font-size: 1.5rem;
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 12px;
    line-height: 1.3;
}

.property-description {
    color: var(--grey-dark);
    margin-bottom: 10px;
    line-height: 1.6;
    font-size: 0.95rem;
}

.property-price {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.property-details {
    display: flex;
    gap: 20px;
    color: #666;
    font-size: 0.9rem;
    justify-content: space-between;
}

.property-detail {
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Reviews Section */
.reviews-section {
    position: relative;
    padding: 100px 0;
    color: var(--text-light);
    overflow: hidden;
    z-index: 1;
    background-color: var(--highlight-bg);
}

.reviews-title {
    text-align: center;
    font-size: 2.5rem;
    color: var(--text-light);
    margin-bottom: 50px;
    font-weight: 400;
}

/* Swiper container styles */
.reviews-swiper {
    position: relative;
    overflow: hidden;
    padding-bottom: 70px;
    padding-left: 12px;
}

.review-card {
    padding: 50px;
    border-radius: 3px;
    margin: 0;
    height: 380px;
    transition: transform 0.3s ease;
    /*background-color: rgba(255, 255, 255, 0.05);*/
}

.review-header {
    display: flex;
    flex-direction: column;
    margin-bottom: 30px;
    padding-bottom: 20px;
}

.review-header > div:first-child {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.review-icon {
    color: var(--text-light);
    font-size: 1.5rem;
    margin-right: 10px;
}

.review-title {
    font-size: 1.3rem;
    font-weight: 500;
    color: var(--text-light);
}

.stars {
    color: var(--grey-mid);
    margin: 0;
}

.review-date {
    color: var(--grey-mid);
    font-size: 0.9rem;
    margin-bottom: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.review-text {
    line-height: 1.8;
    color: var(--grey-light);
    font-style: italic;
    font-size: 1.1rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 7;
    -webkit-box-orient: vertical;
}

/* Swiper navigation buttons */
.swiper-button-next,
.swiper-button-prev {
    background: none;
    border: none;
    color: var(--text-light) !important;
    width: 44px !important;
    height: 44px !important;
    border-radius: 3px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 0 15px;
}

.swiper-button-next:after,
.swiper-button-prev:after {
    font-size: 20px !important;
    font-weight: bold;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Swiper pagination */
.swiper-pagination {
    position: absolute;
    bottom: 0 !important;
    width: 100%;
    text-align: center;
}

.swiper-pagination-bullet {
    width: 8px;
    height: 8px;
    display: inline-block;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    margin: 0 5px;
    transition: all 0.3s ease;
}

.swiper-pagination-bullet-active {
    background: var(--text-light);
    width: 10px;
    height: 10px;
}

/* Responsive styles */
@media (max-width: 992px) {
    .hero-section {
        height: auto;
        padding: 120px 0 100px;
    }
    
    .search-form-wrapper {
        flex-direction: column;
        padding: 20px;
        border-radius: 20px;
        width: 92%;
        gap: 15px;
    }
    
    .search-item {
        width: 100%;
        margin-right: 0;
    }
    
    .date-picker-wrapper {
        width: 100%;
    }
    
    .search-btn {
        width: 100%;
    }
    
    /* Properties section adjustment */
    .properties-section {
        margin-top: 80px;
        padding-top: 30px;
    }
}

@media (max-width: 768px) {
    .hero-section {
        height: auto;
        padding: 100px 0 80px;
    }
    
    .reviews-section {
        padding: 70px 0;
    }
    
    .review-card {
        height: auto;
        min-height: 350px;
        padding: 30px;
    }
    
    .review-text {
        font-size: 1rem;
    }
    
    /* Enhanced search bar responsiveness */
    .search-bar {
        margin-top: -50px;
    }
    
    .search-form-wrapper {
        padding: 15px;
        gap: 12px;
    }
    
    .date-picker-wrapper {
        flex-direction: column;
        height: auto;
        border-radius: 15px;
    }
    
    .check-in, .check-out {
        padding: 2px 0 1px 0;
        height: 45px;
    }
    
    .divider {
        height: 1px;
        width: 100%;
    }
    
    .search-bar .form-control,
    .search-bar input,
    .search-bar select {
        height: 45px;
        font-size: 14px !important;
    }
    
    .guest-container {
        height: 45px;
    }
    
    .search-btn {
        height: 45px;
    }
    
    /* Fix date picker positioning */
    .datepicker-common-2 {
        width: 100% !important;
    }
}

@media (max-width: 576px) {
    .hero-section {
        padding: 80px 0 70px;
    }
    
    .hero-content {
        margin-bottom: 10px;
    }
    
    .search-bar {
        margin-top: -40px;
    }
    
    .search-form-wrapper {
        width: 95%;
        padding: 12px;
        border-radius: 15px;
        gap: 10px;
    }
    
    .attraction-section {
        padding: 10px !important;
    }
    /* Adjust spacing for properties section */
    .properties-section {
        margin-top: 10px;
        padding-top: 20px;
        padding-bottom: 20px ;
    }
    
    /* Improve reviews section on mobile */
    .reviews-section {
        padding: 50px 0;
    }
    
    .reviews-title {
        font-size: 2rem;
        margin-bottom: 30px;
    }
    
    .review-card {
        padding: 20px;
        min-height: 300px;
    }
    
    .review-text {
        font-size: 0.9rem;
        -webkit-line-clamp: 6;
    }
    
    .search-bar input::placeholder {
        font-size: 13px;
    }
    
    
    .search-btn {
        padding: 0;
       
    }
    
    .search-btn i {
        margin: 0;
        font-size: 18px;
    }
    
    /* Fix guest selector on small screens */
    div#guestsss {
        width: 100%;
        left: 0;
        right: 0;
    }
}

/* Animation for search bar on scroll */
.search-bar.scrolled .search-form-wrapper {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
    transform: translateY(-3px);
}

.search-form-wrapper {
    transition: all 0.3s ease;
}

/* About Section */
.about-section {
    padding: 100px 0;
    background-color: var(--background-light);
}

.about-content {
    display: flex;
    align-items: center;
    gap: 60px;
}

.about-illustration {
    /*flex: 1;*/
    text-align: center;
}

.about-illustration img {
    height: 450px;
}

.about-text h2 {
    font-size: 2.5rem;
    color: var(--text-dark);
    margin-bottom: 25px;
    font-weight: 400;
}

.about-text h3 {
    font-size: 1.5rem;
    color: var(--grey-dark);
    margin-bottom: 20px;
    font-weight: 400;
}

.about-text p {
    color: var(--grey-dark);
   
    margin-bottom: 25px;
  
}

.learn-more {
    color: var(--text-dark);
    text-decoration: none;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    border-bottom: 1px solid transparent;
    padding-bottom: 5px;
}

.learn-more:hover {
    color: var(--grey-dark);
    border-bottom: 1px solid var(--grey-dark);
    transform: translateX(5px);
}

/* Newsletter Section */
.newsletter-section {
    background: var(--highlight-bg);
    color: var(--text-light);
    padding: 15px 0;
}

.newsletter-content {
    display: flex;
    justify-content: space-around;
    align-items: center;
}

.newsletter-title {
    font-size: 2.2rem;
    font-weight: 400;
    margin: 0 0 15px 0;
    line-height: 1.3;
}

.newsletter-form {
    flex-basis: 50%;
    width: 100%;
}

.input-button-wrapper {
    display: flex;
    width: 100%;
    position: relative;
    margin-top: 20px;
}

.newsletter-input {
    flex: 1;
    width: 100%;
    padding: 18px 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 3px 0 0 3px;
    font-size: 15px;
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
}

.newsletter-input::placeholder {
    color: var(--grey-mid);
}

.newsletter-input:focus {
    outline: none;
    box-shadow: 0 0 0 1px rgba(255, 255, 255, 0.5);
    border-color: var(--text-light);
}

.newsletter-btn {
    background: var(--cta-button);
    color: var(--text-light);
    border: none;
    padding: 18px 30px;
    border-radius: 0 3px 3px 0;
    font-weight: 500;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.85rem;
    white-space: nowrap;
}

.newsletter-btn:hover {
    background: var(--primary-accent);
}

/* Attraction section */
.attraction-section {
  
    background-color: #ffffff;
}

.attraction-card {
    position: relative;
    height: 400px;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.attraction-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.card-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.card-background img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    filter: grayscale(30%); /* Apply grayscale filter for theme consistency */
}

.attraction-card:hover .card-background img {
    transform: scale(1.05);
    filter: grayscale(0%);
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    transition: background 0.3s ease;
    z-index: 2;
}

.attraction-card:hover .card-overlay {
    background: rgba(0, 0, 0, 0.6);
}

.design-card-content {
    position: absolute;
    bottom: 30px;
    left: 30px;
    color: white;
    transition: opacity 0.4s ease;
    z-index: 5;
    padding: 12px 20px;
    border-radius: 8px;
}

.design-card-content h3 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0;
    color: white;
}

.attraction-card:hover .design-card-content {
    opacity: 0;
}

.card-content {
    position: absolute;
    bottom: 20%;
    left:10%;
    right: 0;
    padding: 40px 30px;
    color: white;
    transform: translateY(20px);
    opacity: 0;
    transition: all 0.5s ease;
    z-index: 5;
}

.attraction-card:hover .card-content {
    transform: translateY(0);
    opacity: 1;
}

.card-title {
    font-size: 2rem;
    font-weight: 500;
    margin-bottom: 10px;
    letter-spacing: 1px;
}

.card-subtitle {
    font-size: 1rem;
    font-weight: 300;
    margin-bottom: 20px;
    opacity: 0.9;
}

.explore-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: transparent;
    border: none;
    color: white;
    padding: 12px 25px;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
}

.explore-btn:hover {
    color: #ffffff;
    transform: translateX(5px);
}

.explore-btn i {
    transition: transform 0.3s ease;
}

.explore-btn:hover i {
    transform: translateX(3px);
}

/* Responsive Design */
@media (max-width: 992px) {
    .about-content {
        flex-direction: column;
        gap: 10px !important;
    }

    .newsletter-content {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }
}

@media (max-width: 768px) {
    .search-form {
        flex-direction: column;
    }

    .hero-title {
        font-size: 2.5rem;
        letter-spacing: 3px;
    }

    .hero-subtitle {
        font-size: 2.2rem;
        margin-top: -5px;
    }

    .hero-location {
        font-size: 1rem;
    }

    .section-title {
        font-size: 1.8rem;
    }

    .section-subtitle {
        max-width: 90%;
    }

    .property-image {
        height: 300px;
    }

    .newsletter-form {
        width: 100%;
    }

    .input-button-wrapper {
        flex-direction: column;
        gap: 15px;
    }

    .newsletter-input {
        border-radius: 4px;
    }

    .newsletter-btn {
        border-radius: 4px;
        width: 100%;
    }

    .attraction-card {
        height: 300px;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 1.5rem;
    }

    .card-content {
        padding: 30px 20px;
    }
}

@media (max-width: 576px) {
    .hero-section {
        height: 70vh;
    }

    .hero-logo {
        width: 200px;
    }

    .hero-title {
        font-size: 2rem;
    }

    .hero-subtitle {
        font-size: 1.8rem;
    }


    .property-card {
        margin-bottom: 30px;
    }

    .review-card {
        padding: 20px;
        margin: 0px;
        height: auto;
    }

    .reviews-slider-container {
        height: auto;
    }
    .swiper-button-next, .swiper-button-prev {
    
        width: 24px !important;
        height: 24px !important;
    }
    .reviews-swiper {
    
        padding-bottom: 30px;
    }
}
/* Responsive CSS for Bentonville Lodging Company Website */

/* Large Screens - Minor Adjustments */
@media (max-width: 1400px) {
    
    .hero-section {
        height: 75vh;
    }
    
    .property-image {
        height: 300px;
    }
    
    .reviews-slider-container {
        height: auto;
        min-height: 380px;
    }
    
    .review-card {
        height: auto;
        min-height: 350px;
    }
}

/* Laptop and Small Desktops */
@media (max-width: 1200px) {
    .hero-title {
        font-size: 3.5rem;
    }
    
    .hero-subtitle {
        font-size: 3rem;
    }
 
    
    .about-content {
        flex-direction: column;
        gap: 40px;
    }
    
    .about-illustration img {
        height: 400px;
    }
    
    .section-subtitle {
        max-width: 85%;
    }
    
    .review-card {
        padding: 30px;
        margin: 15px;
    }
    
    .prev-btn {
        left: 0;
    }
    
    .next-btn {
        right: 0;
    }
}

/* Tablets and Smaller Laptops */
@media (max-width: 992px) {
    .hero-section {
        height: 70vh;
    }
    
    .hero-title {
        font-size: 3rem;
        letter-spacing: 4px;
    }
    
    .hero-subtitle {
        font-size: 2.5rem;
    }
    
    .hero-logo {
        width: 250px;
    }
    
    /* Fix search box position */
    
    
    /* Properties section needs more top margin to avoid overlap */
    .properties-section {
        margin-top: 40px;
    }
    

    
    .about-illustration {
        order: 1;
        margin-bottom: 30px;
    }
    
    .about-text {
        order: 2;
        padding-left: 10px !important ;
    }
    
    .about-illustration img {
        height: 350px;
    }
    
    /* Newsletter section */
    .newsletter-content {
        flex-direction: column;
        text-align: center;
        gap: 30px;
    }
    
    .newsletter-form {
        width: 100%;
    }
    
    .section-subtitle {
        max-width: 95%;
    }
}

/* Mobile Landscape */
@media (max-width: 768px) {
    .hero-section {
        height: auto;
        padding: 150px 0 180px;
    }
    
   
    
    .search-form {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn-search {
        width: 100%;
        margin-top: 5px;
    }
    
    .form-group {
        margin-bottom: 10px;
        width: 100%;
    }
    
    /* Adjust properties section to avoid search bar overlap */

    
    .hero-title {
        font-size: 2.5rem;
        letter-spacing: 3px;
    }
    
    .hero-subtitle {
        font-size: 2.2rem;
        margin-top: -5px;
    }
    
    .hero-location {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .section-subtitle {
        max-width: 100%;
        font-size: 0.9rem;
    }
    
    /* Make review cards smaller */
    .review-card {
        padding: 20px;
        margin: 10px 5px;
        height: auto;
        min-height: 300px;
    }
    
    .review-text {
        font-size: 1rem;
    }
    
    /* Fix adult popup positioning and layout */
    .adult-popup {
        width: 100%;
        left: 0;
        right: 0;
    }
    
    /* Newsletter input and button */
    .input-button-wrapper {
        flex-direction: column;
    }
    
    .newsletter-input {
        width: 100%;
        border-radius: 4px;
    }
    
    .newsletter-btn {
        width: 100%;
        border-radius: 4px;
        margin-top: 10px;
    }
    
    .newsletter-title {
        font-size: 1.8rem;
    }
    
    /* Attraction cards smaller height */
    .attraction-card {
        height: 300px;
    }
    
    .about-illustration img {
        height: 300px;
        width: 100%;
        object-fit: cover;
    }
}

/* Mobile Portrait */
@media (max-width: 576px) {
    .hero-section {
        padding: 120px 0 150px;
    }
    
    .hero-content {
        margin-bottom: 40px;
    }
    
    .hero-logo {
        width: 180px;
    }
    
    .hero-title {
        font-size: 2rem;
        letter-spacing: 2px;
    }
    
    .hero-subtitle {
        font-size: 1.8rem;
    }
    
    .section-title {
        font-size: 1.7rem;
    }
    
    .section-header {
        margin-bottom: 40px;
    }
    
    .property-title {
        font-size: 1.3rem;
    }
    
    .property-detail span {
        font-size: 0.8rem;
    }
    
    /* Smaller attraction cards */
    .attraction-card {
        height: 250px;
    }
    
    .design-card-content h3 {
        font-size: 1.5rem;
    }
    
    .card-title {
        font-size: 1.3rem;
    }
    
    .card-subtitle {
        font-size: 0.9rem;
    }
    
    /* Reviews section fixes */
    .reviews-section {
        padding: 70px 0;
    }
    
    .reviews-title {
        margin-bottom: 30px;
        font-size: 1.7rem;
    }
    
    .review-title {
        font-size: 1.1rem;
    }
    
    .reviews-slider-container {
        padding-bottom: 40px;
    }
    
    /* About section text sizes */
    .about-section {
        padding: 70px 0;
    }
    
    .about-text h2 {
        font-size: 1.8rem;
    }
    
    .about-text p {
        font-size: 0.95rem;
    }
    
    /* Guest popup */
    .adult-popup {
        max-width: 100%;
        box-sizing: border-box;
        padding: 15px;
    }
}

/* Extra Small Devices */
@media (max-width: 400px) {
    .hero-title {
        font-size: 1.7rem;
    }
    
    .hero-subtitle {
        font-size: 1.6rem;
    }
    
    .hero-location {
        font-size: 0.9rem;
    }
    
    .hero-logo {
        width: 150px;
    }
    
    .btn-search {
        padding: 15px;
    }
    
    .property-image {
        height: 220px;
    }
    
    .newsletter-title {
        font-size: 1.5rem;
    }
    
    .attraction-card {
        height: 200px;
    }
    
    .review-title {
        font-size: 1rem;
    }
    
    .review-text {
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .prev-btn, .next-btn {
        width: 36px;
        height: 36px;
        font-size: 0.9rem;
    }
}

/* Mobile Touch Optimizations */
.attraction-card.touch-active .card-overlay {
    background: rgba(0, 0, 0, 0.6);
}

.attraction-card.touch-active .design-card-content {
    opacity: 0;
}

.attraction-card.touch-active .card-content {
    transform: translateY(0);
    opacity: 1;
}

.attraction-card.touch-active .card-background img {
    transform: scale(1.05);
    filter: grayscale(0%);
}

@media (hover: none) {
    .explore-btn:active {
        color: #ffffff;
        transform: translateX(5px);
    }
    
    .explore-btn:active i {
        transform: translateX(3px);
    }
    
    .review-card {
        height: auto;
    }
}

/* Fix for adult popup and guest selector */



/* Modern Search Bar Styling */
.search-bar-below-banner {
     position: relative;
    background: var(--text-light);
    padding-top: 20px;
   padding: 15px 0 5px 0;
    border-radius: 4px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    max-width: 800px;
    top:-12px;
    width: 90%;
    z-index: 20;
    left: 50%;
    transform: translateX(-50%);
}
.search-bar {
    position: relative;
    z-index: 100;
    margin-top: -60px; /* Pull the search bar up to overlap banner */
}

.search-bar form {
    position: relative;
    margin: auto;
}

.search-form-container {
    display: flex;
    justify-content: center;
}

.search-form-wrapper {
    
    display: flex;
   
}

.search-item {
    position: relative;
}

.date-picker-item {
    flex: 2;
    margin-right: 10px;
}

.guest-item {
    flex: 1;
    margin-right: 10px;
}

.search-button-item {
    flex: 0 0 auto;
}

/* Date picker layout */
.date-picker-wrapper {
    display: flex;
   
}

.check-in, .check-out {
    flex: 1;
    position: relative;
}

.divider {
    width: 1px;
    background-color: #E5E7EB;
}

/* Form inputs styling */
.search-bar select {
    font-size: 15px;
    color: #333;
    font-weight: 400;
    padding: 10px 15px 10px 20px;
    background: #F9FAFB;
    border: none;
    height: 50px;
    width: 100%;
    transition: all 0.3s ease;
}

.search-bar input {
    font-size: 15px !important;
    color: #333;
    font-weight: 400;
    padding: 10px 15px 10px 20px;
    background: #F9FAFB;
    border: none;
    height: 50px;
    transition: all 0.3s ease;
}

.search-bar .form-control {
    height: 100%;
    outline: none;
    box-shadow: none;
}

.search-bar .form-control:focus {
    outline: none;
    box-shadow: none;
}

.search-bar input::placeholder {
    color: #6B7280;
    opacity: 1;
}

/* Icons positioning */
.search-bar i {
    color: #6B7280;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 16px;
    right: 20px;
    z-index: 1;
}

/* Datepicker */
.hidden-datepicker {
    position: absolute;
    width: 100%;
    height: 0;
    opacity: 0;
    pointer-events: auto;
}

#datepicker-demo17.datepicker {
    left: 0;
}

/* Guest selector */
.guest-container {
    position: relative;
    height: 50px;
}

.guest-container input {
    width: 100%;
  
}

/* Search button */
.search-btn {
    background:var(--cta-button);
    color: white;
    border: none;
    height: 50px;
    border-radius: 10px;
    padding: 0 28px;
    margin-bottom: 8px;
    font-weight: 500;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    cursor: pointer;
}
.form-control  {
    padding: 0.7rem;
}
.search-btn i {
    font-size: 16px;
    margin-right: 8px;
    position: static;
    transform: none;
    color: white;
}

.search-btn:hover {
    /*transform: translateY(-2px);*/
    /*box-shadow: 0 4px 12px rgba(26, 86, 219, 0.3);*/
    background:var(--cta-button);
}

/* Guest popup styling */
div#guestsss {
    width: 280px;
    background: #fff;
    padding: 20px;
    position: absolute;
    z-index: 999;
    top: 60px;
    left: 0;
    border-radius: 12px;
    display: none;
    box-shadow: 0px 5px 25px rgba(0, 0, 0, 0.15);
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Guest popup content styling */
#guestsss .close1 {
    position: absolute;
    right: 15px;
    left: auto;
    top: 15px;
    font-size: 18px;
    color: #6B7280;
    cursor: pointer;
}

#guestsss .adult-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

#guestsss .adult-box p {
    color: #374151;
    font-size: 14px;
    font-weight: 500;
    margin: 0;
}

#guestsss button.button1 {
    background: #F3F4F6 !important;
    height: 32px;
    width: 32px;
    text-align: center;
    border-radius: 50%;
    font-size: 16px;
    line-height: 28px;
    border: 1px solid #D1D5DB;
    color: #4B5563;
    transition: all 0.2s ease;
    cursor: pointer;
}

#guestsss button.button1:hover {
    background: #F3F4F6 !important;
    border-color: #9CA3AF;
}

#guestsss button.button11.button1 {
    margin-left: 10px;
}

#guestsss button.main-btn.close111 {
    background: var(--cta-button);
    color: white;
    border: none;
    width: 100%;
    padding: 10px;
    border-radius: 100px !important;
    font-weight: 500;
    font-size: 14px;
    margin-top: 10px;
    transition: all 0.2s ease;
}

#guestsss button.main-btn.close111:hover {
    background: var(--cta-button);
}

#guestsss .close1 {
    position: absolute;
    right: 15px;
    left: auto;
    top: 15px;
    font-size: 18px;
    color: #555;
    cursor: pointer;
    transition: all 0.3s ease;
}

#guestsss .close1:hover {
    color: #000;
}

#guestsss .adult-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

#guestsss .adult-box p {
    letter-spacing: 0px;
    color: var(--black-color);
    font-size: 14px;
    text-align: left;
    margin: 0px;
    font-weight: 500;
}

#guestsss button.button1 {
    background: transparent;
    height: 30px;
    width: 30px;
    text-align: center;
    border-radius: 50%;
    font-size: 18px;
    line-height: 26px;
    margin-left: 0;
    border: 1px solid #ddd;
    color: #555;
    transition: all 0.3s ease;
    cursor: pointer;
}

#guestsss button.button1:hover {
    border-color: #888;
    color: #000;
}

#guestsss button.button11.button1 {
    margin-left: 10px;
}

#guestsss button.main-btn.close111 {
    height: 40px;
    padding: 5px 15px;
    font-size: 14px;
    border-radius: 8px;
    width: 100%;
    margin-top: 10px;
}

/* Responsive styling */
@media (max-width: 991px) {
    .search-bar form {
        padding: 20px;
        border-radius: 20px;
    }
    
    .search-bar .main-check {
        margin-bottom: 15px;
    }
    
    .search-bar .guest {
        margin-bottom: 15px;
    }
}

@media (max-width: 767px) {
    .search-bar form {
        width: 95%;
        padding: 15px;
    }
    
    .search-bar input, .search-bar select {
        height: 45px;
        font-size: 14px !important;
    }
    
    .search-bar button.main-btn {
        height: 45px;
    }
    
    div#guestsss {
        width: 100%;
        top: 55px;
    }
    
    .search-bar .check:before {
        display: none;
    }
}

@media (max-width: 575px) {
    .search-bar form {
        width: 100%;
        border-radius: 15px;
        padding: 15px 10px;
    }
    
    .search-bar .row {
        margin-left: -5px;
        margin-right: -5px;
    }
    
    .search-bar .col-lg-6,
    .search-bar .col-lg-3,
    .search-bar .col-md-6 {
        padding-left: 5px;
        padding-right: 5px;
    }
    
    .search-form-row > div {
        padding-left: 5px;
        padding-right: 5px;
    }
}
.datepicker-section{
                         position: relative;
                     }
                     .datepicker-common-2{
                         padding:0;
                     }
                     input#demo17 {
                         position: absolute;
                         left: 0px;
                         top: 0px !important;
                         width: 100% !important;
                         opacity: 0;
                         height: 32px !important;
                         z-index: 9;
                     }
#guestsss button.button1 {
    background: transparent;
    height: 30px;
    width: 30px;
    text-align: center;
    border-radius: 50%;
    font-size: var(--f19);
    line-height: 30px;
    margin-left: 0;
    border: 1px solid var(--black-color);
    color: var(--black-color);
    transition: all .3s;
    cursor: pointer;
}

#guestsss button.button11.button1 {
    margin-left: var(--m14);
}

.datepicker-section {
    position: relative;
}

.datepicker-common-2 {
    padding: 0;
}

input#demo17 {
    position: absolute;
    left: 0px;
    top: 0px !important;
    width: 100% !important;
    opacity: 0;
    height: 32px !important;
    z-index: 9;
}

.form-control:focus {
    background-color: #e3e3e3 !important;
    border-color: #e3e3e3 !important;
    box-shadow: 0 0 0 0rem rgba(13, 110, 253, .25) !important;
}

body {
    background: #fff;
}

.banner {
    height: 350px;
}

.banner img.img-fluid {
    max-height: 350px;
}

.head-sec {
    text-align: center;
    margin-bottom: var(--m40);
}

.head-sec p {
    font-size: var(--f14);
    font-weight: 600;
    color: var(--secondary-color);
    margin-bottom: var(--m5);
    letter-spacing: 3px;
    text-transform: uppercase;
    line-height: 1.7;
}

.head-sec h2 {
    font-size: var(--f32);
    line-height: 1.3;
    font-weight: 600;
    color: var(--black-color);
    text-transform: uppercase;
    margin-bottom: 0px;
    font-family: var(--secondary-font);
    width: auto;
    display: inline-block;
}

.head-sec span {
    font-size: var(--f45);
    font-weight: 400;
    line-height: 1;
    margin-top: -10px;
    color: var(--secondary-color);
    font-family: var(--other-font);
    text-align: right;
}

/* section.featured-pro {
    background: #f7f7f7;
    position: relative;
} */
.calendar{
    margin-top: var(--m40);
    justify-content: space-between !important;
}
.calendar .col-6{
    width: 49%;
}

</style>
@stop
@section("js")
@parent
<script src="{{ asset('front')}}/assets/owl/owl.carousel.min.js" ></script>
<script src="{{ asset('front')}}/js/home.js" ></script>
<script src="{{ asset('front')}}/js/responsive.js" ></script>
<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Initialize Swiper for Reviews
  const reviewsSwiper = new Swiper('.reviews-swiper', {
    // Optional parameters
    slidesPerView: 2,
    spaceBetween: 30,
    loop: true,
    grabCursor: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    
    // Responsive breakpoints
    breakpoints: {
      // Mobile - show 1 slide
      320: {
        slidesPerView: 1,
        spaceBetween: 20
      },
      // Tablet and above - show 2 slides
      768: {
        slidesPerView: 2,
        spaceBetween: 30
      }
    },
    
    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    
    // Pagination
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    },
  });
});
</script>
<script>
	var val=0;
	function functiondec($getter_setter,$show,$cal){
	val=parseInt($($getter_setter).val());
	if(val>0){
	    val=val-1;
	}
	$($getter_setter).val(val);
	//console.log(val);
	person1=val;
	person2=parseInt($($cal).val());
	$show_data=person1+person2;
	$show_actual_data=$show_data+" Guests";
	if($getter_setter=="#adults-data"){
	    $($getter_setter+'-show').html(val +" Adults");
	    if(val<=1){
	       $($getter_setter+'-show').html(val +" Adult");
	    }
	}else{
	     $($getter_setter+'-show').html(val +" Children");
	    if(val<=1){
	       $($getter_setter+'-show').html(val +" Child");
	    }
	}
	$($show).val($show_actual_data);
	}
	function functioninc($getter_setter,$show,$cal){
	val=parseInt($($getter_setter).val());
	//  console.log(val)
	    val=(val*1)+1;
	//  console.log(val)
	$($getter_setter).val(val);
	person1=val;
	person2=parseInt($($cal).val());
	$show_data=person1+person2;
	$show_actual_data=$show_data+" Guests";
	$($show).val($show_actual_data);
	if($getter_setter=="#adults-data"){
	    $($getter_setter+'-show').html(val +" Adults");
	    if(val<=1){
	       $($getter_setter+'-show').html(val +" Adult");
	    }
	}else{
	     $($getter_setter+'-show').html(val +" Children");
	    if(val<=1){
	       $($getter_setter+'-show').html(val +" Child");
	    }
	}
	}
</script>
<script src="{{ asset('datepicker') }}/node_modules/fecha/dist/fecha.min.js"></script>
<script src="{{ asset('datepicker') }}/dist/js/hotel-datepicker.js"></script>
<script>
	@php
	    $new_data_blocked=LiveCart::iCalDataCheckInCheckOutCheckinCheckout(0);
	    $checkin=json_encode($new_data_blocked['checkin']);
	    $checkout=json_encode($new_data_blocked['checkout']);
	    $blocked=json_encode($new_data_blocked['blocked']);

	@endphp

	      var checkin = <?php echo $checkin;  ?>;
	    var checkout = <?php echo ($checkout);  ?>;
	    var blocked= <?php echo ($blocked);  ?>;



	    function clearDataForm(){
	        $("#start_date").val('');
	        $("#end_date").val('');

	    }
	            (function () {
	                @if(Request::get("start_date"))
	                    @if(Request::get("end_date"))
	                        $("#demo17").val("{{ request()->start_date }} - {{ request()->end_date }}");
	                    @endif
	                @endif
	                abc=document.getElementById("demo17");
	                var demo17 = new HotelDatepicker(
	                    abc,
	                    {
	                        @if($checkin)
	                        noCheckInDates: checkin,
	                        @endif
	                        @if($checkout)
	                        noCheckOutDates: checkout,
	                        @endif
	                        @if($blocked)
	                         disabledDates: blocked,
	                        @endif
	                        onDayClick: function() {
	                             d = new Date();
	                                d.setTime(demo17.start);
	                                document.getElementById("start_date").value = getDateData(d);
	                                d = new Date();
	                                console.log(demo17.end)
	                                if(Number.isNaN(demo17.end)){
	                                    document.getElementById("end_date").value = '';
	                                }else{
	                                     d.setTime(demo17.end);
	                                    document.getElementById("end_date").value = getDateData(d);
	                                   // ajaxCallingData();
	                                }
	                        },
	                        clearButton:function(){
	                            return true;
	                        }
	                    }
	                );

	                        @if(Request::get("start_date"))
	                            @if(Request::get("end_date"))
	                                setTimeout(function(){
	                                        $("#demo17").val("{{ request()->start_date }} - {{ request()->end_date }}")
	                                        document.getElementById("start_date").value ="{{ request()->start_date }}";
	                                        document.getElementById("end_date").value ="{{ request()->end_date }}";

	                                    },1000);

	                            @endif
	                        @endif

	            })();

	            $(document).on("click","#clear",function(){
	                $("#clear-demo17").click();
	            })
	            x=document.getElementById("month-2-demo17");
	            x.querySelector(".datepicker__month-button--next").addEventListener("click", function(){
	                y=document.getElementById("month-1-demo17");
	                y.querySelector(".datepicker__month-button--next").click();
	            })  ;


	            x=document.getElementById("month-1-demo17");
	            x.querySelector(".datepicker__month-button--prev").addEventListener("click", function(){
	                y=document.getElementById("month-2-demo17");
	                y.querySelector(".datepicker__month-button--prev").click();
	            })  ;



	          function getDateData(objectDate){

	            let day = objectDate.getDate();

	            let month = objectDate.getMonth()+1;

	            let year = objectDate.getFullYear();


	            if (day < 10) {
	                day = '0' + day;
	            }

	            if (month < 10) {
	                month = `0${month}`;
	            }
	            format1 = `${year}-${month}-${day}`;
	            return  format1 ;
	            console.log(format1); // 07/23/2022
	          }
</script>
  <script>
    // Add interactive functionality
    document.addEventListener("DOMContentLoaded", function () {
      // Heart icon toggle
      const heartIcons = document.querySelectorAll(".heart-icon");
      heartIcons.forEach((heart) => {
        heart.addEventListener("click", function (e) {
          e.preventDefault();
          const icon = this.querySelector("i");
          if (icon.classList.contains("far")) {
            icon.classList.remove("far");
            icon.classList.add("fas");
            icon.style.color = "#e74c3c";
          } else {
            icon.classList.remove("fas");
            icon.classList.add("far");
            icon.style.color = "#ccc";
          }
        });
      });

      // View details button hover effect
      const viewDetailsButtons = document.querySelectorAll(".view-details");
      viewDetailsButtons.forEach((button) => {
        button.addEventListener("click", function () {
          console.log(
            "View details clicked for:",
            this.closest(".property-card").querySelector(".property-name")?.textContent
          );
        });
      });

      // Card hover animations - only on non-touch devices
      if (window.matchMedia("(hover: hover)").matches) {
        const propertyCards = document.querySelectorAll(".property-card");
        propertyCards.forEach((card) => {
          card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-8px)";
          });

          card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
          });
        });
      }
      
      // Mobile optimizations
      function handleResize() {
        // Adjust review slider height on mobile
        if (window.innerWidth <= 768) {
          const reviewsContainer = document.querySelector('.reviews-slider-container');
          if (reviewsContainer) {
            const activeSlide = document.querySelector('.reviews-slide.active');
            if (activeSlide) {
              const height = activeSlide.offsetHeight;
              reviewsContainer.style.height = (height + 70) + 'px';
            }
          }
        }
        
        // Adjust search bar positioning based on screen size
        const searchBar = document.querySelector('.search-bar');
        if (searchBar) {
          if (window.innerWidth <= 576) {
            searchBar.style.marginTop = '-40px';
          } else if (window.innerWidth <= 768) {
            searchBar.style.marginTop = '-50px';
          } else if (window.innerWidth <= 992) {
            searchBar.style.marginTop = '-60px';
          } else {
            searchBar.style.marginTop = '-60px';
          }
        }
      }
      
      // Run on load and on resize
      handleResize();
      window.addEventListener('resize', handleResize);
      
      // Improve search bar functionality
      
      // Fix datepicker position on mobile
      const guestSelector = document.getElementById('show-target-data');
      if (guestSelector) {
        guestSelector.addEventListener('click', function(e) {
          e.preventDefault();
          const guestsPopup = document.getElementById('guestsss');
          
          if (!guestsPopup) return;
          
          // Toggle popup visibility
          if (guestsPopup.style.display === 'block') {
            guestsPopup.style.display = 'none';
          } else {
            guestsPopup.style.display = 'block';
            
            // Position correctly based on device
            if (window.innerWidth <= 768) {
              guestsPopup.style.top = '100%';
              guestsPopup.style.left = '0';
              guestsPopup.style.right = '0';
              guestsPopup.style.width = '100%';
              guestsPopup.style.maxWidth = 'none';
            } else {
              guestsPopup.style.top = '60px';
              guestsPopup.style.width = '280px';
            }
          }
        });
      }
      
      // Close guest popup when clicking apply button
      const applyButton = document.querySelector('.close111');
      if (applyButton) {
        applyButton.addEventListener('click', function() {
          const guestsPopup = document.getElementById('guestsss');
          if (guestsPopup) {
            guestsPopup.style.display = 'none';
          }
        });
      }
      
      // Close guest popup when clicking X button
      const closeButton = document.querySelector('#guestsss .close1');
      if (closeButton) {
        closeButton.addEventListener('click', function() {
          const guestsPopup = document.getElementById('guestsss');
          if (guestsPopup) {
            guestsPopup.style.display = 'none';
          }
        });
      }
      
      // Close guest popup when clicking outside
      document.addEventListener('click', function(e) {
        const guestsPopup = document.getElementById('guestsss');
        const guestSelector = document.getElementById('show-target-data');
        
        if (guestsPopup && guestSelector) {
          if (!guestsPopup.contains(e.target) && 
              e.target !== guestSelector && 
              !guestSelector.contains(e.target)) {
            guestsPopup.style.display = 'none';
          }
        }
      });
      
      // Add animation to search bar on scroll
      window.addEventListener('scroll', function() {
        const searchBar = document.querySelector('.search-bar');
        if (searchBar) {
          if (window.scrollY > 100) {
            searchBar.classList.add('scrolled');
          } else {
            searchBar.classList.remove('scrolled');
          }
        }
      });
      
      // Fix touch interactions for mobile devices
      if ('ontouchstart' in window || navigator.maxTouchPoints) {
        // Make search inputs more touch-friendly
        const searchInputs = document.querySelectorAll('.search-bar input');
        searchInputs.forEach(input => {
          input.addEventListener('touchend', function(e) {
            this.focus();
          });
        });
      }
    });
  </script>
  <script>
      /**
 * Responsive Enhancement Script for Bentonville Lodging
 * This script handles responsive behaviors for small devices
 */

document.addEventListener('DOMContentLoaded', function() {
    // Mobile navigation enhancements
    const handleWindowResize = () => {
        // Adjust height of reviews container on mobile
        const reviewsContainer = document.querySelector('.reviews-slider-container');
        if (reviewsContainer) {
            const activeSlide = document.querySelector('.reviews-slide.active');
            if (activeSlide) {
                if (window.innerWidth <= 768) {
                    // For mobile, set dynamic height based on content
                    const slideHeight = activeSlide.offsetHeight;
                    reviewsContainer.style.height = (slideHeight + 50) + 'px';
                } else {
                    // For desktop, use fixed height
                    reviewsContainer.style.height = '420px';
                }
            }
        }
        
       
    // Better positioning of guest selector popup on mobile
    const guestSelector = document.getElementById('show-target-data');
    if (guestSelector) {
        guestSelector.addEventListener('click', function() {
            const popup = document.getElementById('guestsss');
            if (popup) {
                if (window.innerWidth <= 768) {
                    popup.style.width = '100%';
                    popup.style.left = '0';
                    popup.style.right = '0';
                    popup.style.maxWidth = 'none';
                }
            }
        });
    }

    // Touch events for attractions on mobile
    const attractionCards = document.querySelectorAll('.attraction-card');
    if ('ontouchstart' in window || navigator.msMaxTouchPoints) {
        attractionCards.forEach(card => {
            card.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
                
                // Remove active class from other cards
                attractionCards.forEach(otherCard => {
                    if (otherCard !== card) {
                        otherCard.classList.remove('touch-active');
                    }
                });
            });
        });
    }
    
    // Review slider navigation for mobile touch
    const reviewNavButtons = document.querySelectorAll('.review-nav-btn, .review-dot');
    reviewNavButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Wait for slide transition then adjust height
            setTimeout(() => {
                handleWindowResize();
            }, 500);
        });
    });

    // Initial call and resize listener
    handleWindowResize();
    window.addEventListener('resize', handleWindowResize);
    
    // Reinitialize on orientation change for mobile devices
    window.addEventListener('orientationchange', function() {
        setTimeout(handleWindowResize, 300);
    });
      
       
});


  </script>
@stop

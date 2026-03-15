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
    $currency=$setting_data['payment_currency'];
    $name=$data->title;
    $bannerImage=asset('front/images/breadcrumb.webp');;
    if($data->banner_image){
        $bannerImage=asset($data->banner_image);
    }
    $picture=json_decode($data->picture,true);
    $amenities=json_decode($data->amenities,true);
    $amenitiesNotIncluded=json_decode($data->amenitiesNotIncluded,true);
    $pictures=json_decode($data->pictures,true);
    $prices=json_decode($data->prices,true);
    $address=json_decode($data->address,true);
@endphp
<style>
    div#dev-new-data-area .col-md-6, div#dev-new-modal-days-area .col-md-6{padding:0px;text-align: left;font-size: var(--f14);}
    div#dev-new-data-area .row .col-md-6:last-child{text-align:right;}
    /* Quick navigation styles */
.quick-nav {
    padding: 15px 6px;
    border-bottom: 1px solid #eee;
    /*margin-bottom: 20px;*/
    background: #D3D3D3;
    border-radius: 10px;
    min-height: 76px;
}

.quick-nav-link {
    color: #000000;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 5px 0;
    margin-right: 20px;
}

.quick-nav-link:hover {
    color: #555555;
    border-bottom: 1px solid #000;
}

.active-section {
    border-bottom: 2px solid #000000;
    font-weight: bold;
}

.section-container {
    transition: opacity 0.5s ease;
    
    opacity: 0.9;
}

.section-container:target {
    opacity: 1;
}

.section-scroll {
    scroll-margin-top: 100px;
}

/* Hide full content initially */
.hidden-content {
    display: none;
}

/* Show more button styling */
.show-more-btn {
    color: #000000;
    background: transparent;
    border: none;
    padding: 0;
    margin-top: 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.show-more-btn:hover {
    color: #555555;
}

/* Sticky booking widget */
.sticky-booking-widget {
    position: sticky;
    top: 20px;
    z-index: 100;
    transition: all 0.3s ease;
    max-height: calc(100vh - 40px);
    overflow-y: auto;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    background-color: #ffffff;
}

/* Content sections */

/* Mobile responsiveness */
@media (max-width: 768px) {
    .quick-nav .d-flex {
        flex-wrap: nowrap;
        overflow-x: auto;
        padding-bottom: 10px;
    }
    
    .sticky-booking-widget {
        position: relative;
        top: 0;
        margin: 20px 0;
    }
     /* Mobile gallery styling updates */
    .mobile-gallery .main-gallery-image img {
        border-radius: 0;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    
    .mobile-gallery .secondary-gallery-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        grid-gap: 0.5rem;
    }
    
    .mobile-gallery .secondary-gallery-image img {
        border-radius: 0; /* Reset border radius for all secondary images */
    }
    
    /* Target the last two items in the secondary gallery wrapper */
    .mobile-gallery .secondary-gallery-wrapper .secondary-gallery-image:nth-last-child(2) img {
        border-bottom-left-radius: 8px;
    }
    
    .mobile-gallery .secondary-gallery-wrapper .secondary-gallery-image:last-child img {
        border-bottom-right-radius: 8px;
    }
    
    /* Mobile navigation dropdown styles */
    .mobile-quick-nav {
        margin: 15px 0;
    }
    
    .mobile-nav-wrapper {
        position: relative;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #fff;
    }
    
    .mobile-nav-label {
        padding: 12px 15px;
        display: flex;
        align-items: center;
        color: #212529;
        font-weight: 500;
        pointer-events: none;
    }
    
    .mobile-nav-current {
        flex: 1;
    }
    
    .mobile-nav-select {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 2;
    }
    
    /* Add a dropdown arrow indicator */
    .mobile-nav-wrapper:after {
        content: '\f107';
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #cc6119;
        pointer-events: none;
    }
    
    /* Highlight the wrapper when dropdown is active */
    .mobile-nav-wrapper:focus-within {
        border-color: #cc6119;
        box-shadow: 0 0 0 0.2rem rgba(204, 97, 25, 0.15);
    }
    
    .mobile-nav-select:focus {
        outline: none;
    }
    
    .mobile-nav-select option {
        padding: 10px;
        font-weight: 500;
    }
}

/* Card styles for amenities */
.card {
    background-color: var(--white);
    border: 1px solid #e5e5e5;
    margin-bottom: 15px;
    border-radius: 8px;
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: #fff;
}

.textColor {
    color: #cc6119;
}

/* Review styling */
.review-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.reviewer {
    font-weight: bold;
    font-size: 16px;
}

.review-date {
    color: #6c757d;
}

.review-stars {
    color: #ffc107;
}

.review-text {
    color: #212529;
    line-height: 1.5;
}

/* Policy sections */
.rule, .safety, .cancel {
    
    border-radius: 8px;
}

.area h4.main {
    margin-bottom: 15px;
}

/* Amenities styling */
.amenities .card-body {
    padding: 1.5rem;
}

.amenities .card-body .row .col-md-6 {
    margin-bottom: 10px;
}

.amenities .fa-circle-check {
    color: #28a745;
}

/* Content typography */
.content-sections p {
    font-size: 16px;
    line-height: 1.6;
    color: #212529;
    margin-bottom: 1rem;
}



/* Calendar/Availability styling */
.availability-section .card {
    border-radius: 8px;
    overflow: hidden;
}

.d-flex.justify-content-between {
    margin-bottom: 10px;
    font-weight: 500;
}

/* Hosted row styling */
.row.hosted {
    margin-bottom: 1.5rem;
}

.ammenity-home {
    margin-top: 10px;
}

.ammenity-home span {
    font-size: 14px;
}

.ammenity-home i {
    margin-right: 5px;
}

/* Mobile booking button */
.sticky-booking-mobile {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #ffffff;
    padding: 15px;
    text-align: center;
    box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
    font-weight: bold;
    z-index: 1000;
}

@media (max-width: 767px) {
    .sticky-booking-mobile {
        display: block;
    }
    .section-container  {
        padding: 0px !important;
    }
    .location-map {
    height: 350px !important;
    width: 100%;;
}
}

.card-title {
    font-size: 18px;
    font-weight: 600;
    color: #000;
}

.location-map {
    height: 450px;
    width: 100%;;
}

</style>
   
<a href="#book" class="sticky main-btn book1 book-now"><span class="button-text">BOOK NOW</span></a>
<section class="property-detail">
    <section class="main">
        <div class="container-sm-fluid">
            <div class="container">
                <div class=" row">
                    <div class="row header-name">
                        <div class="col-10">
                            <h4 class="fw-bold">{{$data->title}}</h4>
                            @isset($address['full'])
                              <h6><i class="fa fa-map-marker-alt"></i> {{$address['full']}}</h6>
                            @endisset
                           
                        </div>
                    </div>
                       <section class="gallery-section col-lg-12">
                        <div class="">
                            <!-- Image Grid -->
                            <div class="position-relative mb-4">
                                <!-- Desktop Gallery Grid -->
                                <div class="gallery-grid d-none d-md-grid" id="imageGrid">
                                    @php $i=0; @endphp
                                    @foreach($pictures as $picture)
                                        @if($i==0)
                                            <div class="main-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img left-rounded">
                                            </div>
                                        @elseif($i==1)
                                            <div class="secondary-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img">
                                            </div>
                                        @elseif($i==2)
                                            <div class="secondary-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img top-right-rounded">
                                            </div>
                                        @elseif($i==3)
                                            <div class="secondary-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img">
                                            </div>
                                        @elseif($i==4)
                                            <div class="secondary-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img bottom-right-rounded">
                                            </div>
                                        @else
                                            <!-- Hidden images for lightbox -->
                                            <div class="d-none">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img">
                                            </div>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                                
                                <!-- Mobile Gallery Layout -->
                                <div class="mobile-gallery d-md-none">
                                    @php $i=0; @endphp
                                    @foreach($pictures as $picture)
                                        @if($i==0)
                                            <!-- Main image full width -->
                                            <div class="main-gallery-image" onclick="openLightbox({{$i}})">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img mobile-main-img">
                                            </div>
                                        @elseif($i>0 && $i<=4)
                                            @if($i==1)
                                            <!-- Secondary images in grid -->
                                            <div class="secondary-gallery-wrapper">
                                            @endif
                                                <div class="secondary-gallery-image" onclick="openLightbox({{$i}})">
                                                    <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img mobile-secondary-img">
                                                </div>
                                            @if($i==4)
                                            </div>
                                            @endif
                                        @else
                                            <!-- Hidden images for lightbox -->
                                            <div class="d-none">
                                                <img src="{{ $picture['original'] }}" alt="{{$picture['caption'] ?? ''}}" class="gallery-img">
                                            </div>
                                        @endif
                                        @php $i++; @endphp
                                    @endforeach
                                </div>
                                <!-- Show All Button - Desktop -->
                                <div class="show-all-btn d-none d-md-block">
                                    <button onclick="openShowAll()" title="Show all photos" class="btn btn-secondary shadow-sm">
                                        <span>Show All</span>
                                    </button>
                                </div>
                                
                                <!-- Show All Button - Mobile -->
                                <div class="show-all-btn d-md-none">
                                    <button onclick="openShowAll()" title="Show all photos" class="btn btn-secondary shadow-sm">
                                        <span>Show All</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Lightbox Modal -->
                        <div id="lightboxModal" class="lightbox-modal">
                            <!-- Progress Bar for Slideshow -->
                            <div id="progressBar" class="lightbox-progress progress-bar-animation" style="width: 0%"></div>

                            <!-- Top Controls -->
                            <div class="lightbox-controls">
                                <div class="px-2 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center py-2">
                                    <div class="d-flex align-items-center me-4">
                                        <span class="text-dark fs-5 fw-medium" id="imageCounter" aria-live="polite">1 / {{count($pictures)}}</span>
                                    </div>
                                    <div class="d-flex align-items-center border border-secondary bg-light p-2">
                                        <button onclick="startSlideshow()" id="slideshowBtn" aria-label="Start slideshow"
                                            title="Start slideshow" class="btn btn-sm text-dark me-2">
                                            <i class="fa-solid fa-play" id="slideshowIcon"></i>
                                        </button>
                                        <button onclick="toggleThumbnails()" aria-label="Show thumbnails" title="Show thumbnails"
                                            class="btn btn-sm text-dark me-2">
                                            <i class="fa-solid fa-border-all"></i>
                                        </button>
                                        <button onclick="toggleFullscreen()" aria-label="Toggle fullscreen" title="Toggle fullscreen"
                                            class="btn btn-sm text-dark me-2">
                                            <i class="fa-solid fa-expand" id="fullscreenIcon"></i>
                                        </button>
                                        <button onclick="closeLightbox()" aria-label="Close lightbox" title="Close lightbox"
                                            class="btn btn-sm text-dark">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Image Container -->
                            <div class="lightbox-main">
                                <button onclick="previousImage()" aria-label="Previous image" title="Previous image"
                                    class="lightbox-nav-btn lightbox-prev btn text-dark">
                                    <i class="fa-solid fa-chevron-left fs-2"></i>
                                </button>

                                <div class="lightbox-image-container">
                                    <img id="lightboxImage" src="" alt="" class="lightbox-img slideshow-active">
                                </div>

                                <button onclick="nextImage()" aria-label="Next image" title="Next image"
                                    class="lightbox-nav-btn lightbox-next btn text-dark">
                                    <i class="fa-solid fa-chevron-right fs-2"></i>
                                </button>
                            </div>

                            <!-- Thumbnail Panel -->
                            <div id="thumbnailPanel" class="thumbnail-panel">
                                <div class="p-4">
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <button onclick="toggleThumbnails()" class="btn btn-sm text-dark">
                                            <i class="fa-solid fa-times"></i>
                                        </button>
                                    </div>
                                    <div class="thumbnail-grid" id="thumbnailGrid">
                                        <!-- Thumbnails will be populated by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Show All Images Modal -->
                        <div id="showAllModal" class="show-all-modal">
                            <div class="min-vh-100 p-4">
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <h2 class="fs-3 fw-semibold">All Photos - {{$name}}</h2>
                                    <button onclick="closeShowAll()" class="btn btn-dark">
                                        <i class="fa-solid fa-times"></i>
                                    </button>
                                </div>
                                <div class="all-images-grid" id="allImagesGrid">
                                    <!-- All images will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Quick Navigation -->
                    <div class="col-lg-8 " style="    padding: 30px 20px;
    box-shadow: 0px 0px 13px #dad9d9;
    border-radius: 10px;">
                    <!-- Desktop Navigation Tabs -->
                    <div class="quick-nav d-none d-md-block">
                        <div class="d-flex gap-4 pb-2">
                            <a href="#overview-section" class="quick-nav-link">Summary</a>
                            <a href="#amenities-section" class="quick-nav-link">Amenities</a>
                            <a href="#space-section" class="quick-nav-link">Space</a>
                            <a href="#access-section" class="quick-nav-link">Access</a>
                            <a href="#interaction-section" class="quick-nav-link">Interaction With Guests</a>
                            <a href="#neighborhood-section" class="quick-nav-link">Neighborhood</a>
                            <a href="#availability-section" class="quick-nav-link">Availability</a>
                        </div>
                    </div>
                    
                    <!-- Mobile Navigation Dropdown -->
                    <div class="mobile-quick-nav d-md-none">
                        <div class="mobile-nav-wrapper">
                            <div class="mobile-nav-label">
                                <i class="fa-solid fa-list-ul me-2"></i>
                                <span class="mobile-nav-current">Navigate to section</span>
                            </div>
                            <select class="form-select mobile-nav-select" id="mobileNavSelect">
                                <option value="" disabled selected>Navigate to section...</option>
                                <option value="#overview-section">Summary</option>
                                <option value="#amenities-section">Amenities</option>
                                <option value="#space-section">Space</option>
                                <option value="#access-section">Access</option>
                                <option value="#interaction-section">Interaction With Guests</option>
                                <option value="#neighborhood-section">Neighborhood</option>
                                <option value="#availability-section">Availability</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Content Sections -->
                    <div class="content-sections ">
                        <div class="row hosted">
                            <div class="col-lg-11">
                                <h4 class="fw-bold textColor mt-4">{{$data->title}}</h4>
                                <div class="ammenity-home">
                                    @if($data->bedrooms)
                                        <span class="text-dark me-3"><i class="fa-solid fa-bed"></i> {{$data->bedrooms}} Beds</span>
                                    @endif
                                    @if($data->bathrooms)
                                        <span class="text-dark me-3"><i class="fa-solid fa-bath"></i> {{$data->bathrooms}} Baths</span>
                                    @endif
                                    @if($data->accommodates)
                                        <span class="text-dark me-3"><i class="fa fa-users"></i> {{$data->accommodates}} Guests</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>

                        @if($data->summary)
                        <!-- Overview Section -->
                        <div id="overview-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Overview</h4>
                            <div class="overcontent">
                                <div class="description-short">
                                    <p>{!! nl2br(substr($data->summary, 0, 500)) !!}@if(strlen($data->summary) > 500)...@endif</p>
                                </div>
                                <div class="description-full hidden-content">
                                    <p>{!! nl2br($data->summary) !!}</p>
                                </div>
                            </div>
                            @if(strlen($data->summary) > 500)
                                <button class="show-more-btn btn btn-link text-decoration-none mt-2" data-section="description">Show More <i class="fas fa-chevron-down"></i></button>
                            @endif
                        </div>
                        <hr>
                        @endif

                        <!-- Amenities Section -->
                        <div id="amenities-section" class="section-container section-scroll">
                            <div class="amenities">
                                <h4 class="fw-bold textColor">Amenities</h4>
                                <div class="amenities-preview">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold textColor mb-3">Essentials</h5>
                                            <div class="row">
                                                @php $essentialCount = 0; @endphp
                                                @foreach($amenities as $index => $amenity)
                                                    @if($essentialCount < 6 && in_array(strtolower(trim($amenity)), ['wifi', 'air conditioning', 'heating', 'washer & dryer', 'free parking', 'hair dryer', 'essentials', 'iron']))
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa-solid fa-circle-check me-2"></i>
                                                                <span class="">{{ $amenity }}</span>
                                                            </div>
                                                        </div>
                                                        @php $essentialCount++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold textColor mb-3">Features</h5>
                                            <div class="row">
                                                @php $featureCount = 0; @endphp
                                                @foreach($amenities as $index => $amenity)
                                                    @if($featureCount < 6 && !in_array(strtolower(trim($amenity)), ['wifi', 'air conditioning', 'heating', 'washer & dryer', 'free parking', 'hair dryer', 'essentials', 'iron']))
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa-solid fa-circle-check me-2"></i>
                                                                <span class="">{{ $amenity }}</span>
                                                            </div>
                                                        </div>
                                                        @php $featureCount++; @endphp
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="amenities-full hidden-content">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold textColor mb-3">Essentials</h5>
                                            <div class="row">
                                                @foreach($amenities as $index => $amenity)
                                                    @if(in_array(strtolower(trim($amenity)), ['wifi', 'air conditioning', 'heating', 'washer & dryer', 'free parking', 'hair dryer', 'essentials', 'iron']))
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa-solid fa-circle-check me-2"></i>
                                                                <span class="">{{ $amenity }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold textColor mb-3">Features</h5>
                                            <div class="row">
                                                @foreach($amenities as $index => $amenity)
                                                    @if(!in_array(strtolower(trim($amenity)), ['wifi', 'air conditioning', 'heating', 'washer & dryer', 'free parking', 'hair dryer', 'essentials', 'iron']))
                                                        <div class="col-md-6 mb-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fa-solid fa-circle-check me-2"></i>
                                                                <span class="">{{ $amenity }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="show-more-btn btn btn-link text-decoration-none mt-2" data-section="amenities">Show More <i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <hr>

                        <!-- Availability Section -->
                        <div id="availability-section" class="section-container section-scroll">
                            <h4 class="textColor fw-bold">Availability</h4>
                            <div class="">
                                <div class="">
                                    <div class="card-body">
                                        <iframe src="{{ url('fullcalendar-demo/'.$data->id) }}" width="100%" height="400" style="border:0;" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>

                        @if($data->space)
                        <!-- Space Section -->
                        <div id="space-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Space</h4>
                            <div class="overcontent">
                                <div class="space-short">
                                    <p>{!! nl2br(substr($data->space, 0, 300)) !!}@if(strlen($data->space) > 300)...@endif</p>
                                </div>
                                <div class="space-full hidden-content">
                                    <p>{!! nl2br($data->space) !!}</p>
                                </div>
                            </div>
                            @if(strlen($data->space) > 300)
                                <button class="show-more-btn btn btn-link text-decoration-none mt-2" data-section="space">Show More <i class="fas fa-chevron-down"></i></button>
                            @endif
                        </div>
                        <hr>
                        @endif

                        @if($data->access)
                        <!-- Access Section -->
                        <div id="access-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Access</h4>
                            <div class="overcontent">
                                <div class="access-short">
                                    <p>{!! nl2br(substr($data->access, 0, 300)) !!}@if(strlen($data->access) > 300)...@endif</p>
                                </div>
                                <div class="access-full hidden-content">
                                    <p>{!! nl2br($data->access) !!}</p>
                                </div>
                            </div>
                            @if(strlen($data->access) > 300)
                                <button class="show-more-btn btn btn-link text-decoration-none mt-2" data-section="access">Show More <i class="fas fa-chevron-down"></i></button>
                            @endif
                        </div>
                        <hr>
                        @endif

                        @if($data->interactionWithGuests)
                        <!-- Interaction Section -->
                        <div id="interaction-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Interaction With Guests</h4>
                            <div class="overcontent">
                                <p>{!! nl2br($data->interactionWithGuests) !!}</p>
                            </div>
                        </div>
                        <hr>
                        @endif

                        @if($data->neighborhood)
                        <!-- Neighborhood Section -->
                        <div id="neighborhood-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Neighborhood</h4>
                            <div class="overcontent">
                                <p>{!! nl2br($data->neighborhood) !!}</p>
                            </div>
                        </div>
                        <hr>
                        @endif

                        @if($data->transit)
                        <!-- Transit Section -->
                        <div id="transit-section" class="section-container section-scroll">
                            <h4 class="fw-bold textColor">Transit</h4>
                            <div class="overcontent">
                                <p>{!! nl2br($data->transit) !!}</p>
                            </div>
                        </div>
                        <hr>
                        @endif

                        <!-- Location Section -->
                        <div id="location-section" class="section-container section-scroll">
                            <h4 class="textColor fw-bold">Where you'll be</h4>
                            @isset($address['full'])
                            <iframe 
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q={{urlencode($address['full'])}}" 
                                width="100%" 
                               
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade" class="location-map">
                            </iframe>
                            @endisset
                        </div>
                        <hr>

                        @if($data->houseRules)
                        <!-- House Rules Section -->
                        <div id="rules-section" class="section-container section-scroll">
                            <h4 class="textColor fw-bold">Things to know</h4>
                            <div class="row">
                                <div class="col-lg-12 rule card mb-3">
                                    <div class="area card-body">
                                        <h4 class="main textColor fw-bold">House Rules</h4>
                                        <div class="rules-short">
                                            <p>{!! nl2br(substr($data->houseRules, 0, 300)) !!}@if(strlen($data->houseRules) > 300)...@endif</p>
                                        </div>
                                        <div class="rules-full hidden-content">
                                            <p>{!! nl2br($data->houseRules) !!}</p>
                                        </div>
                                    </div>
                                </div>
                                @if(strlen($data->houseRules) > 300)
                                    <button class="show-more-btn btn btn-link text-decoration-none mt-2" data-section="rules">Show More <i class="fas fa-chevron-down"></i></button>
                                @endif
                            </div>
                        </div>
                        <hr>
                        @endif

                        @if(App\Models\Guesty\GuestyPropertyReview::where("listingId",$data->_id)->orderBy("id","asc")->count()>0)
                        <!-- Reviews Section -->
                        <div id="reviews-section" class="section-container section-scroll">
                            <h4 class="textColor fw-bold">Reviews</h4>
                            <div class="row">
                                @php $listReviews=App\Models\Guesty\GuestyPropertyReview::where("listingId",$data->_id)->orderBy("id","asc")->paginate(10); @endphp
                                @foreach($listReviews as $c)
                                @php $rawReview=json_decode($c->rawReview,true); @endphp
                                <div class="col-12 mb-4">
                                    <div class="card-body">
                                        <div class="review-header">
                                            <div class="reviewer">{{ str_replace('"',"",$c->full_name) ?? 'Guest'}}</div>
                                            <div class="review-date">{{ date('F Y',strtotime($c->createdAtGuesty))}}</div>
                                        </div>
                                        <div class="review-stars mb-2">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <div class="review-text">
                                            {{$rawReview['public_review'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <div class="col-12">
                                    {{$listReviews->appends(request()->all())}}
                                </div>
                            </div>
                        </div>
                        <hr>
                        @endif
                 
                    <!--@if($data->summary)-->
                    <!--<section class="description">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Summary</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="des"> <pre>{!! $data->summary !!}</pre></div>-->
                    <!--                <button class="read-more summ">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->booklet)-->
                    <!--<section class="booklet">-->
                    <!--   <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Booklet</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <p>Please find the property attached booklet</p>-->
                    <!--                <a href="{{ asset($data->booklet) }}" target="_BLANK" download class="book"> <i class="fa-solid fa-download"></i> Click Here</a>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div> -->
                    <!--</section>-->
                    <!--@endif-->
                    <!--<section class="ammenities">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Amenities</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--               <div class="row amn">-->
                    <!--                    @foreach($amenities as $c)-->
                    <!--                        <div class="col-6 mt-2"><i class="fa-solid fa-check text-success"></i> {{ $c }}</div>-->
                    <!--                    @endforeach-->
                    <!--               </div>-->
                    <!--               <button class="amn-more">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@if(count($amenitiesNotIncluded)>0)-->
                    <!--<section class="ammenities">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Amenities Not Include</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">                                       -->
                    <!--                <div class="row">-->
                    <!--                    @foreach($amenitiesNotIncluded as $c)-->
                    <!--                        <div class="col-6 mt-2"><i class="fa-solid fa-times text-danger"></i> {{ $c }}</div>-->
                    <!--                    @endforeach-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->space)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Space</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg space">-->
                    <!--                   <pre>{!! $data->space !!}</pre>-->
                    <!--                </div>-->
                    <!--                <button class="policy-space">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->access)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Access</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg access">-->
                    <!--                     <pre>{!! $data->access !!}</pre>-->
                    <!--                </div>-->
                    <!--                <button class="policy-access">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->interactionWithGuests)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Interaction With Guests</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg">-->
                    <!--                     <pre>{!! $data->interactionWithGuests !!}</pre>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->neighborhood)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Neighborhood</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg">-->
                    <!--                     <pre>{!! $data->neighborhood !!}</pre>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->transit)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Transit</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg">-->
                    <!--                    <pre>{!! $data->transit !!}</pre>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->notes)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>Notes</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg notes">-->
                    <!--                    <pre>{!! $data->notes !!}</pre>-->
                    <!--                </div>-->
                    <!--                <button class="policy-notes">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                    <!--@if($data->houseRules)-->
                    <!--<section class="policies">-->
                    <!--    <div class="container-fluid">-->
                    <!--        <div class="row">-->
                    <!--            <div class="col-3">-->
                    <!--                <h2>House Rules</h2>-->
                    <!--            </div>-->
                    <!--            <div class="col-9">-->
                    <!--                <div class="policy-ctg house-rules">-->
                    <!--                     <pre>{!! $data->houseRules !!}</pre>-->
                    <!--                </div>-->
                    <!--                <button class="policy-rules">Read More</button>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</section>-->
                    <!--@endif-->
                  <!--  <section class="policies">-->
                  <!--      <div class="container-fluid">-->
                  <!--          <div class="row">-->
                  <!--              <div class="col-3">-->
                  <!--                  <h2>Availability</h2>-->
                  <!--              </div>-->
                  <!--              <div class="col-9">-->
                  <!--                  <iframe src="{{ url('fullcalendar-demo/'.$data->id) }}"  width="100%" height="400" style="border:0;" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>-->
                  <!--              </div>-->
                  <!--          </div>-->
                  <!--      </div>-->
                  <!--  </section>-->
                  <!--@if(App\Models\Guesty\GuestyPropertyReview::where("listingId",$data->_id)->orderBy("id","asc")->count()>0)-->
                  <!--  <section class="reviews">-->
                  <!--      <div class="container-fluid">-->
                  <!--          <div class="row">-->
                  <!--              <div class="col-3">-->
                  <!--                  <h2>See what others loved about this property</h2>-->
                  <!--                  <p>Rated by our fantastic guests.</p>-->
                  <!--              </div>-->
                  <!--              <div class="col-9">-->
                  <!--                  @php $listReviews=App\Models\Guesty\GuestyPropertyReview::where("listingId",$data->_id)->orderBy("id","asc")->paginate(10); @endphp-->
                  <!--                  @foreach($listReviews as $c)-->
                  <!--                  @php $rawReview=json_decode($c->rawReview,true); @endphp-->
                  <!--                     <div class="user">-->
                  <!--                         <div class="row">-->
                  <!--                             <div class="review-img">-->
                  <!--                                 <img src="{{ asset('front/no-image.png') }}" alt="">-->
                  <!--                             </div>-->
                  <!--                             <div class="col-review">-->
                  <!--                              @if($c->full_name)-->
                  <!--                                 <h6>{{ str_replace('"',"",$c->full_name) ?? ''}}</h6>-->
                  <!--                              @endif-->
                  <!--                                 <p>{{$rawReview['public_review'] ?? '' }}</p>-->
                  <!--                                 <p>{{ date('F d-Y',strtotime($c->createdAtGuesty))}}-->
                  <!--                             </div>-->
                  <!--                             <hr>-->
                  <!--                         </div>-->
                  <!--                     </div>-->
                  <!--                  @endforeach-->
                  <!--                  {{$listReviews->appends(request()->all())}}-->
                  <!--              </div>-->
                  <!--          </div>-->
                  <!--      </div>-->
                  <!--  </section> -->
                  <!--@endif         -->
                </div>
                </div>
                <div class="col-lg-4" id="book">
                    <div class="get-quote">
                        <div class="forms-booking-tab">
                            <ul class="tabs">
                                <li class="item booking active text-black" data-form="ovabrw_booking_form">Book Your Stay</li>
                            </ul>
                            <div class="ovabrw_booking_form" id="ovabrw_booking_form" style="">
                                <form class="form booking_form" id="booking_form" action="{{url('get-quote')}}" method="get">
                                    <input type="hidden" name="property_id" value="{{ $data->id }}">
                                    <div class="main-cal">
                                        <div class="ovabrw_datetime_wrapper">
                                             {!! Form::text("start_date",Request::get("start_date"),["required","autocomplete"=>"off","inputmode"=>"none","id"=>"start_date","placeholder"=>"Check in"]) !!}
                                             <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                        <div class="ovabrw_datetime_wrapper">
                                             {!! Form::text("end_date",Request::get("end_date"),["required","autocomplete"=>"off","inputmode"=>"none","id"=>"end_date","placeholder"=>"Check Out"]) !!}
                                             <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                        <input type="text" id="demo17" value="" aria-label="Check-in and check-out dates" aria-describedby="demo17-input-description" readonly/>
                                   </div>
                                    <div class="ovabrw_service_select rental_item">
                                        <input type="text" name="Guests"   value="{{ Request::get('Guests') ?? '' }}" readonly="" class="form-control gst" id="show-target-data" placeholder="Guests">
                                         <i class="fa-solid fa-users "></i>
                                         <input type="hidden" value="{{ Request::get('adults') ?? '0' }}"  name="adults" id="adults-data" />
                                         <input type="hidden" value="{{ Request::get('child') ?? '0' }}"  name="child" id="child-data" />
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
                                                     <button class="button1"  type="button" onclick="functiondec('#adults-data','#show-target-data','#child-data')" value="Decrement Value">-</button>
                                                     <button class="button11 button1" type="button"  onclick="functioninc('#adults-data','#show-target-data','#child-data')" value="Increment Value">+</button>
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
                                                     <button class="button1" type="button"  onclick="functiondec('#child-data','#show-target-data','#adults-data')" value="Decrement Value" >-</button>
                                                     <button class="button11 button1" type="button"  onclick="functioninc('#child-data','#show-target-data','#adults-data')" value="Increment Value">+</button>
                                                 </div>
                                             </div>
                                             <button class="main-btn  close111" type="button" onclick="">Apply</button>
                                         </div>
                                    </div>
                                    <div id="dev-new-data-area"></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="ovabrw-book-now" id="submit-button-dev-data" style="display: none;" >
                                                <button type="submit" class="main-btn">
                                                <span> Book Now</span></button>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Or<br>Contact Owner</p>
                                    <p><a href="mailto:{{$data->email ?? $setting_data['email'] }}"><i class="fa-solid fa-envelope"></i> {{$data->email ?? $setting_data['email'] }}</a></p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<div class="side-map">
    <div class="container-fluid">
        @if($data->map)
            <iframe src="{!! $data->map !!}" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
    </div>
</div>
@stop
@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/assets/fancybox/jquery.fancybox.min.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-detail.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/property-detail-responsive.css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('datepicker') }}/dist/css/hotel-datepicker.css"/>
    <link rel="stylesheet" href="{{ asset('front')}}/css/datepicker.css" />
 
    <style>
        /* Gallery specific styles */
        .button1 {
   background: #F3F4F6 !important;
    border-color: #9CA3AF;
}
        /* Desktop gallery grid */
        .gallery-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-gap: 0.5rem;
            border-radius: 1rem;
        }

        .main-gallery-image {
            grid-column: 1;
            grid-row: 1 / span 2;
            position: relative;
            cursor: pointer;
            height: 430px;
        }

        .secondary-gallery-image {
            position: relative;
            cursor: pointer;
            height: 212px;
        }

        .gallery-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0;
        }
        
        /* Medium screen sizes */
        @media (max-width: 992px) and (min-width: 768px) {
            .main-gallery-image {
                height: 300px !important;
            }
            .secondary-gallery-image {
                height: 146px !important;
            }
            .show-all-btn {
                right: 6px !important;
            }
        }

        /* Mobile gallery styling */
        .mobile-gallery {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .mobile-gallery .main-gallery-image {
            height: 250px !important;
            width: 100%;
        }

        .mobile-gallery .secondary-gallery-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 0.5rem;
        }

        .mobile-gallery .secondary-gallery-image {
            height: 120px !important;
        }

        .mobile-gallery .gallery-img {
            border-radius: 8px;
        }
        
        /* Make sure the .mobile-main-img and .mobile-secondary-img look good */
        .mobile-main-img, .mobile-secondary-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .show-all-btn {
            position: absolute;
            bottom: 20px;
            right: 20px;
            z-index: 5;
        }
        
        /* Mobile show-all button position */
        @media (max-width: 767px) {
            .show-all-btn {
                bottom: 10px;
                right: 10px;
            }
        }
        
        .show-all-btn button {
            background-color: rgba(108, 117, 125, 0.8);
            font-weight: 500;
        }
        
        .thumbnail-img {
            width: 100%;
            height: 96px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .active-thumbnail {
            border: 2px solid #0d6efd;
        }
        
        .bg-overlay {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Lightbox styles */
        .lightbox-modal {
            position: fixed;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.95);
            z-index: 1040;
            display: none;
        }

        .lightbox-progress {
            position: absolute;
            top: 0;
            left: 0;
            height: 4px;
            background-color: #0d6efd;
            z-index: 1010;
            display: none;
        }

        .lightbox-controls {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
        }
        
        /* Thumbnail panel styles */
        .thumbnail-panel {
            position: fixed;
            top: 0;
            right: 0;
            height: 100%;
            width: 300px;
            background-color: rgba(255, 255, 255, 0.95);
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            overflow-y: auto;
            border-left: 1px solid #dddddd;
        }
        
        .thumbnail-hidden {
            transform: translateX(100%);
        }
        
        #thumbnailGrid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        
        .cursor-pointer {
            cursor: pointer;
        }

        .lightbox-main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 0 4rem;
        }

        .lightbox-nav-btn {
            position: absolute;
            color: black;
            cursor: pointer;
            z-index: 20;
        }

        .lightbox-prev {
            left: 1.5rem;
        }

        .lightbox-next {
            right: 1.5rem;
        }

        .lightbox-image-container {
            max-width: 1280px;
            max-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .lightbox-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        /* Show all modal */
        .show-all-modal {
            position: fixed;
            inset: 0;
            background-color: rgba(255, 255, 255, 0.95);
            z-index: 40;
            display: none;
            overflow-y: auto;
        }

        .all-images-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .all-images-grid img {
            border-radius: 8px;
        }

        @media (min-width: 768px) {
            .all-images-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (min-width: 1024px) {
            .all-images-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 1280px) {
            .all-images-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Apply specific corner roundings */
        .left-rounded {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        
        .top-right-rounded {
            border-top-right-radius: 8px;
        }
        
        .bottom-right-rounded {
            border-bottom-right-radius: 8px;
        }
        
        /* Animation for slideshow */
        .slideshow-active {
            animation: fadeInOut 0.5s ease-in-out;
        }

        @keyframes fadeInOut {
            0% {
                opacity: 0.7;
            }

            100% {
                opacity: 1;
            }
        }
        div#book button.main-btn.close111 {
            background: black !important;
           color: white;
        }
        .ovabrw-book-now .main-btn {
             background: black !important;
           color: white;
        }
        .datepicker__clear-button {
            color: black !important;
        }
        .datepicker__close-button {
            color: black !important;
        }
    </style>
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/assets/fancybox/jquery.fancybox.min.js" ></script>
    <script src="{{ asset('front')}}/js/property-detail.js" ></script>
    <script src="{{ asset('front')}}/js/gallery.js" ></script>
    <script src="{{ asset('front')}}/js/gallery-events.js" ></script>
    <script>
        // Quick navigation functionality
        $(document).ready(function() {
            // Setup smooth scrolling navigation for desktop
            $('.quick-nav-link').on('click', function(e) {
                e.preventDefault();
                
                const targetId = $(this).attr('href');
                const targetSection = $(targetId);
                
                if (targetSection.length) {
                    $('html, body').animate({
                        scrollTop: targetSection.offset().top - 100
                    }, 800);
                }
            });
            
            // Handle mobile navigation dropdown change
            $('#mobileNavSelect').on('change', function() {
                const targetId = $(this).val();
                if (targetId) {
                    const targetSection = $(targetId);
                    
                    if (targetSection.length) {
                        // Scroll to the selected section
                        $('html, body').animate({
                            scrollTop: targetSection.offset().top - 80
                        }, 800);
                        
                        // Update the dropdown to show current section
                        updateMobileNavSelection(targetId.substring(1));
                    }
                }
            });
            
            // Highlight active section on scroll
            $(window).on('scroll', function() {
                const scrollPosition = $(window).scrollTop();
                
                $('.section-scroll').each(function() {
                    const sectionTop = $(this).offset().top - 150;
                    const sectionBottom = sectionTop + $(this).outerHeight();
                    const sectionId = $(this).attr('id');
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionBottom) {
                        // Update desktop navigation
                        $('.quick-nav-link').removeClass('active-section');
                        $(`.quick-nav-link[href="#${sectionId}"]`).addClass('active-section');
                        
                        // Update mobile dropdown
                        updateMobileNavSelection(sectionId);
                    }
                });
            });
            
            // Function to update mobile navigation dropdown
            function updateMobileNavSelection(sectionId) {
                if (sectionId) {
                    $('#mobileNavSelect').val(`#${sectionId}`);
                    
                    // Update the select styling to show it's active
                    const selectedOption = $(`#mobileNavSelect option[value="#${sectionId}"]`);
                    if (selectedOption.length) {
                        $('.mobile-nav-wrapper').css('border-color', '#cc6119');
                        
                        // Update the mobile nav dropdown text with current section
                        const sectionText = selectedOption.text();
                        $('.mobile-nav-current').text(sectionText);
                        
                        // Add visual highlight to the active selection
                        $('#mobileNavSelect').attr('data-active', sectionId);
                    }
                }
            }
            
            // Initialize with the first visible section on page load
            $(window).trigger('scroll');
            
            // Show More/Less functionality
            $('.show-more-btn').on('click', function() {
                const section = $(this).data('section');
                const shortContent = $(`.${section}-short`);
                const fullContent = $(`.${section}-full`);
                
                if (fullContent.hasClass('hidden-content')) {
                    // Show full content
                    fullContent.removeClass('hidden-content');
                    shortContent.hide();
                    $(this).html('Show Less <i class="fas fa-chevron-up"></i>');
                } else {
                    // Show short content
                    fullContent.addClass('hidden-content');
                    shortContent.show();
                    $(this).html('Show More <i class="fas fa-chevron-down"></i>');
                }
            });
            
            // Sticky booking widget behavior
            const bookingWidget = $('.sticky-booking-widget');
            
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 100) {
                    bookingWidget.css('box-shadow', '0 4px 12px rgba(0,0,0,0.1)');
                } else {
                    bookingWidget.css('box-shadow', 'none');
                }
            });
            
            // Mobile Book Now button
            $('.book1, .sticky-booking-mobile').on('click', function(e) {
                e.preventDefault();
                $('#book').get(0).scrollIntoView({ behavior: 'smooth' });
                
                // Highlight the booking widget
                const originalBoxShadow = bookingWidget.css('box-shadow');
                bookingWidget.css('transition', 'box-shadow 0.3s ease');
                bookingWidget.css('box-shadow', '0 0 20px rgba(0,0,0,0.7)');
                
                setTimeout(() => {
                    bookingWidget.css('box-shadow', originalBoxShadow);
                }, 2000);
            });
            
            // Add mobile booking button
            if ($(window).width() < 768) {
                if ($('.sticky-booking-mobile').length === 0) {
                    $('body').append('<div class="sticky-booking-mobile">Book Now - $249/night</div>');
                }
            }
            
            // Handle window resize
            $(window).resize(function() {
                if ($(window).width() < 768) {
                    if ($('.sticky-booking-mobile').length === 0) {
                        $('body').append('<div class="sticky-booking-mobile">Book Now - $249/night</div>');
                    }
                } else {
                    $('.sticky-booking-mobile').remove();
                }
            });
        });
    </script>
    <script>
        // Initialize gallery images array for use by gallery.js
        window.galleryImages = [
            @foreach($pictures as $index => $picture)
                { 
                    src: '{{ $picture['original'] }}', 
                    alt: '{{ $picture['caption'] ?? "" }}' 
                }@if(!$loop->last),@endif
            @endforeach
        ];
        
        // Declare all gallery functions in global scope first to ensure they're available
        // when HTML elements with onclick attributes are rendered
        window.openLightbox = function(index) {
            currentImageIndex = index;
            document.getElementById('lightboxModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
            updateLightboxImage();
            populateThumbnails();
        };
        
        window.closeLightbox = function() {
            document.getElementById('lightboxModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            stopSlideshow();
            exitFullscreen();
        };
        
        window.openShowAll = function() {
            const modal = document.getElementById('showAllModal');
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            
            // Populate all images
            const allImagesGrid = document.getElementById('allImagesGrid');
            allImagesGrid.innerHTML = '';
            
            images.forEach((image, index) => {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'position-relative';
                imgContainer.innerHTML = `
                    <img src="${image.src}" alt="${image.alt}" class="w-100 rounded" onclick="openLightbox(${index})">
                    <p class="mt-2">${image.alt}</p>
                `;
                allImagesGrid.appendChild(imgContainer);
            });
        };
        
        window.closeShowAll = function() {
            document.getElementById('showAllModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        };
        
        window.nextImage = function() {
            const lightboxImage = document.getElementById('lightboxImage');
            lightboxImage.classList.remove('slideshow-active');
            void lightboxImage.offsetWidth;
            currentImageIndex = (currentImageIndex + 1) % images.length;
            updateLightboxImage();
            lightboxImage.classList.add('slideshow-active');
        };
        
        window.previousImage = function() {
            const lightboxImage = document.getElementById('lightboxImage');
            lightboxImage.classList.remove('slideshow-active');
            void lightboxImage.offsetWidth;
            currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
            updateLightboxImage();
            lightboxImage.classList.add('slideshow-active');
        };
        
        window.startSlideshow = function() {
            if (!isSlideshow) {
                isSlideshow = true;
                document.getElementById('slideshowIcon').classList.remove('fa-play');
                document.getElementById('slideshowIcon').classList.add('fa-pause');
                document.getElementById('progressBar').style.display = 'block';
                let progress = 0;
                const progressBar = document.getElementById('progressBar');
                const slideDuration = 3000;
                const updateInterval = 20;
                const increment = (updateInterval / slideDuration) * 100;
                slideshowInterval = setInterval(() => {
                    progress += increment;
                    progressBar.style.width = `${progress}%`;
                    if (progress >= 100) {
                        progress = 0;
                        progressBar.style.width = '0%';
                        const lightboxImage = document.getElementById('lightboxImage');
                        lightboxImage.classList.add('slideshow-active');
                        nextImage();
                        setTimeout(() => {
                            lightboxImage.classList.remove('slideshow-active');
                        }, 500);
                    }
                }, updateInterval);
            } else {
                stopSlideshow();
            }
        };
        
        window.toggleFullscreen = function() {
            if (!isFullscreen) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                }
                document.getElementById('fullscreenIcon').classList.remove('fa-expand');
                document.getElementById('fullscreenIcon').classList.add('fa-compress');
                isFullscreen = true;
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
                document.getElementById('fullscreenIcon').classList.remove('fa-compress');
                document.getElementById('fullscreenIcon').classList.add('fa-expand');
                isFullscreen = false;
            }
        };
        
        window.toggleThumbnails = function() {
            const panel = document.getElementById('thumbnailPanel');
            if (!thumbnailsVisible) {
                panel.classList.remove('thumbnail-hidden');
                thumbnailsVisible = true;
            } else {
                panel.classList.add('thumbnail-hidden');
                thumbnailsVisible = false;
            }
        };

        // Create the images array from PHP data
        const images = [
            @foreach($pictures as $index => $picture)
                { 
                    src: '{{ $picture['original'] }}', 
                    alt: '{{ $picture['caption'] ?? "" }}' 
                }@if(!$loop->last),@endif
            @endforeach
        ];

        // Gallery state variables
        let currentImageIndex = 0;
        let isSlideshow = false;
        let slideshowInterval;
        let isFullscreen = false;
        let thumbnailsVisible = false;

        // Helper functions that don't need to be in global scope
        function updateLightboxImage() {
            const lightboxImage = document.getElementById('lightboxImage');
            if (lightboxImage) {
                lightboxImage.src = images[currentImageIndex].src;
                lightboxImage.alt = images[currentImageIndex].alt;
                const counter = document.getElementById('imageCounter');
                if (counter) {
                    counter.textContent = `${currentImageIndex + 1} / ${images.length}`;
                }
                updateActiveThumbnail();
            }
        }
        
        function populateThumbnails() {
            const thumbnailGrid = document.getElementById('thumbnailGrid');
            if (!thumbnailGrid) return;
            
            thumbnailGrid.innerHTML = '';
            images.forEach((image, index) => {
                const thumbnailDiv = document.createElement('div');
                thumbnailDiv.className = 'position-relative cursor-pointer thumbnail-hover';
                thumbnailDiv.onclick = () => selectThumbnail(index);
                thumbnailDiv.innerHTML = `
                    <img src="${image.src}" alt="${image.alt}" class="w-100 thumbnail-img rounded ${index === currentImageIndex ? 'active-thumbnail' : ''}">
                    ${index === currentImageIndex ? '<div class="position-absolute top-0 start-0 w-100 h-100 bg-overlay rounded"></div>' : ''}
                `;
                thumbnailGrid.appendChild(thumbnailDiv);
            });
        }
        
        function updateActiveThumbnail() {
            const thumbnails = document.querySelectorAll('#thumbnailGrid > div');
            thumbnails.forEach((thumb, index) => {
                const img = thumb.querySelector('img');
                const overlay = thumb.querySelector('div');
                if (index === currentImageIndex) {
                    if (img) img.classList.add('active-thumbnail');
                    if (!overlay) {
                        thumb.innerHTML += '<div class="position-absolute top-0 start-0 w-100 h-100 bg-overlay rounded"></div>';
                    }
                } else {
                    if (img) img.classList.remove('active-thumbnail');
                    if (overlay) {
                        overlay.remove();
                    }
                }
            });
        }
        
        function selectThumbnail(index) {
            currentImageIndex = index;
            updateLightboxImage();
        }
        
        function stopSlideshow() {
            isSlideshow = false;
            clearInterval(slideshowInterval);
            const slideshowIcon = document.getElementById('slideshowIcon');
            if (slideshowIcon) {
                slideshowIcon.classList.remove('fa-pause');
                slideshowIcon.classList.add('fa-play');
            }
            const progressBar = document.getElementById('progressBar');
            if (progressBar) {
                progressBar.style.display = 'none';
                progressBar.style.width = '0%';
            }
        }
        
        function exitFullscreen() {
            if (isFullscreen && document.exitFullscreen) {
                document.exitFullscreen();
                const fullscreenIcon = document.getElementById('fullscreenIcon');
                if (fullscreenIcon) {
                    fullscreenIcon.classList.remove('fa-compress');
                    fullscreenIcon.classList.add('fa-expand');
                }
                isFullscreen = false;
            }
        }
        
        function handleKeyDown(e) {
            const lightboxModal = document.getElementById('lightboxModal');
            if (!lightboxModal || lightboxModal.style.display === 'none') return;

            switch (e.key) {
                case 'ArrowLeft':
                    previousImage();
                    break;
                case 'ArrowRight':
                case ' ':
                    e.preventDefault();
                    nextImage();
                    break;
                case 'Escape':
                    closeLightbox();
                    break;
                case 'f':
                case 'F':
                    toggleFullscreen();
                    break;
            }
        }

        // Initialize everything when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Make sure thumbnailPanel starts hidden
            const thumbnailPanel = document.getElementById('thumbnailPanel');
            if (thumbnailPanel) {
                thumbnailPanel.classList.add('thumbnail-hidden');
            }
            
            // Set up event listeners for keyboard navigation
            window.addEventListener('keydown', handleKeyDown);
            document.addEventListener('fullscreenchange', function() {
                isFullscreen = !!document.fullscreenElement;
                const fullscreenIcon = document.getElementById('fullscreenIcon');
                if (fullscreenIcon) {
                    if (isFullscreen) {
                        fullscreenIcon.classList.remove('fa-expand');
                        fullscreenIcon.classList.add('fa-compress');
                    } else {
                        fullscreenIcon.classList.remove('fa-compress');
                        fullscreenIcon.classList.add('fa-expand');
                    }
                }
            });
        });
    </script>
    <script>
    function functiondec($getter_setter,$show,$cal){
        $("#submit-button-dev-data").hide();
        val=parseInt($($getter_setter).val());
        if(val>0){
            val=val-1;
        }
        $($getter_setter).val(val);
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
        ajaxCallingData();
    }
    function functioninc($getter_setter,$show,$cal){
        $("#submit-button-dev-data").hide();
        val=parseInt($($getter_setter).val());
        val=val+1;
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
        ajaxCallingData();
    }
</script>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Days</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body" id="dev-new-modal-days-area">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal1">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Additional Fee</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <!-- Modal body -->
      <div class="modal-body" id="dev-new-modal-service-area">
        Modal body..
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
    function clearDataForm(){
        $("#start_date").val('');
        $("#end_date").val('');
        $("#submit-button-dev-data").hide();
        $("#dev-new-modal-days-area").html('');
        $("#dev-new-modal-service-area").html('');
        $("#dev-new-data-area").html('');
    }
    $(document).on("change","#pet_fee_data_guarav",function(){
        ajaxCallingData();
    });
    $(document).on("change","#heating_pool_fee_data_guarav",function(){
        ajaxCallingData();
    });
    function ajaxCallingData(){
        $("#dev-new-modal-days-area").html(null);
        $("#dev-new-modal-service-area").html(null);
        $("#dev-new-data-area").html(null);
        $("#submit-button-dev-data").hide();
        pet_fee_data_guarav=$("#pet_fee_data_guarav").val();
        heating_pool_fee_data_guarav=$("#heating_pool_fee_data_guarav").val();
        adults=$("#adults-data").val();
        childs=$("#child-data").val();
        total_guests=parseInt(adults)+parseInt(childs);
        if(total_guests>0){
             if($("#txtFrom").val()!=""){
                 if($("#txtTo").val()!=""){
                     $.post("{{route('checkajax-get-quote')}}",{start_date:$("#start_date").val(),end_date:$("#end_date").val(),heating_pool_fee_data_guarav:heating_pool_fee_data_guarav,pet_fee_data_guarav:pet_fee_data_guarav,adults:adults,childs:childs,book_sub:true,property_id:{{ $data->id }}},function(data){
                        if(data.status==400){
                            $("#dev-new-modal-days-area").html(null);
                            $("#dev-new-modal-service-area").html(null);
                            $("#dev-new-data-area").html(null);
                            $("#submit-button-dev-data").hide();
                            toastr.error(data.message);
                        }else{
                            $("#submit-button-dev-data").show();
                            $("#dev-new-modal-days-area").html(data.modal_day_view);
                            $("#dev-new-modal-service-area").html(data.modal_service_view);
                            $("#dev-new-data-area").html(data.data_view);
                        }
                    });
                 }
             }
        }else{
            $("#dev-new-modal-days-area").html(null);
            $("#dev-new-modal-service-area").html(null);
            $("#dev-new-data-area").html(null);
            $("#submit-button-dev-data").hide();
        }
    }
    </script>
    <script src="{{ asset('datepicker') }}/node_modules/fecha/dist/fecha.min.js"></script>
    <script src="{{ asset('datepicker') }}/dist/js/hotel-datepicker.js"></script>
    <script>
    @php
        $new_data_blocked=LiveCart::iCalDataCheckInCheckOutCheckinCheckout($data->id);
        $checkin=json_encode($new_data_blocked['checkin']);
        $checkout=json_encode($new_data_blocked['checkout']);
        $blocked=json_encode($new_data_blocked['blocked']);
    @endphp
    var checkin = <?php echo $checkin;  ?>;
    var checkout = <?php echo ($checkout);  ?>;
    var blocked= <?php echo ($blocked);  ?>;
    
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
                minNights: {{ $data->terms_min_night }},
                endDate: "{{date('Y-m-d', strtotime('+30 months'))}}",
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
                        ajaxCallingData();
                    }
                },
                clearButton:function(){ return true;}
            }
        );
        @if(Request::get("start_date"))
            @if(Request::get("end_date"))
                setTimeout(function(){$("#demo17").val("{{ request()->start_date }} - {{ request()->end_date }}");document.getElementById("start_date").value ="{{ request()->start_date }}";document.getElementById("end_date").value ="{{ request()->end_date }}";ajaxCallingData();},1000);
            @endif
        @endif
    })();
    $(document).on("click","#clear",function(){$("#clear-demo17").click();});
    x=document.getElementById("month-2-demo17");
    x.querySelector(".datepicker__month-button--next").addEventListener("click", function(){y=document.getElementById("month-1-demo17");y.querySelector(".datepicker__month-button--next").click();})  ;
    x=document.getElementById("month-1-demo17");
    x.querySelector(".datepicker__month-button--prev").addEventListener("click", function(){y=document.getElementById("month-2-demo17");y.querySelector(".datepicker__month-button--prev").click();})  ;
    function getDateData(objectDate){let day = objectDate.getDate();let month = objectDate.getMonth()+1;let year = objectDate.getFullYear();if (day < 10) {day = '0' + day;}if (month < 10) {month = `0${month}`;}format1 = `${year}-${month}-${day}`;return  format1 ;}  
</script>

<!-- Mobile Booking Button (added for mobile devices) -->
<div class="sticky-booking-mobile d-md-none">
    <span>Book Now</span>
</div>
@stop 
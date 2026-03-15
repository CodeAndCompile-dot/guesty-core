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

@endphp

@php
$list=App\Models\AttractionCategory::orderBy("id","asc")->paginate(100);
@endphp
<style>
    /* Improved Styles for Attractions Page */

.how-we-value-wrapp.atr {
    padding: 40px 0;
    background-color: #f9f9f9;
}

.section-heading {
    text-align: center;
    margin-bottom: 50px;
}

.section-heading h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
    font-weight: 600;
}

.section-heading p {
    max-width: 700px;
    margin: 0 auto;
    color: #666;
}

.attractions-row {
    margin-left: -15px;
    margin-right: -15px;
}

.atrr {
    margin-bottom: 40px;
    padding: 0 15px;
}

.img-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    height: 320px; /* Fixed height for consistent desktop view */
}

.img-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.img-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.img-card:hover img {
    transform: scale(1.05);
}

.img-card .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.3);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.img-card:hover .overlay {
    opacity: 1;
}

.atr-cont {
    padding: 15px 0;
    text-align: center;
}

.atr-cont h4 {
    font-size: 20px;
    margin: 0;
    color: #333;
    font-weight: 600;
    transition: color 0.3s ease;
    line-height: 1.3;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.atr-cont a {
    text-decoration: none;
    display: block;
}

.atr-cont a:hover h4 {
    color: #007bff;
}

/* Better responsive adjustments */
@media (min-width: 1400px) {
    .container {
        max-width: 1320px;
    }
    
    .img-card {
        height: 580px;
    }
    
    /* Desktop-specific grid adjustments */
    .large-desktop-view .atrr {
        padding: 0 20px;
    }
    
    .attractions-row {
        display: flex;
        flex-wrap: wrap;
    }
    
    /* Better spacing for larger screens */
    .section-heading {
        margin-bottom: 60px;
    }
}

@media (min-width: 1200px) and (max-width: 1399px) {
    .img-card {
        height: 350px;
    }
    
    /* Enhanced desktop layout */
    .attractions-row {
        margin-left: -20px;
        margin-right: -20px;
    }
    
    .atrr {
        padding: 0 20px;
        margin-bottom: 40px;
    }
}

@media (min-width: 992px) and (max-width: 1199px) {
    .img-card {
        height: 320px;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .img-card {
        height: 280px;
    }
}

@media (max-width: 767px) {
    .how-we-value-wrapp.atr {
        padding: 20px 0;
    }
    
    .img-card {
        height: 250px;
    }
}

/* Desktop-specific enhancements */
@media (min-width: 1200px) {
    /* Improved card hover effects for desktop */
    .img-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
    }
    
    /* Better text alignment and spacing */
    .atr-cont {
        padding: 18px 10px;
    }
    
    /* Ensure consistent grid layout */
    .attractions-row {
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    }
    
    .atrr {
        display: flex;
        flex-direction: column;
    }
    
    .img-card {
        flex: 1;
        margin-bottom: 15px;
    }
}
</style>
<section class="how-we-value-wrapp atr">
    <div class="container">
        <div class="section-heading">
            <h2>Explore Attractions</h2>
            <p>Discover the best attractions and activities in the area for your next vacation.</p>
        </div>
        <div class="row attractions-row">
            @foreach($list as $c)
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 atrr">
                <div class="img-card">
                  <a href="{{  url('attractions/category/'.$c->seo_url) }}">
                     @if($c->image)
                     <img src="{{ asset($c->image)}}" class="img-fluid" alt="{{ $c->name }}">
                     @endif
                     <div class="overlay"></div>
                  </a>
                </div>
                <div class="atr-cont">
                    <a href="{{  url('attractions/category/'.$c->seo_url) }}"><h4>{{ $c->name }}</h4></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
{!! $data->seo_section !!}
@stop
@section("css")
@parent

@stop
@section("js")
@parent
<script src="{{ asset('front')}}/js/attraction.js" ></script>
<script>
    // Enhanced JavaScript for Attractions Page

document.addEventListener('DOMContentLoaded', function() {
    // Add touch support for mobile devices
    const attractionCards = document.querySelectorAll('.atrr .img-card');
    
    if (attractionCards.length > 0) {
        attractionCards.forEach(card => {
            // Touch support for mobile
            card.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            card.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.classList.remove('touch-active');
                }, 300);
            });
            
            // Enhanced hover effects for desktop
            card.addEventListener('mouseenter', function() {
                this.classList.add('hover-active');
                // Add smooth animation for overlay
                const overlay = this.querySelector('.overlay');
                if (overlay) {
                    overlay.style.opacity = '1';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                this.classList.remove('hover-active');
                // Remove overlay animation
                const overlay = this.querySelector('.overlay');
                if (overlay) {
                    overlay.style.opacity = '0';
                }
            });
        });
    }
    
    // Add lazy loading for images to improve performance
    if ('loading' in HTMLImageElement.prototype) {
        const images = document.querySelectorAll('.img-card img');
        images.forEach(img => {
            img.setAttribute('loading', 'lazy');
        });
    }
    
    // Grid layout adjustment for better desktop view
    function adjustGridLayout() {
        const container = document.querySelector('.attractions-row');
        if (container) {
            const viewportWidth = window.innerWidth;
            if (viewportWidth >= 1400) {
                // Ensure proper spacing for large desktop displays
                container.classList.add('large-desktop-view');
            } else {
                container.classList.remove('large-desktop-view');
            }
        }
    }
    
    // Run on load and resize
    adjustGridLayout();
    window.addEventListener('resize', adjustGridLayout);
});
</script>
@stop

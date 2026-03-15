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

    <style>
      /* Partner Page Specific Styles */
      /* Additional Card Improvements */
      .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 50%);
        opacity: 0;
        transition: opacity 0.3s ease;
      }
      
      .partner-card:hover .card-overlay {
        opacity: 1;
      }
      
      /* Animation for cards */
      @keyframes cardFadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }
      
      .partner-card {
        animation: cardFadeIn 0.6s ease forwards;
        opacity: 0;
      }
      
      .col-md-6:nth-child(1) .partner-card { animation-delay: 0.1s; }
      .col-md-6:nth-child(2) .partner-card { animation-delay: 0.2s; }
      .col-md-6:nth-child(3) .partner-card { animation-delay: 0.3s; }
      .col-md-6:nth-child(4) .partner-card { animation-delay: 0.4s; }
      .col-md-6:nth-child(5) .partner-card { animation-delay: 0.5s; }
      .col-md-6:nth-child(6) .partner-card { animation-delay: 0.6s; }
      
      /* Hero Section */
      .partner-hero {
        background-color: var(--highlight-bg);
        padding: 80px 0 60px;
        margin-bottom: 40px;
        color: var(--text-light);
        text-align: center;
        position: relative;
        overflow: hidden;
      }

      .partner-hero h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 600;
        position: relative;
        z-index: 1;
      }

      .partner-hero p {
        max-width: 800px;
        margin: 0 auto;
        font-size: 1.2rem;
        color: var(--grey-light);
      }

      .partner-card {
        background-color: var(--text-light);
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        height: 100%;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
      }

      .partner-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
      }

      .partner-card-img {
        height: 350px;
        overflow: hidden;
        position: relative;
      }

      .partner-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
      }

      .partner-card:hover .partner-card-img img {
        transform: scale(1.08);
      }

      .partner-card-content {
        padding: 25px 20px 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1;
        gap: 15px;
      }

      .partner-card-title {
        font-size: 1.5rem;
        margin-bottom: 0px;
        font-weight: 600;
        color: var(--primary-accent, #333);
        line-height: 1.3;
      }

      .partner-card-text {
        color: var(--grey-dark);
        margin-bottom: 0px;
        font-size: 1rem;
        line-height: 1.7;
        flex-grow: 1;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      .see-more-btn {
        background-color: var(--cta-button, #e74c3c);
        color: var(--text-light);
        border: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        font-size: 1rem;
        text-align: center;
        align-self: flex-start; /* Align button to the left in flex container */
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      }

      .see-more-btn:hover {
        background-color: var(--primary-accent, #c0392b);
        color: var(--text-light);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      }

      .coming-soon {
        position: absolute;
        top: 15px;
        right: 15px;
        background-color: var(--highlight-bg);
        color: var(--text-light);
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        z-index: 10;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
      }

      .partner-intro {
        text-align: center;
        max-width: 900px;
       
      }

      .partner-intro p {
        color: var(--grey-dark);
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: center !important;
        /*margin-bottom: 30px;*/
      }

      /* Desktop-specific improvements */
      @media (min-width: 992px) {
    
       

        .partner-card-title {
          font-size: 1.7rem;
          height: auto; /* Will be equalized by JS */
          min-height: 60px;
        }

        .partner-card-text {
          font-size: 1rem;
          height: auto; /* Will be equalized by JS */
          min-height: 120px;
        }

        /* Equal height cards */
        .row {
          display: flex;
          flex-wrap: wrap;
          margin: 0 -15px;
        }
        
        .col-md-6, .col-xl-4 {
          padding: 0 15px;
          display: flex;
        }
        
        .partner-card {
          flex-direction: column;
          width: 100%;
        }
      }

   
      /* Tablet styling */
      @media (max-width: 991px) and (min-width: 769px) {
       
        
        .partner-card-title {
          font-size: 1.4rem;
        }
      }
      
      /* Mobile styling */
      @media (max-width: 768px) {
        .partner-hero {
          padding: 40px 0;
        }
        
        .partner-hero h1 {
          font-size: 2.2rem;
        }

        .partner-hero p {
          font-size: 1rem;
          padding: 0 15px;
        }
        
        .partner-intro p {
          font-size: 1rem;
          padding: 0 15px;
        }

    
        
        .partner-card-content {
          padding: 20px 15px;
        }
        
        .partner-card-title {
          font-size: 1.3rem;
          margin-bottom: 10px;
        }
        
        .partner-card-text {
          margin-bottom: 15px;
          -webkit-line-clamp: 3;
        }
        
        .see-more-btn {
          width: 100%;
          padding: 10px 20px;
        }
      }
      
      /* Small mobile devices */
      @media (max-width: 480px) {
        .partner-hero h1 {
          font-size: 1.8rem;
        }
        
       
      }
    </style>
    <!-- Partner Hero Section -->
    <section class="partner-hero">
      <div class="container">
        <h1>EXCLUSIVE DEALS FOR BENTONVILLE LODGING GUESTS</h1>
        <p>
          Our partners offer special discounts and unique experiences to enhance
          your stay in NWA
        </p>
      </div>
    </section>

    <!-- Partner Content Section -->
    <section class="container">
      <div class="partner-intro mx-auto">
        <p>
          A trip to NWA is not simply just about where you stay, but its about
          making most of the incredible area. From biking the endless trails, to
          fantastic dining, arts, culture, and more we have partnered up with
          some fantastic businesses to bring you more value on your stay in NWA!
        </p>
        <p>
          Below are our wonderful partners offering you discounts, free food
          items, or other great deals. When you book with us you will get access
          to a digital guide book where you can find your unique codes to
          receive discounts and more. Additionally, this information is included
          in every home with QR codes to easily access the deals.
        </p>
      </div>
    </section>

    <!-- Partner Cards Section -->
    <section class="partner-cards-section">
      <div class="container">
        <div class="row">
          <!-- Card 1 -->
          <div class="col-md-6 col-xl-4 mb-4">
            <div class="partner-card">
              <div class="partner-card-img">
                <img
                 src="{{asset('front/images/riseandride.avif')}}"
                  alt="Rise and Ride Bike Rentals"
                  loading="lazy"
                />
                <div class="card-overlay"></div>
              </div>
              <div class="partner-card-content">
                <div>
                  <h3 class="partner-card-title">Rise and Ride Bike Rentals</h3>
                  <p class="partner-card-text">
                    Hit the trails and save $20 with Rise and Ride E-bike rentals!
                    Also enjoy free delivery to any of our properties in
                    Bentonville.
                  </p>
                </div>
                <a href="https://riseandridebike.com/" class="see-more-btn">See More</a>
              </div>
            </div>
          </div>

          <!-- Card 2 -->
          <div class="col-md-6 col-xl-4 mb-4">
            <div class="partner-card">
              <div class="partner-card-img">
                <img
                 src="{{asset('front/images/confier.avif')}}"
                  alt="Conifer"
                  loading="lazy"
                />
                <div class="card-overlay"></div>
              </div>
              <div class="partner-card-content">
                <div>
                  <h3 class="partner-card-title">Conifer</h3>
                  <p class="partner-card-text">
                    Staying with Bentonville Lodging Co and looking for a fine
                    dining experience? Enjoy a free appetizer with your meal
                    courtesy of Chef Matt Cooper at Conifer!
                  </p>
                </div>
                <a href="https://www.coniferbentonville.com/" class="see-more-btn">See More</a>
              </div>
            </div>
          </div>

          <!-- Card 3 -->
          <div class="col-md-6 col-xl-4 mb-4">
            <div class="partner-card">
              <div class="partner-card-img">
                <img
                  src="{{asset('front/images/nwa-exec-clean.avif')}}"
                  alt="Executive Cleaning Co"
                  loading="lazy"
                />
                <div class="card-overlay"></div>
              </div>
              <div class="partner-card-content">
                <div>
                  <h3 class="partner-card-title">NWA Executive Cleaning Co</h3>
                  <p class="partner-card-text">
                    Whether a guest or owner with us, our exclusive cleaning
                    partners at NWA Executive clean will provide you a personal
                    residence clean at a discount!
                  </p>
                </div>
                <a href="https://www.nwaexecutivecleaning.com/" class="see-more-btn">See More</a>
              </div>
            </div>
          </div>

         
        
        </div>
      </div>
    </section>
    
    <!-- Bootstrap & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- Inline JavaScript for responsive behavior -->
    <script>
      $(document).ready(function() {
        // Function to equalize card heights in desktop view
        function equalizeCardHeights() {
          if (window.innerWidth >= 992) {
            // Group cards by rows
            let rows = {};
            $('.partner-card').each(function() {
              let top = $(this).offset().top;
              if (!rows[top]) {
                rows[top] = [];
              }
              rows[top].push($(this));
            });
            
            // Process each row separately
            $.each(rows, function(top, cards) {
              // Reset heights first
              $(cards).find('.partner-card-title').css('height', 'auto');
              $(cards).find('.partner-card-text').css('height', 'auto');
              
              // Get maximum heights in this row
              let maxTitleHeight = 0;
              let maxTextHeight = 0;
              
              $(cards).each(function() {
                let titleHeight = $(this).find('.partner-card-title').outerHeight();
                let textHeight = $(this).find('.partner-card-text').outerHeight();
                
                maxTitleHeight = Math.max(maxTitleHeight, titleHeight);
                maxTextHeight = Math.max(maxTextHeight, textHeight);
              });
              
              // Apply equal heights to this row
              $(cards).each(function() {
                $(this).find('.partner-card-title').css('height', maxTitleHeight + 'px');
                $(this).find('.partner-card-text').css('height', maxTextHeight + 'px');
              });
            });
          } else {
            // Reset for mobile
            $('.partner-card-title').css('height', 'auto');
            $('.partner-card-text').css('height', 'auto');
          }
        }
        
        // Function for lazy loading images
        function lazyLoadImages() {
          const imgObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                const img = entry.target;
                const src = img.getAttribute('data-src');
                if (src) {
                  img.setAttribute('src', src);
                  img.removeAttribute('data-src');
                }
                observer.unobserve(img);
              }
            });
          }, { rootMargin: '50px' });
          
          document.querySelectorAll('img[data-src]').forEach(img => {
            imgObserver.observe(img);
          });
        }
        
        // Run on page load with slight delay to ensure content is rendered
        setTimeout(function() {
          equalizeCardHeights();
          lazyLoadImages();
        }, 100);
        
        // Run on window resize with debounce
        let resizeTimer;
        $(window).resize(function() {
          clearTimeout(resizeTimer);
          resizeTimer = setTimeout(equalizeCardHeights, 250);
        });
        
        // Re-equalize heights after images load
        $('.partner-card-img img').on('load', function() {
          equalizeCardHeights();
        });
      });
    </script>

   {!! $data->seo_section !!}
@stop
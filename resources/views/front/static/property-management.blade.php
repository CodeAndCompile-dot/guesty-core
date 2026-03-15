@extends("front.layouts.master")
@section("title",$data->meta_title)
@section("keywords",$data->meta_keywords)
@section("description",$data->meta_description)
@section("logo",$data->image)
@section("header-section")
{!! $data->header_section !!}
<!-- Add Swiper.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
@stop
@section("footer-section")
{!! $data->footer_section !!}
@stop
@section("container")
@php
$currency=$setting_data['payment_currency'];
@endphp
    <style>
    

      .logo {
        color: #00bcd4;
        font-size: 1.2rem;
        font-style: italic;
        font-weight: 300;
        margin-bottom: 1rem;
      }

      .main-heading {
        color: var(--text-dark);
        font-size: 3.5rem;
        font-weight: 300;
        line-height: 1.2;
        margin-bottom: 1rem;
      }

      .sub-heading {
        color: var(--text-dark);
        font-size: 4rem;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 2rem;
      }

      .description {
        color: var(--text-dark);
        font-size: 1.1rem;
        line-height: 1.6;
        max-width: 600px;
        margin-bottom: 2rem;
      }

      .form-card {
        background: white;
        border-radius: 15px;
        padding: 2.5rem;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 0 auto;
      }

      .form-title {
        color: #2c5530;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 2rem;
        text-align: center;
      }

      .form-group {
        margin-bottom: 1.5rem;
      }

      .form-control {
        border: none;
        border-radius: 25px;
        padding: 15px 20px;
        background-color: #f8f9fa;
        font-size: 1rem;
        /* transition: all 0.3s ease; */
      }

      .form-control:focus {
        background-color: #e3f2fd;
        /* box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1); */
        border: 1px solid #00bcd4;
      }

      .form-control::placeholder {
        color: #999;
        font-weight: 400;
      }

      .form-row {
        display: flex;
        gap: 10px;
      }

      .form-row .form-control {
        flex: 1;
      }

      .select-control {
        border: none;
        border-radius: 25px;
        padding: 15px 20px;
        background-color: #f8f9fa;
        font-size: 1rem;
        color: #999;
        cursor: pointer;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23999' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 15px center;
        background-repeat: no-repeat;
        background-size: 16px;
        padding-right: 45px;
      }

      .select-control:focus {
        background-color: #e3f2fd;
        box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
        border: 1px solid #00bcd4;
        outline: none;
        color: #333;
      }

      .submit-btn {
        background: linear-gradient(45deg, #424242, #2e2d2c);
        border: none;
        border-radius: 25px;
        padding: 15px 30px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
        cursor: pointer;
      }

      .submit-btn:hover {
        background: linear-gradient(45deg, #424242, #2e2d2c);
      }

      /* Rental Evaluation Form Styles */
      .rental-evaluation-section {
        background-size: cover;
        background-position: center;
        padding: 100px 0;
        color: var(--text-dark);
        position: relative;
      }

      .rental-evaluation-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
      }

      .rental-evaluation-section .container {
        position: relative;
        z-index: 2;
      }

      .form-card {
        background: white;
        border-radius: 8px;
        padding: 2.5rem;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
        max-width: 100%;
        margin: 0 auto;
      }

      .form-title {
        color: var(--highlight-bg);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 2rem;
        text-align: center;
      }

      .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
      }

      .form-group {
        flex: 1;
      }

      .form-control {
        border: 1px solid var(--grey-light);
        border-radius: 4px;
        padding: 12px 15px;
        width: 100%;
        font-size: 0.95rem;
        transition: all 0.3s ease;
      }

      .form-control:focus {
        border-color: var(--highlight-bg);
        box-shadow: 0 0 0 3px rgba(26, 26, 26, 0.1);
        outline: none;
      }

      .submit-btn {
        background-color: var(--highlight-bg);
        border: none;
        border-radius: 4px;
        padding: 15px 30px;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        width: 100%;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      /* Submit button hover effect removed */

      @media (max-width: 768px) {
        .rental-evaluation-section {
          padding: 60px 0;
        }

        .main-heading {
          font-size: 2.2rem;
        }

        .sub-heading {
          font-size: 2.5rem;
        }

        .form-card {
          margin: 2rem 0;
          padding: 2rem;
        }

        .form-row {
         
          gap: 15px;
        }

        .form-group {
          width: 100%;
        }
      }

      @media (max-width: 576px) {
        .main-heading {
          font-size: 1.8rem;
        }

        .sub-heading {
          font-size: 2rem;
        }

        .description {
          font-size: 1rem;
        }

        .form-card {
          padding: 1.5rem;
          margin-top: 2.5rem;
        }

        .form-title {
          font-size: 1.3rem;
          margin-bottom: 1.5rem;
        }

        .submit-btn {
          padding: 12px 20px;
          font-size: 0.9rem;
        }
      }

      /* Main Section Styles */
      .main-section {
        background-color: var(--background-light);
        padding: 80px 0;
      }

      .expert-tag {
        color: var(--grey-dark);
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
      }

      .main-heading {
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.2;
        margin-bottom: 40px;
      }

      .property-image {
        width: 100%;
        height: auto;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      }

      .feature-section {
        margin-bottom: 50px;
        display: flex;
      }

      .feature-icon {
        width: 140px;
        height: 60px;
       
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        transition: transform 0.3s ease;
      }

      .feature-icon:hover {
        transform: translateY(-5px);
      }

      .feature-icon i {
        font-size: 2rem;
        color: var(--text-light);
      }

      .feature-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 15px;
        text-transform: uppercase;
      }

      .feature-description {
        color: var(--grey-dark);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 20px;
      }

      .read-more-btn {
        color: var(--highlight-bg);
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        transition: color 0.3s ease;
      }

      .read-more-btn:hover {
        color: var(--primary-accent);
        text-decoration: none;
      }

      .content-container {
        padding: 0 15px;
      }

      /* Services Section */
      .services-section {
        background-color: var(--text-light);
        padding: 80px 0;
      }

      .services-tag {
        color: var(--grey-dark);
        font-size: 1.2rem;
        font-weight: 500;
        font-style: italic;
        margin-bottom: 20px;
      }

      .services-heading {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 30px;
      }

      .services-description {
        color: var(--grey-dark);
        font-size: 1.1rem;
        line-height: 1.7;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
      }

      .service-card {
        text-align: center;
        padding: 40px 10px 10px 30px;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        background: var(--text-light);
        transition: transform 0.3s ease;
        height: 100%;
      }

      .service-card:hover {
        transform: translateY(-10px);
      }

      .service-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 30px;
       
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .service-icon i {
        font-size: 2.2rem;
        color: var(--highlight-bg);
      }

      .service-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        line-height: 1.3;
      }

      .service-text {
        color: var(--grey-dark);
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 25px;
      }

      .learn-more-btn {
        color: var(--highlight-bg);
        text-decoration: none;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
      }

      .learn-more-btn:hover {
        color: var(--primary-accent);
        text-decoration: none;
      }

      /* Marketing Section */
      .marketing-section {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 80px 0;
        color: var(--text-light);
        position: relative;
      }

      .marketing-tag {
        color: var(--grey-light);
        font-size: 1.2rem;
        font-weight: 500;
        font-style: italic;
        margin-bottom: 20px;
      }

      .marketing-highlight {
        background-color: var(--highlight-bg);
        color: var(--text-light);
        padding: 8px 15px;
        border-radius: 5px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
      }

      .marketing-heading {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 30px;
        line-height: 1.2;
      }

      .marketing-description {
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 800px;
        margin: 0 auto;
      }

      .marketing-card {
        background: var(--text-light);
        border-radius: 20px;
            padding: 20px 10px 10px 15px;
        color: var(--text-dark);
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        height: 100%;
      }

      .marketing-card-icon {
        width: 80px;
        height: 80px;
       
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
      }

      .marketing-card-icon i {
        font-size: 2rem;
        color: var(--text-light);
      }

      .marketing-card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 20px;
        text-align: center;
      }

      .marketing-features {
        list-style: none;
        padding: 0;
        margin: 0;
      }

      .marketing-features li {
        color: var(--grey-dark);
        font-size: 0.95rem;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        text-align: left;
      }

      .marketing-features li::before {
        content: "✓";
        color: var(--highlight-bg);
        font-weight: bold;
        margin-right: 10px;
        font-size: 1.1rem;
      }

      .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
      }

      .slider-nav:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.1);
      }

      .slider-nav.prev {
        left: 30px;
      }

      .slider-nav.next {
        right: 30px;
      }

      /* FAQ Section */
      .faq-section {
        background-color: var(--background-light);
        padding: 80px 0;
      }

      .faq-tag {
        color: var(--grey-dark);
        font-size: 1.2rem;
        font-weight: 500;
        font-style: italic;
        margin-bottom: 20px;
      }

      .faq-heading {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 50px;
        line-height: 1.2;
      }

      .faq-item {
        background: var(--text-light);
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
      }

      .faq-question {
        padding: 25px 30px;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-dark);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background-color 0.3s ease;
      }

      .faq-question:hover {
        background-color: var(--background-light);
      }

      .faq-question::after {
        content: "+";
        color: var(--highlight-bg);
        font-size: 1.5rem;
        font-weight: bold;
        transition: transform 0.3s ease;
      }

      .faq-question.active::after {
        transform: rotate(45deg);
      }

      .faq-answer {
        padding: 0 30px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        color: var(--grey-dark);
        line-height: 1.6;
      }

      .faq-answer.show {
        padding: 0 30px 25px;
        max-height: 300px;
      }

      /* CTA Section */
      .cta-section {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7));
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        padding: 80px 0;
        color: var(--text-light);
        text-align: center;
      }

      .cta-tag {
        color: var(--grey-light);
        font-size: 1.2rem;
        font-weight: 500;
        font-style: italic;
        margin-bottom: 20px;
      }

      .cta-highlight {
        background-color: var(--highlight-bg);
        color: var(--text-light);
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 20px;
        font-size: 1.1rem;
      }

      .cta-description {
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
      }

      .cta-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 30px;
      }

      .cta-btn {
        padding: 15px 30px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
      }

      .cta-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-2px);
      }

      .cta-btn.primary {
        background: var(--highlight-bg);
        border-color: var(--highlight-bg);
      }

      .cta-btn.primary:hover {
        background: var(--primary-accent);
        border-color: var(--primary-accent);
        color: var(--text-light);
      }

      .badges-container {
        display: flex;
        justify-content: flex-start;
        margin-top: 20px;
        align-items: center;
        gap: 20px;
      }

      .badges-container img {
        max-height: 80px;
        border-radius: 8px;
      }

      .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
      }

      .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* Responsive Design */
      @media (max-width: 991px) {
        .main-heading,
        .services-heading,
        .marketing-heading,
        .faq-heading {
          font-size: 2rem;
        }

        .main-section,
        .services-section,
        .marketing-section,
        .faq-section,
        .cta-section {
          padding: 60px 0;
        }

        .feature-section {
          flex-direction: column;
        }

        .feature-icon {
          margin-bottom: 15px;
          margin-right: 10px;
          width: 80px;
        }
      }

      @media (max-width: 767px) {
        .main-heading,
        .services-heading,
        .marketing-heading,
        .faq-heading {
          font-size: 1.8rem;
        }

        .main-section,
        .services-section,
        .marketing-section,
        .faq-section,
        .cta-section {
          padding: 40px 0;
        }

        .property-image {
          margin-bottom: 40px;
        }

        .feature-section {
          margin-bottom: 20px;
        }

        .cta-buttons {
         
          align-items: center;
        }

        .slider-nav {
          display: none;
        }

        .badges-container {
          justify-content: center;
          flex-wrap: wrap;
        }
      }

      @media (max-width: 575px) {
        .main-heading,
        .services-heading,
        .marketing-heading,
        .faq-heading {
          font-size: 1.6rem;
        }
      }

   
 

      .fade-in {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease forwards;
      }

      @keyframes fadeInUp {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .slide-in {
        animation: slideIn 1s ease;
      }

      @keyframes slideIn {
        from {
          opacity: 0;
          transform: translateX(50px);
        }
        to {
          opacity: 1;
          transform: translateX(0);
        }
      }

    /* Features Swiper Styles */
.features-swiper {
  padding: 20px 10px 70px;
  margin: 0 -15px;
  position: relative;
}

/* Center marketing cards on desktop */
@media (min-width: 768px) {
  #marketing-slider {
    justify-content: center;
  }
}

.features-swiper .swiper-slide {
  padding: 15px;
}

.features-swiper .feature-section {
  height: 100%;
  margin-bottom: 0;
 
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.features-swiper .feature-icon {
  margin: 0 auto 20px;
  width: 80px;
  height: 80px;
}

.features-swiper .feature-description {
  margin-bottom: 0;
}

.features-swiper .swiper-pagination {
  bottom: 0 !important;
  position: relative !important;
  margin-top: 20px !important;
}

.features-swiper .swiper-pagination-bullet {
  background: #333333;
  opacity: 0.5;
}

.features-swiper .swiper-pagination-bullet-active {
  background: #333333;
  opacity: 1;
}

@media (min-width: 768px) {
  .features-swiper {
    display: none;
  }
}

 /* Services Swiper Styles */
.services-swiper {
  padding: 20px 10px 70px;
  margin: 0 -15px;
  position: relative;
}

.services-swiper .swiper-slide {
  height: auto;
  padding: 15px;
}

.services-swiper .service-card {
  height: 100%;
  margin-bottom: 0;
}

.services-swiper .swiper-pagination {
  bottom: 0 !important;
  position: relative !important;
  margin-top: 20px !important;
}

.services-swiper .swiper-pagination-bullet {
  background: #333333;
  opacity: 0.5;
}

.services-swiper .swiper-pagination-bullet-active {
  background: #333333;
  opacity: 1;
}

@media (min-width: 768px) {
  .services-swiper {
    display: none;
  }
}

/* Testimonial Swiper Styles */
/* Testimonial Swiper Styles */
.testimonials-section {
  position: relative;
  color: #333333;
  overflow: hidden;
}

.testimonials-section::before {
  content: none; /* Remove the overlay */
}

.testimonials-container {
  position: relative;
  z-index: 1;
}

.testimonial-card {
  background-color: #ffffff;
  border-radius: 15px;
  padding: 25px;
  height: 100%;
  min-height: 450px;
  border: 1px solid #e0e0e0;
  transition: transform 0.3s, box-shadow 0.3s;
  margin: 10px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.testimonial-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.owner-info {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.owner-avatar {
  
  overflow: hidden;
  border-radius: 50%;
  margin-right: 15px;
 
  flex-shrink: 0;
 
}

.owner-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.owner-details {
  flex-grow: 1;
}

.owner-details h5 {
  margin: 0;
  font-size: 1.1rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #333333;
  font-weight: 600;
}

.owner-role {
  margin: 0;
  font-size: 0.9rem;
  color: #666666;
}

.testimonial-text {
  font-style: italic;
  margin-bottom: 15px;
  line-height: 1.6;
  color: #333333;
}

.star-rating {
  color: #ffc107;
  display: flex;
  justify-content: flex-start;
  flex-wrap: nowrap;
}

.star {
  margin-right: 2px;
}

/* Swiper specific styles */
.swiper-container {
  padding: 20px 10px 50px;
  overflow: hidden;
}

.swiper-pagination {
  bottom: 0 !important;
}

.swiper-pagination-bullet {
  width: 12px;
  height: 12px;
  background-color: rgba(0, 0, 0, 0.2);
  opacity: 1;
  transition: all 0.3s ease;
  margin: 0 5px !important;
}

.swiper-pagination-bullet-active {
  background-color: #333333;
  width: 14px;
  height: 14px;
}

.swiper-button-next,
.swiper-button-prev {
  width: 50px;
  height: 50px;
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 50%;
  color: #333333;
  opacity: 0.8;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
  background-color: #ffffff;
  opacity: 1;
  box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

.swiper-button-next:after,
.swiper-button-prev:after {
  font-size: 20px;
  font-weight: bold;
  color: #333333;
}

/* Responsive styles */
@media (max-width: 1400px) {
  .testimonials-section {
    padding: 70px 0;
  }
}

@media (max-width: 1200px) {
  .testimonials-section {
    padding: 60px 0;
  }
}

@media (max-width: 992px) {
  .testimonials-section {
    padding: 50px 0;
  }
  
  .swiper-button-next,
  .swiper-button-prev {
    width: 40px;
    height: 40px;
  }
  
  .testimonial-card {
    padding: 20px;
  }
}

@media (max-width: 768px) {
  .testimonials-section {
    padding: 40px 0 60px 0;
  }
  
  .swiper-button-next,
  .swiper-button-prev {
    width: 35px;
    height: 35px;
    display: none;
  }
  
  .testimonial-card {
    margin: 5px;
    padding: 20px;
  }
  
  .owner-avatar {
    width: 45px;
    height: 45px;
  }
}

@media (max-width: 576px) {
  .testimonials-section {
    padding: 10px 0 0px 0;
  }
   .form-btn-size {
       width: 350px !important;
    }
  .testimonial-card {
    padding: 15px;
  }
  
  .testimonial-text {
    font-size: 0.95rem;
    line-height: 1.5;
  }
  
  .owner-avatar {
    width: 40px;
    height: 40px;
  }
  
  .owner-details h5 {
    font-size: 1rem;
  }
  
  .owner-role {
    font-size: 0.8rem;
  }
  
  .swiper-pagination-bullet {
    width: 8px;
    height: 8px;
  }
  
  .swiper-pagination-bullet-active {
    width: 10px;
    height: 10px;
  }
   .submit-form {
        
                    padding: 10px 15px !important;
                   
                    font-size: 0.8rem !important;
                   
    }  
}

@media (max-width: 400px) {
  .testimonials-section {
    padding: 25px 0 45px 0;
  }
  
  .testimonial-card {
    padding: 12px;
  }
  
  .testimonial-text {
    font-size: 0.9rem;
  }
}

/* Additional styles for section titles and text colors */
.section-title {
  text-align: center;
  color: #333333;
  font-weight: 600;
}

.section-title h2 {
  font-size: 2.5rem;
  margin-bottom: 15px;
  font-weight: 700;
  color: #333333;
}

.section-title .highlight {
  color: #333333;
  font-weight: 700;
}

.section-description {
  font-size: 1.1rem;
  max-width: 900px;
  margin: 0 auto;
  color: #666666;
}
    .submit-form {
         border: none;
                    border-radius: 4px;
                    padding: 15px 30px;
                    color: white;
                    font-weight: 600;
                    font-size: 1rem;
                    width: 100%;
                    cursor: pointer;
                    text-transform: uppercase;
                    letter-spacing: 1px;
    }  
    
    .form-btn-size {
        width: 400px;
    }
    </style>

    <!-- form for free rental evaluation -->
    <section class="rental-evaluation-section">
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Content -->
          <div class="col-lg-7 col-md-6 fade-in">
            <div class="ps-lg-5 ps-md-3 ps-2">
              <div
                class="logo"
                style="
                  color: var(--grey-dark);
                  font-size: 1.2rem;
                  text-transform: uppercase;
                  letter-spacing: 2px;
                  margin-bottom: 1.5rem;
                "
              >
                Property Management
              </div>
              <h1
                class="main-heading"
                style="
                  color: var(--text-dark);
                  font-size: 3rem;
                  font-weight: 500;
                  line-height: 1.2;
                  margin-bottom: 1rem;
                "
              >
                Maximize Your Bentonville
              </h1>
              <h2
                class="sub-heading"
                style="
                  color: var(--text-dark);
                  font-size: 3.5rem;
                  font-weight: 700;
                  line-height: 1.1;
                  margin-bottom: 2rem;
                "
              >
                Vacation Rental Potential
              </h2>
              <p
                class="description"
                style="
                  color: var(--grey-dark);
                  font-size: 1.1rem;
                  line-height: 1.7;
                  max-width: 700px;
                  margin-bottom: 2rem;
                "
              >
              Do you own a short-term rental or are considering investing in one? At Bentonville Lodging Co, our professional property management services are designed to maximize your revenue while reducing your stress. We offer flexible management options tailored to your needs, so you can enjoy peace of mind knowing your property is in capable hands.

Our expert team handles every detail — from maintenance and marketing to guest communication and experiences. Partner with Bentonville Lodging Co for reliable, results-driven vacation rental management that ensures your property stands out and performs at its best.

              </p>
            </div>
          </div>

          <!-- Right Form -->
          <div class="col-lg-5 col-md-6 fade-in">
            <div
            id="request_info_btn"
              class="form-card"
              style="
                background: white;
                border-radius: 8px;

                box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
                max-width: 100%;
                margin: 0 auto;
              "
            >
              <h3
                class="form-title"
                style="
                  color: var(--highlight-bg);
                  font-size: 1.5rem;
                  font-weight: 600;
                  margin-bottom: 2rem;
                  text-align: center;
                "
              >
                 <strong>Request Management Information</strong>
              </h3>
              <form id="rentalForm12" action="{{route('property-management-post')}}" method="post">
                  @csrf
                <div
                  class="form-row"
                  style="display: flex; gap: 15px; margin-bottom: 15px"
                >
                  <div class="form-group" style="flex: 1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="First name"
                      name="first_name"
                      required
                      style="
                        border: 1px solid var(--grey-light);
                        border-radius: 4px;
                        padding: 12px 15px;
                        width: 100%;
                        font-size: 0.95rem;
                      "
                    />
                  </div>
                  <div class="form-group" style="flex: 1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Last name"
                      name="last_name"
                      required
                      style="
                        border: 1px solid var(--grey-light);
                        border-radius: 4px;
                        padding: 12px 15px;
                        width: 100%;
                        font-size: 0.95rem;
                      "
                    />
                  </div>
                </div>

                <div
                  class="form-row"
                  style="display: flex; gap: 15px; margin-bottom: 15px"
                >
                  <div class="form-group" style="flex: 1">
                    <input
                      type="email"
                      class="form-control"
                      placeholder="Email"
                      name="email"
                      required
                      style="
                        border: 1px solid var(--grey-light);
                        border-radius: 4px;
                        padding: 12px 15px;
                        width: 100%;
                        font-size: 0.95rem;
                      "
                    />
                  </div>
                  <div class="form-group" style="flex: 1">
                    <input
                      type="tel"
                      class="form-control"
                      placeholder="Phone"
                      name="mobile"
                      required
                      style="
                        border: 1px solid var(--grey-light);
                        border-radius: 4px;
                        padding: 12px 15px;
                        width: 100%;
                        font-size: 0.95rem;
                      "
                    />
                  </div>
                </div>

                <div
                  class="form-group"
                  style="margin-bottom: 20px; width: 100%"
                >
                 {!! Form::select("property_type", Helper::getTownList(), null, ["class"=>"form-control", "placeholder"=>"Select Town", "required" => true]) !!}

                </div>
                   <div class="form-group" style="flex: 1">
                    <input
                      type="text"
                      class="form-control"
                      placeholder="Property Address"
                      name="property_address"
                      required
                      style="
                        border: 1px solid var(--grey-light);
                        border-radius: 4px;
                        padding: 12px 15px;
                        width: 100%;
                        font-size: 0.95rem;
                      "
                    />
                  </div>
                   @if($setting_data['g_captcha_enabled'])
                                    @if($setting_data['g_captcha_enabled']=="yes")
                                        @if($setting_data['google_captcha_site_key']!="" && $setting_data['google_captcha_secret_key']!="")
                                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                            <div class="g-recaptcha" data-sitekey="{{ $setting_data['google_captcha_site_key'] }}"></div>
                                        </div>
                                        @endif
                                    @endif
                                @endif
                <button
                  type="submit"
                  class="submit-btn submit-form"
                 
                >
                  Request Information
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Main Hero Section -->
    <section class="main-section">
      <div class="container">
        <div class="row align-items-center">
          <!-- Left Column - Image -->
          <div class="col-lg-4 col-md-12 mb-4 mb-lg-0">
            <div class="fade-in">
              <img
                src="{{ asset('front/images/services.jpeg')}}"
                alt="Beautiful vacation rental property"
                class="property-image"
              />
            </div>
          </div>

          <!-- Right Column - Content -->
          <div class="col-lg-7 col-md-12">
            <div class="content-container">
              <div class="expert-tag fade-in">EXPERT MANAGEMENT</div>
              <h1 class="main-heading fade-in">
                WHY HIRE BENTONVILLE LODGING COMPANY TO MANAGE YOUR VACATION
                RENTAL?
              </h1>

              <!-- Experience Section -->
              <div class="feature-section fade-in d-none d-md-flex">
                <div class="feature-icon d-lg-flex d-none">
                 <img src="{{asset('front/images/WhatsApp%20Image%202025-09-26%20at%209.49.06%20PM.jpeg')}}" />
                </div>
                <div>
                    <div style="display:flex; align-items:center;">
                        <div class="feature-icon d-lg-none">
                  <img src="{{asset('front/images/WhatsApp%20Image%202025-09-26%20at%209.49.06%20PM.jpeg')}}" />
                </div>
                  <h3 class="feature-title">7 Years of Hosting</h3>
                    </div>
                    
                  <p class="feature-description">
                  Bentonville Lodging Company brings over seven years of experience managing vacation rentals. We understand guest expectations, property care, and how to maximize your rental’s performance.

                  </p>
                
                </div>
              </div>

              <!-- Personal Touch Section -->
              <div class="feature-section fade-in d-none d-md-flex">
                <div class="feature-icon  d-lg-flex d-none">
                  <img src="{{asset('front/images/Technology-Driven%20Bookings.jpeg')}}" />
                </div>
                <div>
                    <div style="display:flex; align-items:center;">
                         <div class="feature-icon d-lg-none">
                  <img src="{{asset('front/images/Technology-Driven%20Bookings.jpeg')}}" />
                </div>
                  <h3 class="feature-title">Technology-Driven Bookings
</h3>
                    </div>
                  <p class="feature-description">
                  Our advanced booking system ensures seamless reservations, real-time updates, and smooth operations, making the experience hassle-free for both owners and guests.
                  </p>
                 
                </div>
              </div>

              <!-- Pricing Section -->
              <div class="feature-section fade-in d-none d-md-flex">
                <div class="feature-icon d-lg-flex d-none">
                 <img src="{{asset('front/images/Integrated%20Marketing%20Team.jpeg')}}" />
                </div>
                <div>
                    <div style="display:flex; align-items:center;">
                        
                     <div class="feature-icon d-lg-none">
                  <img src="{{asset('front/images/Integrated%20Marketing%20Team.jpeg')}}" />
                </div>
                  <h3 class="feature-title">Integrated Marketing Team</h3>
                    </div>
                  <p class="feature-description">
                   Our in-house marketing team promotes your property across multiple channels, leveraging professional strategies to boost visibility, attract quality guests, and increase revenue.


                  </p>
                 
                </div>
              </div>
            </div>
            
            <!-- Mobile View with Swiper -->
            <div class="d-block d-md-none">
              <div class="swiper-container features-swiper">
                <div class="swiper-wrapper">
                  <!-- Experience Slide -->
                  <div class="swiper-slide">
                    <div class="feature-section fade-in">
                      <div class="feature-icon">
                        <img src="{{asset('front/images/WhatsApp%20Image%202025-09-26%20at%209.49.06%20PM.jpeg')}}" />
                      </div>
                      <h3 class="feature-title">7 Years of Hosting</h3>
                      <p class="feature-description">
                        Bentonville Lodging Company brings over seven years of experience managing vacation rentals. We understand guest expectations, property care, and how to maximize your rental's performance.
                      </p>
                    </div>
                  </div>

                  <!-- Technology Slide -->
                  <div class="swiper-slide">
                    <div class="feature-section fade-in">
                      <div class="feature-icon">
                        <img src="{{asset('front/images/Technology-Driven%20Bookings.jpeg')}}" />
                      </div>
                      <h3 class="feature-title">Technology-Driven Bookings</h3>
                      <p class="feature-description">
                        Our advanced booking system ensures seamless reservations, real-time updates, and smooth operations, making the experience hassle-free for both owners and guests.
                      </p>
                    </div>
                  </div>

                  <!-- Marketing Slide -->
                  <div class="swiper-slide">
                    <div class="feature-section fade-in">
                      <div class="feature-icon">
                        <img src="{{asset('front/images/Integrated%20Marketing%20Team.jpeg')}}" />
                      </div>
                      <h3 class="feature-title">Integrated Marketing Team</h3>
                      <p class="feature-description">
                        Our in-house marketing team promotes your property across multiple channels, leveraging professional strategies to boost visibility, attract quality guests, and increase revenue.
                      </p>
                    </div>
                  </div>
                </div>
                <div class="swiper-pagination" style="position: static; margin-top: 20px;"></div>
              </div>
            </div>
          </div>
          
          <div
            class="badges-container"
            style="
              display: flex;
              justify-content: center;
              margin: 30px auto;
              
              width: 100%;
            "
          >
            <img
             src="{{asset('front/images/superhost_badge.avif')}}"
              alt="Superhost Badge"
              style="max-height: 100px; border-radius: 8px"
            />
            <img
             src="{{asset('front/images/download.avif')}}"
              alt="Verified Host Badge"
              style="max-height: 100px; border-radius: 8px"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
           
            <h2 class="services-heading fade-in">
Our Process
            </h2>
            <p class="services-description fade-in">
              At Bentonville Lodging Co., our property management is designed to fit your needs as an owner. Unlike many companies that offer a “take it or leave it” approach, we let you choose the management style that works best for you. 

            </p>
            <p class="services-description fade-in">
             Whether you prefer a fully hands-off experience or want to stay involved with guests, we can tailor our services to match your preferences.
            </p>
          </div>
        </div>

        <div class="row mt-5 d-none d-md-flex">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="service-card fade-in">
              <div class="service-icon">
                 <img src="{{asset('front/images/Request%20Information.jpeg')}}" />
              </div>
              <h3 class="service-title">
               Request Information / Schedule On-Site Consultation<br />
              </h3>
              <p class="service-text">
               Reach out to us to learn more about our services or schedule a consultation. We’ll discuss your property, goals, and answer any questions you may have.

              </p>
             
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="service-card fade-in">
              <div class="service-icon">
                <img src="{{asset('front/images/Select%20the%20Management%20Packag.jpeg')}}" />
              </div>
              <h3 class="service-title">
               Select the Management Package You Want
              </h3>
              <p class="service-text">
               Choose the management package that fits your needs — from fully hands-off management to a more involved approach with guest interactions.

              </p>
            
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="service-card fade-in">
              <div class="service-icon">
                 <img src="{{asset('front/images/Start%20Managing.jpeg')}}" />
              </div>
              <h3 class="service-title">Pick a Date for Us to Start Managing</h3>
              <p class="service-text">
                Once you’ve selected your package, simply set a start date, and our team will take care of the rest, ensuring a smooth transition and seamless management.
              </p>
             
            </div>
          </div>
        </div>
           <!-- Mobile View with Swiper -->
        <div class="d-block d-md-none">
          <div class="swiper-container services-swiper">
            <div class="swiper-wrapper">
              <!-- Service 1 Slide -->
              <div class="swiper-slide">
                <div class="service-card fade-in">
                  <div class="service-icon">
                    <img src="{{asset('front/images/Request%20Information.jpeg')}}" />
                  </div>
                  <h3 class="service-title">
                    Request Information / Schedule On-Site Consultation
                  </h3>
                  <p class="service-text">
                    Reach out to us to learn more about our services or schedule a consultation. We'll discuss your property, goals, and answer any questions you may have.
                  </p>
                </div>
              </div>

              <!-- Service 2 Slide -->
              <div class="swiper-slide">
                <div class="service-card fade-in">
                  <div class="service-icon">
                    <img src="{{asset('front/images/Select%20the%20Management%20Packag.jpeg')}}" />
                  </div>
                  <h3 class="service-title">
                    Select the Management Package You Want
                  </h3>
                  <p class="service-text">
                    Choose the management package that fits your needs — from fully hands-off management to a more involved approach with guest interactions.
                  </p>
                </div>
              </div>

              <!-- Service 3 Slide -->
              <div class="swiper-slide">
                <div class="service-card fade-in">
                  <div class="service-icon">
                    <img src="{{asset('front/images/Start%20Managing.jpeg')}}" />
                  </div>
                  <h3 class="service-title">Pick a Date for Us to Start Managing</h3>
                  <p class="service-text">
                    Once you've selected your package, simply set a start date, and our team will take care of the rest, ensuring a smooth transition and seamless management.
                  </p>
                </div>
              </div>
            </div>
            <div class="swiper-pagination" style="position: static; margin-top: 20px;"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- Marketing Section -->
    <section class="marketing-section">
      <div class="slider-nav prev d-md-none" onclick="changeSlide(-1)">
        <i class="fas fa-chevron-left"></i>
      </div>
      <div class="slider-nav next d-md-none" onclick="changeSlide(1)">
        <i class="fas fa-chevron-right"></i>
      </div>

      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <div class="marketing-tag fade-in">more bookings</div>
            <div class="marketing-highlight fade-in">
              We Keep Your Property Booked
            </div>
            <h2 class="marketing-heading fade-in">
Vacation Rental Management Options
            </h2>
          
            <p class="marketing-description fade-in" >
             Choose the management style that best fits your needs as a property owner. We offer flexible options so you can stay as involved as you like while we handle the rest.
            </p>
          </div>
        </div>

        <div class="row mt-5 mx-md-5 gap-md-3" id="marketing-slider">
          <div class="col-md-5 col-12 mb-4">
            <div class="marketing-card fade-in">
              <div class="marketing-card-icon">
                 <img src="{{asset('front/images/The%20Full%20Set.jpeg')}}" />
              </div>
              <h3 class="marketing-card-title">The Full Set</h3>
              <p>Sit back and relax — we handle everything needed to make your short-term rental thrive. This hands-off option, paid as a percentage of gross bookings, includes guest management, integrated marketing, property maintenance, cleaning, stocking, lawn care, and even replacement of linens and towels if needed. As the owner, you only need to ensure property bills are paid — we take care of the rest. This is our most popular option for owners who want a worry-free experience.</p>
              <ul class="marketing-features">
                <li>Guest messaging</li>
                <li>
                  Integrated marketing
                </li>
                <li>
                  Property maintenance
                </li>
                <li>Property cleaning</li>
                <li>Operating supplies</li>
              </ul>
            </div>
          </div>

          <div class="col-md-5 col-12 mb-4">
            <div class="marketing-card fade-in">
              <div class="marketing-card-icon">
                <img src="{{asset('front/images/The%20Essentials.jpeg')}}" />
              </div>
              <h3 class="marketing-card-title">The Essentials</h3>
              <p>A balanced option for owners who want to be partially involved. We handle the essential tasks that make a short-term rental successful, including guest management, basic marketing, cleaning, and basic home stock. Owners are responsible for maintenance, landscaping, and replacing damaged items unless requested at a billed rate. This option is paid through a reduced percentage of gross bookings plus the cleaning fee charged to guests.</p>
              <ul class="marketing-features">
                <li>
                  Guest messaging
                </li>
                <li>Basic marketing</li>
                <li>Property cleaning</li>
                <li>Operating supplies</li>
              </ul>
            </div>
          </div>

        </div>
        <div class="mx-auto form-btn-size" >
            
          <button
          id="scrollBtn"
                  type="submit"
                  class="submit-btn submit-form"
                 
                >
                  Request Information
                </button>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-md-10 mx-auto">
            <div class="text-center">
              <div class="faq-tag fade-in">faqs</div>
              <h2 class="faq-heading fade-in">
                Frequently Asked Questions<br />About Vacation Rental Management
              </h2>
            </div>

            <div class="faq-item fade-in">
              <div class="faq-question" onclick="toggleFaq(this)">
               Are there restrictions on how often I use my home?
              </div>
              <div class="faq-answer">
                We do have some minor guidelines on owner use to ensure your property achieves its best possible performance. That said, these restrictions can be tailored to fit your personal needs. Our goal is always to maximize your home’s income potential while still giving you the access and enjoyment you want as an owner.
              </div>
            </div>

            <div class="faq-item fade-in">
              <div class="faq-question" onclick="toggleFaq(this)">
              Do I have to sign a long-term contract?
              </div>
              <div class="faq-answer">
                Our management agreements are for 12 months. However, you can cancel anytime with no penalty by providing a 90-day written notice to our team. We believe in earning your trust through results, not locking you into something that doesn’t work for you.
              </div>
            </div>

            <div class="faq-item fade-in">
              <div class="faq-question" onclick="toggleFaq(this)">
               What is full-service property management?
              </div>
              <div class="faq-answer">
               Our <strong> Full-Service Package</strong> covers everything: professional marketing, booking and channel management, guest communication, cleaning coordination, routine maintenance, inspections, and financial reporting.
If you’d prefer more flexibility, our <strong> Essentials Package </strong>lets you pick and choose services—such as handling your own maintenance—while still relying on us for the key areas that drive performance.
              </div>
            </div>

            <div class="faq-item fade-in">
              <div class="faq-question" onclick="toggleFaq(this)">
               Does Bentonville Lodging Co manage Airbnbs?
              </div>
              <div class="faq-answer">
                Yes. We manage listings across all major booking platforms including Airbnb, VRBO, Booking.com, and others. Our channel management system keeps your property visible everywhere while preventing double bookings.
              </div>
            </div>

            <div class="faq-item fade-in">
              <div class="faq-question" onclick="toggleFaq(this)">
               In what cities do you offer property management services?
              </div>
              <div class="faq-answer">
               Our core markets are <strong> Bentonville, Rogers, and Bella Vista </strong> , but we provide coverage across the greater Northwest Arkansas region—from Garfield to Fayetteville, including the Beaver Lake area. Our local expertise and presence allow us to deliver a personalized, high-touch service that national companies simply can’t match.
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Review section  -->
    <section>
      <div class="text-center mb-md-5 fade-in">
        <h2 class="section-title">
          What Owners <span class="highlight">Say About Us</span>
        </h2>
        <p class="section-description">
        See what fellow property owners have shared about their experience with Bentonville Lodging Co. Their testimonials give you an authentic look at the value of partnering with us—highlighting how our team helps maximize returns, streamline operations, and thrive in Bentonville’s competitive vacation rental market.
        </p>
      </div>
      <section class="testimonials-section">
        <div class="testimonials-container container">
          <div class="swiper-container testimonial-swiper">
            <div class="swiper-wrapper">
              <!-- Testimonial 1 -->
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Brenda G</h5>
                     
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging Co is the most detail oriented hospitality management co and Air BnB hosts that you will come across. Their properties are impeccably maintained and you will thoroughly enjoy your stay at any of their properties. Troy and I have had the pleasure of staying at a number of their properties and we also trust Luke and Sarah to mange a property of our own. Bentonville Lodging Co. is committed to providing both their clients and their guests first class experiences. We highly recommend them!"
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
              
              <!-- Testimonial 2 -->
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Kay L</h5>
                    
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging Company LLC is an exceptional property management company.  Luke Jenkins takes a direct interest in the current and future needs of our property.  He simply and beautifully explains options, both for physical improvements and marketing strategies, for us to consider.  He routinely and clearly communicates with us and makes personal checks both when we have tenants and when we don’t.  This is an invaluable partnership.
"
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
              
              <!-- Testimonial 3 -->
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Vickie G</h5>
                      
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging manages my property.  I find them very responsive to questions or problems that might arise and have been very pleased so far."
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
              
              <!-- Testimonial 4 -->
            <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Brenda G</h5>
                     
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging Co is the most detail oriented hospitality management co and Air BnB hosts that you will come across. Their properties are impeccably maintained and you will thoroughly enjoy your stay at any of their properties. Troy and I have had the pleasure of staying at a number of their properties and we also trust Luke and Sarah to mange a property of our own. Bentonville Lodging Co. is committed to providing both their clients and their guests first class experiences. We highly recommend them!"
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
              
              <!-- Testimonial 5 -->
              <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Kay L</h5>
                    
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging Company LLC is an exceptional property management company.  Luke Jenkins takes a direct interest in the current and future needs of our property.  He simply and beautifully explains options, both for physical improvements and marketing strategies, for us to consider.  He routinely and clearly communicates with us and makes personal checks both when we have tenants and when we don’t.  This is an invaluable partnership.
"
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
              
              <!-- Testimonial 6 -->
            <div class="swiper-slide">
                <div class="testimonial-card">
                  <div class="owner-info">
                    <div class="owner-avatar">
                    </div>
                    <div class="owner-details">
                      <h5>Vickie G</h5>
                      
                    </div>
                  </div>
                  <p class="testimonial-text">
                    "Bentonville Lodging manages my property.  I find them very responsive to questions or problems that might arise and have been very pleased so far."
                  </p>
                  <div class="star-rating">
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                    <i class="fas fa-star star"></i>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Navigation buttons -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            
            <!-- Pagination dots -->
            <div class="swiper-pagination"></div>
          </div>
        </div>
      </section>
    </section>

    <!-- CTA Section -->
    <section class="cta-section mt-5">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center">
            <div class="cta-tag fade-in">get started</div>
            <div class="cta-highlight fade-in">
              Let's Get Something Scheduled!
            </div>
            <p class="cta-description fade-in">
              Our experienced team is committed to guiding you toward success in
              this thriving market. Contact us today, and let's collaborate on a
              tailored approach for your property. Together, we'll provide an
              exceptional experience for your guests and boost your rental
              income.
            </p>
            <p class="cta-description fade-in">
              Don't hesitate — take the first step now!
            </p>

            <div class="cta-buttons fade-in">
              <a href="tel:{!! $setting_data['mobile'] ?? '#' !!}" class="cta-btn">
                <i class="fas fa-phone"></i>
                Call Us
              </a>
              <a
                href="mailto:{!! $setting_data['email'] ?? '#' !!}"
                class="cta-btn primary"
              >
                <i class="fas fa-envelope"></i>
                Email Us
              </a>
            
            </div>
          </div>
        </div>
      </div>
    </section>

    
    <!-- Add Swiper.js JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <!-- Add custom Swiper JS -->
    <script>
        document.getElementById('scrollBtn').addEventListener('click', () => {
    document.getElementById('request_info_btn').scrollIntoView({
      behavior: 'smooth'
    });
  });
      // Animation on scroll
      document.addEventListener("DOMContentLoaded", function () {

        const fadeElements = document.querySelectorAll(".fade-in");

        const observer = new IntersectionObserver(
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

        fadeElements.forEach((element, index) => {
          // Add staggered delay for smooth animation
          element.style.transitionDelay = `${index * 0.1}s`;
          observer.observe(element);
        });
      });

      // FAQ functionality
      function toggleFaq(element) {
        // Toggle active class on the question
        element.classList.toggle("active");

        // Toggle show class on the answer
        const answer = element.nextElementSibling;
        answer.classList.toggle("show");
      }

      // Marketing slider functionality
      let currentSlide = 0;
      const slides = document.querySelectorAll("#marketing-slider .col-lg-4");
      const totalSlides = slides.length;

      function changeSlide(direction) {
        // For desktop, we show 3 cards at once, so no need for sliding
        // For mobile/tablet, implement sliding logic
        if (window.innerWidth <= 991) {
          currentSlide = (currentSlide + direction + totalSlides) % totalSlides;

          slides.forEach((slide, index) => {
            if (index === currentSlide) {
              slide.style.display = "block";
            } else {
              slide.style.display = "none";
            }
          });
        }
      }

      // Initialize slider for mobile
      function initSlider() {
        if (window.innerWidth <= 991) {
          slides.forEach((slide, index) => {
            if (index === 0) {
              slide.style.display = "block";
            } else {
              slide.style.display = "none";
            }
          });
        } else {
          slides.forEach((slide) => {
            slide.style.display = "block";
          });
        }
      }

      // Call this on load and resize
      window.addEventListener("load", initSlider);
      window.addEventListener("resize", initSlider);


    </script>
    <script>
      document
        .getElementById("rentalForm")
        .addEventListener("submit", function (e) {
          e.preventDefault();

          // Get form data
          const formData = new FormData(this);
          const formValues = {};

          // Get all form inputs
          const inputs = this.querySelectorAll("input, select");
          inputs.forEach((input) => {
            if (input.value) {
              formValues[input.placeholder || input.name] = input.value;
            }
          });

          // Show success message with custom styling instead of alert
          const formCard = document.querySelector(".form-card");
          const formHeight = formCard.offsetHeight;
          const formHTML = formCard.innerHTML;

          // Replace form with success message
          formCard.innerHTML = `
                <div style="min-height: ${
                  formHeight - 100
                }px; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center;">
                    <div style="font-size: 3rem; color: var(--highlight-bg); margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 style="font-size: 1.5rem; margin-bottom: 15px; color: var(--highlight-bg);">Thank You!</h3>
                    <p style="color: var(--grey-dark); margin-bottom: 25px;">We've received your rental property details and will contact you soon with your free evaluation.</p>
                    <button id="newRequestBtn" style="background-color: var(--highlight-bg); border: none; border-radius: 4px; padding: 12px 25px; color: white; font-weight: 500; cursor: pointer;">Submit Another Request</button>
                </div>
            `;

          // Add event listener to reset form
          document
            .getElementById("newRequestBtn")
            .addEventListener("click", function () {
              formCard.innerHTML = formHTML;
              // Reattach event listeners
              attachFormEventListeners();
            });
        });

      function attachFormEventListeners() {
        // Re-attach form submit event
        const rentalForm = document.getElementById("rentalForm");
        if (rentalForm) {
          rentalForm.addEventListener("submit", function (e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(this);
            const formValues = {};

            // Get all form inputs
            const inputs = this.querySelectorAll("input, select");
            inputs.forEach((input) => {
              if (input.value) {
                formValues[input.placeholder || input.name] = input.value;
              }
            });

            // Show success message (you can replace this with actual form submission)
            alert(
              "Thank you for your interest! We will contact you soon with your rental evaluation."
            );

            // Reset form
            this.reset();
          });
        }

        // Add smooth focus animations
        document.querySelectorAll(".form-control").forEach((element) => {
          element.addEventListener("focus", function () {
            this.style.borderColor = "var(--highlight-bg)";
            this.style.boxShadow = "0 0 0 3px rgba(26, 26, 26, 0.1)";
            this.parentElement.style.transform = "translateY(-2px)";
            this.parentElement.style.transition = "transform 0.2s ease";
          });

          element.addEventListener("blur", function () {
            this.style.borderColor = "var(--grey-light)";
            this.style.boxShadow = "none";
            this.parentElement.style.transform = "translateY(0)";
          });
        });
      }

      // Add typing animation for the main heading in the rental section
      function typeWriter(element, text, speed = 100) {
        let i = 0;
        element.innerHTML = "";
        function type() {
          if (i < text.length) {
            element.innerHTML += text.charAt(i);
            i++;
            setTimeout(type, speed);
          }
        }
        type();
      }

      // Initialize animations on page load
      window.addEventListener("load", function () {
        // Attach initial event listeners
        attachFormEventListeners();

        // Apply text animation
        const mainHeading = document.querySelector(
          ".rental-evaluation-section .main-heading"
        );
        if (mainHeading) {
          const originalText = mainHeading.textContent;
          setTimeout(() => {
            typeWriter(mainHeading, originalText, 50);
          }, 500);
        }
      });
    </script>
    <script>
   
// Initialize Swiper for Testimonials and Features
document.addEventListener('DOMContentLoaded', function() {
      // Initialize Services Swiper (mobile only)
  const servicesSwiper = new Swiper('.services-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    }
  });
  // Initialize Features Swiper (mobile only)
  const featuresSwiper = new Swiper('.features-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: '.swiper-pagination',
      clickable: true,
    }
  });
  
  // Initialize Testimonial Swiper
  const testimonialSwiper = new Swiper('.testimonial-swiper', {
    // Optional parameters
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    grabCursor: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    
    // Responsive breakpoints
    breakpoints: {
      // Mobile
      320: {
        slidesPerView: 1,
        spaceBetween: 20
      },
      // Mobile landscape
      576: {
        slidesPerView: 1,
        spaceBetween: 20
      },
      // Tablet
      768: {
        slidesPerView: 2,
        spaceBetween: 30
      },
      // Desktop
      1024: {
        slidesPerView: 3,
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
  
  // Add active class to visible slides
  testimonialSwiper.on('slideChange', function() {
    const slides = document.querySelectorAll('.testimonial-swiper .swiper-slide');
    slides.forEach(slide => {
      slide.classList.remove('active-slide');
    });
    
    // Find visible slides and add active class
    setTimeout(() => {
      const activeSlides = document.querySelectorAll('.testimonial-swiper .swiper-slide-visible');
      activeSlides.forEach(slide => {
        slide.classList.add('active-slide');
      });
    }, 100);
  });
  
  // Trigger initial slide change to set active slides
  setTimeout(() => {
    testimonialSwiper.slideNext(0);
    testimonialSwiper.slidePrev(0);
  }, 200);
});
    </script>
    {!! $data->seo_section !!}
@stop


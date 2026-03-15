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
    @include("front.layouts.banner")
 





<section class="contact-wrapper">
     <div class="contact_sec">
        <div class="contact-us">
           <div class="container">
              <div class="row">
                 <div class="col-sm-12 col-md-12 col-lg-4  pl-0 pr-0">
                    <div class="cda-content-area">
                       <div class="contact-page-heading">
                          {!! $data->longDescription !!}
                       </div>
                       <div class="cda-single-content d-flex">
                          <div class="cda-icon">
                             <i class="fas fa-globe"></i>
                          </div>
                          <div class="cda-content-inner">
                             <h4>Our Location</h4>
                             <p>{!! $setting_data['address'] ?? '#' !!} </p>
                          </div>
                       </div>
                       <div class="cda-single-content  d-flex">
                          <div class="cda-icon">
                             <i class="fas fa-mail-bulk"></i>
                          </div>
                          <div class="cda-content-inner">
                             <h4> E-Mail Address</h4>
                             <p><a href="mailto:{!! $setting_data['email'] ?? '#' !!}">{!! $setting_data['email'] ?? '#' !!}</a></p>
                          </div>
                       </div>
                       <div class="cda-single-content  d-flex">
                          <div class="cda-icon">
                             <i class="fas fa-headset"></i>
                          </div>
                          <div class="cda-content-inner">
                             <h4>Phone Number</h4>
                             <p><a href="tel:{!! $setting_data['mobile'] ?? '#' !!}">{!! $setting_data['mobile'] ?? '#' !!}</a></p>
                          </div>
                       </div>
                       <div class="cda-single-content  d-flex">
                       </div>
                    </div>
                 </div>
                 <div class="col-sm-12 col-md-12 col-lg-8 pl-0 pr-0">
                    <div class="contact_from_box">
                       <div class="our-contact-heading">
                          <div class="heading">
                             <h6>Contact Form</h6>
                             <p>Please inquire by filing out the form below.</p>
                             <div class="border_line"></div>
                          </div>
                         
                       </div>
                         <div class="main">
                 
                        <div class="form">
                    {!! Form::open(["route"=>"contactPost","id"=>"wendy-contact-us","class"=>"form-wrapper"])  !!}
                                <div class="form-floating">
                                    <input
                                        class="form-control"
                                        type="text"
                                        name="name"
                                        required="required"
                                        id="name" placeholder="Enter Name" title="Enter Name"
                                        />
                                    <label for="name">Full Name</label>
                                </div>
                                <div class="form-floating">
                                    <input
                                        class="form-control"
                                        type="email"
                                        name="email"
                                        required="required"
                                        id="email" placeholder="Enter Email" title="Enter Email"
                                        />
                                    <label for="email">Email</label>
                                </div>
                                <div class="form-floating">
                                    <input
                                        class="form-control"
                                        type="tel"
                                        name="mobile"
                                        required="required"
                                        id="phone" placeholder="Enter Number" title="Enter Phone Number"
                                        />
                                    <label for="phone">Phone number</label>
                                </div>
                                <div class="form-floating">
                                        <textarea class="form-control" placeholder="Enter Message" title="Enter Message" id="floatingTextarea" name="message"></textarea>
                          <label for="floatingTextarea">Message</label>
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
                                <div class="quote_btn mt-4">
                                   <button class="main-btn" type="submit">Send Message</button>
                                </div>
                                {!! Form::close() !!}
                </div>
                        </div>
                       <p class="form-message"></p>
                    </div>
                 </div>
              </div>
           </div>
        </div>
     </div>
  </section>
  


@if($setting_data['map'])
                        
                            <div class="map" >
                                <iframe src="{!! $setting_data['map'] ?? '' !!}" width="100%" height="350"></iframe>
                            </div>
                       
                    @endif










    

{!! $data->seo_section !!}
@stop

@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/contact.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/contact-responsive.css" />
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/contact.js" ></script>
@stop
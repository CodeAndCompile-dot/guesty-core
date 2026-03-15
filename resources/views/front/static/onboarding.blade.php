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
              
                 <div class="col-sm-12 col-md-12 col-lg-12 pl-0 pr-0">
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
                    {!! Form::open(["route"=>"onboardingPost","id"=>"wendy-contact-us1","class"=>"form-wrapper","files"=>true])  !!}
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("first_name",null,["class"=>"form-control","placeholder"=>"Enter First Name", "title"=>"Enter First Name","required"]) !!}
                                        <label for="name">First Name*</label>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("last_name",null,["class"=>"form-control","placeholder"=>"Enter Last Name", "title"=>"Enter Last Name","required"]) !!}
                                        <label for="name">Last Name*</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::email("email",null,["class"=>"form-control","placeholder"=>"Enter Email ID", "title"=>"Enter Email ID","required"]) !!}
                                        <label for="name">Email ID*</label>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("mobile",null,["class"=>"form-control","placeholder"=>"Enter Mobile", "title"=>"Enter Mobile","required"]) !!}
                                        <label for="name">Mobile*</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input required class="form-control" name="bill_to_address" placeholder="Enter Bill to address" title="Enter Bill to address" id="floatingTextarea" >
                                        <label for="floatingTextarea">Bill to address*</label>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input required class="form-control" name="rental_property_address" placeholder="Enter Rental property address" title="Enter Rental property address" id="floatingTextarea" >
                                        <label for="floatingTextarea">Rental property address*</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("owner_birthday",null,["class"=>"form-control datepicker","placeholder"=>"Enter Owner's Birthday -The person who's name accuntswill be setup under", "title"=>"Enter Owner's Birthday -The person who's name accuntswill be setup under","required"]) !!}
                                        <label for="name">Owner's Birthday -The person who's name accunts will be setup under*</label>
                                    </div>
                                </div>
                                  
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("company_name",null,["class"=>"form-control","placeholder"=>"If you will be associating a company with your accounts, provide the company name", "title"=>"If you will be associating a company with your accounts, provide the company name"]) !!}
                                        <label for="name">If you will be associating a company with your accounts, provide the company name</label>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Front Of Driver's License</label>
                                        {!! Form::file("file1",null,["class"=>"form-control"]) !!}
                                        
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Back of driver's license</label>
                                        {!! Form::file("file2",null,["class"=>"form-control"]) !!}
                                        
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("social_security_number",null,["class"=>"form-control","placeholder"=>"Enter Social security number", "title"=>"Enter Social security number"]) !!}
                                        <label for="name">Social security number</label>
                                    </div>
                                </div>
                                
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("business_ein_number",null,["class"=>"form-control","placeholder"=>"Enter Business EIN nmer", "title"=>"Enter Business EIN nmer"]) !!}
                                        <label for="name">Business EIN nmer</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        {!! Form::text("routing_number_of_deposites",null,["class"=>"form-control","placeholder"=>"Enter Routing number for deposits", "title"=>"Enter Routing number for deposits"]) !!}
                                        <label for="name">Routing number for deposits</label>
                                    </div>
                                </div>
                 
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("account_number",null,["class"=>"form-control","placeholder"=>"Enter Account number for deposits", "title"=>"Enter Account number for deposits","required"]) !!}
                                        <label for="name">Account number for deposits*</label>
                                    </div>
                                </div>
                 
                                  
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("account_name",null,["class"=>"form-control","placeholder"=>"Enter Name or business name on credit card", "title"=>"Enter Name or business name on credit card","required"]) !!}
                                        <label for="name">Name or business name on credit card*</label>
                                    </div>
                                </div>
                 
                                <div class="col-12">
                                    <div class="form-floating">
                                        {!! Form::text("account_card_number",null,["class"=>"form-control","placeholder"=>"Enter Credit card", "title"=>"Enter Credit card","required"]) !!}
                                        <label for="name">Credit card*</label>
                                    </div>
                                </div>
                 
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("account_exp",null,["class"=>"form-control","placeholder"=>"Enter Credit card expiration date", "title"=>"Enter Credit card expiration date","required"]) !!}
                                        <label for="name">Credit card expiration date*</label>
                                    </div>
                                </div>
                 
                                  
                                <div class="col-6">
                                    <div class="form-floating">
                                        {!! Form::text("account_cvv",null,["class"=>"form-control","placeholder"=>"Enter Credit card CVV", "title"=>"Enter Credit card CVV","required"]) !!}
                                        <label for="name">Credit card CVV*</label>
                                    </div>
                                </div>

                    
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea required class="form-control" name="housekeeping_closet_access" placeholder="Enter Notes for the housekeeping team including housekeeper's closet access" title="Enter Notes for the housekeeping team including housekeeper's closet access" id="floatingTextarea"  required ></textarea>
                                        <label for="floatingTextarea">Notes for the housekeeping team including housekeeper's closet access*</label>
                                    </div>
                                </div>

                    
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea required class="form-control" name="wifi_lock_Access" placeholder="Enter Provide wifi lock access login instructions" title="Enter Provide wifi lock access login instructions" id="floatingTextarea" required></textarea>
                                        <label for="floatingTextarea">Provide wifi lock access login instructions*</label>
                                    </div>
                                </div>

                    
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea required class="form-control" name="security_camera_login_instruction" placeholder="Enter Provide security camera login instructions" title="Enter Provide security camera login instructions" id="floatingTextarea" required></textarea>
                                        <label for="floatingTextarea">Provide security camera login instructions*</label>
                                    </div>
                                </div>
                            </div>
                                
                                
                        
                           
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





    

{!! $data->seo_section !!}
@stop

@section("css")
    @parent
    <link rel="stylesheet" href="{{ asset('front')}}/css/contact.css" />
    <link rel="stylesheet" href="{{ asset('front')}}/css/contact-responsive.css" />
    <style>.col-6 {
    padding-right: 20px;
}</style>
@stop 
@section("js")
    @parent
    <script src="{{ asset('front')}}/js/contact.js" ></script>
@stop
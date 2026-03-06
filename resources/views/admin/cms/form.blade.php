<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("name") !!}
            {!! Form::text("name",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("name")}}</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
          <label>SEO URL ( Only A-z,0-9,_,- are allowed)</label>
            {!! Form::text("seo_url",null,["class"=>"form-control", "readonly","pattern"=>"[a-zA-Z0-9-_]+", "title"=>"Enter Valid SEO URL", "oninvalid"=>"this.setCustomValidity('SEO URL is not Valid Please enter first letter must be a-z and only accept chars a-z 0-9,-,_')" ,"onchange"=>"try{setCustomValidity('')}catch(e){}", "oninput"=>"setCustomValidity(' ')","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("seo_url")}}</span>
        </div>
    </div>

   @isset($data)
    <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("bannerImage") !!}
            {!! Form::file("bannerImage",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("bannerImage")}}</span>
             @isset($data)
                @if($data->bannerImage!="")
                     <img src="{{ asset(($data->bannerImage)) }}" width="200" >
                @endif
            @endisset
        </div>
    </div>
      <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("image") !!}
            {!! Form::file("image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image")}}</span>
             @isset($data)
                @if($data->image!="")
                     <img src="{{ asset(($data->image)) }}" width="200" >
                @endif
            @endisset
        </div>
    </div>
  @endisset
    @isset($data)
        @if($data->seo_url=="home")
                <div class="col-md-3 ">
        <div class="form-group">

              {!! Form::label("Owner Image") !!}
              {!! Form::file("image_2",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image_2")}}</span>
             @isset($data)
                @if($data->image_2!="")
                     <img src="{{ asset(($data->image_2)) }}" width="200" >
                @endif
            @endisset
            </div>
            </div>
        @endif
        @if($data->seo_url=="about-us")

                <div class="col-md-3 d-none">
        <div class="form-group">
              {!! Form::label("image_2") !!}
              {!! Form::file("image_2",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image_2")}}</span>
             @isset($data)
                @if($data->image_2!="")
                     <img src="{{ asset(($data->image_2)) }}" width="200" >
                @endif
              @endisset
                </div>
                </div>
        @endif
    @endisset
    <div class="col-md-12 d-none">
        <div class="form-group">
            {!! Form::label("template") !!}
            {!! Form::select("templete",Helper::getTempletes(),null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("templete")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("publish") !!}
            {!! Form::select("publish",["published"=>"published","draft"=>"draft","pending"=>"pending"],null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("publish")}}</span>
        </div>
    </div>


</div>
    @isset($data)
@if($data->seo_url=="home")

<div class="row ">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("strip_title") !!}
            {!! Form::text("strip_title",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("strip_title")}}</span>
        </div>
        <div class="form-group">
            {!! Form::label("strip_anchor") !!}
            {!! Form::text("strip_anchor",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("strip_anchor")}}</span>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("strip_description") !!}
            {!! Form::textarea("strip_desction",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("strip_desction")}}</span>
        </div>
    </div>

    <div class="col-md-4 ">
        <div class="form-group">
            {!! Form::label("strip_image") !!}
            {!! Form::file("strip_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("strip_image")}}</span>
             @isset($data)
                @if($data->strip_image!="")
                     <img src="{{ asset(($data->strip_image)) }}" width="200" >
                @endif
            @endisset
        </div>
    </div>
    </div>
    <div class="row ">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("about_us_image") !!}
            {!! Form::file("section_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("section_image")}}</span>
             @isset($data)
                @if($data->section_image!="")
                     <img src="{{ asset(($data->section_image)) }}" width="200" >
                @endif
            @endisset
        </div>
</div>
<div class="row ">
        <div class="form-group">
            {!! Form::label("about_us_image2") !!}
            {!! Form::file("image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image")}}</span>
             @isset($data)
                @if($data->image!="")
                     <img src="{{ asset(($data->image)) }}" width="200" >
                @endif
            @endisset
        </div>
</div>
    <div class="col-md-8 d-none">
        <div class="form-group">
            {!! Form::label("about_us_description") !!}
            {!! Form::textarea("section_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section_desc")}}</span>
        </div>
    </div>

</div>
@elseif( $data->seo_url=="about-us")
<div class="row ">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("about_us_image") !!}
            {!! Form::file("section_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("section_image")}}</span>
             @isset($data)
                @if($data->section_image!="")
                     <img src="{{ asset(($data->section_image)) }}" width="200" >
                @endif
            @endisset
        </div>
</div>
<div class="row ">
        <div class="form-group d-none">
            {!! Form::label("about_us_image2") !!}
            {!! Form::file("image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image")}}</span>
             @isset($data)
                @if($data->image!="")
                     <img src="{{ asset(($data->image)) }}" width="200" >
                @endif
            @endisset
        </div>
</div>
    <div class="col-md-8 d-none">
        <div class="form-group">
            {!! Form::label("about_us_description") !!}
            {!! Form::textarea("section_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section_desc")}}</span>
        </div>
    </div>
</div>
@endif
@endisset

@isset($data)
@if($data->seo_url == 'property-management')
<div class="row">
     <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Genuine Partnership & Personalization Section Image") !!}
                {!! Form::file("section2_img",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section2_img")}}</span>
                 @isset($data)
                    @if($data->section2_img!="")
                         <img src="{{ asset(($data->section2_img)) }}" width="200" >
                    @endif
                @endisset
         </div>
     </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label("Genuine Partnership & Personalization Description") !!}
            {!! Form::textarea("section2_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section2_desc")}}</span>
        </div>
    </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label("Protecting Your Investments Description") !!}
            {!! Form::textarea("section3_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section3_desc")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Your Home Section Image") !!}
                {!! Form::file("section4_main_img",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section4_main_img")}}</span>
                 @isset($data)
                    @if($data->section4_main_img!="")
                         <img src="{{ asset(($data->section4_main_img)) }}" width="200" >
                    @endif
                @endisset
         </div>
     </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label("Your Home Section Description") !!}
            {!! Form::textarea("section4_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_desc")}}</span>
        </div>
    </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub heading1") !!}
            {!! Form::textarea("section4_sub_heading1",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_heading1")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Your Home sub Image1") !!}
                {!! Form::file("section4_sub_icon1",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section4_sub_icon1")}}</span>
                 @isset($data)
                    @if($data->section4_sub_icon1!="")
                         <img src="{{ asset(($data->section4_sub_icon1)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub Description1") !!}
            {!! Form::textarea("section4_sub_desc1",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_desc1")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub heading2") !!}
            {!! Form::textarea("section4_sub_heading2",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_heading2")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Your Home sub Image2") !!}
                {!! Form::file("section4_sub_icon2",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section4_sub_icon2")}}</span>
                 @isset($data)
                    @if($data->section4_sub_icon2!="")
                         <img src="{{ asset(($data->section4_sub_icon2)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub Description2") !!}
            {!! Form::textarea("section4_sub_desc2",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_desc2")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub heading3") !!}
            {!! Form::textarea("section4_sub_heading3",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_heading3")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Your Home sub Image3") !!}
                {!! Form::file("section4_sub_icon3",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section4_sub_icon3")}}</span>
                 @isset($data)
                    @if($data->section4_sub_icon3!="")
                         <img src="{{ asset(($data->section4_sub_icon3)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Your Home sub Description3") !!}
            {!! Form::textarea("section4_sub_desc3",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section4_sub_desc3")}}</span>
        </div>
    </div>

    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Peace of Mind Section Image") !!}
                {!! Form::file("section5_main_img",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section5_main_img")}}</span>
                 @isset($data)
                    @if($data->section5_main_img!="")
                         <img src="{{ asset(($data->section5_main_img)) }}" width="200" >
                    @endif
                @endisset
         </div>
     </div>
    <div class="col-md-8">
        <div class="form-group">
            {!! Form::label("Peace of Mind Section Description") !!}
            {!! Form::textarea("section5_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_desc")}}</span>
        </div>
    </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub heading1") !!}
            {!! Form::textarea("section5_sub_heading1",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_heading1")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Peace of Mind sub Image1") !!}
                {!! Form::file("section5_sub_icon1",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section5_sub_icon1")}}</span>
                 @isset($data)
                    @if($data->section5_sub_icon1!="")
                         <img src="{{ asset(($data->section5_sub_icon1)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub Description1") !!}
            {!! Form::textarea("section5_sub_desc1",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_desc2")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub heading2") !!}
            {!! Form::textarea("section5_sub_heading2",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_heading2")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Peace of Mind sub Image2") !!}
                {!! Form::file("section5_sub_icon2",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section5_sub_icon2")}}</span>
                 @isset($data)
                    @if($data->section5_sub_icon2!="")
                         <img src="{{ asset(($data->section5_sub_icon2)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub Description2") !!}
            {!! Form::textarea("section5_sub_desc2",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_desc2")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub heading3") !!}
            {!! Form::textarea("section5_sub_heading3",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_heading3")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Peace of Mind sub Image3") !!}
                {!! Form::file("section5_sub_icon3",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section5_sub_icon3")}}</span>
                 @isset($data)
                    @if($data->section5_sub_icon3!="")
                         <img src="{{ asset(($data->section5_sub_icon3)) }}" width="50" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Peace of Mind sub Description3") !!}
            {!! Form::textarea("section5_sub_desc3",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section5_sub_desc3")}}</span>
        </div>
    </div>
     <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("Design & Experience section Description3") !!}
            {!! Form::textarea("section6_desc",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("section6_desc")}}</span>
        </div>
    </div>
    <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Design & Experience Image1") !!}
                {!! Form::file("section6_img1",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section6_img1")}}</span>
                 @isset($data)
                    @if($data->section6_img1!="")
                         <img src="{{ asset(($data->section6_img1)) }}" width="100" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Design & Experience Image2") !!}
                {!! Form::file("section6_img2",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section6_img2")}}</span>
                 @isset($data)
                    @if($data->section6_img2!="")
                         <img src="{{ asset(($data->section6_img2)) }}" width="100" >
                    @endif
                @endisset
         </div>
     </div>
     <div class="col-md-4">
         <div class="form-group">
                {!! Form::label("Design & Experience Image3") !!}
                {!! Form::file("section6_img3",["class"=>"form-control"]) !!}
                <span class="text-danger">{{ $errors->first("section6_img3")}}</span>
                 @isset($data)
                    @if($data->section6_img3!="")
                         <img src="{{ asset(($data->section6_img3)) }}" width="100" >
                    @endif
                @endisset
         </div>
     </div>

</div>
@endif
@endisset

<div class="row d-none">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("description") !!}
            {!! Form::textarea("description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("description")}}</span>
        </div>
    </div>
</div>
<div class="row ">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("shortDescription") !!}
            {!! Form::textarea("shortDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("shortDescription")}}</span>
        </div>
    </div>

</div>
<div class="row ">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("mediumDescription") !!}
            {!! Form::textarea("mediumDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("mediumDescription")}}</span>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("longDescription") !!}
            {!! Form::textarea("longDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("longDescription")}}</span>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("seo_section") !!}
            {!! Form::textarea("seo_section",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("seo_section")}}</span>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-12 alert alert-warning text-center">
        <h3>Seo Section</h3>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("meta_title") !!}
            {!! Form::textarea("meta_title",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_title")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("meta_keywords") !!}
            {!! Form::textarea("meta_keywords",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_keywords")}}</span>
        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("ogimage") !!}
            {!! Form::file("ogimage",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("ogimage")}}</span>
             @isset($data)
                @if($data->ogimage!="")
                     <img src="{{ asset(($data->ogimage)) }}" width="100" />
                @endif
            @endisset
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("meta_description") !!}
            {!! Form::textarea("meta_description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_description")}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("header_section") !!}
            {!! Form::textarea("header_section",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("header_section")}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("footer_section") !!}
            {!! Form::textarea("footer_section",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("footer_section")}}</span>
        </div>
    </div>
</div>

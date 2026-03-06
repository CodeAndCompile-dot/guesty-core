<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("name") !!}
            {!! Form::text("name",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("name")}}</span>
        </div>
    </div>
 
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("seo_url") !!}
            {!! Form::text("seo_url",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("seo_url")}}</span>
        </div>
    </div>
 
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("banner_heading") !!}
            {!! Form::text("banner_heading",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("banner_heading")}}</span>
        </div>
    </div>
 
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("banner_sub_heading") !!}
            {!! Form::text("banner_sub_heading",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("banner_sub_heading")}}</span>
        </div>
    </div>

    <div class="col-md-4 ">
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
 
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("vacation_heading") !!}
            {!! Form::text("vacation_heading",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_heading")}}</span>
        </div>
    </div>
 
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("vacation_sub_heading") !!}
            {!! Form::text("vacation_sub_heading",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_sub_heading")}}</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_one_title") !!}
            {!! Form::text("vacation_one_title",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_one_title")}}</span>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_one_link") !!}
            {!! Form::text("vacation_one_link",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_one_link")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_one_alt") !!}
            {!! Form::text("vacation_one_alt",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_one_alt")}}</span>
        </div>
    </div>
    
    <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("vacation_one_image") !!}
            {!! Form::file("vacation_one_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_one_image")}}</span>
             @isset($data)
                @if($data->vacation_one_image!="")
                     <img src="{{ asset(($data->vacation_one_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_two_title") !!}
            {!! Form::text("vacation_two_title",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_two_title")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_two_link") !!}
            {!! Form::text("vacation_two_link",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_two_link")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_two_alt") !!}
            {!! Form::text("vacation_two_alt",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_two_alt")}}</span>
        </div>
    </div>
    <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("vacation_two_image") !!}
            {!! Form::file("vacation_two_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_two_image")}}</span>
             @isset($data)
                @if($data->vacation_two_image!="")
                     <img src="{{ asset(($data->vacation_two_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_three_title") !!}
            {!! Form::text("vacation_three_title",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_three_title")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_three_link") !!}
            {!! Form::text("vacation_three_link",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_three_link")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_three_alt") !!}
            {!! Form::text("vacation_three_alt",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_three_alt")}}</span>
        </div>
    </div>
    <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("vacation_three_image") !!}
            {!! Form::file("vacation_three_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_three_image")}}</span>
             @isset($data)
                @if($data->vacation_three_image!="")
                     <img src="{{ asset(($data->vacation_three_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_four_title") !!}
            {!! Form::text("vacation_four_title",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_four_title")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_four_link") !!}
            {!! Form::text("vacation_four_link",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_four_link")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("vacation_four_alt") !!}
            {!! Form::text("vacation_four_alt",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_four_alt")}}</span>
        </div>
    </div>
    
    <div class="col-md-3 ">
        <div class="form-group">
            {!! Form::label("vacation_four_image") !!}
            {!! Form::file("vacation_four_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("vacation_four_image")}}</span>
             @isset($data)
                @if($data->vacation_four_image!="")
                     <img src="{{ asset(($data->vacation_four_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>



    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("templete") !!}
            {!! Form::select("templete",Helper::getTempletes(),'common',["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("templete")}}</span>
        </div>
    </div>
    <div class="col-md-4  d-none">
        <div class="form-group">
            {!! Form::label("publish") !!}
            {!! Form::select("publish",["published"=>"published","draft"=>"draft","pending"=>"pending"],'published',["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("publish")}}</span>
        </div>
    </div>

   

        
</div>
<div class="row">
    <div class="col-md-12">
         <div class=" attraction-area">
             <div class="row">
                 <div class="col-md-12">
                    <h3>Attraction Section</h3>
                
             
                 </div>
             </div>
            @isset($data)
                @if($data->attraction_secion!="")
                @php $c=100;
                $attractions=json_decode($data->attraction_secion,true);
                @endphp
                @foreach($attractions as $cat)
                     <div class="row gaya-data-attraction">
                 
                     <div class="col-md-1">
                       <label>Attraction  Action</label>
                          <button type="button" class="btn btn-danger btn-delete-more"><i class="fa fa-times"></i> </button>
                     </div>
                     <div class="col-md-3">
                        <label>Attraction  Heading</label>
                         <input type="text" name="attraction_heading[]" value="{{ $cat['attraction_heading'] }}" class="form-control" required />
                         <br>
                         <label>Attraction  Image</label>
                         <input type="file" name="attraction_image[]"  />
                         @if($cat['attraction_image'])
                            <img src="{{ asset(($cat['attraction_image'])) }}" width="200" > 
                            <input type="hidden" name="attraction_image_hidden[]" value="{{$cat['attraction_image']}}" />
                         @endif
                           <br>
                         <label>Image alt</label>
                         <input type="text" name="attraction_title[]"  value="{{ $cat['attraction_title'] ?? '' }}" class="form-control"  />
                         
                     </div>
                     <div class="col-md-8">
                            <label>Attraction  Content</label>
                          <textarea class="ckeditor"  name="attraction_content[]" required>{!! $cat['attraction_content'] !!}</textarea>
                     </div>
                     
                     <div class="col-md-12"><hr></div>
                 </div>
                 @php $c++; @endphp
                 @endforeach
                @endif
            @endisset
           
         </div>
        <button type="button" class="btn btn-warning  btn-add-more"><i class="fa fa-plus"></i> Add  Attraction</button>
    </div>
 </div>
<div class="row">
    <div class="col-md-12">
         <div class=" video-area">
             <div class="row">
                 <div class="col-md-12">
                    <h3>Video Section</h3>
                
             
                 </div>
             </div>
              @isset($data)
                @if($data->video_section!="")
                @php $c=100;
                $attractions=json_decode($data->video_section,true);
                @endphp
                @foreach($attractions as $cat)
                     <div class="row gaya-data-attraction">
                 
                     <div class="col-md-1">
                       <label>Video  Action</label>
                          <button type="button" class="btn btn-danger btn-delete-more"><i class="fa fa-times"></i> </button>
                     </div>
                     <div class="col-md-3">
                        <label>Video  Heading</label>
                         <input type="text" name="video_heading[]" value="{{ $cat['video_heading'] }}"  class="form-control" required />
                         <br>
                         <label>Youtube link</label>
                         <input type="text" name="video_link[]"  value="{{ $cat['video_link'] }}"  class="form-control" required />
                     </div>
                     <div class="col-md-8">
                            <label>Video  Content</label>
                          <textarea class="ckeditor" name="video_content[]" required>{!! $cat['video_content'] !!}</textarea>
                     </div>
                     
                     <div class="col-md-12"><hr></div>
                 </div>
                 @php $c++; @endphp
                 @endforeach
                @endif
            @endisset
           
         </div>
        <button type="button" class="btn btn-warning btn-add-more-video"><i class="fa fa-plus"></i> Add  Video</button>
    </div>
 </div>
<div class="row ">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("Footer Heading") !!}
            {!! Form::textarea("description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("description")}}</span>
        </div>
    </div>
</div>
<div class="row d-none">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("shortDescription") !!}
            {!! Form::textarea("shortDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("shortDescription")}}</span>
        </div>
    </div>
  
</div>
<div class="row d-none">
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
            {!! Form::label("Footer keywords") !!}
            {!! Form::textarea("longDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("longDescription")}}</span>
        </div>
    </div>
  
</div>

<div class="row">
    <div class="col-md-12 alert alert-warning text-center">
        <h3>Seo Section</h3>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("meta_title") !!}
            {!! Form::textarea("meta_title",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_title")}}</span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("meta_keywords") !!}
            {!! Form::textarea("meta_keywords",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_keywords")}}</span>
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
            {!! Form::label("header_section (only_for_html_code)") !!}
            {!! Form::textarea("header_section",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("header_section")}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("footer_section (only_for_html_code)") !!}
            {!! Form::textarea("footer_section",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("footer_section")}}</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("first_name") !!}
            {!! Form::text("first_name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("first_name")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("last_name") !!}
            {!! Form::text("last_name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("last_name")}}</span>
        </div>
    </div>
        <div class="col-md-4">
        <div class="form-group">
          <label>SEO URL ( Only A-z,0-9,_,- are allowed)</label>
            {!! Form::text("seo_url",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("seo_url")}}</span>
        </div>
    </div>
    <div class="col-md-4">
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
     <div class="col-md-4 d-none ">
        <div class="form-group">
            {!! Form::label("contactImage") !!}
            {!! Form::file("contactImage",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("contactImage")}}</span>
             @isset($data)
                @if($data->contactImage!="")
                     <img src="{{ asset(($data->contactImage)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>
    
    
    <div class="col-md-4">
        <div class="form-group">
            
            {!! Form::label("email") !!}
            {!! Form::email("email",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("email")}}</span>
            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            
            {!! Form::label("mobile") !!}
            {!! Form::text("mobile",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("mobile")}}</span>
            
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            
            {!! Form::label("profile") !!}
            {!! Form::text("profile",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("profile")}}</span>
            
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
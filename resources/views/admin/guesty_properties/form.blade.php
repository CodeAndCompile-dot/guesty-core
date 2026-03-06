<h4 class="text-warning">Seo Section</h4>
<div class="row">
  
    <div class="col-md-6">
        <div class="form-group">
          {!! Form::label("Location") !!}
          {!! Form::select("location_id",ModelHelper::getParentLocationSelectList(),$data->location_id,["id"=>"location_id","class"=>"form-control","placeholder"=>"Choose Location"]) !!}
        </div>
      </div>
  
  
    <div class="col-md-6">
        <div class="form-group">
          {!! Form::label("Sub Location") !!}
          {!! Form::select("sub_location_id",[],null,["class"=>"form-control","placeholder"=>"Choose Sub Location","id"=>"sub_location_id"]) !!}
        </div>
      </div>
  
  
  
  <div class="col-md-3">
    <div class="form-group">
         <label>SEO URL ( Only A-z,0-9,_,- are allowed)*</label>
            {!! Form::text("seo_url",null,["class"=>"form-control", "pattern"=>"[a-zA-Z0-9-_]+", "title"=>"Enter Valid SEO URL", "oninvalid"=>"this.setCustomValidity('SEO URL is not Valid Please enter first letter must be a-z and only accept chars a-z 0-9,-,_')" ,"onchange"=>"try{setCustomValidity('')}catch(e){}", "oninput"=>"setCustomValidity(' ')","required"=>"required"]) !!}
      <span class="text-danger">{{ $errors->first("seo_url") }}</span>
    </div>
  </div>
     <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("ordering") !!}
            {!! Form::number("ordering",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("ordering")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
          {!! Form::label("status") !!}
          {!! Form::select("status",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
        </div>
      </div>
    <div class="col-md-3">
        <div class="form-group">
          {!! Form::label("Home page show") !!}
          {!! Form::select("is_home",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
        </div>
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
            {!! Form::label("meta_description") !!}
            {!! Form::textarea("meta_description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("meta_description")}}</span>
        </div>
    </div>

    <div class="col-md-4 d-none">
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
    

    <div class="col-md-3">
        <div class="form-group d-none">
            {!! Form::label("banner_image") !!}
            {!! Form::file("banner_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("banner_image")}}</span>
             @isset($data)
                @if($data->banner_image!="")
                     
                     
                     <img src="{{ asset(($data->banner_image)) }}" width="200" />
                @endif
            @endisset
        </div>
    </div>
    
    
    <div class="col-md-3 d-none">
        <div class="form-group">
            {!! Form::label("feature_image") !!}
            {!! Form::file("feature_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("feature_image")}}</span>
             @isset($data)
                @if($data->feature_image!="")
                     <img src="{{ asset(($data->feature_image)) }}" width="200" />
                @endif
            @endisset
        </div>
    </div>
    
    
    
    <div class="col-md-3 d-none">
        <div class="form-group">
            {!! Form::label("booklet") !!}
            {!! Form::file("booklet",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("booklet")}}</span>
             @isset($data)
                @if($data->booklet!="")
                     <a href="{{ asset(($data->booklet)) }}" target="_BLANK" >Attachment</a>  <br>
                     
                
                @endif
            @endisset
        </div>
    </div>
    
    

</div>
<div class="row d-none">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("map") !!}
            {!! Form::textarea("map",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("map")}}</span>
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        {!! Form::label("rental_aggrement_attachment") !!}
        {!! Form::file("rental_aggrement_attachment",["class"=>"form-control"]) !!}
        <span class="text-danger">{{ $errors->first("rental_aggrement_attachment")}}</span>
         @isset($data)
            @if($data->rental_aggrement_attachment!="")
                 <a href="{{ asset(($data->rental_aggrement_attachment)) }}" download>download</a>
            @endif
        @endisset
    </div>
</div>
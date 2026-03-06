<div class="row">


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
<div class="col-md-12">
        <div class="form-group">
            {!! Form::label("property_id*") !!}
            {!! Form::select("location_id",ModelHelper::getProperptySelectList(),null,["class"=>"form-control","placeholder"=>"Choose Property","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("heading") }}</span>
        </div>
    </div>
   

   
</div>



<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("Email Body") !!}
            {!! Form::textarea("longDescription",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("longDescription")}}</span>
        </div>
    </div>
  
</div>

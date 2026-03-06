<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("title") !!}
            {!! Form::text("title",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("title")}}</span>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("Page*") !!}
            {!! Form::select("cms_id",ModelHelper::getPageSelectList(),null,["class"=>"form-control","placeholder"=>"Choose Page","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("cms_id") }}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("video") !!}
            {!! Form::file("image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("image")}}</span>
             @isset($data)
                @if($data->image!="")
                     <a href="{{ asset(($data->image)) }}" target="_BLANK" > Video</a> 
                @endif
            @endisset
        </div>
    </div>
  
    


    <div class="col-md-3">
        <div class="form-group">
            
            {!! Form::label("link") !!}
            {!! Form::text("link",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("link")}}</span>
            
        </div>
    </div>
   
</div>


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("description") !!}
            {!! Form::textarea("description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("description")}}</span>
        </div>
    </div>
   

</div>
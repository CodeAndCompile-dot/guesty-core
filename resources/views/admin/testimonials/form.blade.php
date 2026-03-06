<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("name") !!}
            {!! Form::text("name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("name")}}</span>
        </div>
    </div>


        
    <div class="col-md-4 d-none">
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

    <div class="col-md-4 d-none">
        <div class="form-group">
            
            {!! Form::label("email") !!}
            {!! Form::email("email",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("email")}}</span>
            
        </div>
    </div>
    <div class="col-md-3 d-none">
        <div class="form-group">
            
            {!! Form::label("stay_date") !!}
            {!! Form::text("stay_date",null,["class"=>"form-control datepicker"]) !!}
            <span class="text-danger">{{ $errors->first("stay_date")}}</span>
            
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            
            {!! Form::label("score") !!}
            {!! Form::selectRange("score",1,5,null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("score")}}</span>
            
        </div>
    </div>
 
    <div class="col-md-3 d-none">
        <div class="form-group">
            
            {!! Form::label("Property") !!}
            {!! Form::select("property_id",ModelHelper::getProperptySelectList(),null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("property_id")}}</span>
            
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            
            {!! Form::label("status") !!}
            {!! Form::select("status",["true"=>"true","false"=>"false"],null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("status")}}</span>
            
        </div>
    </div>
 
</div>


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("message") !!}
            {!! Form::textarea("message",null,["class"=>"form-control","rows"=>2]) !!}
            <span class="text-danger">{{ $errors->first("message")}}</span>
        </div>
    </div>

  


</div>

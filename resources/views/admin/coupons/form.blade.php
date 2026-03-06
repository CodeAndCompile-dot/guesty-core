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
            {!! Form::label("code") !!}
            {!! Form::text("code",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("code")}}</span>
        </div>
    </div>
 


<div class="col-md-6">
        <div class="form-group">
            {!! Form::label("property_id*") !!}
            {!! Form::select("property_id",ModelHelper::getProppertySelectList(),null,["class"=>"form-control","placeholder"=>"Choose Property","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("property_id") }}</span>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="form-group">
            {!! Form::label("type") !!}
            {!! Form::select("type",["per"=>"%","excat"=>"excat"],null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("type")}}</span>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="form-group">
            {!! Form::label("amount") !!}
            {!! Form::number("amount",null,["class"=>"form-control","required"=>"required"]) !!}
            <span class="text-danger">{{ $errors->first("amount")}}</span>
        </div>
    </div>

   
</div>

<div class="row ">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("description") !!}
            {!! Form::textarea("description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("description")}}</span>
        </div>
    </div>
</div>


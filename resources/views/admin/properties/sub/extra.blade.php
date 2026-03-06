


<div class="row d-none">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("tags") !!}
            {!! Form::text("tags",null,["class"=>"form-control","data-role"=>"tagsinput"]) !!}
            <span class="text-danger">{{ $errors->first("tags")}}</span>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("cancellation_policy") !!}
            {!! Form::textarea("cancellation_policy",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("cancellation_policy")}}</span>
        </div>
    </div>
   
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("booking_policy") !!}
            {!! Form::textarea("booking_policy",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("booking_policy")}}</span>
        </div>
    </div>
   
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("Damage and Incidentals") !!}
            {!! Form::textarea("short_description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("short_description")}}</span>
        </div>
    </div>
 
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("notes") !!}
            {!! Form::textarea("notes",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("notes")}}</span>
        </div>
    </div>
   
</div>




<div class="row d-none">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("type") !!}
            {!! Form::text("type",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("type")}}</span>
        </div>
    </div>

   
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("question") !!}
            {!! Form::textarea("question",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("question")}}</span>
        </div>
    </div>



</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("answer") !!}
            {!! Form::textarea("answer",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("answer")}}</span>
        </div>
    </div>
 


</div>
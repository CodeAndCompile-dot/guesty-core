<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("email") !!}
            {!! Form::email("email",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("email")}}</span>
        </div>
    </div>
   
</div>

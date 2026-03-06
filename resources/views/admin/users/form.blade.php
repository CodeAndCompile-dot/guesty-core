<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("name") !!}
            {!! Form::text("name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("name")}}</span>
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
            
            {!! Form::label("password") !!}
            {!! Form::password("password",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("password")}}</span>
            
        </div>
    </div>
   

</div>


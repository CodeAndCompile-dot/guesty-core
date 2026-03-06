@extends('admin.layouts')

@section('title', 'Admin ')

@section('content_header')
        <h1 class="m-0 text-dark">Change Password </h1>
@stop

@section('content')
		@parent

{!! Form::open(['route' => 'change-password.post','files'=>true]) !!}
<div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Change Password</h4>
      
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        
                <div class="row">
                  
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label("old password") !!}
                            {!! Form::text("old_password",null,["class"=>"form-control","required"=>"required"]) !!}
                            <span class="text-danger">{{ $errors->first("old_password")}}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label("New Password") !!}
                            {!! Form::text("new_password",null,["class"=>"form-control","required"=>"required"]) !!}
                            <span class="text-danger">{{ $errors->first("new_password")}}</span>
                        </div>
                    </div>
                </div>
                
               
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button class="btn btn-success"><span class="fa fa-save"></span> Save</button>
      </div>

    </div>
</div>
  {!! Form::close() !!}
@stop

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("first_name") !!}
            {!! Form::text("first_name",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("first_name")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("last_name") !!}
            {!! Form::text("last_name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("last_name")}}</span>
        </div>
    </div>
      
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("mobile") !!}
            {!! Form::text("mobile",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("mobile")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("email") !!}
            {!! Form::email("email",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("email")}}</span>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("bill_to_address") !!}
            {!! Form::text("bill_to_address",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("bill_to_address")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("rental_property_address") !!}
            {!! Form::text("rental_property_address",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("rental_property_address")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("owner_birthday") !!}
            {!! Form::text("owner_birthday",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("owner_birthday")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("company_name") !!}
            {!! Form::text("company_name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("company_name")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("file1") !!}
            {!! Form::file("file1",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("file1")}}</span>
            @if(isset($data))
                @if($data->file1)
                    <a href="{{ asset($data->file1) }}" target="_BLANK" >File</a>
                @endif
            @endif
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("file2") !!}
            {!! Form::file("file2",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("file2")}}</span>
            @if(isset($data))
                @if($data->file2)
                    <a href="{{ asset($data->file2) }}" target="_BLANK" >File</a>
                @endif
            @endif
        </div>
    </div>
    
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("social_security_number") !!}
            {!! Form::text("social_security_number",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("social_security_number")}}</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("business_ein_number") !!}
            {!! Form::text("business_ein_number",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("business_ein_number")}}</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("routing_number_of_deposites") !!}
            {!! Form::text("routing_number_of_deposites",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("routing_number_of_deposites")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("account_number") !!}
            {!! Form::text("account_number",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("account_number")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("account_name") !!}
            {!! Form::text("account_name",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("account_name")}}</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("account_card_number") !!}
            {!! Form::text("account_card_number",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("account_card_number")}}</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("account_exp") !!}
            {!! Form::text("account_exp",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("account_exp")}}</span>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::label("account_cvv") !!}
            {!! Form::text("account_cvv",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("account_cvv")}}</span>
        </div>
    </div>
      
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("housekeeping_closet_access") !!}
            {!! Form::textarea("housekeeping_closet_access",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("housekeeping_closet_access")}}</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("wifi_lock_Access") !!}
            {!! Form::textarea("wifi_lock_Access",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("wifi_lock_Access")}}</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("security_camera_login_instruction") !!}
            {!! Form::textarea("security_camera_login_instruction",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("security_camera_login_instruction")}}</span>
        </div>
    </div>
 
  
</div>

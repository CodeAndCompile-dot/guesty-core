<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("name") !!}
            {!! Form::text("name",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("name")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("mobile") !!}
            {!! Form::text("mobile",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("mobile")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("email") !!}
            {!! Form::email("email",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("email")}}</span>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("property_address") !!}
            {!! Form::text("property_address",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("property_address")}}</span>
        </div>
    </div>
    <!--<div class="col-md-4">-->
    <!--    <div class="form-group">-->
    <!--        {!! Form::label("property_type") !!}-->
    <!--        {!! Form::select("property_type",["Single Family"=>"Single Family","Condo"=>"Condo","Townhouse"=>"Townhouse","Commerical"=>"Commerical"],null,["class"=>"form-control","placeholder"=>"Choose Property Type"]) !!}-->
    <!--        <span class="text-danger">{{ $errors->first("property_type")}}</span>-->
    <!--    </div>-->
    <!--</div>-->
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("Select town") !!}
            {!! Form::select("property_type",Helper::getTownList(),null,["class"=>"form-control","placeholder"=>"Select town"]) !!}
            <span class="text-danger">{{ $errors->first("property_type")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("number_of_bedrooms") !!}
            {!! Form::selectRange("number_of_bedrooms",0,1000,null,["class"=>"form-control","placeholder"=>"Choose Number of Bedrooms"]) !!}
            <span class="text-danger">{{ $errors->first("number_of_bedrooms")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("number_of_bathrooms") !!}
            {!! Form::selectRange("number_of_bathrooms",0,1000,null,["class"=>"form-control","placeholder"=>"Choose Number of Bathrooms"]) !!}
            <span class="text-danger">{{ $errors->first("number_of_bathrooms")}}</span>
        </div>
    </div>
    
    <div class="col-md-8 d-none">
        <div class="form-group">
            {!! Form::label("Property Revenue Analysis") !!}
            {!! Form::text("what_is_your_rental_goal",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("what_is_your_rental_goal")}}</span>
        </div>
    </div>
    
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("what_are_you_looking_to_rent_your_property") !!}
            {!! Form::text("what_are_you_looking_to_rent_your_property",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("what_are_you_looking_to_rent_your_property")}}</span>
        </div>
    </div>
    
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("is_the_property_currently_closed") !!}
            {!! Form::select("is_the_property_currently_closed",["Yes"=>"Yes","No"=>"No"],null,["class"=>"form-control","placeholder"=>"Choose ----"]) !!}
            <span class="text-danger">{{ $errors->first("is_the_property_currently_closed")}}</span>
        </div>
    </div>
 <div class="col-md-12 d-none">
        <div class="form-group">
            {!! Form::label("message") !!}
            {!! Form::textarea("message",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("message")}}</span>
        </div>
    </div>

  
   
</div>

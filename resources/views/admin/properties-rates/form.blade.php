<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("start_date*") !!}
            {!! Form::text("start_date",null,["class"=>"form-control","required","id"=>"datepicker"]) !!}
            <span class="text-danger">{{ $errors->first("start_date")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("end_date*") !!}
            {!! Form::text("end_date",null,["class"=>"form-control","required","id"=>"datepicker1"]) !!}
            <span class="text-danger">{{ $errors->first("end_date")}}</span>
        </div>
    </div>



    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("Season Name*") !!}
            {!! Form::text("name_of_price",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("name_of_price")}}</span>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label("type_of_price*") !!}
            {!! Form::select("type_of_price",["default"=>"default","weekly"=>"weekly"],null,["class"=>"form-control","required","id"=>"type_of_price","placeholder"=>"Select Type of price"]) !!}
            <span class="text-danger">{{ $errors->first("type_of_price")}}</span>
        </div>
    </div>
    
</div>
<div id="price-section">
   
</div>


<div class="row">


    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("discount_weekly (%)") !!}
            {!! Form::number("discount_weekly",null,["class"=>"form-control","max"=>100,"min"=>0]) !!}
            <span class="text-danger">{{ $errors->first("discount_weekly")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("discount_monthly (%)") !!}
            {!! Form::number("discount_monthly",null,["class"=>"form-control","max"=>100,"min"=>0]) !!}
            <span class="text-danger">{{ $errors->first("discount_monthly")}}</span>
        </div>
    </div>

 

    <div class="col-md-3 d-none">
        <div class="form-group">
            {!! Form::label("is_available") !!}
            {!! Form::select("is_available",["0"=>"0","1"=>"1"],null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("is_available")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("platform_type") !!}
            {!! Form::text("platform_type",'airbnb',["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("platform_type")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("currency") !!}
            {!! Form::text("currency",'USD',["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("currency")}}</span>
        </div>
    </div>
    
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("notes") !!}
            {!! Form::text("notes",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("notes")}}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label("min_stay *") !!}
            {!! Form::text("min_stay",null,["class"=>"form-control","required"]) !!}
            <span class="text-danger">{{ $errors->first("min_stay")}}</span>
        </div>
    </div>
    <div class="col-md-4 d-none">
        <div class="form-group">
            {!! Form::label("base_min_stay") !!}
            {!! Form::text("base_min_stay",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("base_min_stay")}}</span>
        </div>
    </div>
   
	<div class="col-md-6 ">
		<div class="form-group">
			{!! Form::label("checkin_day") !!}
			{!! Form::select("checkin_day",Helper::getWeekNameSelect(),null,["class"=>"form-control","placeholder"=>"Checkin Day"]) !!}
		</div>
	</div>
	
	<div class="col-md-6 ">
		<div class="form-group">
			{!! Form::label("checkout_day") !!}
			{!! Form::select("checkout_day",Helper::getWeekNameSelect(),null,["class"=>"form-control","placeholder"=>"Checkout Day"]) !!}
		</div>
	</div>
    <input type="hidden" name="property_id" value="{{$property_id }}">
 
     

</div>

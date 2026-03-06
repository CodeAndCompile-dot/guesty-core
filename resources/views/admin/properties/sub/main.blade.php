<div class="row">
	 
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("name*") !!}
			{!! Form::text("name",null,["class"=>"form-control","placeholder"=>"Enter name","required"=>"required"]) !!}
			<span class="text-danger">{{ $errors->first("name") }}</span>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("heading*") !!}
			{!! Form::text("heading",null,["class"=>"form-control","placeholder"=>"Enter heading","required"=>"required"]) !!}
			<span class="text-danger">{{ $errors->first("heading") }}</span>
		</div>
	</div>
	
	
	<div class="col-md-4">
		<div class="form-group">
			   <label>SEO URL ( Only A-z,0-9,_,- are allowed)*</label>
            {!! Form::text("seo_url",null,["class"=>"form-control", "pattern"=>"[a-zA-Z0-9-_]+", "title"=>"Enter Valid SEO URL", "oninvalid"=>"this.setCustomValidity('SEO URL is not Valid Please enter first letter must be a-z and only accept chars a-z 0-9,-,_')" ,"onchange"=>"try{setCustomValidity('')}catch(e){}", "oninput"=>"setCustomValidity(' ')","required"=>"required"]) !!}
			<span class="text-danger">{{ $errors->first("seo_url") }}</span>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("location*") !!}
			{!! Form::select("location_id",ModelHelper::getLocationSelectList(),null,["class"=>"form-control","placeholder"=>"Choose Location","required"=>"required"]) !!}
			<span class="text-danger">{{ $errors->first("heading") }}</span>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("property_status") !!}
			{!! Form::select("property_status",Helper::getPropertyStatus(),null,["class"=>"form-control","placeholder"=>"Choose Property Status","required"]) !!}
			<span class="text-danger">{{ $errors->first("property_status") }}</span>
		</div>
	</div>

	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("status") !!}
			{!! Form::select("status",Helper::getBooleanDataActual(),null,["class"=>"form-control","required"]) !!}
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("Display price") !!}
			{!! Form::text("price",null,["class"=>"form-control","placeholder"=>"Enter price"]) !!}
			<span class="text-danger">{{ $errors->first("price") }}</span>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("mobile") !!}
			{!! Form::text("mobile",null,["class"=>"form-control","placeholder"=>"Enter mobile"]) !!}
			<span class="text-danger">{{ $errors->first("mobile") }}</span>
		</div>
	</div>
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("email") !!}
			{!! Form::email("email",null,["class"=>"form-control","placeholder"=>"Enter email"]) !!}
			<span class="text-danger">{{ $errors->first("email") }}</span>
		</div>
	</div>
	
	
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("vrbo_link") !!}
			{!! Form::text("vrbo_link",null,["class"=>"form-control","placeholder"=>"Enter VRBO LINK"]) !!}
			<span class="text-danger">{{ $errors->first("vrbo_link") }}</span>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("airbnb_link") !!}
			{!! Form::text("airbnb_link",null,["class"=>"form-control","placeholder"=>"Enter AIRBNB LINK"]) !!}
			<span class="text-danger">{{ $errors->first("airbnb_link") }}</span>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="form-group">
			{!! Form::label("ordering") !!}
			{!! Form::number("ordering",null,["class"=>"form-control","placeholder"=>"Enter ordering"]) !!}
			<span class="text-danger">{{ $errors->first("ordering") }}</span>
		</div>
	</div>
	
	
</div>
<div class="row">
	
	<div class="col-md-4 ">
		<div class="form-group">
			{!! Form::label("instant_booking_button") !!}
			{!! Form::select("instant_booking_button",Helper::getBooleanDataActual(),null,["class"=>"form-control","required"]) !!}
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("standard_rate") !!}
			{!! Form::number("standard_rate",null,["class"=>"form-control","placeholder"=>"Enter Standard Rate"]) !!}
			<span class="text-danger">{{ $errors->first("standard_rate") }}</span>
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("min_stay") !!}
			{!! Form::number("min_stay",null,["class"=>"form-control","placeholder"=>"Enter Min Stay"]) !!}
			<span class="text-danger">{{ $errors->first("min_stay") }}</span>
		</div>
	</div>
	
	<div class="col-md-2 ">
		<div class="form-group">
			{!! Form::label("checkin_day") !!}
			{!! Form::select("checkin_day",Helper::getWeekNameSelect(),null,["class"=>"form-control","placeholder"=>"Checkin Day"]) !!}
		</div>
	</div>
	
	<div class="col-md-2 ">
		<div class="form-group">
			{!! Form::label("checkout_day") !!}
			{!! Form::select("checkout_day",Helper::getWeekNameSelect(),null,["class"=>"form-control","placeholder"=>"Checkout Day"]) !!}
		</div>
	</div>
	
</div>

<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            {!! Form::label("feature_image") !!}
            {!! Form::file("feature_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("feature_image")}}</span>
             @isset($data)
                @if($data->feature_image!="")
                     <img src="{{ asset(($data->feature_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>
	<div class="col-md-6">
        <div class="form-group">
            {!! Form::label("banner_image") !!}
            {!! Form::file("banner_image",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("banner_image")}}</span>
             @isset($data)
                @if($data->banner_image!="")
                     <img src="{{ asset(($data->banner_image)) }}" width="200" > 
                @endif
            @endisset
        </div>
    </div>

</div>
<div class="row">
    
   @php  //put third party listing url only use for admin see @endphp
	<div class="col-md-12 d-none">
		<div class="form-group">
			{!! Form::label("website") !!}
			{!! Form::text("website",null,["class"=>"form-control","placeholder"=>"Enter website"]) !!}
			<span class="text-danger">{{ $errors->first("website") }}</span>
		</div>
	</div>
	
	<div class="col-md-12">
		<div class="form-group">
			{!! Form::label("address") !!}
			{!! Form::textarea("address",null,["class"=>"form-control","placeholder"=>"Enter address","rows"=>2]) !!}
			<span class="text-danger">{{ $errors->first("address") }}</span>
		</div>
	</div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("map iframe src") !!}
            {!! Form::textarea("map",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("map")}}</span>
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("listing description") !!}
            {!! Form::textarea("description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("description")}}</span>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("Property Detail Description") !!}
            {!! Form::textarea("long_description",null,["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("long_description")}}</span>
        </div>
    </div>
   

 
</div>





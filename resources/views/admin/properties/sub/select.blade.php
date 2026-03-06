<div class="row">
  

	<div class="col-md-3">
		<div class="form-group">
			{!! Form::label("bedroom") !!}
			{!! Form::selectRange("bedroom",1,100,null,["class"=>"form-control","placeholder"=>"Select bedroom"]) !!}
			<span class="text-danger">{{ $errors->first("bedroom") }}</span>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			{!! Form::label("bathroom") !!}
			{!! Form::selectRange("bathroom",1,100,null,["class"=>"form-control","placeholder"=>"select bathroom"]) !!}
			<span class="text-danger">{{ $errors->first("bathroom") }}</span>
		</div>
	</div>


	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("full_bath") !!}
			{!! Form::selectRange("full_bath",1,100,null,["class"=>"form-control","placeholder"=>"Select full bath"]) !!}
			<span class="text-danger">{{ $errors->first("full_bath") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("half_bath") !!}
			{!! Form::selectRange("half_bath",1,100,null,["class"=>"form-control","placeholder"=>"Select half bath"]) !!}
			<span class="text-danger">{{ $errors->first("half_bath") }}</span>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			{!! Form::label("sleeps") !!}
			{!! Form::selectRange("sleeps",1,100,null,["class"=>"form-control","placeholder"=>"select sleeps"]) !!}
			<span class="text-danger">{{ $errors->first("sleeps") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("spaces") !!}
			{!! Form::text("spaces",null,["class"=>"form-control","placeholder"=>"Enter spaces"]) !!}
			<span class="text-danger">{{ $errors->first("spaces") }}</span>
		</div>
	</div>


	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("area") !!}
			{!! Form::text("area",null,["class"=>"form-control","placeholder"=>"Enter area"]) !!}
			<span class="text-danger">{{ $errors->first("area") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("property_view") !!}
			{!! Form::text("property_view",null,["class"=>"form-control","placeholder"=>"Enter property_view"]) !!}
			<span class="text-danger">{{ $errors->first("property_view") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none d-none">
		<div class="form-group">
			{!! Form::label("checkin day ") !!}
			{!! Form::text("checkin",null,["class"=>"form-control","placeholder"=>"Enter checkin"]) !!}
			<span class="text-danger">{{ $errors->first("checkin") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("checkout day ") !!}
			{!! Form::text("checkout",null,["class"=>"form-control","placeholder"=>"Enter checkout"]) !!}
			<span class="text-danger">{{ $errors->first("checkout") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("category") !!}
			{!! Form::text("category",null,["class"=>"form-control","placeholder"=>"Enter category"]) !!}
			<span class="text-danger">{{ $errors->first("category") }}</span>
		</div>
	</div>
	
	<div class="col-md-3">
		<div class="form-group">
			{!! Form::label("beds") !!}
			{!! Form::selectRange("beds",1,100,null,["class"=>"form-control","placeholder"=>"select beds"]) !!}
			<span class="text-danger">{{ $errors->first("beds") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("king_beds") !!}
			{!! Form::selectRange("king_beds",1,100,null,["class"=>"form-control","placeholder"=>"select king_beds"]) !!}
			<span class="text-danger">{{ $errors->first("king_beds") }}</span>
		</div>
	</div>
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("queen_beds") !!}
			{!! Form::selectRange("queen_beds",1,100,null,["class"=>"form-control","placeholder"=>"select queen_beds"]) !!}
			<span class="text-danger">{{ $errors->first("queen_beds") }}</span>
		</div>
	</div>
	
	<div class="col-md-3 d-none">
		<div class="form-group">
			{!! Form::label("extra_bed") !!}
			{!! Form::text("extra_bed",null,["class"=>"form-control","placeholder"=>"Enter extra_bed"]) !!}
			<span class="text-danger">{{ $errors->first("extra_bed") }}</span>
		</div>
	</div>
	<div class="col-md-3">
		<div class="form-group">
			{!! Form::label("is_home") !!}
			{!! Form::select("is_home",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>

</div>

<div class="row d-none">
	
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("is_trending") !!}
			{!! Form::select("is_trending",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("is_top") !!}
			{!! Form::select("is_top",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("is_feature") !!}
			{!! Form::select("is_feature",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("is_bestseller") !!}
			{!! Form::select("is_bestseller",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>

	<div class="col-md-2">
		<div class="form-group">
			{!! Form::label("is_hot") !!}
			{!! Form::select("is_hot",Helper::getBooleanDataActual(),null,["class"=>"form-control"]) !!}
		</div>
	</div>
	
</div>
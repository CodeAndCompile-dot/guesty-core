<div class="row">
    <div class="col-md-12">
        <label>Tax Percentage</label>
        {!! Form::text("tax",null,["class"=>"form-control","pattern"=>"^[1-9]\d*(\.\d+)?$","title"=>"please enter number only" ]) !!}
    </div>
    
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <label>Pet Fee Charge</label>
        {!! Form::text("pet_fee",null,["class"=>"form-control","pattern"=>"^[1-9]\d*(\.\d+)?$","title"=>"please enter number only" ]) !!}
    </div>
    <div class="col-md-3">
        <label>Max Pet </label>
        {!! Form::selectRange("max_pet",0,200,null,["class"=>"form-control"]) !!}
    </div>
    <div class="col-md-3">
        <label>Interval</label>
        {!! Form::select("pet_fee_interval",["per_pet"=>"Per Pet","one_time"=>"One time"],null,["class"=>"form-control"]) !!}
    </div>
    <div class="col-md-3">
        <label>Tax/Non-Tax</label>
        {!! Form::select("pet_fee_type",["taxable"=>"taxable","non-taxable"=>"non-taxable"],null,["class"=>"form-control"]) !!}
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-9">
        <label>Heating the Swimming Pool fee</label>
        {!! Form::number("heating_pool_fee",null,["class"=>"form-control","pattern"=>"^[1-9]\d*(\.\d+)?$","title"=>"please enter number only" ]) !!}
    </div>
    
    <div class="col-md-3">
        <label>Tax/Non-Tax</label>
        {!! Form::select("heating_pool_fee_type",["taxable"=>"taxable","non-taxable"=>"non-taxable"],null,["class"=>"form-control"]) !!}
    </div>
   
</div>
<br>

<div class="row">
    <div class="col-md-9">
        <label>Additional Guest Charge</label>
        {!! Form::text("guest_fee",null,["class"=>"form-control","pattern"=>"^[1-9]\d*(\.\d+)?$","title"=>"please enter number only" ]) !!}
    </div>
    <div class="col-md-3">
        <label>After How many Guests</label>
        {!! Form::selectRange("no_of_guest",0,200,null,["class"=>"form-control"]) !!}
    </div>
</div>
<br>



<div class="row" >
	<div class="col-md-12">
		<a href="javascript:;" class="add-fee-data btn btn-info"><i class="fa fa-plus"></i> Add Additional Fee</a>
		 	 <hr>
	</div>




</div>
<div class="row gaurav-delete-property">
    <div class="col-md-2">
        <strong>Fee Name</strong>
    </div>
    <div class="col-md-2">
        <strong>Rate</strong>
    </div>
    <div class="col-md-2">
        <strong>Rate Type</strong>
    </div>
    <div class="col-md-2">
        <strong>Include in</strong>
    </div>
    <div class="col-md-2">
        <strong>Status</strong>
    </div>
    <div class="col-md-2">
        <strong>Action</strong>
    </div>
    
    <div class="col-md-12">
        <br>
    </div>
</div>
<div id="fee-area-section">
    @isset($data)
@foreach(App\Models\PropertyFee::where("property_id",$data->id)->get() as $c)
    <div class="row gaurav-delete-property">
        <div class="col-md-2">
            {!! Form::text("fee_name[]",$c->fee_name,["required","class"=>"form-control","placeholder"=>"Fee Name"]) !!}
        </div>
        <div class="col-md-2">
            {!! Form::text("fee_rate[]",$c->fee_rate,["required","class"=>"form-control","placeholder"=>"Fee Rate","pattern"=>"^[1-9]\d*(\.\d+)?$","title"=>"please enter number only" ]) !!}
        </div>
        <div class="col-md-2">
            {!! Form::select("fee_type[]",["Percentage"=>"Percentage","Excat"=>"Amount"],$c->fee_type,["required","class"=>"form-control"]) !!}
        </div>
        <div class="col-md-2">
            {!! Form::select("fee_apply[]",["total"=>"Not Taxable","gross"=>"Taxable"],$c->fee_apply,["required","class"=>"form-control"]) !!}
        </div>
        <div class="col-md-2">
            {!! Form::select("fee_status[]",["active"=>"active","deactive"=>"deactive"],$c->fee_status,["required","class"=>"form-control"]) !!}
        </div>
        <div class="col-md-2">
            <a href="javascript:;" class="delete-fee-data btn btn-danger " ><i class="fa fa-trash"></i> </a>
        </div>
        
        <div class="col-md-12">
            <br>
        </div>
    </div>
@endforeach
@endisset
</div>





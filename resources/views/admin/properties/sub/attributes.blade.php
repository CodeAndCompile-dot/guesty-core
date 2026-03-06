<!-- Button to Open the Modal -->
<button type="button" class="btn btn-primary btn-sm btn-add-attribute" >
  <span class="fa fa-plus"></span> Add Attribute 
</button>
<br>
<br>
<br>
<div class="row">
    <div class="col-md-3"><strong>Group Attribute</strong></div>
    <div class="col-md-3"><strong>Attribute</strong></div>
    <div class="col-md-3"><strong>Value</strong></div>
    <div class="col-md-3"><strong>Action</strong></div>
</div>
@isset($product)
@foreach(ModelHelper::getAttributeGroupProduct($product->id) as $attr)
 <div class="rows-data">
            <br><br>
            <div class="row">
                <div class="col-md-3">
                    {!! Form::select("attribute_group_id[]",ModelHelper::getAttributeGroupSelect(),$attr->attribute_group_id,["class"=>"form-control attribute_group_field","placeholder"=>"choose attribute group"]) !!}
                </div>
                <div class="col-md-3">
                    {!! Form::select("attribute_id[]",[],null,["class"=>"form-control attribute_id_ajax","placeholder"=>"choose attribute","data-value"=>$attr->attribute_id]) !!}
                </div>
                <div class="col-md-3">

                    {!! Form::text("value[]",$attr->value,["class"=>"form-control","placeholder"=>"Enter Value"]) !!}
                </div>
                <div class="col-md-3">
                    <a href="javaScript:void(0)" data-id="{{ $attr->id }}" class="btn btn-danger btn-sm btn-delete-attribute-prefix"> <span class="fa fa-trash"></span> </a>
                </div>
            </div>
        </div>
@endforeach
@endisset
<div class="attribute-field"></div>
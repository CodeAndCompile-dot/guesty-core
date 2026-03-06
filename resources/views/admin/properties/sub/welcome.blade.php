<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("welcome_package_attachment") !!}
            {!! Form::file("welcome_package_attachment",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("welcome_package_attachment")}}</span>
             @isset($data)
                @if($data->welcome_package_attachment!="")
                     <a href="{{ asset(($data->welcome_package_attachment)) }}" target="_BLANK" >Attachment</a> 
                @endif
            @endisset
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("welcome_package_description") !!}
            {!! Form::textarea("welcome_package_description",null,["class"=>"form-control","rows"=>"2"]) !!}
            <span class="text-danger">{{ $errors->first("welcome_package_description")}}</span>
        </div>
    </div>
 
</div>


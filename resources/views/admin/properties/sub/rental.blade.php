<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label("rental_aggrement_attachment") !!}
            {!! Form::file("rental_aggrement_attachment",["class"=>"form-control"]) !!}
            <span class="text-danger">{{ $errors->first("rental_aggrement_attachment")}}</span>
             @isset($data)
                @if($data->rental_aggrement_attachment!="")
                     <a href="{{ asset(($data->rental_aggrement_attachment)) }}" target="_BLANK" >Attachment</a> 
                @endif
            @endisset
        </div>
    </div>

</div>

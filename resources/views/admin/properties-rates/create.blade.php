@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name=" Property Rates";$route="properties-rates";
@endphp            
@section('content_header')
    <h1 class="m-0 text-dark"><span class="badge badge-primary">{!! $property->name !!}</span> <i class='fa fa-arrow-right'></i> {!! $name !!} Management</h1>
@stop

@section('content')
    @parent
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
          @php 
            $addbar=["name"=>$name,"add-data"=>true,"add-name"=>"Add ". Str::singular($name),"add-anchor"=>route($route.'.create',[$property_id]),"back-anchor"=>route($route,[$property_id])];
          @endphp
          @include("admin.common.add-bar")
          </div>
        
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-12">
         
          <div class="card  ">
            <div class="card-header">
              <h3 class="card-title"><span class="badge badge-primary">{!! $property->name !!}</span> <i class='fa fa-arrow-right'></i> Create {{ Str::singular($name) }}</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               {!! Form::open(['route' => [$route.'.store',[$property_id]],"files"=>"true"]) !!}
     
                    @include("admin.".$route.".form")
               
                    <button class="btn btn-success"><span class="fa fa-save"></span> Save</button>
                
                {!! Form::close() !!}
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
@stop
@section("js")
@parent

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
$(function() {

  $('#datepicker,#datepicker1').daterangepicker({
    opens: 'left',autoUpdateInput: false
  }, function(start, end, label) {
    $("#datepicker").val(start.format('YYYY-MM-DD'))
    $("#datepicker1").val(end.format('YYYY-MM-DD'))
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
  });
});
</script>
<script >
  $( function() {
    // $( "#datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
    // $( "#datepicker1" ).datepicker({ dateFormat: 'yy-mm-dd' });
  } );
 var default1=` <div class="row default">
        <div class="col-md-12">
            <div class="form-group">
                {!! Form::label("price") !!}
                {!! Form::number("price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("price")}}</span>
            </div>
        </div>
        <div class="col-md-6 d-none">
            <div class="form-group">
                {!! Form::label("base_price") !!}
                {!! Form::number("base_price",null,["class"=>"form-control","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("base_price")}}</span>
            </div>
        </div>
    </div>
  `;
 var  weekly=`
   


     <div class="row default">
        
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label("monday_price") !!}
                {!! Form::number("monday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("monday_price")}}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label("tuesday_price") !!}
                {!! Form::number("tuesday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("tuesday_price")}}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label("wednesday_price") !!}
                {!! Form::number("wednesday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("wednesday_price")}}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label("thrusday_price") !!}
                {!! Form::number("thrusday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("thrusday_price")}}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label("friday_price") !!}
                {!! Form::number("friday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("friday_price")}}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label("saturday_price") !!}
                {!! Form::number("saturday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("saturday_price")}}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label("sunday_price") !!}
                {!! Form::number("sunday_price",null,["class"=>"form-control","required","min"=>"0"]) !!}
                <span class="text-danger">{{ $errors->first("sunday_price")}}</span>
            </div>
        </div>
    </div>
  `;
$(document).on("change","#type_of_price",function(){
 

  if($(this).val()=="default"){
    $("#price-section").html(default1);
  }else if($(this).val()=="weekly"){
    $("#price-section").html(weekly);
  }else{
      $("#price-section").html('');
  }
});

</script>
@stop
@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Properties Calendar";$route="properties-calendar";
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
            $addbar=["name"=>$name,"back-anchor"=>route($route.'.index',[$property_id]),"add-data"=>false];
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
     
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label("ical_link") !!}
                                {!! Form::text("ical_link",null,["class"=>"form-control","required"]) !!}
                                <span class="text-danger">{{ $errors->first("ical_link")}}</span>
                            </div>
                        </div>
                   
                      </div>
               
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
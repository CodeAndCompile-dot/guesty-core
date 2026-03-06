@extends("admin.layouts.master")
@section('title', 'Admin')
@php 
            $name="Slider";
            $route="sliders";
@endphp            
@section('content_header')
    <h1 class="m-0 text-dark">{{$name}}s Management</h1>
@stop

@section('content')
    @parent
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
          @php 
            $addbar=["name"=>$name."s","add-data"=>true,"add-name"=>"Add ".$name,"add-anchor"=>route($route.'.create'),"back-anchor"=>route($route.'.index')];
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
         
          <div class="card  card-primary">
            <div class="card-header">
              <h3 class="card-title">{{ $name }}s Listing</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @php $copy=0;@endphp
               {!! Form::model($data,['route' => [$route.'.store'],"files"=>"true","method"=>"put"]) !!}
     
                    @include("admin.".$route.".form")
               
                    <button class="btn btn-success"><span class="fa fa-save"></span> Update</button>
                
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
<script>
    $(function(){
    CKEDITOR.replace('description',{ allowedContent:true,filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'});
    CKEDITOR.replace('description_ger',{ allowedContent:true,filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'});
  });
</script>
@stop
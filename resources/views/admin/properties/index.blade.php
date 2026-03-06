@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Properties";$route="properties";
@endphp            
@section('content_header')
    <h1 class="m-0 text-dark">{{$name}} Management</h1>
@stop

@section('content')
    @parent
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
          @php 
            $addbar=["name"=>$name,"add-data"=>false,"add-name"=>"Add ". Str::singular($name),"add-anchor"=>route($route.'.create'),"refresh-calander-data"=>"true","set-pricelab"=>true];
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
              <h3 class="card-title">{{ $name }} Listing</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="data-table-gaurav" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>SEO URL</th>
                        <th>Status</th>
                        <th>Instant Booking</th>
                        <th>Calender</th>
                        <th>Rates</th>
                        
                    
                        <th>Created</th>
                        <th>Last Updated</th>
                      
              
                        
                    
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php $sno=1;@endphp
                    @foreach($data as $client)
                        <tr>
                           
                             <td> {{ $sno++ }}</td>
                            <td>
                                {{ $client->name }}
                            </td>
                            <td>
                                {{ $client->seo_url }}
                            </td>
                            <td>
                                @if($client->status == "true")
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>
                            <td>
                                @if($client->instant_booking_button == "true")
                                    <i class="fa fa-check text-success"></i>
                                @else
                                    <i class="fa fa-times text-danger"></i>
                                @endif
                            </td>

                            
                             <td>
                                 <a href="{{ route('properties-calendar.index',$client->id) }}" class="btn btn-success btn-block btn-xs raw-margin-right-8">View Calender</a>
                            </td>
                            <td>
                                   <a href="{{ route('properties-rates',[$client->id]) }}" class="btn btn-warning btn-block btn-xs raw-margin-right-8"><i class="fa fa-eye"></i>  Rates</a>
                            </td>
                          
                         
                        
                            <td>
                                {{ date('d-F-Y',strtotime($client->created_at)) }}
                            </td>
                        
                            <td>
                                {{ date('d-F-Y',strtotime($client->updated_at)) }}
                            </td>
                        
                            
                   
                           
                        
                            <td>

                                <a href="{!! route($route.'.edit', [$client->id]) !!}" 
                                            onclick="return confirm('Are you sure you want to Edit this {{ $name }}?')" class="btn btn-success btn-xs raw-margin-right-8 btn-block"><i
                                            class="fa fa-pencil-alt"></i> Edit </a>


                                <a href="{!! route($route.'.copyData', [$client->id]) !!}" 
                                            onclick="return confirm('Are you sure you want to copy this {{ $name }}?')" class="d-none btn btn-info btn-xs raw-margin-right-8 btn-block"><i
                                            class="fa fa-copy"></i> Copy </a>
                                @if($client->status=="true")

                                    <a href="{!! route($route.'.deactive', [$client->id]) !!}" 
                                            onclick="return confirm('Are you sure you want to deactive this {{ $name }}?')" class="btn btn-warning btn-xs raw-margin-right-8 btn-block"><i
                                            class="fa fa-times"></i>Deactive </a>
                                @else
                                     <a href="{!! route($route.'.active', [$client->id]) !!}" 
                                            onclick="return confirm('Are you sure you want to active this {{ $name }}?')" class="btn btn-outline-warning btn-xs raw-margin-right-8 btn-block"><i
                                            class="fa fa-check"></i>Active </a>
                                @endif
                                <form method="post" class="d-none" action="{!! route($route.'.destroy', [$client->id]) !!}"
                                      style="margin-top: 5px;">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-danger btn-block btn-xs raw-margin-right-8"
                                            onclick="return confirm('Are you sure you want to delete this {{ $name }}, Destroy All child data?')"><i
                                                class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
                                
                              
                              <br>
                            
                            </td>
                        </tr>
                    @endforeach
                </tbody>
           
              </table>
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
  
  $("#data-table-gaurav").DataTable({"lengthMenu": [[ 50, -1], [ 50, "All"]],dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",});

 
</script>
@stop
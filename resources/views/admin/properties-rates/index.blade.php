@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name=" Property Rates";$route="properties-rates";
@endphp            
@section('content_header')
    <h1 class="m-0 text-dark"><span class="badge badge-primary">{!! $property->name !!}</span> <i class='fa fa-arrow-right'></i>  {!! $name !!}  Management</h1>
@stop

@section('content')
    @parent
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
          @php 
            $addbar=["name"=>$name,"add-data"=>true,"add-name"=>"Add ". Str::singular($name),"add-anchor"=>route($route.'.create',[$property_id]),"back-anchor"=>route('properties.index')];
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
              <h3 class="card-title"><span class="badge badge-primary">{!! $property->name !!}</span> <i class='fa fa-arrow-right'></i>  {!! $name !!}  Listing</h3>
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
                        
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Name</th>
                        <th>Type of Price</th>
                        
                        
                        <th>Min Stay</th>
                        <th>Checkin</th>
                        <th>Checkout</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php $sno=1;@endphp
                    @foreach($data as $client)
                        <tr>
                           
                            <td>{{ $sno++ }}</td>

                       
                            <td>{{ $client->start_date }}</td>
                            
                            
                            <td>{{ $client->end_date }}</td>
                            <td>{{ $client->name_of_price }}</td>
                            
                            
                            
                            <td>{{ $client->type_of_price }}</td>
                            @php
                                $day=Helper::getWeekNameSelect();
                            @endphp
                            <td>{{ $client->min_stay }}</td>
                            <td>{{ $day[$client->checkin_day] ?? '' }}</td>
                            <td>{{ $day[$client->checkout_day] ?? '' }}</td>
                            
                           
                         
                            <td>
                                <a href="{!! route($route.'.edit', [$property_id,$client->id]) !!}" class="btn btn-outline-success btn-xs raw-margin-right-8 "><i
                                            class="fa fa-pencil-alt"></i> Edit</a>
                              
                                <form method="post" action="{!! route($route.'.destroy', [$property_id,$client->id]) !!}"
                                     style="display: inline-block;" >
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-outline-danger btn-xs raw-margin-right-8"
                                            onclick="return confirm('Are you sure you want to delete this {{ $name }}, Destroy All child data?')"><i
                                                class="fa fa-trash"></i> Delete
                                    </button>
                                </form>
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
  $("#data-table-gaurav").DataTable({"lengthMenu": [[ 50, -1], [ 50, "All"]]});
                   




</script>
@stop
@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Properties Calendar";$route="properties-calendar";
@endphp            
@section('content_header')
    <h1 class="m-0 text-dark"><span class="badge badge-primary">{!! $property->name !!}</span> <i class='fa fa-arrow-right'></i>  {!! $name !!}  Management</h1>
@stop

@section('content')
    @parent
    <style>.tooltip {position: relative;display: inline-block;}
.tooltip .tooltiptext {visibility: hidden;width: 140px;background-color: #555;color: #fff;text-align: center;border-radius: 6px;padding: 5px;position: absolute;z-index: 1;bottom: 150%;left: 50%;margin-left: -75px;opacity: 0;transition: opacity 0.3s;}
.tooltip .tooltiptext::after {content: "";position: absolute;top: 100%;left: 50%;margin-left: -5px;border-width: 5px;border-style: solid;border-color: #555 transparent transparent transparent;}
.tooltip:hover .tooltiptext {visibility: visible;opacity: 1;}</style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12">
          @php 
            $addbar=[
            "name"=>$name,
            "add-data"=>true,
            "add-name"=>"Add Properties Calendar Import",
            "add-anchor"=>route($route.'.create',[$property_id]),
            "back-anchor"=>route('properties.index'),
            "custom-data"=>true,
            "custom-anchor"=>route('properties-calendar.selfIcalRefresh',$property->id),
            'custom-name'=>"Refresh Self Ical",

            "custom-data1"=>true,
            "custom-anchor1"=>route('properties-calendar.import-list',$property->id),
            'custom-name1'=>"Import List",
            "copy-data"=>true,


            
            'download-ical'=>true,
            "download-ical-link"=>asset('uploads/ical/'.sprintf("%06d", $property->id).".ics"),
            "copy-ical-link"=>asset('uploads/ical/'.sprintf("%06d", $property->id).".ics")
            ];
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
                <div class="row">
                    <div class="col-md-2">&nbsp;</div>
                      <div class="col-md-8">
                  <iframe src="{{ url('fullcalendar-demo/'.$property_id) }}"  width="100%" height="400" style="border:0;" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </div>
                  <div class="col-md-2">&nbsp;</div>
              </div>
              <table id="data-table-gaurav" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        
                        <th>Ical Link</th>
                        
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Event Type</th>
                        <th>Booking Status</th>
                     
                        <th class="d-none" >Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php $sno=1;@endphp
                    @foreach($data as $client)
                        <tr>
                           
                            <td>{{ $sno++ }}</td>

                       
                            <td>{{$client->ical_link}}</td>
                            
                            <td>{{$client->start_date}}</td>
                            <td>{{$client->end_date}}</td>
                            <td>{{$client->event_type}}</td>
                            <td>{{$client->booking_status}}</td>
                            
                            
                            <td class="d-none" >
                               
                                <form method="post" class="d-none" action="{!! route($route.'.destroy', [$property_id,$client->id]) !!}"
                                      >
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
                   

function myFunction() {
  var copyText = $("#copy-data");
  
  navigator.clipboard.writeText(copyText.data("href"));
  
  alert("copied")
}




</script>
@stop
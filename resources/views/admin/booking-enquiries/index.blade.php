@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Booking Enquiries";$route="booking-enquiries";
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
            $addbar=["name"=>$name,"add-data"=>true,"add-name"=>"Add ". Str::singular($name),"add-anchor"=>route($route.'.create')];
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
                        <th> #</th>
                        <th>Checkin</th>
                        <th>Checkout</th>
                        <th>Booking-id</th>
                        <th>Property</th>
                        <th>Customer</th>
                        <th>Guests</th>
                        <th>Nights</th>
                        <th>Amount</th>

                        <th>Request of Date</th>
                   
                        <th>Booking Status</th>
                        <th>Status</th>
                        
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php 
                            $sno=1; 
                            $payment_currency=ModelHelper::getDataFromSetting('payment_currency');
                        @endphp
                    @foreach($data as $client)
                        <tr>
                           
                            <td>{{ $sno++ }}</td>
                            <td>{{ date("F jS, Y",strtotime($client->checkin)) }}</td>
                            <td>{{ date("F jS, Y",strtotime($client->checkout)) }}</td>
                            <td>{{ $client->id }}</td>
                            <td>{{ App\Models\GuestyProperty::find($client->property_id)->title ?? $client->property_id }}</td>
                           
                           
                           
                            <td>
                                {{ $client->name }}
                                <br>
                                {{ $client->email }}
                           
                            </td>
                            <td>{{ $client->total_guests }}</td>
                            <td>{{ $client->total_night }}</td>
                            <td>{!! $payment_currency !!}{{ $client->total_amount }}</td>
                            <td>{{ date("F jS, Y",strtotime($client->created_at)) }}</td>
                         
                            <td>{!! Helper::getBookingStatus($client->booking_status,$client->id) !!}</td>
                            <td> 
                        
                                <div class="btn-group btn-group-sm"><a href="#" class="btn btn-info">Status</a>
                                  <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                  </button>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item {{ $client->rental_aggrement_status=='true'?'text-success':'text-danger' }}" href="javascript:;" >Rental  {!! Helper::checkStatus($client->rental_aggrement_status) !!}</a>
                                    <a class="dropdown-item " href="javascript:;" >Payment  ({!! $client->payment_status !!})</a>
                                    <a class="dropdown-item {{ $client->welcome_email=='true'?'text-success':'text-danger' }}" href="javascript:;" >Welcome  {!! Helper::checkStatus($client->welcome_email) !!}</a>
                                    <a class="dropdown-item {{ $client->review_email=='true'?'text-success':'text-danger' }}" href="javascript:;" >Review  {!! Helper::checkStatus($client->review_email) !!}</a>
                                    <a class="dropdown-item {{ $client->reminder_email=='true'?'text-success':'text-danger' }}" href="javascript:;" >Reminder  {!! Helper::checkStatus($client->reminder_email) !!}</a>
                                    <a class="dropdown-item {{ $client->checkin_email=='true'?'text-success':'text-danger' }}" href="javascript:;" >Checkin  {!! Helper::checkStatus($client->checkin_email) !!}</a>
                                    <a class="dropdown-item {{ $client->checkout_email=='true'?'text-success':'text-danger' }}" href="javascript:;" >Checkout  {!! Helper::checkStatus($client->checkout_email) !!}</a>
                                   
                                  </div>
                                </div>
                                
                            
                            </td>
                           
                       
                      
                           
                         
                            <td>
                                <a href="javascript:;" class="btn btn-outline-primary btn-sm raw-margin-right-8 " data-toggle="modal" data-target="#myModal{{ $client->id }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                
                                @if($client->booking_status!='booking-cancel')
                                @if($client->booking_status!='booking-confirmed' )
                                <a href="{!! route($route.'.edit', [$client->id]) !!}" class="btn btn-outline-success btn-sm raw-margin-right-8 d-none"><i
                                            class="fa fa-pencil-alt"></i> </a>
                                @elseif($client->booking_type_admin!="invoice")
                                  <a href="{!! route($route.'.edit', [$client->id]) !!}" class=" d-none btn btn-outline-success btn-sm raw-margin-right-8 "><i
                                            class="fa fa-pencil-alt"></i> </a>
                                            @endif
                                            
                                            @if($client->booking_status=='booking-confirmed')
                                            <form method="post" action="{!! route($route.'.destroy', [$client->id]) !!}"
                                                  style="display: none;">
                                                {!! csrf_field() !!}
                                                {!! method_field('DELETE') !!}
                                                <button type="submit" class="btn btn-outline-danger btn-sm raw-margin-right-8"
                                                        onclick="return confirm('Are you sure you want to cancel this {{ $name }}?')"> 
                                                        
                                                        Cancel Booking
                                                </button>
                                            </form>
                                            @endif
                                @endif
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
@php $data123=$data; @endphp
 @foreach($data123 as $client)
<!-- The Modal -->
<div class="modal" id="myModal{{ $client->id }}">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Booking Detail</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        @php
            $data=$client->toArray();
            $property=App\Models\GuestyProperty::find($client->property_id);
        @endphp
                <table class="table table-bordered" >
                            <tbody>
                                <tr>
                                    <th colspan="4" ><strong>Property Detail </strong></th>
                                </tr>
                                <tr>
                                    <th>Request ID</th>
                                    <td>{{ $data['request_id']  }}</td>
                               
                                    <th>Booking Date</th>
                                    <td>{{ date("F jS, Y",strtotime($data['created_at'])) }}</td>
                                </tr>
                                <tr>
                                    <th>Booking Status</th>
                                    <td>
                                        {!! Helper::getBookingStatus($client->booking_status,$client->id) !!}
                                    </td>
                                
                                    <td ><strong>Property Name :</strong></td>

                                    <td >{{$property->title ?? $client->property_id }}</td>
                                </tr>
                              
                                    @if($data['booking_type_admin']=="invoice")
                                        <tr>
                                            <th colspan="3">Rental Aggrement</th>
                                            <th>{{ $data['rental_aggrement_status'] }}</th>
                                        </tr>
                                    @endif
                                    @if($data['rental_aggrement_status']=="true")
    
                                    <tr>
                                        <th>Sign</th>
                                        <td>
                                            @if($data['rental_aggrement_signature'])
                                                <img src="{{ asset($data['rental_aggrement_signature']) }}" style="width: 100px;" />
                                            @endif
                                        </td>
                                   
                                        <th>Image</th>
                                        <td>
                                            @if($data['rental_aggrement_images'])
                                                <img src="{{ asset($data['rental_aggrement_images']) }}" style="width: 100px;" />
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

                             


                                <tr>
                                    <th colspan="4" ><strong>User Detail </strong></th>
                                </tr>
                                <tr>
                                    <td ><strong>Name :</strong></td>
                                    <td >{{$data['name']}}</td>
                               
                                    <td ><strong>Email :</strong></td>
                                    <td >{{$data['email']}}</td>
                                </tr>
                                <tr>
                                    <td ><strong>Mobile:</strong></td>
                                    <td >{{$data['mobile']}}</td>
                              
                                    <td ><strong>Message :</strong></td>
                                    <td >
                                        {{$data['message']}}
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        <table class="table table-bordered" >
                            <tbody>
                                <tr>
                                    <th colspan="5" ><strong>Booking Detail </strong></th>
                                </tr>

                               

                                <tr>
                                    <th ><strong>Checkin :</strong></th>
                                    <th ><strong>Checkout :</strong></th>
                                    <th ><strong>Total Guest :</strong></th>
                                    <th ><strong>Total Night :</strong></th>
                                    <td ><strong> Amount :</strong></td>
                                    
                                </tr>
                                <tr>
                                    <td >{{$data['checkin'] }}</td>
                                    <td >{{$data['checkout'] }}</td>
                                    <td >{{$data['total_guests'] }} ({{$data['adults']}} Adults, {{$data['child']}} Child)</td>
                                    <td >{{$data['total_night'] }}</td>
                                    <td ></td>
                                </tr>
                                	
							
								
								
                                @foreach(json_decode($data['before_total_fees'] ?? '[]') as $c)
                                @php
                             
                                @endphp
                                <tr>
                                    <td colspan="4" ><strong>{{$c->title ?? ''}} :</strong></td>
                                    <td >{!! $payment_currency !!}{{number_format($c->amount,2) }}</td>
                                </tr>
                                @endforeach
                                
                               
                                
                      
                                
                                <tr>
                                    <td colspan="4" ><strong>Total <span class="text-success">(Paid)</span>:</strong></td>
                                    <td >{!! $payment_currency !!}{{number_format($data['total_amount'],2) }}</td>
                                </tr>
                                
                                
                                


                            </tbody>
                        </table>




      </div>

@if($client->booking_status!='booking-cancel')
      <!-- Modal footer -->
      <div class="modal-footer">
           <a href="{!! route($route.'.edit', [$client->id]) !!}" class="btn btn-outline-success btn-sm raw-margin-right-8 d-none"><i
                    class="fa fa-pencil-alt"></i> </a>
        @if($client->booking_status=='booking-confirmed')
        <form method="post" action="{!! route($route.'.destroy', [$client->id]) !!}"
              style="display: none;">
            {!! csrf_field() !!}
            {!! method_field('DELETE') !!}
            <button type="submit" class="btn btn-outline-danger btn-sm raw-margin-right-8"
                    onclick="return confirm('Are you sure you want to cancel this {{ $name }}?')">
                Cancel Booking
            </button>
        </form>
        @endif
      </div>
@endif
    </div>
  </div>
</div>

@endforeach

<script>
  $("#data-table-gaurav").DataTable({"lengthMenu": [[ 50, -1], [ 50, "All"]]});

 
</script>
@stop

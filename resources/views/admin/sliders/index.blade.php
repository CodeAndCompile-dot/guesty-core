@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Sliders";$route="sliders";
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
                        <th>#</th>
                        <th>title</th>
                        <th>Page</th>
                        <th> Video</th>
                 
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                        @php $sno=1;@endphp
                    @foreach($data as $client)
                        <tr>
                           
                            <td> {{ $sno++ }}</td>
                            <td>
                                {{ $client->title }}
                            </td>
                       
                            <td>{{ App\Models\Cms::find($client->cms_id)->name ?? $client->cms_id }}</td>
                            <td>
                                    @if($client->image)
                                          <a href="{{ asset($client->image) }}" target="_BLANK">Video</a> 
                                    @endif
                            </td>
                            
                            <td>
                                <a href="{!! route($route.'.edit', [$client->id]) !!}" class="btn btn-outline-success btn-xs raw-margin-right-8"><i
                                            class="fa fa-pencil-alt"></i> Edit</a>
                                <a href="{!! route($route.'.copyData', [$client->id]) !!}" class="btn btn-outline-info btn-xs raw-margin-right-8"><i
                                            class="fa fa-copy"></i> Copy</a>
                                            
                                          
                                            
                                <form method="post" action="{!! route($route.'.destroy', [$client->id]) !!}"
                                      style="display: inline-block;">
                                    {!! csrf_field() !!}
                                    {!! method_field('DELETE') !!}
                                    <button type="submit" class="btn btn-outline-danger btn-xs raw-margin-right-8"
                                            onclick="return confirm('Are you sure you want to delete this {{ $name }}?')"><i
                                                class="fa fa-trash"></i>  Delete
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
  
  $("#data-table-gaurav").DataTable({"lengthMenu": [[ 50, -1], [ 50, "All"]], "order": [[ 1, "asc" ]],dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" +
         "<'row'<'col-sm-12'tr>>" +
         "<'row'<'col-sm-5'i><'col-sm-7'p>>",});
  
</script>
@stop
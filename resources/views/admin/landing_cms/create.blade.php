@extends('admin.layouts')
@section('title', 'Admin')
@php 
    $name="Landing Pages";$route="landing_cms";
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
            $addbar=["name"=>$name,"add-data"=>false,"add-name"=>"Add ". Str::singular($name),"add-anchor"=>route($route.'.create'),"back-anchor"=>route($route.'.index')];
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
              <h3 class="card-title">Create {{ Str::singular($name) }}</h3>
               <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
               {!! Form::open(['route' => $route.'.store',"files"=>"true"]) !!}
     
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
<script>
      var ckeditor_gaurav=0;
             $(document).on("click",".btn-add-more",function(){
                 html=`
                 <div class="row gaya-data-attraction">
                 
                     <div class="col-md-1">
                       <label>Attraction  Action</label>
                          <button type="button" class="btn btn-danger btn-delete-more"><i class="fa fa-times"></i> </button>
                     </div>
                     <div class="col-md-3">
                        <label>Attraction  Heading</label>
                         <input type="text" name="attraction_heading[]" class="form-control" required />
                         <br>
                         <label>Attraction  Image</label>
                         <input type="file" name="attraction_image[]" required />
                           <br>
                         <label>Image alt</label>
                         <input type="text" name="attraction_title[]" class="form-control"  />
                     </div>
                     <div class="col-md-8">
                            <label>Attraction  Content</label>
                          <textarea class="ckeditor" id="ckeditorJugar-`+ckeditor_gaurav+`" name="attraction_content[]" required></textarea>
                     </div>
                     
                     <div class="col-md-12"><hr></div>
                 </div>
                 `;
                 
                 $(".attraction-area").append(html);
                 //$( 'textarea.ckeditorJugar-'+ckeditor_gaurav ).ckeditor();
                 CKEDITOR.replace( 'ckeditorJugar-'+ckeditor_gaurav );
                 ckeditor_gaurav=ckeditor_gaurav+1;
             });
             
              $(document).on("click",".btn-add-more-video",function(){
                 html=`
                 <div class="row gaya-data-attraction">
                 
                     <div class="col-md-1">
                       <label>Video  Action</label>
                          <button type="button" class="btn btn-danger btn-delete-more"><i class="fa fa-times"></i> </button>
                     </div>
                     <div class="col-md-3">
                        <label>Video  Heading</label>
                         <input type="text" name="video_heading[]" class="form-control" required />
                         <br>
                         <label>Youtube link</label>
                         <input type="text" name="video_link[]" class="form-control" required />
                     </div>
                     <div class="col-md-8">
                            <label>Video  Content</label>
                          <textarea class="ckeditor" id="ckeditorJugar-`+ckeditor_gaurav+`" name="video_content[]" required></textarea>
                     </div>
                     
                     <div class="col-md-12"><hr></div>
                 </div>
                 `;
                 
                 $(".video-area").append(html);
                 //$( 'textarea.ckeditorJugar-'+ckeditor_gaurav ).ckeditor();
                 CKEDITOR.replace( 'ckeditorJugar-'+ckeditor_gaurav );
                 ckeditor_gaurav=ckeditor_gaurav+1;
             });
             $(document).on("click",".btn-delete-more",function(){
                 $(this).parents(".gaya-data-attraction").remove();
             })
         </script>

<script>





  CKEDITOR.replace( 'longDescription',{ allowedContent:true,filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'} );
  CKEDITOR.replace( 'mediumDescription',{ allowedContent:true,filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'} );
  

</script>
@stop
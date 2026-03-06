@extends('adminlte::page')

@section("css")
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('toastr/toastr.css') }}">
<style>
    .table td, .table th {
        padding: 6px;
        font-size: 14px;
}
</style>
@stop

@section("js")
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script src="{{ asset("ckeditor/ckeditor.js") }}"></script>
<script src="{{ asset("toastr/toastr.js") }}"></script>
<script>
$(function(){


    @if(Session::has("success"))
        toastr.success("{{ Session::get("success") }}");
    @endif
    @if(Session::has("danger"))
        toastr.error("{{ Session::get("danger") }}");
    @endif

    $(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
});
 $(document).on("change",".all-select",function(){

    select=$(this).prop("checked");
    if(select){
        $(".single-select").each(function(){
            $(this).prop("checked",true);
        });
    }else{
        $(".single-select").each(function(){
            $(this).prop("checked",false);
        });
    }
});


$(document).on("change",".single-select",function(){
    flag=0;
    $(".single-select").each(function(){
        if($(this).prop("checked")){

        }else{
            flag=1;
        }
    });
    if(flag==1){
        $(".all-select").prop("checked",false);
    }else{
        $(".all-select").prop("checked",true);
    }
})

function ajaxCallCheckBoxDelete(url,indexPage,indexName){
    id=[];
        flag=0;
        $(".single-select").each(function(){
            if($(this).prop("checked")){
                id.push($(this).val());
            }else{
                flag=1;
            }
        });
        if(id.length>0){
            if(confirm("Are you sure you want to delete this "+indexName+"?")){
                
                data={_token:"{{ csrf_token() }}",id:id};
                $.post(url,data,function(data){
                    if(flag==1)
                        location.href=location.href;
                    else
                        location.href=indexPage;                    
                })
            }
        }else{
            alert("please select checkbox")
        }
}

</script>

@stop


@section("footer")
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; {{ date('Y')}} <a href="{{ url('/') }}">Administrator</a>.</strong> All rights
    reserved.
@stop
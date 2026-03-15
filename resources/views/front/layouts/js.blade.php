<script src="{{ asset('front') }}/assets/jquery/jquery-3.7.0.min.js"></script>
<script src="{{ asset('front') }}/assets/bootstrap-5.3.0/dist/js/bootstrap.bundle.min.js" ></script>
<script src="{{ asset('front') }}/assets/jquery-ui/jquery-ui.min.js"></script>
<script src="{{ asset('front') }}/js/main.js"></script>
<script src="{{ asset('toastr/toastr.js') }}"></script>
<script>
$(document).ready(function(){
    @if(Session::has("success"))
        toastr.success("{{ Session::get('success') }}");
    @endif
    @if(Session::has("danger"))
        toastr.error("{{ Session::get('danger') }}");
    @endif
});
$('#reload').click(function () {$.ajax({type: 'GET',url: "{{ url('reload-captcha')}}",success: function (data) {$(".captcha span").html(data.captcha);}});});
$(document).ready(function(){
  $("#menu-toggle1").click(function(){
    $("#tag1").css("transform", "translateX(0em)");
  });
  $("#close-menu1").click(function(){
    $("#tag1").css("transform", "translateX(-47em)");
  });
  $("#tag1").click(function(){
    $(this).css("transform", "translateX(-47em)");
  });
});
</script>

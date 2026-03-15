<div class="banner">
    <div class="c-hero__background">
        <img class="img-fluid" src="{{ $bannerImage }}" title="{{ $name }}" alt="{{ $name }}">    
    </div>
    <div class="guides">
        <h1 class="c-hero__title">{{$name}}</h1>
    </div>
</div>
<div class="breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb single-breadcrumb">
            <a href="{{ url('/') }}"><i class="fa-solid fa-house"></i>Home</a>
            <span><i class="fa-solid fa-chevron-right"></i></span> {{$name}}
        </div>
    </div>
</div>
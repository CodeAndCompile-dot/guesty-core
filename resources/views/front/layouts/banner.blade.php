{{-- Banner partial — Phase 10 will flesh this out --}}
<section class="breadcrumb" style="background-image: url({{ $bannerImage ?? asset('front/images/internal-banner.webp') }});">
    <div class="auto-container">
        <h2>{{ $name ?? '' }}</h2>
        <ul class="page-breadcrumb">
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>/ {{ $name ?? '' }}</li>
        </ul>
    </div>
</section>

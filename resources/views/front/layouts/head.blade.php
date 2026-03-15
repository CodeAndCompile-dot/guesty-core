<!-- Required meta tags -->
<meta charset="utf-8">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="keywords" content="@yield('keywords')" />
<meta name="description" content="@yield('description')" />
<meta name="robots" content="index, follow"/>
<meta name="coverage" content="Worldwide" />
<link rel="icon" href="{{ asset($setting_data['favicon'] ?? 'front/images/favicon.png') }}"  />
<meta property="og:site_name" content="{{ $setting_data['website'] }}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="@yield('title')" />
<meta property="og:description" content="@yield('description')" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:domain" content="{{ url()->current() }}" />
<meta name="twitter:title" content="@yield('title')" />
<meta name="twitter:description" content="@yield('description')" />
@isset($ogimage)
    @if($ogimage)
        <meta name="twitter:image" content="{{ asset($ogimage) }}" />
        <meta property="og:image" content="{{ asset($ogimage) }}" />
    @else
        @if($setting_data['ogimage'])
            <meta name="twitter:image" content="{{ asset($setting_data['ogimage'] ?? 'front/images/logo.png') }}" />
            <meta property="og:image" content="{{ asset($setting_data['ogimage'] ?? 'front/images/logo.png') }}" />
        @else
            <meta name="twitter:image" content="{{ asset($setting_data['header_logo'] ?? 'front/images/logo.png') }}" />
            <meta property="og:image" content="{{ asset($setting_data['header_logo'] ?? 'front/images/logo.png') }}" />
        @endif
    @endif
@else
    @if($setting_data['ogimage'])
        <meta name="twitter:image" content="{{ asset($setting_data['ogimage'] ?? 'front/images/logo.png') }}" />
        <meta property="og:image" content="{{ asset($setting_data['ogimage'] ?? 'front/images/logo.png') }}" />
    @else
        <meta name="twitter:image" content="{{ asset($setting_data['header_logo'] ?? 'front/images/logo.png') }}" />
        <meta property="og:image" content="{{ asset($setting_data['header_logo'] ?? 'front/images/logo.png') }}" />
    @endif
@endisset


<link href="{{ url()->current() }}" rel="canonical">
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "Organization",
  "name": "@yield('title')",
  "url": "{{ url('/') }}",
  "logo": "{{ asset($setting_data['favicon'] ?? 'front/images/favicon.png') }}",
  "description": "@yield('description')",
  "contactPoint": {
    "@@type": "ContactPoint",
    "contactType": " contact us",
    "telephone":"{{ $setting_data['mobile'] }}",
    "email":"{{ $setting_data['email'] }}"
  },
  "sameAs": [
    "{{ $setting_data['twitter'] }}",
    "{{ $setting_data['linkedin'] }}",
    "{{ $setting_data['facebook'] }}"
  ]
}
</script>
@include("front.layouts.css")
@yield("css")
<style>
    :root {
    /* Color Palette */
    --primary-accent: #000000; /* Pure Black */
    --background-light: #F5F5F5; /* Off White */
    --grey-light: #E0E0E0; /* Light Grey */
    --grey-mid: #B0B0B0; /* Medium Grey */
    --grey-dark: #707070; /* Dark Grey */
    --highlight-bg: #1A1A1A; /* Charcoal */
    --text-light: #FFFFFF; /* White */
    --text-dark: #000000; /* Black */
    --cta-button: #707070; /* Mid Grey */
    --cta-text: #FFFFFF; /* White */
    
    /* Legacy variables mapped to new palette */
    --red-cottage-red: #000000; /* Changed to pure black */
    --red-cottage-dark: #1A1A1A; /* Changed to charcoal */
    --red-cottage-light: #F5F5F5; /* Changed to off white */
    --red-cottage-beige: #E0E0E0; /* Changed to light grey */
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Montserrat', sans-serif;
    line-height: 1.6;
    color: var(--text-dark);
    background: var(--background-light);
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'Playfair Display', serif;
    font-weight: 500;
}
/* Styles the entire scrollbar */
::-webkit-scrollbar {
  width: 12px; /* Sets the width for vertical scrollbars */
  height: 12px; /* Sets the height for horizontal scrollbars */
}

/* Styles the scrollbar track */
::-webkit-scrollbar-track {
  background-color: #f1f1f1; /* Light grey background for the track */
}

/* Styles the scrollbar thumb */
::-webkit-scrollbar-thumb {
  background-color: black; /* Darker grey for the thumb */
  border-radius: 6px; /* Rounded corners for the thumb */
  border: 2px solid #f1f1f1; /* Creates a border around the thumb, matching track background */
}

/* Styles the scrollbar thumb on hover */
::-webkit-scrollbar-thumb:hover {
  background-color: #555; /* Even darker grey on hover */
}
</style>
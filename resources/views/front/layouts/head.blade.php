<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', config('app.name', 'Bentonville Lodging Co.'))</title>
<meta name="keywords" content="@yield('keywords')">
<meta name="description" content="@yield('description')">
<link rel="stylesheet" href="{{ asset('front/assets/bootstrap-5.3.0/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('front/assets/fontawesome-free-6.4.0-web/css/all.min.css') }}">
@yield('css')
<script src="{{ asset('front/assets/jquery/jquery-3.6.0.min.js') }}"></script>

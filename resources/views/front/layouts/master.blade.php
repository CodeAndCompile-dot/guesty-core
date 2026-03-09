<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
        @include("front.layouts.head")
    	@yield("header-section")
    	{!! $setting_data['google-analytics'] ?? '' !!}
    	{!! $setting_data['google-tag-master'] ?? '' !!}
    	{!! $setting_data['facebook-pixel-code'] ?? '' !!}
    	{!! $setting_data['other-thing-on-head'] ?? '' !!}
	</head>
    <body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="100">
        {!! $setting_data['after-body-open-tag'] ?? '' !!}
    	@include("front.layouts.header")
    	@yield('container')
    	@include("front.layouts.footer")
        @yield("footer-section")
        {!! $setting_data['chat-bot'] ?? '' !!}
    </body>
</html>

{{-- Front footer partial — Phase 10 will flesh this out --}}
<footer class="py-4">
    <div class="container text-center">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'Bentonville Lodging Co.') }}. All rights reserved.</p>
    </div>
</footer>
<script src="{{ asset('front/assets/bootstrap-5.3.0/js/bootstrap.bundle.min.js') }}"></script>
@yield('js')

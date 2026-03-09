@extends('front.layouts.master')

@section('title', 'Rental Agreement')

@section('container')
<section class="rental-agreement-section py-5">
    <div class="container">
        <h2 class="mb-4">Rental Agreement</h2>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                {{-- Booking Summary --}}
                @if(isset($booking))
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Booking Summary</h5></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <p><strong>Guest:</strong> {{ $booking->first_name ?? '' }} {{ $booking->last_name ?? '' }}</p>
                                <p><strong>Email:</strong> {{ $booking->email ?? '' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p><strong>Check-in:</strong> {{ $booking->checkin ?? '' }}</p>
                                <p><strong>Check-out:</strong> {{ $booking->checkout ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Agreement Content --}}
                @if(isset($data) && !empty($data->rental_agreement))
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">Agreement Terms</h5></div>
                    <div class="card-body">
                        {!! $data->rental_agreement !!}
                    </div>
                </div>
                @endif

                {{-- Signature Form --}}
                <form action="{{ url('rental-aggrement-data-save') }}" method="POST" enctype="multipart/form-data" id="rental-agreement-form">
                    @csrf

                    @if(isset($booking))
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    @endif

                    <div class="card mb-4">
                        <div class="card-header"><h5 class="mb-0">Signature</h5></div>
                        <div class="card-body">
                            <p class="text-muted">Please sign below to confirm your agreement.</p>

                            <div class="signature-pad-container border rounded p-2 mb-3" style="max-width: 500px;">
                                <canvas id="signature-pad" width="480" height="200" class="border rounded"></canvas>
                            </div>

                            <input type="hidden" name="signature_data" id="signature-data">

                            <div class="mb-3">
                                <button type="button" id="clear-signature" class="btn btn-outline-secondary btn-sm">
                                    Clear Signature
                                </button>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="agree-terms" name="agree_terms" required>
                                <label class="form-check-label" for="agree-terms">
                                    I agree to the rental agreement terms and conditions.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg">Submit Agreement</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let drawing = false;

    ctx.strokeStyle = '#000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';

    canvas.addEventListener('mousedown', function (e) {
        drawing = true;
        ctx.beginPath();
        ctx.moveTo(e.offsetX, e.offsetY);
    });

    canvas.addEventListener('mousemove', function (e) {
        if (!drawing) return;
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
    });

    canvas.addEventListener('mouseup', function () {
        drawing = false;
        document.getElementById('signature-data').value = canvas.toDataURL('image/png');
    });

    canvas.addEventListener('mouseleave', function () {
        drawing = false;
    });

    document.getElementById('clear-signature').addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        document.getElementById('signature-data').value = '';
    });

    document.getElementById('rental-agreement-form').addEventListener('submit', function () {
        if (!document.getElementById('signature-data').value) {
            document.getElementById('signature-data').value = canvas.toDataURL('image/png');
        }
    });
});
</script>
@endsection

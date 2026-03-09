{{-- Booking confirmed — user email with Pay Now link --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2 style="color:#333;">Booking Confirmed</h2>
    <p>Hey {{ $data['name'] ?? 'Guest' }},</p>
    <p>Your booking has been confirmed. Please find details and payment link below.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <p style="margin-top:20px;">
        <a href="{{ url('booking/payment/paypal/'.$data['id']) }}"
           style="background:#007bff; color:#fff; padding:12px 24px; text-decoration:none; border-radius:4px;">
            Pay Now
        </a>
    </p>

    <hr>
    <p>Thanks for reading!</p>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

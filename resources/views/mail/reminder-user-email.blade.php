{{-- Payment reminder — User --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>Payment Reminder</h2>
    <p>Dear {{ $data['name'] ?? 'Guest' }},</p>
    <p>This is a friendly reminder that your next payment instalment is due for the following booking.</p>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <p style="margin-top:20px;">
        <a href="{{ url('booking/payment/paypal/'.$data['id']) }}"
           style="background-color:#007bff; color:#fff; padding:12px 24px; text-decoration:none; border-radius:4px; display:inline-block;">
            Pay Now
        </a>
    </p>

    @if(!empty($property->signature_img))
        <p style="margin-top:15px;"><img src="{{ asset('uploads/signature/'.$property->signature_img) }}" alt="Signature" style="max-width:200px;"></p>
    @endif

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

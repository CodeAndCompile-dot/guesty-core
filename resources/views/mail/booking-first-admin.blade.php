{{-- Booking confirmation — Admin email --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2 style="color:#333;">Booking Confirmation</h2>
    <p>Hey {{ ModelHelper::getDataFromSetting('mailer_admin_name') ?? 'Admin' }},</p>
    <p>Payment.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>User Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0" style="border-collapse:collapse;">
        <tr><td>Name</td><td>{{ $data['name'] ?? '' }}</td></tr>
        <tr><td>Email</td><td>{{ $data['email'] ?? '' }}</td></tr>
        <tr><td>Mobile</td><td>{{ $data['mobile'] ?? '' }}</td></tr>
        <tr><td>Message</td><td>{{ $data['message'] ?? '' }}</td></tr>
    </table>

    @if(!empty($data['rental_aggrement_signature']))
    <h4>Rental Agreement Signature</h4>
    <img src="{{ asset('uploads/signature/'.$data['rental_aggrement_signature']) }}" style="max-width:300px;" alt="Signature">
    @endif

    @if(!empty($data['rental_aggrement_images']))
    <h4>Rental Agreement Images</h4>
    @foreach(json_decode($data['rental_aggrement_images'] ?? '[]') as $img)
        <img src="{{ asset('uploads/signature/'.$img) }}" style="max-width:200px; margin:5px;" alt="Agreement Image">
    @endforeach
    @endif

    @if(!empty($data['rental_agreement_link']))
    <p><a href="{{ $data['rental_agreement_link'] }}">View Rental Agreement</a></p>
    @endif

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <hr>
    <p>Thanks for reading!</p>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

{{-- New booking request — Admin notification --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>New Booking Request</h2>
    <p>Hey {{ ModelHelper::getDataFromSetting('mailer_admin_name') ?? 'Admin' }},</p>
    <p>A new booking request has been submitted.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>User Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td>Name</td><td>{{ $data['name'] ?? '' }}</td></tr>
        <tr><td>Email</td><td>{{ $data['email'] ?? '' }}</td></tr>
        <tr><td>Mobile</td><td>{{ $data['mobile'] ?? '' }}</td></tr>
        <tr><td>Message</td><td>{{ $data['message'] ?? '' }}</td></tr>
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

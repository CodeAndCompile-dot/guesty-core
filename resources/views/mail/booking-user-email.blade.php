{{-- New booking request — User confirmation --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>Booking Request Received</h2>
    <p>Dear {{ $data['name'] ?? 'Guest' }},</p>
    <p>Thank you for your booking request. We have received it and will get back to you shortly.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

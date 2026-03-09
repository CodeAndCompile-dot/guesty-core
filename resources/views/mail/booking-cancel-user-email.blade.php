{{-- Booking cancellation — User email --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2 style="color:#c00;">Booking Cancelled</h2>
    <p>Hey {{ $data['name'] ?? 'Guest' }},</p>
    <p>Your booking has been cancelled.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    <hr>
    <p>If you have questions, please contact us.</p>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

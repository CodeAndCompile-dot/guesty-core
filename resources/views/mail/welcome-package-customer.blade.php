{{-- Welcome package — Customer --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>Welcome Package</h2>
    <p>Dear {{ $data['name'] ?? 'Guest' }},</p>
    <p>We are looking forward to hosting you! Here are the details for your upcoming stay.</p>

    <h4>Booking Detail</h4>
    <table width="100%" cellpadding="5" cellspacing="0">
        <tr><td><strong>Check-in</strong></td><td>{{ $data['checkin'] ?? '' }}</td></tr>
        <tr><td><strong>Check-out</strong></td><td>{{ $data['checkout'] ?? '' }}</td></tr>
    </table>

    @php
        $accessCode = !empty($data['mobile']) ? substr($data['mobile'], -4) : '----';
    @endphp
    <p><strong>Access Code:</strong> {{ $accessCode }}</p>

    @if(!empty($property->welcome_package_description))
        <div style="margin-top:15px;">
            {!! $property->welcome_package_description !!}
        </div>
    @endif

    @if(!empty($property->signature_img))
        <p style="margin-top:15px;"><img src="{{ asset('uploads/signature/'.$property->signature_img) }}" alt="Signature" style="max-width:200px;"></p>
    @endif

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

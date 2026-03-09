{{-- Rental agreement — Admin notification --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>Rental Agreement Signed</h2>
    <p>Hey {{ ModelHelper::getDataFromSetting('mailer_admin_name') ?? 'Admin' }},</p>
    <p>A rental agreement has been signed by a guest. Details below:</p>

    <h4>Guest Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td>Name</td><td>{{ $data['name'] ?? '' }}</td></tr>
        <tr><td>Email</td><td>{{ $data['email'] ?? '' }}</td></tr>
        <tr><td>Mobile</td><td>{{ $data['mobile'] ?? '' }}</td></tr>
        @if(!empty($data['address']))
            <tr><td>Address</td><td>{{ $data['address'] }}</td></tr>
        @endif
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    @if(!empty($data['signature_data']))
        <h4>Signature</h4>
        <p><img src="{{ $data['signature_data'] }}" alt="Guest Signature" style="max-width:300px; border:1px solid #ccc;"></p>
    @endif

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

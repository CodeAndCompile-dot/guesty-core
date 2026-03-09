{{-- Payment reminder — Admin notification --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>Payment Reminder Sent</h2>
    <p>Hey {{ ModelHelper::getDataFromSetting('mailer_admin_name') ?? 'Admin' }},</p>
    <p>A payment reminder has been sent to the guest for the following booking.</p>

    <h4>Property Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td><strong>Property Name</strong></td><td>{{ $property->name ?? $property->title ?? '' }}</td></tr>
    </table>

    <h4>User Detail</h4>
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr><td>Name</td><td>{{ $data['name'] ?? '' }}</td></tr>
        <tr><td>Email</td><td>{{ $data['email'] ?? '' }}</td></tr>
        <tr><td>Mobile</td><td>{{ $data['mobile'] ?? '' }}</td></tr>
    </table>

    <h4>Booking Detail</h4>
    @include("mail.booking-common-data")

    @if(!empty($property->signature_img))
        <p><img src="{{ asset('uploads/signature/'.$property->signature_img) }}" alt="Signature" style="max-width:200px;"></p>
    @endif

    @if(!empty($property->images) && $property->images->count())
        <h4>Property Images</h4>
        @foreach($property->images->take(3) as $img)
            <img src="{{ asset('uploads/properties/'.$img->image) }}" alt="Property" style="max-width:180px; margin:2px;">
        @endforeach
    @endif

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

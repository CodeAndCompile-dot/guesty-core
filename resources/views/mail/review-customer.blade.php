{{-- Review request — Customer --}}
@php $setting_data = $setting_data ?? []; @endphp
<div style="font-family:'Poppins',sans-serif; max-width:600px; margin:auto;">
    <h2>We'd Love Your Feedback!</h2>
    <p>Dear {{ $data['name'] ?? 'Guest' }},</p>
    <p>Thank you for staying with us! We hope you had a wonderful time at <strong>{{ $property->name ?? $property->title ?? '' }}</strong>.</p>
    <p>We would really appreciate it if you could take a moment to leave a review about your experience.</p>

    <p style="margin-top:20px;">
        <a href="{{ url('properties/detail/'.($property->seo_url ?? $property->id)) }}"
           style="background-color:#007bff; color:#fff; padding:12px 24px; text-decoration:none; border-radius:4px; display:inline-block;">
            Leave a Review
        </a>
    </p>

    @if(!empty($property->signature_img))
        <p style="margin-top:15px;"><img src="{{ asset('uploads/signature/'.$property->signature_img) }}" alt="Signature" style="max-width:200px;"></p>
    @endif

    <hr>
    {!! ModelHelper::getDataFromSetting('mail_footer') ?? '' !!}
</div>

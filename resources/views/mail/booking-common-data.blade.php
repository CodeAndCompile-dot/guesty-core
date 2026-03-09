{{-- Booking common data partial — included by most booking-related emails --}}
@php
    $payment_currency = $setting_data['payment_currency'] ?? '$';
@endphp
<table width="100%" cellpadding="5" cellspacing="0" style="border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr style="background:#f5f5f5;">
            <th align="left">Check In</th>
            <th align="left">Check Out</th>
            <th align="left">Total Guest</th>
            <th align="left">Total Night</th>
            <th align="right">Amount</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $data['checkin'] ?? '' }}</td>
            <td>{{ $data['checkout'] ?? '' }}</td>
            <td>{{ $data['total_guests'] ?? 0 }}</td>
            <td>{{ $data['total_night'] ?? 0 }}</td>
            <td align="right">{!! $payment_currency !!}{{ number_format($data['gross_amount'] ?? 0, 2) }}</td>
        </tr>
    </tbody>
</table>

@if(!empty($data['before_total_fees']))
    @foreach(json_decode($data['before_total_fees'] ?? '[]') as $fee)
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr>
            <td>{{ $fee->title ?? $fee->name ?? '' }}</td>
            <td align="right">{!! $payment_currency !!}{{ number_format($fee->amount ?? 0, 2) }}</td>
        </tr>
    </table>
    @endforeach
@endif

@if(($data['tax'] ?? 0) > 0)
<table width="100%" cellpadding="3" cellspacing="0">
    <tr>
        <td>Total Tax</td>
        <td align="right">{!! $payment_currency !!}{{ number_format($data['tax'], 2) }}</td>
    </tr>
</table>
@endif

@if(($data['sub_amount'] ?? 0) != ($data['gross_amount'] ?? 0))
    @if(count(json_decode($data['after_total_fees'] ?? '[]')) > 0)
    <table width="100%" cellpadding="3" cellspacing="0">
        <tr>
            <td><strong>Sub Total</strong></td>
            <td align="right"><strong>{!! $payment_currency !!}{{ number_format($data['sub_amount'] ?? 0, 2) }}</strong></td>
        </tr>
    </table>
    @endif
@endif

@foreach(json_decode($data['after_total_fees'] ?? '[]') as $fee)
<table width="100%" cellpadding="3" cellspacing="0">
    <tr>
        <td>{{ $fee->title ?? $fee->name ?? '' }}</td>
        <td align="right">{!! $payment_currency !!}{{ number_format($fee->amount ?? 0, 2) }}</td>
    </tr>
</table>
@endforeach

<table width="100%" cellpadding="3" cellspacing="0" style="border-top:2px solid #333; margin-top:5px;">
    <tr>
        <td><strong>Total</strong></td>
        <td align="right"><strong>{!! $payment_currency !!}{{ number_format($data['total_amount'] ?? 0, 2) }}</strong></td>
    </tr>
</table>

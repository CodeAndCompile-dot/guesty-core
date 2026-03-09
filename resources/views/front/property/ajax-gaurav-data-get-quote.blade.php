{{-- AJAX pricing partial for get-quote page --}}
@if(isset($mainData) && isset($property))
<div class="quote-pricing-result">
    <h5>{{ $property->title ?? '' }}</h5>

    <table class="table table-sm table-borderless">
        @if(!empty($mainData->fareAccommodation))
        <tr>
            <td>Accommodation</td>
            <td class="text-end">${{ number_format($mainData->fareAccommodation, 2) }}</td>
        </tr>
        @endif

        @if(!empty($mainData->cleaning))
        <tr>
            <td>Cleaning Fee</td>
            <td class="text-end">${{ number_format($mainData->cleaning, 2) }}</td>
        </tr>
        @endif

        @if(!empty($mainData->pet_fee_amount) && $mainData->pet_fee_amount > 0)
        <tr>
            <td>Pet Fee</td>
            <td class="text-end">${{ number_format($mainData->pet_fee_amount, 2) }}</td>
        </tr>
        @endif

        @if(!empty($mainData->additional_fees) && is_array($mainData->additional_fees))
            @foreach($mainData->additional_fees as $fee)
            <tr>
                <td>{{ $fee['title'] ?? 'Additional Fee' }}</td>
                <td class="text-end">${{ number_format($fee['amount'] ?? 0, 2) }}</td>
            </tr>
            @endforeach
        @endif

        @if(!empty($mainData->subTotal))
        <tr class="border-top">
            <td><strong>Subtotal</strong></td>
            <td class="text-end"><strong>${{ number_format($mainData->subTotal, 2) }}</strong></td>
        </tr>
        @endif

        @if(!empty($mainData->tax))
        <tr>
            <td>Tax</td>
            <td class="text-end">${{ number_format($mainData->tax, 2) }}</td>
        </tr>
        @endif

        @if(!empty($mainData->totalPrice))
        <tr class="border-top fw-bold">
            <td>Total</td>
            <td class="text-end">${{ number_format($mainData->totalPrice, 2) }}</td>
        </tr>
        @endif
    </table>

    @if(!empty($mainData->totalPrice) && $mainData->totalPrice > 0)
        <input type="hidden" name="total_price" value="{{ $mainData->totalPrice }}">
        <input type="hidden" name="fare_accommodation" value="{{ $mainData->fareAccommodation ?? 0 }}">
        <input type="hidden" name="cleaning_fee" value="{{ $mainData->cleaning ?? 0 }}">
        <input type="hidden" name="tax_amount" value="{{ $mainData->tax ?? 0 }}">
        <input type="hidden" name="sub_total" value="{{ $mainData->subTotal ?? 0 }}">
    @endif
</div>
@else
<div class="quote-pricing-result">
    <p class="text-muted">Select dates and number of guests to see pricing.</p>
</div>
@endif

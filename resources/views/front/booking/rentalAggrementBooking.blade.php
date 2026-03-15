@extends('front.layouts.master')
@section('title', $data->meta_title)
@section('keywords', $data->meta_keywords)
@section('description', $data->meta_description)

@section('container')

    @php
       $name=$data->name;
        $bannerImage=asset('front/images/breadcrumb.webp');
        $payment_currency = $setting_data['payment_currency'];
        $propety = App\Models\Guesty\GuestyProperty::find($booking['property_id']);
    @endphp
    <!-- start banner sec -->
    @include("front.layouts.banner")

    <!-- Rental Agreement Section -->
    <section class="rental-agreement-sec">
        <div class="container">
            <div class="rental-container">
                <!-- Property Details Card -->
                <div class="card property-card">
                    <div class="card-header">
                        <h2>Property Details</h2>
                    </div>
                    <div class="card-body">
                        <div class="property-info-item">
                            <div class="info-label">Property Name</div>
                            <div class="info-value">{{ $propety->meta_title }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Details Card -->
                <div class="card booking-card">
                    <div class="card-header">
                        <h2>Booking Details</h2>
                    </div>
                    <div class="card-body">
                        <!-- Booking Summary -->
                        <div class="booking-summary">
                            <div class="summary-grid">
                                <div class="summary-item">
                                    <div class="item-label">Check-in</div>
                                    <div class="item-value">{{ $booking['checkin'] }}</div>
                                </div>
                                <div class="summary-item">
                                    <div class="item-label">Check-out</div>
                                    <div class="item-value">{{ $booking['checkout'] }}</div>
                                </div>
                                <div class="summary-item">
                                    <div class="item-label">Total Guests</div>
                                    <div class="item-value">{{ $booking['total_guests'] }} ({{ $booking['adults'] }} Adults, {{ $booking['child'] }} Child)</div>
                                </div>
                                <div class="summary-item">
                                    <div class="item-label">Total Nights</div>
                                    <div class="item-value">{{ $booking['total_night'] }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price Breakdown -->
                        <div class="price-breakdown">
                            <h3>Price Breakdown</h3>
                            <table class="price-table">
                                <tbody>
                                    <!-- Additional Guest Fee -->
                                    @if ($booking['rest_guests'])
                                        @if ($booking['rest_guests'] > 0)
                                            @if ($booking['guest_fee'])
                                                @if ($booking['guest_fee'] > 0)
                                                    <tr class="fee-item">
                                                        <td class="fee-name">
                                                            <span>Additional Guest Fee</span>
                                                            <small>({{ $booking['total_night'] }} nights × {!! $setting_data['payment_currency'] !!}{{ $booking['single_guest_fee'] }} × {{ $booking['rest_guests'] }} Guests)</small>
                                                        </td>
                                                        <td class="fee-amount">
                                                            {!! $setting_data['payment_currency'] !!}{{ number_format($booking['guest_fee'], 2) }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Pet Fee (taxable) -->
                                    @if ($booking['pet_fee_type'] == 'taxable')
                                        @if ($booking['total_pets'])
                                            @if ($booking['total_pets'] > 0)
                                                @if ($booking['pet_fee'])
                                                    @if ($booking['pet_fee'] > 0)
                                                        <tr class="fee-item">
                                                            <td class="fee-name">Pet Fee</td>
                                                            <td class="fee-amount">
                                                                {!! $setting_data['payment_currency'] !!}{{ number_format($booking['pet_fee'], 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Heating Pool Fee (taxable) -->
                                    @if ($booking['heating_pool_fee_type'] == 'taxable')
                                        @if ($booking['heating_pool_fee'])
                                            @if ($booking['heating_pool_fee'] > 0)
                                                <tr class="fee-item">
                                                    <td class="fee-name">Heating the Swimming Pool Fee</td>
                                                    <td class="fee-amount">
                                                        {!! $setting_data['payment_currency'] !!}{{ number_format($booking['heating_pool_fee'], 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    <!-- Before Total Fees -->
                                    @if(isset($booking['before_total_fees']))             
                                        @foreach (json_decode($booking['before_total_fees']) as $c)
                                            <tr class="fee-item">
                                                <td class="fee-name">
                                                    @if($c->title){{ str_replace('_', ' ',$c->title) }}@endif
                                                </td>
                                                <td class="fee-amount">
                                                    @if($c->amount){!! $payment_currency !!}{{ number_format($c->amount, 2) }}@endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                  
                                    <!-- Tax (hidden) -->
                                    @if ($booking['tax'])
                                        <tr class="fee-item d-none">
                                            <td class="fee-name">Tax ({{ $booking['define_tax'] ?? '' }}%)</td>
                                            <td class="fee-amount">
                                                {!! $payment_currency !!}{{ number_format($booking['tax'], 2) }}
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Sub Total -->
                                    @if ($booking['sub_amount'] != $booking['gross_amount'])
                                        @if (count(json_decode($booking['after_total_fees'])) > 0)
                                            <tr class="subtotal-row">
                                                <td class="subtotal-label">Sub Total</td>
                                                <td class="subtotal-amount">
                                                    {!! $payment_currency !!}{{ number_format($booking['sub_amount'], 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif

                                    <!-- Pet Fee (non-taxable) -->
                                    @if ($booking['pet_fee_type'] != 'taxable')
                                        @if ($booking['total_pets'])
                                            @if ($booking['total_pets'] > 0)
                                                @if ($booking['pet_fee'])
                                                    @if ($booking['pet_fee'] > 0)
                                                        <tr class="fee-item">
                                                            <td class="fee-name">Pet Fee</td>
                                                            <td class="fee-amount">
                                                                {!! $setting_data['payment_currency'] !!}{{ number_format($booking['pet_fee'], 2) }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endif

                                    <!-- Heating Pool Fee (non-taxable) -->
                                    @if ($booking['heating_pool_fee_type'] != 'taxable')
                                        @if ($booking['heating_pool_fee'])
                                            @if ($booking['heating_pool_fee'] > 0)
                                                <tr class="fee-item">
                                                    <td class="fee-name">Heating the Swimming Pool Fee</td>
                                                    <td class="fee-amount">
                                                        {!! $setting_data['payment_currency'] !!}{{ number_format($booking['heating_pool_fee'], 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                     
                                    <!-- After Total Fees -->
                                    @if(isset($booking['after_total_fees']))
                                        @foreach (json_decode($booking['after_total_fees']) as $c)
                                            <tr class="fee-item">
                                                <td class="fee-name">{{ $c->name }}</td>
                                                <td class="fee-amount">
                                                    {!! $payment_currency !!}{{ number_format($c->amount, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                    <!-- Total -->
                                    <tr class="total-row">
                                        <td class="total-label">Total</td>
                                        <td class="total-amount">
                                            {!! $payment_currency !!}{{ number_format($booking['total_amount'], 2) }}
                                        </td>
                                    </tr>

                                    <!-- Discounts -->
                                    @php $gaurav_discount=0;@endphp
                                    @if ($booking['discount'])
                                        @if ($booking['discount'] != '')
                                            @if ($booking['discount'] != 0)
                                                @php $gaurav_discount=1;@endphp
                                                <tr class="discount-row">
                                                    <td class="discount-label">Discount ({{ $booking['discount_coupon'] }})</td>
                                                    <td class="discount-amount">
                                                        -{!! $setting_data['payment_currency'] !!}{{ number_format($booking['discount'], 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    @if ($booking['extra_discount'])
                                        @if ($booking['extra_discount'] != '')
                                            @if ($booking['extra_discount'] > 0)
                                                @php $gaurav_discount=1;@endphp
                                                <tr class="discount-row">
                                                    <td class="discount-label">Extra Discount</td>
                                                    <td class="discount-amount">
                                                        -{!! $setting_data['payment_currency'] !!}{{ number_format($booking['extra_discount'], 2) }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endif
                                    
                                    @if ($gaurav_discount == 1)
                                        <tr class="final-total-row">
                                            <td class="final-total-label">Total Amount after Discount</td>
                                            <td class="final-total-amount">
                                                {!! $setting_data['payment_currency'] !!}{{ number_format($booking['after_discount_total'], 2) }}
                                            </td>
                                        </tr>
                                    @endif

                                    <!-- Payment Details -->
                                    @if ($booking['amount_data'])
                                        @php
                                            $amount_data = json_decode($booking['amount_data'], true);
                                        @endphp
                                        @if (is_array($amount_data))
                                            @foreach ($amount_data as $c)
                                                @php $status='';@endphp
                                                @if (isset($c['status']))
                                                    @php $status='<span class="payment-status">Paid</span>'; @endphp
                                                @endif
                                                <tr class="payment-row">
                                                    <td class="payment-label">{{ $c['message'] }} {!! $status !!}</td>
                                                    <td class="payment-amount">
                                                        {!! $payment_currency !!}{{ number_format($c['amount'], 2) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Rental Agreement Card -->
                <div class="card agreement-card">
                    {!! Form::open([
                        'route' => 'rental-aggrement-data-save',
                        'files' => true,
                        'onsubmit' => 'return checkSignature()',
                        'class' => 'agreement-form'
                    ]) !!}
                    <input type="hidden" name="booking_id" value="{{ $booking['id'] }}">
                    
                    <div class="card-header">
                        <h2>Rental Agreement</h2>
                    </div>
                    
                    <div class="card-body">
                        <div class="agreement-document">
                            @if ($property->rental_aggrement_attachment)
                                <iframe id="iframepdf" src="{{ asset($property->rental_aggrement_attachment) }}"
                                    frameborder="0" scrolling="auto" height="500" width="100%"></iframe>
                            @endif
                        </div>
                        
                        <div class="agreement-consent">
                            <label class="consent-checkbox">
                                <input type="checkbox" id="agree" name="agree" required>
                                <span>I have read and agree to the Terms & Conditions.</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- E-Signature Card -->
                <div class="card signature-card">
                    <div class="card-header">
                        <h2>E-Signature</h2>
                    </div>
                    
                    <div class="card-body">
                        <div class="row signature-row">
                            <div class="col-md-6">
                                <div class="signature-pad-container">
                                    <label>Sign your agreement below</label>
                                    <canvas id="sig-canvas"></canvas>
                                    <div class="signature-actions">
                                        <button type="button" class="btn-clear" id="sig-clearBtn">Clear Signature</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="upload-id-container">
                                    <label>Please upload valid identity proof</label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" name="image" id="identity-proof" required>
                                        <label for="identity-proof" class="file-label">
                                            <span class="file-text">Choose File</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields -->
                        <div class="hidden-fields">
                            <textarea id="sig-dataUrl" name="signature" class="form-control" rows="5">Data URL for your signature will go here!</textarea>
                            <img id="sig-image" src="" alt="Your signature will go here!" />
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn-submit" id="sig-submitBtn">Pay Now</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

@stop

@section('js')
    <script>
        function checkSignature() {
            var canvas = document.getElementById('sig-canvas');
            if (isCanvasEmpty(canvas)) {
                toastr.error('Please enter signature!');
                return false;
            } else {
                return true;
            }
        }

        function isCanvasEmpty(canvas) {
            const blankCanvas = document.createElement('canvas');
            blankCanvas.width = canvas.width;
            blankCanvas.height = canvas.height;
            return canvas.toDataURL() === blankCanvas.toDataURL();
        }

        (function() {
            window.requestAnimFrame = (function(callback) {
                return window.requestAnimationFrame ||
                    window.webkitRequestAnimationFrame ||
                    window.mozRequestAnimationFrame ||
                    window.oRequestAnimationFrame ||
                    window.msRequestAnimaitonFrame ||
                    function(callback) {
                        window.setTimeout(callback, 1000 / 60);
                    };
            })();

            var canvas = document.getElementById("sig-canvas");
            var ctx = canvas.getContext("2d");
            ctx.strokeStyle = "#000000";
            ctx.lineWidth = 2;

            var drawing = false;
            var mousePos = {
                x: 0,
                y: 0
            };
            var lastPos = mousePos;

            canvas.addEventListener("mousedown", function(e) {
                drawing = true;
                lastPos = getMousePos(canvas, e);
            }, false);

            canvas.addEventListener("mouseup", function(e) {
                drawing = false;
            }, false);

            canvas.addEventListener("mousemove", function(e) {
                mousePos = getMousePos(canvas, e);
            }, false);

            // Add touch event support for mobile
            canvas.addEventListener("touchstart", function(e) {

            }, false);

            canvas.addEventListener("touchmove", function(e) {
                var touch = e.touches[0];
                var me = new MouseEvent("mousemove", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(me);
            }, false);

            canvas.addEventListener("touchstart", function(e) {
                mousePos = getTouchPos(canvas, e);
                var touch = e.touches[0];
                var me = new MouseEvent("mousedown", {
                    clientX: touch.clientX,
                    clientY: touch.clientY
                });
                canvas.dispatchEvent(me);
            }, false);

            canvas.addEventListener("touchend", function(e) {
                var me = new MouseEvent("mouseup", {});
                canvas.dispatchEvent(me);
            }, false);

            function getMousePos(canvasDom, mouseEvent) {
                var rect = canvasDom.getBoundingClientRect();
                return {
                    x: mouseEvent.clientX - rect.left,
                    y: mouseEvent.clientY - rect.top
                }
            }

            function getTouchPos(canvasDom, touchEvent) {
                var rect = canvasDom.getBoundingClientRect();
                return {
                    x: touchEvent.touches[0].clientX - rect.left,
                    y: touchEvent.touches[0].clientY - rect.top
                }
            }

            function renderCanvas() {
                if (drawing) {
                    ctx.moveTo(lastPos.x, lastPos.y);
                    ctx.lineTo(mousePos.x, mousePos.y);
                    ctx.stroke();
                    lastPos = mousePos;
                }
            }

            // Prevent scrolling when touching the canvas
            document.body.addEventListener("touchstart", function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, false);
            document.body.addEventListener("touchend", function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, false);
            document.body.addEventListener("touchmove", function(e) {
                if (e.target == canvas) {
                    e.preventDefault();
                }
            }, false);

            (function drawLoop() {
                requestAnimFrame(drawLoop);
                renderCanvas();
            })();

            function clearCanvas() {
                canvas.width = canvas.width;
            }

            // Set up the UI
            var sigText = document.getElementById("sig-dataUrl");
            var sigImage = document.getElementById("sig-image");
            var clearBtn = document.getElementById("sig-clearBtn");
            var submitBtn = document.getElementById("sig-submitBtn");
            clearBtn.addEventListener("click", function(e) {
                clearCanvas();
                sigText.innerHTML = "Data URL for your signature will go here!";
                sigImage.setAttribute("src", "");
                e.preventDefault();
            }, false);
            submitBtn.addEventListener("click", function(e) {
                var dataUrl = canvas.toDataURL();
                sigText.innerHTML = dataUrl;
                sigImage.setAttribute("src", dataUrl);
            }, false);

            // File input label update
            const fileInput = document.getElementById('identity-proof');
            const fileLabel = document.querySelector('.file-text');

            if (fileInput && fileLabel) {
                fileInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        fileLabel.textContent = this.files[0].name;
                    } else {
                        fileLabel.textContent = 'Choose File';
                    }
                });
            }
        })();
    </script>
@stop

@section('css')
    <style>
        /* Modern Black & White Theme Variables */
        :root {
            /* Color Palette */
            --primary-accent: #000000; /* Pure Black */
            --background-light: #F5F5F5; /* Off White */
            --grey-light: #E0E0E0; /* Light Grey */
            --grey-mid: #B0B0B0; /* Medium Grey */
            --grey-dark: #707070; /* Dark Grey */
            --highlight-bg: #1A1A1A; /* Charcoal */
            --text-light: #FFFFFF; /* White */
            --text-dark: #000000; /* Black */
            --accent-success: #28a745; /* Success Green */
            --accent-error: #dc3545; /* Error Red */
        }

        /* Base Styles */
        .rental-agreement-sec {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            color: var(--text-dark);
            background-color: var(--background-light);
            padding: 40px 0;
        }

        .rental-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Card Styling */
        .card {
            background-color: var(--text-light);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            overflow: hidden;
        }

        .card-header {
            background-color: var(--primary-accent);
            padding: 15px 20px;
            position: relative;
        }

        .card-header h2 {
            color: var(--text-light);
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .card-body {
            padding: 20px;
        }

        /* Property Info Styling */
        .property-info-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
        }

        .info-label {
            font-weight: 600;
            width: 150px;
            color: var(--grey-dark);
        }

        .info-value {
            flex: 1;
            color: var(--text-dark);
        }

        /* Booking Summary Grid */
        .booking-summary {
            margin-bottom: 30px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
            border: 1px solid var(--grey-light);
            border-radius: 6px;
        }

        .summary-item {
            padding: 15px;
            border-right: 1px solid var(--grey-light);
            border-bottom: 1px solid var(--grey-light);
        }

        .summary-item:last-child {
            border-right: none;
        }

        .item-label {
            font-size: 14px;
            color: var(--grey-dark);
            margin-bottom: 5px;
        }

        .item-value {
            font-size: 16px;
            font-weight: 500;
            color: var(--text-dark);
        }

        /* Price Breakdown Table */
        .price-breakdown {
            margin-top: 20px;
        }

        .price-breakdown h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: var(--highlight-bg);
            font-weight: 600;
        }

        .price-table {
            width: 100%;
            border-collapse: collapse;
        }

        .price-table tr {
            border-bottom: 1px solid var(--grey-light);
        }

        .price-table tr:last-child {
            border-bottom: none;
        }

        .price-table td {
            padding: 12px 15px;
            vertical-align: top;
        }

        .fee-name {
            color: var(--text-dark);
            font-weight: 400;
            width: 70%;
        }

        .fee-name small {
            display: block;
            font-size: 12px;
            color: var(--grey-dark);
            margin-top: 4px;
        }

        .fee-amount {
            text-align: right;
            font-weight: 500;
            width: 30%;
        }

        /* Total Rows */
        .subtotal-row {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .subtotal-label {
            font-weight: 600;
        }

        .subtotal-amount {
            font-weight: 600;
            text-align: right;
        }

        .total-row {
            border-top: 2px solid var(--grey-dark);
            border-bottom: none !important;
        }

        .total-label {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-accent);
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            text-align: right;
            color: var(--primary-accent);
        }

        .discount-row {
            color: var(--accent-error);
        }

        .discount-amount {
            text-align: right;
            color: var(--accent-error);
            font-weight: 500;
        }

        .final-total-row {
            background-color: rgba(0, 0, 0, 0.05);
            border-top: 1px solid var(--grey-mid);
            border-bottom: none !important;
        }

        .final-total-label {
            font-weight: 700;
        }

        .final-total-amount {
            font-weight: 700;
            text-align: right;
        }

        .payment-status {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--accent-success);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 5px;
        }

        /* Rental Agreement */
        .agreement-document {
            margin-bottom: 20px;
        }

        #iframepdf {
            border: 1px solid var(--grey-light);
            border-radius: 4px;
            width: 100%;
        }

        .agreement-consent {
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 4px;
        }

        .consent-checkbox {
            display: flex;
            align-items: center;
            cursor: pointer;
            user-select: none;
        }

        .consent-checkbox input {
            margin-right: 10px;
        }

        /* E-Signature Styling */
        .signature-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -15px;
        }

        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 15px;
        }

        .signature-pad-container,
        .upload-id-container {
            margin-bottom: 20px;
        }

        .signature-pad-container label,
        .upload-id-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
        }

        #sig-canvas {
            border: 2px dashed var(--grey-mid);
            border-radius: 8px;
            cursor: crosshair;
            width: 100%;
            height: 160px;
            background-color: var(--text-light);
            margin-bottom: 10px;
        }

        .signature-actions {
            display: flex;
            justify-content: flex-end;
        }

        .btn-clear {
            background: transparent;
            border: 1px solid var(--grey-dark);
            color: var(--grey-dark);
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }

        .btn-clear:hover {
            background-color: var(--grey-light);
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            margin-top: 10px;
        }

        #identity-proof {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 10;
        }

        .file-label {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed var(--grey-mid);
            border-radius: 8px;
            padding: 30px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.02);
            cursor: pointer;
        }

        .file-text {
            color: var(--grey-dark);
        }

        /* Hidden Fields */
        .hidden-fields {
            display: none;
        }

        /* Form Actions */
        .form-actions {
            margin-top: 30px;
            text-align: center;
        }

        .btn-submit {
             border-radius: 50px;
             padding: 15px;
             height: auto;
            background-color: var(--primary-accent);
            color: var(--text-light);
            border: none;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            min-width: 200px;
        }

        .btn-submit:hover {
            background-color: var(--highlight-bg);
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .rental-container {
                padding: 0 15px;
            }
            
            .summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .summary-item:nth-child(2n) {
                border-right: none;
            }
        }

        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .fee-name {
                width: 60%;
            }
            
            .fee-amount {
                width: 40%;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 15px;
            }
            
            .summary-grid {
                grid-template-columns: 1fr;
            }
            
            .summary-item {
                border-right: none;
            }
            
            .price-table td {
                padding: 10px;
            }
            
            .fee-name small {
                font-size: 10px;
            }
            
            .btn-submit {
                width: 100%;
            }
        }
    </style>
@stop
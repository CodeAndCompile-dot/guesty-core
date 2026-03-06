 <h5 class="text-warning">Day for send email</h5>
 <div class="row">
    <div class="col-md-3"><strong>Welcome Package send day before check in</strong></div>
    <div class="col-md-9">
        <div class="form-group">
            <input type="text" class="form-control" name="input['welcome_package_send_day']" value="{{ ModelHelper::getDataFromSetting('welcome_package_send_day') }}" placeholder="welcome_package_send_day">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3"><strong>Review send day before check out</strong></div>
    <div class="col-md-9">
        <div class="form-group">
            <input type="text" class="form-control" name="input['review_send_day']" value="{{ ModelHelper::getDataFromSetting('review_send_day') }}" placeholder="review_send_day">
        </div>
    </div>
</div>


<h5 class="text-warning">Payment</h5>
<div class="row">
    <div class="col-md-3"><strong>Payment Currency Sign</strong></div>
    <div class="col-md-9">
        <div class="form-group">
            {!! Form::select("input['payment_currency']",["$"=>"Dollar","€"=>"Euro","CAD"=>"CAD"],ModelHelper::getDataFromSetting('payment_currency'),["class"=>"form-control","id"=>"payment_currency"]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3"><strong>Payment Interval</strong></div>
    <div class="col-md-9">
        <div class="form-group">
            {!! Form::select("input['payment_interval']",["1"=>"1","2"=>"2","3"=>"3"],ModelHelper::getDataFromSetting('payment_interval'),["class"=>"form-control","id"=>"payment_interval"]) !!}
        </div>
    </div>
</div>
<div id="second-payment" class="d-none">
    <h6 class="text-warning">Second Payment</h6>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Number of days to allow only two payments</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="number" class="form-control" name="input['second_min_total_days']" value="{{ ModelHelper::getDataFromSetting('second_min_total_days') }}" placeholder="Number of days to allow only two payments">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Payment Mode</div>
                <div class="col-md-8">
                    <div class="form-group">
                       
                        {!! Form::select("input['second_payment_mode']",["online"=>"online","manually"=>"manually"],ModelHelper::getDataFromSetting('second_payment_mode'),["class"=>"form-control","id"=>"second_payment_mode"]) !!}

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                    <div class="col-md-4">What % to collect </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="number" class="form-control" name="input['second_payment_per']" value="{{ ModelHelper::getDataFromSetting('second_payment_per') }}" placeholder="What % to collect on second Payment">
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                    <div class="col-md-4">How Many Days before checkin</div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="number" class="form-control" name="input['second_how_many_days']" value="{{ ModelHelper::getDataFromSetting('second_how_many_days') }}" placeholder="Second Payment How Many Days before checkin">
                        </div>
                    </div>
            </div>
        </div>
        <div class="col-md-12 d-none" id="third-second-payment" >
            <div class="row">
                <div class="col-md-4">How Many Days before checkin (If third payment applicable)</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="number" class="form-control" name="input['second_third_how_many_days']" value="{{ ModelHelper::getDataFromSetting('second_third_how_many_days') }}" placeholder="Second Payment How Many Days before checkin when third is active">
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="row">
                        <div class="col-md-4">What % to collect (If third payment applicable)</div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" name="input['second_third_payment_per']" value="{{ ModelHelper::getDataFromSetting('second_third_payment_per') }}" placeholder="What % to collect on second Payment (If third payment applicable)">
                            </div>
                        </div>
                </div>
            </div>
        </div>
        
    
            
   

    </div>
</div>

<div id="third-payment" class="d-none">
    <h6 class="text-warning">Third Payment</h6>
    <div class="row">
         <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Number of days to allows three payments</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="number" class="form-control" name="input['third_min_total_days']" value="{{ ModelHelper::getDataFromSetting('third_min_total_days') }}" placeholder="Number of days to allows three payments">
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">How Many Days before checkin</div>
                <div class="col-md-8">
                    <div class="form-group">
                        <input type="number" class="form-control" name="input['third_how_many_days']" value="{{ ModelHelper::getDataFromSetting('third_how_many_days') }}" placeholder="third Payment How Many Days before checkin">
                    </div>
                </div>
            </div>
        </div>
         <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">Payment Mode</div>
                <div class="col-md-8">
                    <div class="form-group">
                       
                        {!! Form::select("input['third_payment_mode']",["online"=>"online","manually"=>"manually"],ModelHelper::getDataFromSetting('third_payment_mode'),["class"=>"form-control","id"=>"third_payment_mode"]) !!}

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                    <div class="col-md-4">What % to collect </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="number" class="form-control" name="input['third_payment_per']" value="{{ ModelHelper::getDataFromSetting('third_payment_per') }}" placeholder="What % to collect on third Payment">
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
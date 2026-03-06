
 <h5 class="text-warning">Guesty Open API KEYs</h2>
 <div class="row ">
 	<div class="col-md-3"><strong>OpenClientid</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['OpenClientid']" value="{{ ModelHelper::getDataFromSetting('OpenClientid') }}" placeholder="OpenClientid">
		</div>
	</div>
</div>
<div class="row ">
 	<div class="col-md-3"><strong>OpenClientSecretkey</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['OpenClientSecretkey']" value="{{ ModelHelper::getDataFromSetting('OpenClientSecretkey') }}" placeholder="OpenClientSecretkey">
		</div>
	</div>
</div>

 <div class="row ">
 	<div class="col-md-3"><strong>BookingClientid</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['BookingClientid']" value="{{ ModelHelper::getDataFromSetting('BookingClientid') }}" placeholder="BookingClientid">
		</div>
	</div>
</div>
<div class="row ">
 	<div class="col-md-3"><strong>BookingClientSecretkey</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['BookingClientSecretkey']" value="{{ ModelHelper::getDataFromSetting('BookingClientSecretkey') }}" placeholder="BookingClientSecretkey">
		</div>
	</div>
</div>

 <h5 class="text-warning">Contact US </h2>
 <div class="row">
	<div class="col-md-3"><strong>Bloacked email</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			
			<textarea class="form-control" name="input['blocked_email']" placeholder="blocked_email">{{ ModelHelper::getDataFromSetting('blocked_email') }}</textarea>
		</div>
	</div>
</div>
  <h5 class="text-warning ">Which Payment Gateway</h2>
  <div class="row">
 
	<div class="col-md-12">
		<div class="form-group">
		    
		    {!! Form::select("input['which_payment_gateway']",["stripe"=>"stripe"],ModelHelper::getDataFromSetting('which_payment_gateway'),["class"=>"form-control"]) !!}
		
		</div>
	</div>
</div>

 
 <h5 class="text-warning">Stripe</h2>
 <div class="row ">
 	<div class="col-md-3"><strong>Publish Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['stripe_publish_key']" value="{{ ModelHelper::getDataFromSetting('stripe_publish_key') }}" placeholder="stripe publish key">
		</div>
	</div>
</div>
<div class="row ">
 	<div class="col-md-3"><strong>Secret Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['stripe_secret_key']" value="{{ ModelHelper::getDataFromSetting('stripe_secret_key') }}" placeholder="stripe secret key">
		</div>
	</div>
</div>
 <h5 class="text-warning d-none">Paypal</h2>
 <div class="row d-none">
 	<div class="col-md-3"><strong>Access token Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['paypal_access_token']" value="{{ ModelHelper::getDataFromSetting('paypal_access_token') }}" placeholder="Paypal Access Token">
		</div>
	</div>
</div>

<h5 class="text-warning d-none">IGMS</h2>
<div class="row d-none">
 	<div class="col-md-3"><strong>Access token Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['igms_access_token']" value="{{ ModelHelper::getDataFromSetting('igms_access_token') }}" placeholder="IGMS Access Token">
		</div>
	</div>
</div>

<h5 class="text-warning">PriceLab</h2>
<div class="row">
 	<div class="col-md-3"><strong>Access token Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['pricelab_access_token']" value="{{ ModelHelper::getDataFromSetting('pricelab_access_token') }}" placeholder="Pricelab Access Token">
		</div>
	</div>
</div>
<h5 class="text-warning d-none">Hosttools</h2>
<div class="row d-none">
 	<div class="col-md-3"><strong>Access token Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['hosttools_access_token']" value="{{ ModelHelper::getDataFromSetting('hosttools_access_token') }}" placeholder="Hosttools Access Token">
		</div>
	</div>
</div>
<h5 class="text-warning">Google Captcha</h2>
<div class="row">
 	<div class="col-md-3"><strong>Enable / Disabled</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			{!! Form::select("input['g_captcha_enabled']",["no"=>"no","yes"=>"yes"],ModelHelper::getDataFromSetting('g_captcha_enabled'),["class"=>"form-control"]) !!}
		</div>
	</div>
</div>
<div class="row">
 	<div class="col-md-3"><strong>Site Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['google_captcha_site_key']" value="{{ ModelHelper::getDataFromSetting('google_captcha_site_key') }}" placeholder="Google Captcha Site Key">
		</div>
	</div>
</div>
<div class="row">
 	<div class="col-md-3"><strong>Secret Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['google_captcha_secret_key']" value="{{ ModelHelper::getDataFromSetting('google_captcha_secret_key') }}" placeholder="Google Captcha Secret Key">
		</div>
	</div>
</div>
<h5 class="text-warning d-none">Google map</h2>
<div class="row d-none">
 	<div class="col-md-3"><strong>Access token Key</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['google_map_access_token']" value="{{ ModelHelper::getDataFromSetting('google_map_access_token') }}" placeholder="Google map Access Token">
		</div>
	</div>
</div>




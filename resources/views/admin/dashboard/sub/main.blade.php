
<div class="row">
	<div class="col-md-3"><strong>Address</strong></div>
	<div class="col-md-9">
		<div class="form-group">

			<textarea class="form-control" name="input['address']" placeholder="address">{{ ModelHelper::getDataFromSetting('address') }}</textarea>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3"><strong>about</strong></div>
	<div class="col-md-9">
		<div class="form-group">

			<textarea class="form-control" name="input['about']" placeholder="about">{{ ModelHelper::getDataFromSetting('about') }}</textarea>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>map</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['map']" value="{{ ModelHelper::getDataFromSetting('map') }}" placeholder="map ">
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-3"><strong>property-list-map</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['property-list-map']" value="{{ ModelHelper::getDataFromSetting('property-list-map') }}" placeholder="map ">
		</div>
	</div>
</div>






<div class="row">
	<div class="col-md-3"><strong>Mobile</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['mobile']" value="{{ ModelHelper::getDataFromSetting('mobile') }}" placeholder="mobile ">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>email</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['email']" value="{{ ModelHelper::getDataFromSetting('email') }}" placeholder="email ">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>WhatsApp Mobile</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['mobile1']" value="{{ ModelHelper::getDataFromSetting('mobile1') }}" placeholder="mobile1 ">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3"><strong>Copyright</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea class="form-control" name="input['copyright']" placeholder="copyright">{{ ModelHelper::getDataFromSetting('copyright') }}</textarea>
		</div>
	</div>
</div>
<div class="row   d-none">
	<div class="col-md-3"><strong>Chat bot</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['chat-bot']"  placeholder="Chat bot ">{{ ModelHelper::getDataFromSetting('chat-bot') }}</textarea>
		</div>
	</div>

	<div class="col-md-3"><strong>Google analytics</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['google-analytics']" placeholder="Google analytics">{{ ModelHelper::getDataFromSetting('google-analytics') }}</textarea>
		</div>
	</div>

	<div class="col-md-3"><strong>Google tag-master</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['google-tag-master']" placeholder="Google tag-master">{{ ModelHelper::getDataFromSetting('google-tag-master') }}</textarea>
		</div>
	</div>

	<div class="col-md-3"><strong> facebook pixel code</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['facebook-pixel-code']"  placeholder="facebook-pixel-code">{{ ModelHelper::getDataFromSetting('facebook-pixel-code') }}</textarea>
		</div>
	</div>

	<div class="col-md-3"><strong> Common Footer Data</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['common-footer-data']"  placeholder="Common Footer Data">{{ ModelHelper::getDataFromSetting('common-footer-data') }}</textarea>
		</div>
	</div>

	<div class="col-md-3"><strong> Common Header Data</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<textarea type="text" class="form-control" name="input['common-header-data']"  placeholder="Common Header Data">{{ ModelHelper::getDataFromSetting('common-header-data') }}</textarea>
		</div>
	</div>
</div>

<h5 class="text-warning">JS/CSS Version</h5>

<div class="row">
	<div class="col-md-3"><strong>Version</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['version_css_js']" value="{{ ModelHelper::getDataFromSetting('version_css_js') }}" placeholder="Version">
		</div>
	</div>
</div>
<h5 class="text-warning">POP-UP Text</h5>

<div class="row">
	<div class="col-md-3"><strong>Text</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input type="text" class="form-control" name="input['pop-up']" value="{{ ModelHelper::getDataFromSetting('pop-up') }}" placeholder="POP-UP Text">
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3"><strong>Website</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			
			<input class="form-control" name="input['website']" placeholder="website without http and www" value="{{ ModelHelper::getDataFromSetting('website') }}">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>Owner Name</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			
			<input class="form-control" name="input['owner_name']" placeholder="Owner Name" value="{{ ModelHelper::getDataFromSetting('owner_name') }}">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>favicon</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input class="form-control" name="input['favicon']" type="file">
			@if(ModelHelper::getDataFromSetting('favicon'))
				<img src="{{ asset(ModelHelper::getDataFromSetting('favicon')) }}" width="100px" />
			@endif

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>og image</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			<input class="form-control" name="input['ogimage']" type="file">
			@if(ModelHelper::getDataFromSetting('ogimage'))
				<img src="{{ asset(ModelHelper::getDataFromSetting('ogimage')) }}" width="100px" />
			@endif

		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>Header logo</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			
			<input class="form-control" name="input['header_logo']" type="file">
			@if(ModelHelper::getDataFromSetting('header_logo'))
				<img src="{{ asset(ModelHelper::getDataFromSetting('header_logo')) }}" width="200px" />
			@endif
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3"><strong>Footer logo</strong></div>
	<div class="col-md-9">
		<div class="form-group" >
			
			<input class="form-control" name="input['footer_logo']" type="file">
			@if(ModelHelper::getDataFromSetting('footer_logo'))
				<img src="{{ asset(ModelHelper::getDataFromSetting('footer_logo')) }}" width="200px" />
			@endif
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3"><strong>Owner Image</strong></div>
	<div class="col-md-9">
		<div class="form-group" >
			
			<input class="form-control" name="input['owner_image']" type="file">
			@if(ModelHelper::getDataFromSetting('owner_image'))
				<img src="{{ asset(ModelHelper::getDataFromSetting('owner_image')) }}" width="200px" />
			@endif
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-3"><strong>Home Video</strong></div>
	<div class="col-md-9">
		<div class="form-group" >
			
			<input class="form-control" name="input['home_video']" type="file">
			@if(ModelHelper::getDataFromSetting('home_video'))
			
				        <video id="mob" class="mob__video" src="{{ asset(ModelHelper::getDataFromSetting('home_video')) }}" controls style="width:400px;"> </video>
			@endif
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-3"><strong>Home video text</strong></div>
	<div class="col-md-9">
		<div class="form-group">
			
			<textarea class="form-control" name="input['home-video-text']" placeholder="home-video-text">{{ ModelHelper::getDataFromSetting('home-video-text') }}</textarea>
		</div>
	</div>
</div>
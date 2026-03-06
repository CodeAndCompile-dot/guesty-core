

 <div class="input-images-2" style="padding-top: .5rem;padding-bottom: .5rem;"></div>


@if(isset($data))	
	@if(count(ModelHelper::getImageByProduct($data->id))>0)
  	<div id="gallery">
  		 
        
        <div id="image-container">
        <h2>Change Order of Images in Photo Gallery with Drag and Drop </h2>
        <div id="txtresponse" class="text-success"> </div>
         <div id="submit-container"> 
            <input type='button' class="btn btn-info" value='Update Caption and ordering' id='submit' />
        </div>
        
            <div class="row" id="image-list" >
              
                @foreach(ModelHelper::getImageByProduct($data->id) as $image)

                     
                     
                    <div class="col-md-3 " >
                    	<div class="gaurav-class" id="image_{{ (($image->id)) }}">
				            <img src="{{ asset(($image->image)) }}"  class="img-responsive" width="100%" ><br>
				            <a href="javaScript:void(0)" class="text-danger delete-image-product" data-id="{{ $image->id }}"> <i class="fa fa-times"></i> Delete</a>
				            <input type="text" id="caption_{{ (($image->id)) }}" class="caption_name form-control" placeholder="caption name"  value="{{ $image->caption }}" >
				        </div>
			        </div>
                @endforeach
            </div>

        </div>            
      
    </div>

@endif
@endif        
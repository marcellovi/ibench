<!DOCTYPE html>
<html lang="en">
  <head>


     @include('admin.title')
    @include('admin.style')


  </head>

  <body>
  @include('admin.top')

@include('admin.menu')



<div id="content">
  <div id="content-header">
    <div id="breadcrumb">  </div>
    <h1>Edit Banner</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
      @if(Session::has('error'))
      <div class="alert alert-error">
              <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
              {{ Session::get('error') }}
              </div>
      @endif

      @if(Session::has('success'))


        <div class="alert alert-success">
              <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
               {{ Session::get('success') }}
              </div>

	@endif

        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Edit Banner</h5>
          </div>
          <div class="widget-content">
         <?php $url = URL::to("/"); ?>
                   <form role="form" method="POST" action="{{ route('admin.edit-banner') }}" enctype="multipart/form-data" accept-charset="utf-8" id="formID">
                     {{ csrf_field() }}





















              <input type="hidden" name="id" value="<?php echo $slideshow[0]->id; ?>">




              <div class="form-group">
                <label class="control-label">Image <span class="required">*</span></label>
                <div class="controls">





                  <input type="file" id="photo" name="photo" class="form-control<?php if($slideshow[0]->slide_image==""){?> validate[required]<?php } ?>">

						  @if ($errors->has('photo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif


                </div>

                <?php $url = URL::to("/"); ?>
					  <?php
					   $testimonialphoto="/media/";
						$path ='/local/images'.$testimonialphoto.$slideshow[0]->slide_image;
						if($slideshow[0]->slide_image!=""){
            ?>
              <div class="form-group">
                <label class="control-label">Position </label>
                <div class="controls"  style="display: flex">
                <div style="display: flex; margin-right: 20px">
                <input type="radio" checked="false" <?php echo ($slideshow[0]->position=='1')?'checked':'' ?>  id="contactChoice1"
                    name="position" value="1">
                    <label for="contactChoice1">1</label>
                    </div>
                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice2"
                    name="position" <?php echo ($slideshow[0]->position=='2')?'checked':'' ?>  value="2">
                    <label for="contactChoice2">2</label>
                </div>
                <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice3"
                    name="position" <?php echo ($slideshow[0]->position=='3')?'checked':'' ?>  value="3">
                    <label for="contactChoice3">3</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice4"
                    name="position" <?php echo ($slideshow[0]->position=='4')?'checked':'' ?>  value="4">
                    <label for="contactChoice4">4</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice5"
                    name="position" <?php echo ($slideshow[0]->position=='5')?'checked':'' ?>  value="5">
                    <label for="contactChoice5">5</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice6"
                    name="position" <?php echo ($slideshow[0]->position=='6')?'checked':'' ?>  value="6">
                    <label for="contactChoice6">6</label>
                </div>


                </div>
            </div>
					  <div class="form-group">
                      <div class="controls">
					  <div>
					  <img src="<?php echo $url.$path;?>" class="thumb" width="100">
					  </div>
					  </div>
                      </div>
						<?php } else { ?>
					  <div class="form-group">
                      <div class="controls">
					  <div>
					  <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="100">
					  </div>
					  </div>
                      </div>
						<?php } ?>





					  <input type="hidden" name="currentphoto" value="<?php echo $slideshow[0]->slide_image;?>">



              </div>





               <div class="form-group">
                <label class="control-label">Link </label>
                <div class="controls">



                    <input id="slide_btn_link" class="form-control"  name="slide_btn_link" value="<?php echo $slideshow[0]->slide_btn_link; ?>" type="text">


                </div>
              </div>



              <div class="form-group">
                <label class="control-label">Enable? </label>
                <div class="controls">



                    <input id="slide_status"  name="slide_status" value="1" type="checkbox" <?php if($slideshow[0]->slide_status==1){?> checked <?php } ?>>


                </div>
              </div>












              <div class="form-actions">
                        <div>


						   <a href="<?php echo $url;?>/admin/banners" class="btn btn-primary">Cancel</a>

						  <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button>
								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>

                           <button id="send" type="submit" class="btn btn-success">Submit</button>
						  <?php } ?>

                        </div>
                        </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>




	 @include('admin.footer')
  </body>
</html>

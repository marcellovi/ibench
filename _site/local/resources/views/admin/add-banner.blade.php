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
    <h1>Adicionar Banner</h1>
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
            <h5>Adicionar Banner</h5>
          </div>
          <div class="widget-content">


              <?php $url = URL::to("/"); ?>


               <form role="form" method="POST" action="{{ route('admin.add-banner') }}" enctype="multipart/form-data" id="formID" accept-charset="utf-8">
                     {{ csrf_field() }}

             <div class="form-group">
                <label class="control-label">Position </label>
                <div class="controls"  style="display: flex">
                <div style="display: flex; margin-right: 20px">
                <input type="radio" checked="false"  id="contactChoice1"
                    name="position" value="1">
                    <label for="contactChoice1">1</label>
                    </div>
                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice2"
                    name="position"value="2">
                    <label for="contactChoice2">2</label>
                </div>
                <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice3"
                    name="position" value="3">
                    <label for="contactChoice3">3</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice4"
                    name="position"  value="4">
                    <label for="contactChoice4">4</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice5"
                    name="position"  value="5">
                    <label for="contactChoice5">5</label>
                    </div>

                    <div style="display: flex; margin-right: 20px">

                    <input type="radio" id="contactChoice6"
                    name="position"   value="6">
                    <label for="contactChoice6">6</label>
                </div>


                </div>
            </div>

              <div class="form-group">
                <label class="control-label">Link </label>
                <div class="controls">

                    <input id="slide_btn_link" class="form-control"  name="slide_btn_link" value="" type="text">

                </div>
              </div>




              <div class="form-group custom-file col-md-4 mb-3">
                <label class="custom-file-label">Imagem <span class="required">*</span></label>
                <div class="controls">



                  <input type="file" id="photo" name="photo" class="validate[required] custom-file-input">

						  @if ($errors->has('photo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif

                </div>
              </div>


              <div class="form-group">
                <label class="control-label">Enable? </label>
                <div class="controls">



                    <input id="slide_status"  name="slide_status" value="1" type="checkbox">


                </div>
              </div>








              <?php $url = URL::to("/"); ?>
              <div class="form-actions">
                        <div>


                        <a href="<?php echo $url;?>/admin/banners" class="btn btn-primary">Cancel</a>

						  <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button>
								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>

                            <button id="send" type="submit" class="btn btn-success">Submit</button>
								<?php } ?>
                        </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>



</div>






     @include('admin.footer')

  </body>
</html>

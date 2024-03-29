<!DOCTYPE html>

<html lang="en">

  <head>





     @include('admin.title')

    @include('admin.style')



    <!-- marcello tinymce -->

 <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=cae5v8ohqq2y45yurx2yqba3ng4rqukel679jhibsfg3gk4r"></script>

<script> tinymce.init({ selector:'textarea' });</script>

  </head>



  <body>

  @include('admin.top')



@include('admin.menu')













  <div id="content">

  <div id="content-header">

    <div id="breadcrumb">  </div>

    <h1>Edit Page</h1>

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

            <h5>Edit Page</h5>

          </div>

          <div class="widget-content">

         <?php $url = URL::to("/"); ?>

                   <form role="form" method="POST" action="{{ route('admin.edit-page') }}" enctype="multipart/form-data" accept-charset="utf-8" id="formID">

                     {{ csrf_field() }}





              <div class="form-group">

                <label>Heading</label>

                <input id="page_title" class="validate[required] form-control"  name="page_title" value="<?php echo $pages[0]->page_title; ?>" type="text">

                           @if ($errors->has('page_title'))

                                    <span class="help-block" style="color:red;">

                                        <strong>That page is already exists</strong>

                                    </span>

                                @endif

              </div>









              <div class="form-group">

                <label>Description</label>

                  <textarea id="page_desc" class="validate[required] span8" name="page_desc" style="min-height:200px;"><?php echo $pages[0]->page_desc; ?></textarea>

              </div>



              <?php if($pages[0]->page_id!=4){?>

              <div class="form-group">

                <label>Image</label>



                 <input type="file" id="photo" name="photo" class="form-control"><br/><br/><span> (Size is : 1140px X 450px)</span>

						  @if ($errors->has('photo'))

                                    <span class="help-block" style="color:red;">

                                        <strong>{{ $errors->first('photo') }}</strong>

                                    </span>

                                @endif





                <?php $url = URL::to("/"); ?>

					  <?php

					   $servicephoto="/media/";

						$path ='/local/images'.$servicephoto.$pages[0]->photo;

						if($pages[0]->photo!=""){

						?>

					  <div class="form-group">

					  <img src="<?php echo $url.$path;?>" class="thumb" width="100">

                      </div>

						<?php } else { ?>

					  <div class="form-group">

					  <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="100">

                      </div>

						<?php } ?>

              </div>







               <div class="form-group">

                <label>Showing Image</label>

                  <input type="checkbox" id="show_photo" class="" value="1" name="show_photo" <?php if($pages[0]->show_photo==1){?> checked <?php } ?>>

              </div>

              <?php } ?>



              <input type="hidden" name="currentphoto" value="<?php echo $pages[0]->photo;?>">





					  <input type="hidden" name="page_id" value="<?php echo $pages[0]->page_id; ?>">



















              <div class="form-actions">

                <a href="<?php echo $url;?>/admin/pages" class="btn btn-primary">Cancel</a>



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


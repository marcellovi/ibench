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

    <h1>Add Page</h1>

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

            <h5>Add Page</h5>

          </div>

          <div class="widget-content">





              <?php $url = URL::to("/"); ?>

                   <form role="form" method="POST" action="{{ route('admin.add-page') }}" enctype="multipart/form-data" id="formID" accept-charset="utf-8">

                     {{ csrf_field() }}

              <div class="form-group">

                <label>Heading</label>

                  <input id="page_title" class="validate[required] form-control"  name="page_title" value="" type="text">

                         @if ($errors->has('page_title'))

                                    <span class="help-block" style="color:red;">

                                        <strong>That page is already exists</strong>

                                    </span>

                                @endif

              </div>





              <div class="form-group">

                <label>Description</label>

                  <textarea id="page_desc" class="validate[required] form-control" name="page_desc" style="min-height:200px;"></textarea>

              </div>







              <div class="form-group">

                <label>Image</label>

                   <input type="file" id="photo" name="photo" class="form-control"><br/><br/><span> (Size is : 1140px X 450px)</span>

						  @if ($errors->has('photo'))

                                    <span class="help-block" style="color:red;">

                                        <strong>{{ $errors->first('photo') }}</strong>

                                    </span>

                                @endif

              </div>




              <div class="form-group">
                <div class="form-check form-check-inline">

                  <label class="form-check-label">Showing Image</label>

                    <input type="checkbox" id="show_photo" class="form-check-input" value="1" name="show_photo">

                </div>
              </div>








              <?php $url = URL::to("/"); ?>

              <div class="form-actions">

                         <a href="<?php echo $url;?>/admin/pages" class="btn btn-primary">Cancel</a>







						  <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button>

								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>



                           <button id="send" type="submit" class="btn btn-success">Submit</button>

								<?php } ?>



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


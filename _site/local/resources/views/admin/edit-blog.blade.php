<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.title')
    @include('admin.style')
  </head>
  <!-- Marcello :: Tiny textarea -->
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=cae5v8ohqq2y45yurx2yqba3ng4rqukel679jhibsfg3gk4r"></script>
<script> tinymce.init({ selector:'textarea' });</script>
  <body>
@include('admin.top')
<!--close-top-serch-->
<!--sidebar-menu-->
@include('admin.menu')

  <div id="content">
  <div id="content-header">
    <div id="breadcrumb">  </div>
    <h1>Editar Blog</h1>
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
            <h5>Editar Blog</h5>
          </div>
          <div class="widget-content">
          <?php $url = URL::to("/"); ?>
            <form method="post" action="{{ route('admin.edit-blog') }}" enctype="multipart/form-data" accept-charset="utf-8"  name="basic_validate" id="formID" novalidate="novalidate">
              {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label">Titulo</label>
                <div class="controls">
                    <input id="name" class="validate[required] form-control"  name="post_title" value="<?php echo utf8_decode($blog[0]->post_title); ?>" type="text">
			@if ($errors->has('post_title'))
                                    <span class="help-block" style="color:red;">
                                        <strong>Este Blog jj&aacute; existe</strong>
                                    </span>
                        @endif
                </div>
              </div>

              <div class="form-group">
                <label class="control-label">Descri&ccedil;&atilde;o</label>
                <div class="controls">

                  <textarea id="post_desc" class="validate[required] form-control" name="post_desc"><?php echo $blog[0]->post_desc; ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label">Tipo de M&iacute;dia</label>
                <div class="controls">

                  <select class="validate[required] form-control"  name="media_type"  onChange="showDiv(this)">
                      <option value=""></option>
                      <?php
                      $mediatype=array("image","mp3","video");
                      foreach($mediatype as $media){?>
                      <option value="<?php echo $media;?>" <?php if($blog[0]->post_media_type==$media){?> selected <?php } ?>><?php echo $media;?></option>
                      <?php } ?>
                      </select>
                </div>
              </div>

               <input type="hidden" name="post_type" value="blog">
                  <input type="hidden" name="post_id" value="<?php echo $blog[0]->post_id; ?>">

              <div class="form-group" id="mediaurl" <?php if($blog[0]->post_media_type!="video"){?> style="display:none;" <?php } ?>>
                <label class="control-label">Youtube ou Vimeo Url</label>
                <div class="controls">

                  <input id="video_url" class="validate[required] form-control"  name="video_url" value="<?php echo $blog[0]->post_video;?>" type="text">
                                   <br/>( Example : https://www.youtube.com/watch?v=2MpUj-Aua48 OR https://vimeo.com/56282283 )
                </div>
              </div>

               <input type="hidden" name="save_video" value="<?php echo $blog[0]->post_video;?>">


                 <div class="form-group" id="mediamp3" <?php if($blog[0]->post_media_type!="mp3"){?> style="display:none;" <?php } ?>>
                <label class="control-label">Mp3 Upload</label>
                <div class="controls">

                  <input type="file" id="audio_file" name="audio_file" class="<?php if(empty($blog[0]->post_audio)){?>validate[required]<?php } ?> form-control">
                      <?php if($blog[0]->post_media_type=="mp3"){?>
                          <?php $url = URL::to("/");
                                $mp3path ='/local/images/media/'.$blog[0]->post_audio;

				?> <a href="<?php echo $url.$mp3path;?>" target="_blank" class="tagcolr"><?php echo $blog[0]->post_audio; ?></a>
                       <?php } ?>

			@if ($errors->has('audio_file'))
                                    <span class="help-block" style="color:red;">
                                        <strong><?php echo str_replace("mpga","mp3",$errors->first('audio_file'));?></strong>
                                    </span>
                        @endif
                </div>
              </div>
               <input type="hidden" name="save_audio" value="<?php echo $blog[0]->post_audio;?>">


               <div class="form-group" id="mediaimg" <?php if($blog[0]->post_media_type!="image"){?> style="display:none;" <?php } ?>>
                <label class="control-label">Imagem</label>
                <div class="controls">

                  <input type="file" id="photo" name="photo" class="<?php if(empty($blog[0]->post_image)){?>validate[required]<?php } ?> form-control">

				@if ($errors->has('photo'))
                                    <span class="help-block" style="color:red;">
                                        <strong>{{ $errors->first('photo') }}</strong>
                                    </span>
                                @endif
                </div>

                <?php $url = URL::to("/"); ?>
                      <?php
                      if($blog[0]->post_media_type=="image"){
                            $path ='/local/images/media/'.$blog[0]->post_image;
                            if($blog[0]->post_image!=""){
                            ?>
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
						<?php } } ?>
              </div>
		<div class="form-group">
                <label class="control-label">Tags</label>
                <div class="controls">

                    <input id="tags" class="form-control" value="<?php echo utf8_decode($blog[0]->post_tags);?>"  name="tags"  type="text" data-role="tagsinput" required="required">
                  <p>(Example : blog post,latest post,popular blog,trending,social media )</p>
                </div>
              </div>
              <input type="hidden" name="save_tags" value="<?php echo $blog[0]->post_tags;?>">

		<input type="hidden" name="currentphoto" value="<?php echo $blog[0]->post_image;?>">

              <div class="form-actions">
                        <div>
                               <a href="<?php echo $url;?>/admin/blog" class="btn btn-primary">Cancel</a>
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

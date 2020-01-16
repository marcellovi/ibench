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
      <div id="breadcrumb"> </div>
      <h1>Editar Categoria</h1>
    </div>
    <div class="container-fluid">
      <hr>
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
              <h5>Edit Category</h5>
            </div>
            <div class="widget-content">
              <?php $url = URL::to("/"); ?>
              <form role="form" method="POST"
                action="{{ route('admin.editcategory') }}" enctype="multipart/form-data" accept-charset="utf-8"
                id="formID">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="control-label">Nome</label>
                  <div class="controls">
                    <input id="name" class="validate[required] form-control" name="name"
                      value="<?php echo utf8_decode($category[0]->cat_name); ?>" type="text">
                    @if ($errors->has('name'))
                    <span class="help-block" style="color:red;">
                      <strong>That category is already exists</strong>
                    </span>
                    @endif

                  </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $category[0]->id; ?>">

                <div class="form-group custom-file col-md-4">
                  <label class="custom-file-label">Imagem</label>
                  <div class="controls">

                    <input type="file" id="photo" name="photo" class="custom-file-input"><br /><br /><span> (Tamanho : 400px X
                      290px)</span>
                    @if ($errors->has('photo'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                    @endif

                  </div>

                  <?php $url = URL::to("/"); ?>
                  <?php
                      $servicephoto="/media/";
                      $path ='/local/images'.$servicephoto.$category[0]->image;
                      if($category[0]->image!=""){
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
                  <?php } ?>

                  <input type="hidden" name="currentphoto" value="<?php echo $category[0]->image;?>">
                  <div class="form-group">
                    <label class="control-label">Exibir no Menu Principal?</label> <!-- Marcello Display Main Menu -->
                    <div class="controls">
                      <input id="display_menu" name="display_menu" <?php if(!empty($category[0]->display_menu)){?>
                        checked <?php } ?> value="1" type="checkbox">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label">Exibir na Ordem</label> <!-- Marcello Display Menu Order -->
                    <div class="controls">
                      <input id="display_order" class="form-control" name="display_order"
                        value="<?php echo $category[0]->display_order;?>" type="text">
                    </div>
                  </div>
                  <div class="form-actions">
                    <div>
                      <a href="<?php echo $url;?>/admin/category" class="btn btn-primary">Cancelar</a>

                      <?php if(config('global.demosite')=="yes"){?><button type="button"
                        class="btn btn-success btndisable">Submit</button>
                      <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
                      <?php } else { ?>
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
  </div>
  @include('admin.footer')
</body>

</html>

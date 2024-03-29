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
      <h1>Adicionar Categoria</h1>
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
              <h5>Adicionar Categoria</h5>
            </div>
            <div class="widget-content">

              <?php $url = URL::to("/"); ?>
              <form role="form" method="POST"
                action="{{ route('admin.addcategory') }}" enctype="multipart/form-data" id="formID"
                accept-charset="utf-8">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="control-label">Nome</label>
                  <div class="controls">

                    <input id="name" class="validate[required] form-control" name="name" value="" type="text">
                    @if ($errors->has('name'))
                    <span class="help-block" style="color:red;">
                      <strong>That category is already exists</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Exibir no Menu Principal?</label>
                  <div class="controls">
                    <input id="display_menu" name="display_menu" value="1" type="checkbox">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Exibir na Ordem</label>
                  <div class="controls">

                    <input id="display_order" class="form-control" name="display_order" value="" type="text">

                  </div>
                </div>

                <div class="custom-file col-md-4">
                  <label class="custom-file-label">Imagem</label>
                  <div class="controls">
                    <input type="file" id="photo" name="photo" class="custom-file-input"><br /><br /><span> (Size is : 400px X
                      290px)</span>

                    @if ($errors->has('photo'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <?php $url = URL::to("/"); ?>
                <div class="form-actions mt-3">
                  <div>

                    <a href="<?php echo $url;?>/admin/category" class="btn btn-primary">Cancelar</a>

                    <?php if(config('global.demosite')=="yes"){?><button type="button"
                      class="btn btn-success btndisable">Submit</button>
                    <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
                    <?php } else { ?>
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

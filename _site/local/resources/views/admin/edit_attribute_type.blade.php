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
    <h1>Editar Tipo</h1>
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
            <h5>Editar Tipo</h5>
          </div>
          <div class="widget-content">

              <?php $url = URL::to("/"); ?>
                   <form role="form" method="POST" action="{{ route('admin.edit_attribute_type') }}"  enctype="multipart/form-data" accept-charset="utf-8" id="formID">
                     {{ csrf_field() }}

              <div class="form-group">
                <label class="control-label">Nome</label>
                <div class="controls">

                    <input id="name" class="validate[required] form-control"  name="name" value="<?php echo utf8_decode($attribute[0]->attr_name);?>" type="text">
				@if ($errors->has('name'))
                                    <span class="help-block" style="color:red;">
                                        <strong>Este Tipo Ja Existe</strong>
                                    </span>
                                @endif

                </div>
              </div>

              <div class="form-group">
                <label class="control-label">Incluir na Pesquisa?</label> <!-- Marcello Search for category and product -->
                <div class="controls">
                   <input id="enable_search" class=""  name="enable_search" value="1" type="checkbox" <?php if($attribute[0]->search==1){?> checked <?php } ?>>

                </div>
              </div>
              <input type="hidden" name="attr_id" value="<?php echo $attribute[0]->attr_id; ?>">

              <div class="form-group">

              <?php $url = URL::to("/"); ?>
              <div class="form-actions">
                        <div>
                            <a href="<?php echo $url;?>/admin/attribute_type" class="btn btn-primary">Cancelar</a>
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
</div>
 @include('admin.footer')
  </body>
</html>

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
    <h1>Editar SubCategoria</h1>
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
            <h5>Editar SubCategoria</h5>
          </div>
          <div class="widget-content nopadding">
         <?php $url = URL::to("/"); ?>   
                   <form class="form-horizontal form-label-left" role="form" method="POST" action="{{ route('admin.editsubcategory') }}" enctype="multipart/form-data" accept-charset="utf-8" id="formID">
                     {{ csrf_field() }}  
                     
              <div class="control-group">
                <label class="control-label">Selecionar Categoria</label>
                <div class="controls">
                                
                <select class="validate[required] span8"  name="cat_id">
		  <option value=""></option>
		  <?php foreach($category as $service){?>
                  <option value="<?php echo $service->id;?>" <?php if($subcategory[0]->cat_id==$service->id){?> selected="selected" <?php } ?> ><?php echo utf8_decode($service->cat_name);?></option>
		  <?php } ?>
		</select>              
                  
                </div>
              </div> 
              <div class="control-group">
                <label class="control-label">Nome</label>
                <div class="controls">
                    <input id="name" class="validate[required] span8"  name="name" value="<?php echo utf8_decode($subcategory[0]->subcat_name); ?>" type="text">
                    @if ($errors->has('name'))
                                    <span class="help-block" style="color:red;">
                                        <strong>That sub service is already exists</strong>
                                    </span>
                    @endif                     
                </div>
              </div>              
              <input type="hidden" name="subid" value="<?php echo $subcategory[0]->subid; ?>">
              <div class="control-group">
                <label class="control-label">Imagem</label>
                <div class="controls">
                  <input type="file" id="photo" name="photo" class="span8"><br/><br/><span> (Size is : 400px X 290px)</span>
		@if ($errors->has('photo'))
                    <span class="help-block" style="color:red;">
                    <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                @endif             
                  
                </div                     
                <?php $url = URL::to("/"); ?>
		<?php 
		$subservicephoto="/media/";
		$path ='/local/images'.$subservicephoto.$subcategory[0]->subimage;
		if($subcategory[0]->subimage!=""){
		?>
		<div class="control-group">
                  <div class="controls">
		  <div class="span8">
		  <img src="<?php echo $url.$path;?>" class="thumb" width="100">
		  </div>
		  </div>
                </div>
		<?php } else { ?>
		   <div class="control-group">
                      <div class="controls">
			  <div class="span8">
			  <img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="100">
			  </div>
			  </div>
                      </div>
		<?php } ?>					  
		<input type="hidden" name="currentphoto" value="<?php echo $subcategory[0]->subimage;?>">
                                                          
              <div class="form-actions">
                  <div class="span8">
		  <a href="<?php echo $url;?>/admin/subcategory" class="btn btn-primary">Cancelar</a>
		  
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
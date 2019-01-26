<!DOCTYPE html>
<html lang="en">
  <head>
   
   @include('admin.title')
    
    @include('admin.style')
	
    
  </head>

  
  
   <body>
  
  
  @include('admin.top')
<!--close-top-serch-->
<!--sidebar-menu-->
@include('admin.menu')
  
  
  <div id="content">
  <div id="content-header">
    <div id="breadcrumb">  </div>
    <h1>Permiss&otilde;es do Produto/Fornecedor</h1>
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
            <h5>Permiss&otilde;es</h5>
          </div>
          <div class="widget-content nopadding">
          <?php $url = URL::to("/"); ?> 
            <form class="form-horizontal" method="post" action="{{ route('admin.permissions') }}" enctype="multipart/form-data" name="basic_validate" id="formID" novalidate="novalidate">
              {{ csrf_field() }}
              
              
              
              <div class="control-group">
                  <label class="control-label">Aprova&ccedil;&atilde;o Manual <br>(Pelo Administrador)</label>
                <div class="controls">
                 
                   <input id="with_submit_product" type="checkbox" name="with_submit_product" value="1" <?php if($msettings[0]->with_submit_product==1){?> checked <?php } ?> class="" >
                </div>
              </div>
              
              
              
						
					
              
              <div class="form-actions">
                        <div class="span8">
                         <a href="<?php echo $url;?>/admin/permissions" class="btn btn-primary">Cancelar</a>
						  <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button> 
								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
						  
                          <button id="send" type="submit" class="btn btn-success">Confirmar</button>
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

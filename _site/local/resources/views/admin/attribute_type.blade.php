<?php
use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$log_id = Auth::user()->id;
				 ?>
<!DOCTYPE html>
<html lang="en">
  <head>   
   @include('admin.title')    
   @include('admin.style')
   @include('admin.table-style')    
  </head>
  <body> 
  @include('admin.top')
  @include('admin.menu')
<?php $url = URL::to("/"); ?>  
  
  <div id="content">
  <div id="content-header">
    <div id="breadcrumb">  </div>
    <h1>Tipo Atributo</h1>
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

                 <div align="right">                    
                   <?php if(config('global.demosite')=="yes"){?>
                  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-primary btndisable">Adicionar Tipo</a> 
                  <?php } else { ?>
                  <a href="<?php echo $url;?>/admin/add_attribute_type" class="btn btn-primary">Adicionar Tipo</a>
                  <?php } ?>
             </div>
        <div class="widget-box">          

          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Tipo Atributo</h5>
          </div>          
          
          <div class="widget-content nopadding">         
            <table class="table table-bordered data-table" id="datatable-responsive">
              <thead>
                <tr>                          
                  <th>Sno</th>						  
                  <th>Nome</th>                           
                  <th>Search?</th>                          
                  <th>Status</th>                         
                  <th>A&ccedil;&atilde;o</th>                         
                 </tr>                          
              </thead>
              <tbody>
             <?php 
                  $i=1;
                  foreach ($attribute_type as $type) { ?>                  
                      
                    <tr class="gradeX">                        
                     <td><?php echo $i;?></td>						 
                           <td><?php echo utf8_decode($type->attr_name);?></td>                          
                          
                          <td><?php if($type->search==1){ echo 'yes'; } else { echo 'no'; } ?></td>                          
                          <?php if($type->status==1){ $status_txt = "Active"; $clrs = "green"; } else { $status_txt = "Inactive"; $clrs = "red"; }?>
                          
                              <td style="color:<?php echo $clrs;?>; font-weight:bold;"><?php echo $status_txt;?></td>
                              <td>
                          <?php if($log_id==$type->user_id){?>
                          <?php if(config('global.demosite')=="yes"){?>
				    <a href="#" class="btn btn-success btndisable">Ativo</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
				  <?php } else { ?>						  
                                      <a href="<?php echo $url;?>/admin/attribute_type/action/{{ $type->attr_id }}/1" class="btn btn-success">Ativo</a>
                              <?php } ?>                  
                  
                  <?php if(config('global.demosite')=="yes"){?>
				    <a href="#" class="btn btn-danger btndisable">Inativo</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
				  <?php } else { ?>						  
					  <a href="<?php echo $url;?>/admin/attribute_type/action/{{ $type->attr_id }}/0" class="btn btn-danger">Inativo</a>
				  <?php } ?>                  
						   
                          <?php } ?><?php if(config('global.demosite')=="yes"){?>
						  <a href="#" class="btn btn-success btndisable">Editar</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
				  <?php } else { ?>
						  <a href="<?php echo $url;?>/admin/edit_attribute_type/{{ $type->attr_id }}" class="btn btn-success">Editar</a>
						  <?php } ?>                             
                          
				   <?php if(config('global.demosite')=="yes"){?>
				   <a href="#" class="btn btn-danger btndisable">Deletar</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
				  <?php } else { ?>
						 <a href="<?php echo $url;?>/admin/attribute_type/{{ $type->attr_id }}" class="btn btn-danger" onClick="return confirm('Tem certez que deseja deletar?')">Deletar</a>
                                     <?php } ?>
                                             </td>                   
                          </tr>                    
                <?php $i++;} ?>                                
              </tbody>
            </table>           
          </div>          
        </div>  
     </div>
         </div>
         </div>         
         </div>  
  @include('admin.footer') 	
  </body>
</html>
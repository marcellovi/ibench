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
    <h1>Categorias</h1>
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
<form action="{{ route('admin.category') }}" method="post">
             
        {{ csrf_field() }}
        <div align="right">
        <?php if(config('global.demosite')=="yes"){?>
 	    <a href="#" class="btn btn-danger btndisable">Deletar Todos</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
	<?php } else { ?>
	    <input type="submit" value="Delete All" class="btn btn-danger" id="checkBtn" onClick="return confirm('Tem certeza que deseja deletar?');">
	<?php } ?>
        <?php if(config('global.demosite')=="yes"){?>
	    <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-primary btndisable">Adcionar Categoria</a> 
	<?php } else { ?>
            <a href="<?php echo $url;?>/admin/addcategory" class="btn btn-primary">Adicionar Categoria</a>
	<?php } ?>
        </div>
    <div class="widget-box">
        <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Categorias</h5>
        </div>
          
    <div class="widget-content nopadding">
        
        <table class="table table-bordered data-table" id="datatable-responsive">
        <thead>
            <tr>
            <th><input type="checkbox" id="selectAll" class="main"></th>
            <th>Sno</th>
            <th>Image</th>
            <th>Nome</th>
            <th>A&ccedil;&atilde;o</th>
            </tr>
        </thead>
        <tbody>
        <?php 
	$i=1;
            foreach ($category as $service) { ?>
                              
                <tr class="gradeX">
                <td align="center"><input type="checkbox" name="cat_id[]" value="<?php echo $service->id;?>"/></td>
                <td><?php echo $i;?></td>
	<?php 
	    $servicephoto="/media/";
            $path ='/local/images'.$servicephoto.$service->image;
            if($service->image!=""){
	?>
		<td><img src="<?php echo $url.$path;?>" class="thumb" width="70"></td>
	<?php } else { ?>
		<td><img src="<?php echo $url.'/local/images/noimage.jpg';?>" class="thumb" width="70"></td>
	<?php } ?>
                <td><?php echo utf8_decode($service->cat_name);?></td>
                <td>
                    <?php if(config('global.demosite')=="yes"){?>
			<a href="#" class="btn btn-success btndisable">Editar</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
		    <?php } else { ?>
			<a href="<?php echo $url;?>/admin/editcategory/{{ $service->id }}" class="btn btn-success">Editar</a>
		    <?php } ?>
		    <?php if(config('global.demosite')=="yes"){?>
			<a href="#" class="btn btn-danger btndisable">Deletar</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
		    <?php } else { ?>
			<a href="<?php echo $url;?>/admin/category/{{ $service->id }}" class="btn btn-danger" onClick="return confirm('Tem certeza que deseja deletar?')">Deletar</a>
		<?php } ?>
		</td>
            </tr>
                        
            <?php $i++;} ?>
                               
            </tbody>
            </table>
           </div>
          </div>
   </form>
   </div>
   </div>
   </div>
   </div>
    @include('admin.footer') 
</body>
</html>
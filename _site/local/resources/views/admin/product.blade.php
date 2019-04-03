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
    <h1>Produtos</h1>
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
     <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Lista de Produtos</h5>
     </div>
          
     <div class="widget-content nopadding">
         
      <table class="table table-bordered data-table" id="datatable-responsive">
      <thead>
      <tr>
      <th>Sno</th>
      <th>Usuario</th>
      <th>Categoria</th>
      <th>Nome Produto</th>
      <th>Pre&ccedil;o</th>
      <th>Situa&ccedil;&atilde;o</th>
      <th>Mais Detalhes</th>
      <th>Status</th>
      <th>A&ccedil;&atilde;o</th>
      </thead>
      <tbody>
 <?php 
	$i=1;
	foreach ($productt as $product) {  
            if($product->prod_status==0){ $btn = "btn btn-danger"; $text = "Aguardando Aprova&ccedil;&atilde;o"; $sid = 1; } 
            else { $btn = "btn btn-success"; $text = "Aprovado"; $sid=0; } 
?>
    
	<tr>
        <td><?php echo $i;?></td>
                         
<?php
	$user_count = DB::table('users')
		    ->where('id', '=' , $product->user_id)
                    ->count();
	if(!empty($user_count)){
		$user = DB::table('users')
		        ->where('id', '=' , $product->user_id)
			->get();
		$username = $user[0]->name;
	}
	else{$username = "";}			  
?>
        <td><?php echo $username;?></td>
                         
 <?php
    if($product->prod_cat_type=="cat"){
        $product_catcount = DB::table('category')
                        ->where('id', '=', $product->prod_category)
                        ->count();
        if (!empty($product_catcount)) {
                    $product_cat = DB::table('category')
                            ->where('id', '=', $product->prod_category)
                            ->get();

                    $category_name = $product_cat[0]->cat_name;
        } else {
                    $category_name = "";
        }
    } else if ($product->prod_cat_type == "subcat") {
                $subcount = DB::table('subcategory')
                        ->where('subid', '=', $product->prod_category)
                        ->count();
            if (!empty($subcount)) {
                    $subcategory = DB::table('subcategory')
                            ->where('subid', '=', $product->prod_category)
                            ->get();
                    $category_name = $subcategory[0]->subcat_name;
            } else {
                    $category_name = "";
            }
    }
?>
            <td><?php echo utf8_decode($category_name); ?></td>
            <td><?php echo utf8_decode($product->prod_name); ?></td>

            <td><?php if (!empty($product->prod_offer_price)) { ?><span style="text-decoration:line-through; color:#FF0000;"><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", ".") . ' '; ?></span> <span> <?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_offer_price, 2, ",", "."); ?></span> <?php } else { ?> <span><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", "."); ?></span> <?php } ?>	</td>

            <td><?php
                if ($product->delete_status == "") {
                    echo "ativo";
                }
                if ($product->delete_status == "inactive") {
                    echo '<span style="color:red;">inativo</span>';
                }
                if ($product->delete_status == "active") {
                    echo '<span style="color:#04c;">bloqueado</span>';
                }
                ?></td>

                <td>
                    <?php if (config('global.demosite') == "yes") { ?>
                        <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span> <a href="#" class="btn btn-primary btndisable">+Detalhes</a> 
                    <?php } else { ?>
                        <a href="<?php echo $url; ?>/admin/view_product/<?php echo $product->prod_token; ?>" class="btn btn-primary">+Detalhes</a>
                    <?php } ?>
                </td>

                <td>
                        <?php if (config('global.demosite') == "yes") { ?>
                            <a href="#" class="<?php echo $btn; ?> btndisable"><?php echo $text; ?></a>  <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
                        <?php } else { ?>
                            <a href="<?php echo $url; ?>/admin/product/action/{{ $product->prod_id }}/{{ $sid }}/{{ $product->user_id }}" class="<?php echo $btn; ?>"><?php echo $text; ?></a>
                        <?php } ?>

                </td>
                          
                <td>

                        <?php if (config('global.demosite') == "yes") { ?>
                            <a href="#" class="btn btn-danger btndisable">Deletar</a>  <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
                        <?php } else { ?>
                            <a href="<?php echo $url; ?>/admin/product/{{ $product->prod_token }}" class="btn btn-danger" onClick="return confirm('Tem certeza que deseja deletar?')">Deletar</a>

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

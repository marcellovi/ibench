<?php
	use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		$headertype = $setts[0]->header_type;  
                
                /* Marcello :: Pega as informacoes do Usuario */
                $userid = Auth::user()->id;
                $editprofile = DB::select('select * from users where id = ?',[$userid]);
	?>
<!DOCTYPE html>

<html class="no-js"  lang="en">
<head>

		

   @include('style')
   




</head>
<body class="cnt-home">

  

   
    @include('header')

<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.my_products')</li>
			</ul>
		</div>
	</div>
</div>

<div class="body-content outer-top-xs">
	<div class="container-fluid">
    <div class="row">
                     <div class="col-md-12 col-sm-12">
                    @if(Session::has('success'))

	    <p class="alert alert-success">

	      {{ Session::get('success') }}

	    </p>

	@endif


	
	
 	@if(Session::has('error'))

	    <p class="alert alert-danger">

	      {{ Session::get('error') }}

	    </p>

	@endif
    </div>
    </div>
		<div class="row ">
			<div class="shopping-cart">
				<div class="shopping-cart-table">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_products') (<?php echo $viewcount;?>)</div></div>
                <div class="col-md-6 text-right">
                    
                    <?php 
                        /* Hide When Store is Close / Hidden */
                       // if($editprofile[0]->delete_status == ''){                          
                    ?>
                    <a href="<?php echo $url;?>/add-product" class="btn-upper btn btn-primary">@lang('languages.add_product')</a> 
                        <?php // } ?>
                   <!-- Marcello Add Product
                   <a href="#" class="btn-upper btn btn-primary">@lang('languages.add_product')</a> 
                 -->
                    <!-- Marcello Hide Import  
                    <a href="<?php echo $url;?>/importExport" class="btn-upper btn btn-primary">@lang('languages.goto_import_export')</a>
                  --> 
                </div>
                
                <div class="height20 clearfix"></div>
<form action="#" method="get">
                <div class="row">
                <div class="col-md-6 ">
		

        
        <div class="form-group">
            <label class="info-title" for="exampleInputName">@lang('languages.product_name') </label>
        
            <input type="text" value="<?= array_key_exists('name', $data) ? $data['name'] : ' ' ?>" name="name" id="name" class="form-control unicase-form-control validate[required]">
         </div>
    
        </div>
 
<div class="col-md-6">
    
        <div class="form-group">
        <label class="info-title" for="exampleInputTitle">@lang('languages.category') </label>
        <select  class="form-control unicase-form-control validate[required]" id="category" name="category">
                      <option value=""></option>
                      <?php foreach($category as $service){?>
                      <option value="<?php echo $service->id;?>" disabled="true"><?php echo $service->cat_name;?></option>
                      <?php 
                      $subcount = DB::table('subcategory')
                        ->where('delete_status','=','')
                        ->where('status','=',1)
                        ->where('cat_id','=',$service->id)
                        ->orderBy('subcat_name', 'asc')->count();
                        if(!empty($subcount)){
                        $subcategory = DB::table('subcategory')
                        ->where('delete_status','=','')
                        ->where('status','=',1)
                        ->where('cat_id','=',$service->id)
                        ->orderBy('subcat_name', 'asc')->get();
                        foreach($subcategory as $subview){
                      ?>
                      
                      <option value="<?php echo $subview->subid;?>"> - <?php echo $subview->subcat_name;?></option>
                      <?php } } ?>
                      <?php } ?>
                      </select>
                      
                      <script>document.getElementById("category").value = <?= array_key_exists('category', $data) ? $data['category'] : ' ' ?>;</script>

      </div>

</div>
                </div>

          
    <div class="row">
<div class="col-md-6 ">
	
        
    <div class="form-group">
        <label class="info-title" for="exampleInputName"><?=utf8_decode('Preço Mínimo')?></label>
    
        <input value="<?= array_key_exists('minvalue', $data) ? $data['minvalue'] : ' ' ?>"  type="number" name="minvalue" id="minvalue" class="form-control unicase-form-control ">
     </div>

    </div>
    <div class="col-md-6 ">
	
        
    <div class="form-group">
        <label class="info-title" for="exampleInputName"><?=utf8_decode('Preço Máximo')?></label>
    
        <input value="<?= array_key_exists('maxvalue', $data) ? $data['maxvalue'] : ' ' ?>" type="number" name="maxvalue" id="maxvalue" class="form-control unicase-form-control">
   
    </div>
    <div class="form-group">
        <label for="exampleInputName"><?=utf8_decode('Produtos com desconto')?></label>
        <?php  if (array_key_exists('discount', $data)) { ?>
            <input checked type="checkbox" name="discount" id="discount" > 
            <?php } else { ?>
            <input  type="checkbox" name="discount" id="discount" > 
            
            <?php } ?>
                     
    </div>

    </div>
    </div>

    <div class="row ">
			<div class="shopping-cart">
				<div class="shopping-cart-table">
                <div class="col-md-6"></div>
                <div class="col-md-6 text-right">
                    
                    <?php 
                        /* Hide When Store is Close / Hidden */
                       // if($editprofile[0]->delete_status == ''){                          
                    ?>
                    <button type="submit" class="btn-upper btn btn-primary">Filtrar</button> 
                        <?php // } ?>
                   <!-- Marcello Add Product
                   <a href="#" class="btn-upper btn btn-primary">@lang('languages.add_product')</a> 
                 -->
                    <!-- Marcello Hide Import   
                    <a href="<?php echo $url;?>/importExport" class="btn-upper btn btn-primary">@lang('languages.goto_import_export')</a>
                  -->
                </div>
                </div>
            </div>
                       </form>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					
					<th class="item">@lang('languages.image')</th>
					<th class="item">@lang('languages.product_name')</th>
					<th class="item">@lang('languages.category')</th>
					<th class="item">@lang('languages.price')</th>
					<th class="item">@lang('languages.featured')</th>
					<th class="item">Situa&ccedil;&atilde;o</th>
                    <th class="item">@lang('languages.status')</th>
                    
                    <th class="item">@lang('languages.action')</th>
				</tr>
			</thead><!-- /thead -->
			
			<tbody>
            
            <?php if(!empty($viewcount)){
                  foreach($viewproduct as $product){
						
			$prod_id = $product->prod_token; 
			$product_img_count = DB::table('product_images')
				->where('prod_token','=',$prod_id)
				->count();					
	    ?>
            <tr>
                <td class="cart-image">
                <?php
                if(!empty($product_img_count)){					
			$product_img = DB::table('product_images')
				->where('prod_token','=',$prod_id)
				->orderBy('prod_img_id','asc')
				->get();
		?>                       
            <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product->prod_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>" alt="" class="img_responsive"></a>
            <?php } else { ?>
            <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product->prod_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt="" class="img_responsive"></a>
            <?php } ?>
                        
		</td>
            <?php
                if($product->prod_cat_type=="cat"){
		    $product_catcount = DB::table('category')
                    	->where('id','=',$product->prod_category)
			->count();
                    if(!empty($product_catcount)){				
                        $product_cat = DB::table('category')
                            ->where('id','=',$product->prod_category)
                            ->get();				

                        $category_name = $product_cat[0]->cat_name;
                    }
                    else
                    {    $category_name = "";  }	
                }
		else if($product->prod_cat_type=="subcat"){
                    $subcount = DB::table('subcategory')
			->where('subid','=',$product->prod_category)
			->count();
			if(!empty($subcount)){
				$subcategory = DB::table('subcategory')
			               ->where('subid','=',$product->prod_category)
			               ->get();
					$category_name = $subcategory[0]->subcat_name;
			}
			else{  $category_name = "";  }		   
		}			
            ?>
                                                    
		<td class="cart-product-name-info">
		<h4 class='cart-product-description'><a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product->prod_slug);?>"><?php echo utf8_decode(substr($product->prod_name,0,30));?></a></h4>
					
		</td>
		<td class="cart-product-edit"><?php echo $category_name;?></td>
		<td class="cart-product-quantity">
                    <div class="price">							
                    <?php if(!empty($product->prod_offer_price) && $product->prod_offer_price > 0 ){?><span><?php echo $setts[0]->site_currency.' '.number_format($product->prod_price,2,",",".").' ';?></span><?php echo $setts[0]->site_currency.' '.number_format($product->prod_offer_price,2,",",".");?><?php } else { ?><?php echo $setts[0]->site_currency.' '.number_format($product->prod_price,2,",",".");?><?php } ?>
                    </div>
		 </td>
                <?php 
                    if($product->prod_status==1){ 
                            $status_txt = __('languages.approved'); 
                            $status_clr = "#0B9752";                             
                    } 
                    else { 
                            $status_txt = __('languages.waiting_for_approval'); 
                            $status_clr = "#f31414";                            
                    } 
                  
                        /** Marcello :: Checar Visibilidade do Produto **/
                    if($product->delete_status==""){ 
                            $status_visibility = "Vis&iacute;vel"; 
                            $visible_clr = "#0B9752";
                            $visibility_oposte = "Invis&iacute;vel";
                    } else if($product->delete_status=="inactive") { 
                            $status_visibility = "Invis&iacute;vel"; 
                            $visible_clr = "#f31414";
                            $visibility_oposte = "Vis&iacute;vel";
                    }else{
                            $status_visibility = "N/A"; 
                            $visible_clr = "#0B9752";
                            $visibility_oposte = "N/A";
                    } 
                    ?>                    
            
                    <td class="cart-product-sub-total"><?php if(empty($product->prod_featured)){ echo 'N&atilde;o'; } else { echo $product->prod_featured; }?></td>
                    
                    <td class="cart-product-grand-total" style="color:<?php echo $status_clr;?>"><?php echo $status_txt;?></td>                    
                
                    <td class="cart-product-sub-total" style="color:<?php echo $visible_clr;?>"><?php echo $status_visibility;?></td>
                    
                    <td class="romove-item">
                 
                    <?php if($product->prod_status==1){ ?>
                    <a href="<?php echo $url;?>/edit-product/<?php echo $product->prod_token;?>" title="@lang('languages.tooledit')" class="icon"><i class="fa fa-edit"></i></a>
                    <?php }                     
                    if($product->delete_status==""){ // if visible change to delete_status inactive ?>
                    <a href="<?php echo $url;?>/status-product/<?php echo $product->prod_token;?>/0" title="Tornar <?php echo $visibility_oposte; ?>" class="icon" onClick="return confirm('Confirma Altera&ccedil;&atilde;o');"><i class="fa fa-eye-slash"></i></a>
                    <?php }else if($product->delete_status=="inactive"){ ?>
                    <a href="<?php echo $url;?>/status-product/<?php echo $product->prod_token;?>/1" title="Tornar <?php echo $visibility_oposte; ?>" class="icon" onClick="return confirm('Confirma Altera&ccedil;&atilde;o');"><i class="fa fa-eye-slash"></i></a>
                    <?php } ?>                    
                    <a href="<?php echo $url;?>/my-product/<?php echo $product->prod_token;?>" title="@lang('languages.tooldelete')" class="icon" onClick="return confirm('@lang('languages.are_you_sure')');"><i class="fa fa-trash-o"></i></a>
                  
                    </td>
                    </tr>
                     <?php }  } ?>
			</tbody><!-- /tbody -->
		</table><!-- /table -->
	</div>
</div>

</div>
		</div> 
        
        </div>
</div>


<div class="height30"></div>

@include('footer')

</body>
</html>

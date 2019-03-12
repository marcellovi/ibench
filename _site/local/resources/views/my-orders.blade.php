<?php
	use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		$headertype = $setts[0]->header_type;
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
				<li class='active'>@lang('languages.my_orders')</li>
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
				<div class="shopping-cart-table ">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_orders') (<?php echo $viewcount;?>)</div></div>
                <div class="col-md-6 text-right"></div>
                
                <div class="height20 clearfix"></div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th class="item">@lang('languages.image')</th>
					<th class="item">@lang('languages.product_name')</th>
					<th class="item">@lang('languages.purchase_id')</th>
					<th class="item">@lang('languages.attributes')</th>
					<th class="item">@lang('languages.price_qty')</th>
					<th class="item">@lang('languages.buyer_details')</th>
					<th class="item">@lang('languages.payment_status')</th>
                    
				</tr>
			</thead><!-- /thead -->
			
			<tbody>
            
            <?php if(!empty($viewcount)){?>
                                <?php foreach($viewproduct as $product){
								
								 $prod_id = $product->prod_token; 
								 $product_img_count = DB::table('product_images')
													->where('prod_token','=',$prod_id)
													->count();
													
								$view_count = 	DB::table('product')
													->where('prod_id','=',$product->prod_id)
													->count();	
								if(!empty($view_count))
								{								
								$view_product = 	DB::table('product')
													->where('prod_id','=',$product->prod_id)
													->get();
								$product_names = $view_product[0]->prod_name;
								$product_slug = $view_product[0]->prod_slug;					
								
								}
								else
								{
								   $product_names = "";
								   $product_slug = "";
								}
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
                                                        <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>" alt="" class="img_responsive"></a>
                                                        <?php } else { ?>
                                                        <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt="" class="img_responsive"></a>
                                                        <?php } ?>
                                                        
                                                        
                       
                                                                                
					</td>
                    
                                                                        
					<td class="cart-product-name-info">
						<h4 class="cart-product-description"><a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($product_slug);?>"><?php echo utf8_decode($product_names);?></a></h4>
						
						
					</td>
					<td class="cart-product-edit"><?php echo $product->purchase_token;?></td>
                    
                    <?php
											$cats = explode(",", $product->prod_attribute);
											$value_namer = "";
											
                                            foreach($cats as $cat) 
											{
											
											    $prod_value_count = DB::table('product_attribute_value')
														->where('delete_status','=','')
														->where('status','=',1)
														->where('value_id','=', $cat)
														->count();
												$prod_value = DB::table('product_attribute_value')
														->where('delete_status','=','')
														->where('status','=',1)
														->where('value_id','=', $cat)
														->get();
														if(!empty($prod_value_count))
														{
														?>
                                                         
                                                         <?php
														
												$prod_type = DB::table('product_attribute_type')
														->where('delete_status','=','')
														->where('status','=',1)
														->where('attr_id','=', $prod_value[0]->attr_id)
														->get();	
														
														
												$value_namer .='<b class="black">'.$prod_type[0]->attr_name.'</b> - '.$prod_value[0]->attr_value.', ';	
												}
												
												?>
                                               
                                                
                                                <?php
														
											}	
											
											$attri_name = rtrim($value_namer,', ');
											
											
											
											
											
											
											?>
                                            
                                            
                                            
					<td class="cart-product-quantity">
						<div class="price">
						<?php if(!empty($product->prod_attribute)){ ?>
                                                       (<?php echo $attri_name;?>)
                                                        <?php } ?>	
						</div>
		            </td>
                    
					<td class="cart-product-sub-total"><?php echo $setts[0]->site_currency.' '.number_format($product->price,2,",",".");?>	<br/> <?php echo $product->quantity;?> @lang('languages.qty')</td>
					<td class="cart-product-grand-total"><a href="<?php echo $url;?>/view-orders/<?php echo $product->ord_id;?>/<?php echo $product->user_id;?>" style="color:#0033CC;">@lang('languages.view_more')</a></td>
                    
                    <td class="cart-product-sub-total">
					<?php if($product->order_status=="completed"){ echo "waiting for admin approval"; } else {
					 echo $product->order_status; } ?></td>
                    
                    
				</tr>
                <?php } } ?>
								
								
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

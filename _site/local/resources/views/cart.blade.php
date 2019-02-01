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
				<li class='active'>@lang('languages.cart')</li>
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
        <?php if(!empty($cart_views_count)){?>
        
        
        
        <div class="row ">
        
        
        <form class="form-horizontal" role="form" method="POST" action="{{ route('checkout') }}" id="formID" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
			<div class="shopping-cart">
				<div class="shopping-cart-table ">
                 
        <div class="row col-md-12"><div class="heading-title">@lang('languages.my_cart')</div></div>
               
       
                
              <div class="height50 clearfix"></div>  
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th class="cart-romove item">@lang('languages.remove')</th>
          <th class="cart-edit item">@lang('languages.edit')</th>
					<th class="cart-description item">@lang('languages.image')</th>
					<th class="cart-product-name item">@lang('languages.product_name')</th>
					<th class="cart-qty item">@lang('languages.quantity')</th>
					<th class="cart-sub-total item">@lang('languages.unitary_value')</th>
					<th class="cart-sub-total item">@lang('languages.subtotal')</th>
				</tr>
			</thead><!-- /thead -->
			<tfoot>
				<tr>
					<td colspan="6">
						<div class="shopping-cart-btn">
							<span class="">
								<a href="<?php echo $url;?>/shop" class="btn btn-upper btn-primary outer-left-xs">@lang('languages.continue_shopping')</a>
								<?php /*?><a href="#" class="btn btn-upper btn-primary pull-right outer-right-xs">Update shopping cart</a><?php */?>
							</span>
						</div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<!-- Adicionar em um Array ID dos produtos que estão fora do Estoque para verificação  -->
				<?php $check_prod_available_qty = array(); ?>

            <?php if(!empty($cart_views_count)){?>
                                <?php 
								
								$price_val=0;
								$ord_id = ""; 
								$prod_name = "";
                                                                
                                                                /* Marcello - Verifica se o produto e' da QuartoG */
                                                                $quatroG = false;                                                                
                                                              
								foreach($cart_views as $product){
								
                                                                  
                                                                 // Marcello - Variavel que recebe o prod_user_id do produto
                                                                 $my_user_id = $product->prod_user_id;
                                                                 
                                                                 
                                                                 // Marcello - Verifica se e' a QuartoG ( id : 113 )
                                                                 if($my_user_id == 113){ $quatroG = true; }
                                                                     
                                                                         
								 $prod_id = $product->prod_token; 
								 $product_img_count = DB::table('product_images')
													->where('prod_token','=',$prod_id)
													->count();
													
								$view_product = DB::table('product')
													->where('prod_token','=',$prod_id)
													->get();
													
										$ord_id .=	$product->ord_id.',';		
																		
													?>
				<tr>
					<td class="romove-item">
                    
                    <a href="<?php echo $url;?>/cart/<?php echo $product->ord_id;?>" onClick="return confirm('@lang('languages.are_you_sure')');" title="Remover" class="icon"><i class="fa fa-trash-o"></i></a>
                    
                    </td>
                    
                    <td class="romove-item">
                    <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>" title="Editar" class="icon"><i class="fa fa-edit"></i></a>
                    </td>
                    
                    
					<td class="cart-image">
						
                        
                        
                        <?php
														if(!empty($product_img_count)){					
														$product_img = DB::table('product_images')
																			->where('prod_token','=',$prod_id)
																			->orderBy('prod_img_id','asc')
																			->get();
																		
														
														?>
                                                        <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>" alt="" ></a>
                                                        <?php } else { ?>
                                                        <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>" class="entry-thumbnail"><img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt="" ></a>
                                                        <?php } ?>
                                                        
                         
					</td>
					<td class="cart-product-name-info">
						<?php $check_qty = DB::table('product')->where('prod_id', '=', $product->prod_id)->get(); ?>
						
						<?php if($check_qty[0]->prod_available_qty < $product->quantity) { ?>
							<?php $check_prod_available_qty = $product->prod_id; ?>
							<p style="color:red;">* Produto Sem Estoque suficiente</p>
						<?php }; ?>
					
						
						<h4 class='cart-product-description'>
                        
                        <a href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>"><?php echo utf8_decode($view_product[0]->prod_name);?></a>
                        </h4>
                        
                         <?php
											$view_user = DB::table('product')
		                                                 ->where('prod_id', '=', $product->prod_id)
														 ->count();
											if(!empty($view_user))
											{
											   $row_user = DB::table('product')
		                                                 ->where('prod_id', '=', $product->prod_id)
														 ->get();
														 
												$check_user = DB::table('users')
		                                                 		->where('id', '=', $row_user[0]->user_id)
														 		->get();
																
												if(!empty($check_user[0]->post_slug))
												{
												   $slug = $check_user[0]->post_slug;
												}
												else
												{
												  $slug = $check_user[0]->name;
												}	
												
												$prod_name .=$view_product[0]->prod_name.',';				 
											 ?>
                                             <input type="hidden" name="prod_user_id[]" value="<?php echo $row_user[0]->user_id;?>">  
                        
						<div class="row">
							<div class="col-sm-12">
								<p><b class="fontsize13">@lang('languages.sold_by'):</b> <a href="<?php echo $url;?>/profile/<?php echo $check_user[0]->id;?>/<?php echo $slug;?>" class="fontsize14 red"><?php echo $check_user[0]->name;?></a></p> 
							</div>
							
						</div><!-- /.row -->
                        <?php } ?>
                        
                        
                         <?php
											/*if(!empty($product->prod_attribute))
											{*/
											
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
                        
						<div class="cart-product-info">
											
                                            <?php if(!empty($product->prod_attribute)){ ?>
                                                        <span class="product-color">(<?php echo $attri_name;?>)</span>
                                                        <?php } ?>
						</div>
					</td>
					
					<td class="cart-product-quantity">
						<div class="quant-input">
				                 <?php /*?><input type="number" value="<?php echo $product->quantity;?>" min="1" max="<?php echo $row_user[0]->prod_available_qty;?>"><?php */?>
				                <?php echo $product->quantity;?>
			              
                          
                          </div>
		            </td>
                     <?php $price_total = $product->price * $product->quantity;
											
											$price_val += $product->price * $product->quantity;
											 ?>
                    
          <td class="cart-product-sub-total"><span class="cart-sub-total-price"><?php echo $setts[0]->site_currency.' '.number_format($product->price,2,",",".").' ';?></span></td>
          
					<td class="cart-product-sub-total"><span class="cart-sub-total-price"><?php echo $setts[0]->site_currency.' '.number_format($price_total,2,",",".").' ';?></span></td>
					
				</tr>
                <?php } } ?>
                <input type="hidden" name="order_ids" value="<?php echo rtrim($ord_id,',');?>">
                                <input type="hidden" name="product_names" value="<?php echo rtrim($prod_name,',');?>">
				
			</tbody><!-- /tbody -->
		</table><!-- /table -->
	</div>
</div>

<?php /*?><div class="col-md-8 col-sm-12 estimate-ship-tax">
	<table class="table">
		<thead>
			<tr>
				<th>
					<span class="estimate-title">Discount Code</span>
					<p>Enter your coupon code if you have one..</p>
				</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td>
						<div class="form-group">
							<input type="text" class="form-control unicase-form-control text-input" placeholder="You Coupon..">
						</div>
						<div class="clearfix pull-right">
							<button type="submit" class="btn-upper btn btn-primary">APPLY COUPON</button>
						</div>
					</td>
				</tr>
		</tbody><!-- /tbody -->
	</table><!-- /table -->
</div><?php */?>
<div class="col-md-6">
</div>

 
<div class="col-md-6 col-sm-12 cart-shopping-total">
	<table class="table">
		<thead>
			<tr>
				<th>
					<div class="cart-sub-total">
						<label>@lang('languages.cart_total')</label><span class="inner-left-md"><?php echo $setts[0]->site_currency.' '.number_format($price_val,2,",",".").' ';?></span>
					</div>
                                    <!-- Marcello : Checando se e' a QuartoG -->
                                    <?php 
                                        if($quatroG){
                                            
                                     ?>
                                        <div class="cart-sub-total">
						<label>Frete QuatroG</label><span class="inner-left-md"><?php echo $setts[0]->site_currency.' '.number_format(275,2,",",".").' ';?></span>
					</div>                 
                                    <?php  } ?>
                                    
                    <!-- Marcello Processing Fee 
                    <div class="height20 clearfix"></div>
                    
                    
					<div class="cart-grand-total">
						<label>@lang('languages.processing_fee')</label><span class="inner-left-md"><?php echo $setts[0]->site_currency.' '.number_format($setts[0]->processing_fee,2).' ';?></span>
					</div>
                    -->
                    <div class="height20 clearfix"></div>
                    <div class="cart-grand-total">
						<label>Frete outros Fornecedores<!-- @lang('languages.shipping_charge') --></label><span class="inner-left-md"> <select class="unicase-form-control validate[required]" id="billing_country" name="shipping_charge" style="width:150px;">
                                                <!-- <option value="">Formas de Envio</option> -->
                                                <option value="local_shipping">Envio Gratuito</option>
                                               <!-- <option value="world_shipping">Envio Personalizado</option> Marcello :: -->
                                                <!-- Marcello Envio Original
                                            	<option value="local_shipping">Local shipping (<?php //echo $admin_details[0]->country;?>)</option>
                                               
                                                
                                                <option value="world_shipping">Frete da Empresa</option>
                                            --> 
                                                </select></span>
					</div>
				</th>
			</tr>
            
             <input type="hidden" name="cart_total" value="<?php echo $price_val;?>">
                                        <input type="hidden" name="processing_fee" value="<?php echo $setts[0]->processing_fee;?>">
                                         <input type="hidden" name="quatroG" value="<?php echo $quatroG;?>">
		</thead><!-- /thead -->
		<tbody>
				<tr>
					<td>
						<div class="cart-checkout-btn pull-right">
							<?php if(count($check_prod_available_qty) > 0 ){ ?>
								<p style="color:red;">*Existe(m) produto(s) sem Estoque suficiente em seu Carrinho!</p>
								<input type="submit" class="btn btn-primary checkout-btn" name="checkout" disabled value="@lang('languages.proceed_to_checkout')" style="background: #fdd922;">
								
							<?php }else{ ?>
								<input type="submit" class="btn btn-primary checkout-btn" name="checkout" value="@lang('languages.proceed_to_checkout')">
							<?php }; ?>                           
							
						</div>
					</td>
				</tr>
		</tbody>
	</table>
</div>			</div>



 </form>






		</div> <!-- /.row -->
		<?php } else { ?>
        
        
        <div class="row">
        
        <div class="col-md-12 shopping-cart">
        
        <div class="nodata">@lang('languages.your_cart_is_empty')</div>
        
        </div>
        
        </div>
        
        <?php } ?>
        
        
        
</div>


<div class="height20"></div>

@include('footer')


</body>
</html>

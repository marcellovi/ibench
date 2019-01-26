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
<body  class="cnt-home">

  

   
    @include('header')
<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.my_wishlist')</li>
			</ul>
		</div>
	</div>
</div>



<div class="body-content">
	<div class="container-fluid">
		<div class="my-wishlist-page">
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
        
			<div class="row">
            
				<div class="col-md-12 my-wishlist">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th colspan="4" class="heading-title">@lang('languages.my_wishlist') (<?php echo $viewcount;?>)</th>
				</tr>
			</thead>
			<tbody>
            <?php if(!empty($viewcount)){?>
                                <?php foreach($viewproduct as $product){
								
								 $prod_id = $product->prod_token; 
								 $product_img_count = DB::table('product_images')
													->where('prod_token','=',$prod_id)
													->count();
								
								?>
                                <?php
											
											$view_product = DB::table('product')
																	  ->where('prod_token', '=' , $product->prod_token)
																	  ->get();	
																	  
																	  ?>

            
				
				<tr>
					<td class="col-md-2">
                    <?php
														
														
														
														
														if(!empty($product_img_count)){					
														$product_img = DB::table('product_images')
																			->where('prod_token','=',$prod_id)
																			->orderBy('prod_img_id','asc')
																			->get();
																		
														
														?>
                    
                    
                    <a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>"><img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>" alt="" class="img_responsive"></a>
                                                        <?php } else { ?>
                                                        <a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>"><img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt="" class="img_responsive"></a>
                                                        <?php } ?>
                    
                    </td>
					<td class="col-md-7">
						<div class="product-name">
                        
                        <a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>"><?php echo utf8_decode($view_product[0]->prod_name);?></a>
                        </div>
						
						<div class="price">
							
							<?php if(!empty($view_product[0]->prod_offer_price)){?><span><?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_price,2).' ';?></span><?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_offer_price,2);?><?php } else { ?><?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_price,2);?><?php } ?>
						</div>
                        
                        
                        
					</td>
					<td class="col-md-2">
						<a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>" class="btn-upper btn btn-primary">@lang('languages.add_to_cart')</a>
					</td>
					<td class="col-md-1 close-btn">
						
                         <a href="<?php echo $url;?>/my-wishlist/<?php echo $view_product[0]->prod_token;?>" onClick="return confirm('@lang('languages.are_you_sure')');"><i class="fa fa-times"></i></a>
					</td>
				</tr>
                
                
                <?php } } ?>
			</tbody>
		</table>
	</div>
</div>			</div>
		</div>
		</div>
</div>


<div class="height30"></div>

@include('footer')
</body>
</html>

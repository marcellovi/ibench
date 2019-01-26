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
				<li class='active'>@lang('languages.product_comparison')</li>
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
        
			<div class="body-content outer-top-xs">
	<div class="container-fluid">
    <div class="product-comparison" id="ptsBlock_535808">
		
       
        
        <div class="col-md-12 row"><div class="heading-title" style="border-bottom:0px !important;">@lang('languages.product_comparison')</div></div>
        
        <div class="height20 clearfix"></div>
        
        
        <?php if(!empty($viewcount)){?>
        
        
        <div class="ptsBlockContent"><div class="ptsContainer">
	<div class="ptsColsWrapper ui-sortable">
		<!-- Description Element -->
      		<div class="ptsEl ptsCol ptsCol-0 ptsTableDescCol ptsElWithArea" data-el="table_col" style="height: auto; width: 25%;">
			<div class="ptsTableElementContent ptsElArea">
			<div class="ptsColHeader ptsCell" style="height: 186px;"></div>
			<div class="ptsColDesc" style="height: 103px;">
				<div class="ptsEl" data-el="table_cell_txt" data-type="txt" style=""><p><span style="font-weight: bold;" data-mce-style="font-weight: bold;">@lang('languages.product')</span></p></div>
			</div>
			<div class="ptsRows ui-sortable" style="height: auto;">
				<div class="ptsCell" style="height: 76px;">
					<div class="ptsEl parahead" data-el="table_cell_txt" data-type="txt" style=""><p>@lang('languages.rating')</p></div>
				</div>
				<div class="ptsCell" style="height: 200px;">
					<div class="ptsEl parahead" data-el="table_cell_txt" data-type="txt" style=""><p>@lang('languages.description')</p></div>
				</div>
				<div class="ptsCell" style="height: 54px;">
					<div class="ptsEl parahead" data-el="table_cell_txt" data-type="txt" style=""><p>@lang('languages.availability')</p></div>
				</div>
				<div class="ptsCell" style="height: 60px;">
					<div class="ptsEl parahead" data-el="table_cell_txt" data-type="txt" style=""><p>@lang('languages.price')</p></div>
				</div>
			</div>
          	<div class="ptsColFooter" style="height: 120px;"></div>
          	</div>
		</div>
        
        
        
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
      		<div class="ptsEl ptsCol ptsElWithArea ptsCol-1" data-el="table_col" style="height: auto; width: 25%;">
			<div class="ptsTableElementContent ptsElArea">
				<div class="ptsColHeader ptsCell" style="height: 186px;">
					<div class="ptsEl ptsElImg ptsElWithArea" data-el="img" data-type="img">
						<div class="ptsElArea">
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
                        
                        
                        
							
						</div>
					</div>
				</div>
                
                
                <?php
							
							
					/************** STAR RATING *************/
		
							
								
          $review_count_02 = DB::table('product_rating')
				->where('prod_id', '=', $view_product[0]->prod_id)
				->count();
				
				if(!empty($review_count_02))
				{
				$review_value_02 = DB::table('product_rating')
				               ->where('prod_id', '=', $view_product[0]->prod_id)
				               ->get();
				
				
				$over_02 = 0;
		        $fine_value_02 = 0;
				foreach($review_value_02 as $review){
				if($review->rating==1){$value1 = $review->rating*1;} else { $value1 = 0; }
		if($review->rating==2){$value2 = $review->rating*2;} else { $value2 = 0; }
		if($review->rating==3){$value3 = $review->rating*3;} else { $value3 = 0; }
		if($review->rating==4){$value4 = $review->rating*4;} else { $value4 = 0; }
		if($review->rating==5){$value5 = $review->rating*5;} else { $value5 = 0; }
		
		$fine_value_02 += $value1 + $value2 + $value3 + $value4 + $value5;
		

      $over_02 +=$review->rating;
	  
	  
	  
				}
				if(!empty(round($fine_value_02/$over_02))){ $roundeding_02 = round($fine_value_02/$over_02); } else {
		  $roundeding_02 = 0; }	
				
				
				}
				
				
				
				
				
				if(!empty($review_count_02))
				                               {
	                                           if(!empty($roundeding_02)){
	
	                                           if($roundeding_02==1){ $rateus_new_02 ='
                                                <p class="review-icon">
                                                    <span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    
                                                    </span>
													<i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </p>';
												}
												if($roundeding_02==2){ $rateus_new_02 ='
                                                <p class="review-icon">
                                                    <span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
													
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </p>';
												}
												
												if($roundeding_02==3){ $rateus_new_02 ='
                                                <p class="review-icon">
                                                    <span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
													
                                                    
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </p>';
												}
												
												if($roundeding_02==4){ $rateus_new_02 ='
                                                <p class="review-icon">
                                                    <span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
											                                                
                                                    
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </p>';
												}
												
												if($roundeding_02==5){ $rateus_new_02 ='
                                                <p class="review-icon">
                                                    <span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													 <i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
											    </p>';
												}
												
												
												}
											    else if(empty($roundeding_02)){  $rateus_new_02 = '
												<p class="review-icon">
                                                    <span>
                                                    
                                                    </span>
													<i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													 <i class="fa fa-star" aria-hidden="true"></i>
											    </p>';
												}
												
												}
												
												
												
												$rateus_empty_02 = '
												<p class="review-icon">
                                                    <span>
                                                    
                                                    </span>
													<i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													<i class="fa fa-star" aria-hidden="true"></i>
													 <i class="fa fa-star" aria-hidden="true"></i>
											    </p>';
												
												
				
				/************** STAR RATING *************/
				
			
				
				?>                                       
                                
 
				<div class="ptsColDesc compare_heading" style="height: 103px;">
					<div class="ptsEl " data-el="table_cell_txt" data-type="txt" style="" ><p><span style="" data-mce-style="color: #9d9d9d;" > <a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>"><?php echo utf8_decode($view_product[0]->prod_name);?></a></span></p></div>
				</div>
				<div class="ptsRows ui-sortable" style="height: auto;">
					<div class="ptsCell" style="height: 76px;">
						
                      <div class="ptsEl ptsElImg ptsElWithArea" data-el="img" data-type="tabel_cell_img">
						<div class="ptsElArea">
							<?php if(!empty($review_count_02)){ echo $rateus_new_02; } else { echo $rateus_empty_02; }?>
						</div>
					</div>
					</div>
					<div class="ptsCell" style="height: 200px;">
						<div class="ptsEl" data-el="table_cell_txt" data-type="txt" style="text-align:left;"><p><?php echo utf8_decode(substr($view_product[0]->prod_desc,0,400));?></p></div>
					</div>
					<div class="ptsCell" style="height: 54px;">
						<div class="ptsActBtn ptsEl ptsElInput" data-el="btn" data-bgcolor="#e17478" data-bgcolor-to="bg" data-bgcolor-elements="a">
							
                            
                            
                            <?php if(!empty($view_product[0]->prod_available_qty)){?>
											<span class="value greenstock">@lang('languages.in_stock') (<?php echo $view_product[0]->prod_available_qty;?>)</span>
                                            <?php } else { ?>
                                     <span class="value redstock">@lang('languages.out_of_stock')</span>
                                      <?php } ?>
						</div>
					</div>
					<div class="ptsCell" style="height: 60px;">
						<div class="ptsEl" data-el="table_cell_txt" data-type="txt" style=""><p class="price-box">
                        
                        <?php if(!empty($view_product[0]->prod_offer_price)){?> <span class="price"> <?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_offer_price,2);?></span> <span class="price-strike"><?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_price,2).' ';?></span><?php } else { ?> <span class="price"><?php echo $setts[0]->site_currency.' '.number_format($view_product[0]->prod_price,2);?></span> <?php } ?>
                        
                        
                        
                        </p></div>
					</div>
				</div>
				<div class="ptsColFooter" style="height: 120px;">
					<div class="ptsEl" data-el="table_cell_txt" data-type="txt" style=""><p><br></p></div>
					<div class="ptsActBtn ptsEl ptsElInput" data-el="btn" data-bgcolor="#90c820" data-bgcolor-to="bg" data-bgcolor-elements="a">
						<a href="<?php echo $url;?>/product/<?php echo $view_product[0]->prod_id;?>/<?php echo utf8_decode($view_product[0]->prod_slug);?>" class="btn-upper btn btn-primary">@lang('languages.add_to_cart')</a>
					</div>
                    <?php if(Auth::user()->id!=$view_product[0]->user_id && Auth::user()->id!=1)
														{?>
                    <div class="ptsWpWhiteList">
                    
                      <div data-icon="fa-heart-o" data-color="rgb(255, 0, 0)" data-type="icon" data-el="table_cell_icon" class="ptsIcon ptsEl ptsElInput">
                      	<i class="fa fa-2x ptsInputShell fa-heart-o" style="color: rgb(255, 0, 0);"></i>
                      </div>
                      <div class="ptsEl" data-el="table_cell_txt" data-type="txt" style=""><p><span style="font-size: 10pt; color: #9d9d9d;" data-mce-style="font-size: 10pt; color: #9d9d9d;"> <a href="<?php echo $url;?>/wishlist/<?php echo Auth::user()->id;?>/<?php echo $view_product[0]->prod_token;?>">  @lang('languages.add_to_wishlist') </a></span></p></div>
                      
                      
                      </div>
                      
                      <?php } ?>
                      
				</div>
			</div>
		</div>
        
        <?php } ?>
        
        
		<!-- 2 -->
		
		<!-- 3 -->
		
		<!-- Other Elements -->
		</div>
		<div style="clear: both;"></div>
	</div></div>
        
        <?php } else { ?>
        
        <div class="row">
        
        <div class="col-md-12 shopping-cart">
        
        <div class="nodata">@lang('languages.no_data') </div>
        
        </div>
        
        </div>
        <?php } ?>
        
        
        
        
        
        
        
        
        
        
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

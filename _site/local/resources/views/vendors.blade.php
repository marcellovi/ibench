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
					<li class='active'>@lang('languages.all_vendors')</li>
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
					<!-- .col-md-12 col-sm-12 -->
				</div>
				<!-- .row -->

				<div class="row">
					<div class="col-md-12 my-wishlist">

						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<div class="heading-title" style="border-bottom:none !important;">
										@lang('languages.all_vendors')
									</div>
								</div>
                <div class="col-md-6 text-right"></div>
              </div>       
            </div>
            <!-- .row -->
            <div class="height10 clearfix"></div>

            <div class="table-responsive">
            	<?php 
            		if(!empty($user_count))
          			{
      				?>
      					<div class="row">
      						<div class="container-fluid">
      							<div class="grider">
      								<?php 

      									foreach($user_view->chunk(4) as $grid)
      									{
  										?>
  										
  										<div class="row" style="margin-left: 2px;margin-right: -20px;">

											<?php
    										foreach ($grid as $vendor) {

      										$viewcount_new = DB::table('product')
					  																	->where('delete_status','=','')
																					   	->where('prod_status','=',1)
																					   	->where('user_id','=',$vendor->id)
                                                                                                                                                                                ->take(50)
																					   	->orderBy('prod_id','desc')
																					   	->count();
										   		if(!empty($viewcount_new))
										   		{
										   			$count_product = $viewcount_new;
										   		}else{
										   			$count_product = 0;
										   		}

										   		$star_count=DB::table('product_rating')
	   																			->leftJoin('product', 'product.prod_id', '=', 'product_rating.prod_id')
		               												->where('product.user_id', '=', $vendor->id)
					   															->count();

													if(!empty($star_count))
													{
														$star_views=DB::table('product_rating')
		               													->leftJoin('product', 'product.prod_id', '=', 'product_rating.prod_id')
		               													->where('product.user_id', '=', $vendor->id)
					   																->get();
														$over_01 = 0;
														$fine_value_01 = 0;

														foreach($star_views as $review)
														{
															if($review->rating==1){$value1 = $review->rating*1;} else { $value1 = 0; }
															if($review->rating==2){$value2 = $review->rating*2;} else { $value2 = 0; }
															if($review->rating==3){$value3 = $review->rating*3;} else { $value3 = 0; }
															if($review->rating==4){$value4 = $review->rating*4;} else { $value4 = 0; }
															if($review->rating==5){$value5 = $review->rating*5;} else { $value5 = 0; }

															$fine_value_01 += $value1 + $value2 + $value3 + $value4 + $value5;
															$over_01 +=$review->rating;
														}	

														if(!empty(round($fine_value_01/$over_01)))
														{ 
															$roundeding_01 = round($fine_value_01/$over_01); 
														} else {
															$roundeding_01 = 0; 
														}		   
													} // .end IF $star_count;

													if(!empty($star_count))
													{
														
														if(!empty($roundeding_01))
														{
															if($roundeding_01==1)
															{ 
																$rateus_new_01 =	'<p class="review-icon">
                                                    <span>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
																										<i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                              		</p>';
                            	}
                            	if($roundeding_01==2)
                          		{ 
                          			$rateus_new_01 =	'<p class="review-icon">
                                                    <span>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                	</p>';
                              }
                              if($roundeding_01==3)
                            	{ 
                            		$rateus_new_01 =	'<p class="review-icon">
                                                    <span>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
																											<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                	</p>';
                              }
                              if($roundeding_01==4)
                            	{ 
                            		$rateus_new_01 =	'<p class="review-icon">
                                                		<span>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
																											<i class="fa fa-star" aria-hidden="true"></i>
																											<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                	</p>';
                              }
                              if($roundeding_01==5)
                            	{ 
                            		$rateus_new_01 =	'<p class="review-icon">
                                                  	<span>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
                                                    	<i class="fa fa-star" aria-hidden="true"></i>
																											<i class="fa fa-star" aria-hidden="true"></i>
																											<i class="fa fa-star" aria-hidden="true"></i>
												 															<i class="fa fa-star" aria-hidden="true"></i>
                                                    </span>
                                                  </p>';
                              }

                            } else if(empty($roundeding_01)) {  
                            	$rateus_new_01 = '<p class="review-icon">
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

		 											$rateus_empty_01 = '<p class="review-icon">
                                              	<span></span>
																								<i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
																								<i class="fa fa-star" aria-hidden="true"></i>
																								<i class="fa fa-star" aria-hidden="true"></i>
													 											<i class="fa fa-star" aria-hidden="true"></i>
												 											</p>';
												
											?>

											<div class="col-lg-3 col-sm-6">
												<?php 

													$userphoto="/media/";

													if($vendor->profile_banner!="")
													{
														$path =$url.'/local/images'.$userphoto.$vendor->profile_banner;
													}else{
														$path =$url.'/local/images/noimage.jpg';
													}
												?>
												<div class="card hovercard">
                					<div style='background: url("<?php echo $path;?>"); background-size: cover; height: 135px;'></div><?php 

                						$userphoto_two="/media/";

                						if($vendor->photo!="")
                						{
                							$path_two =$url.'/local/images'.$userphoto.$vendor->photo;
                						}else{
                							$path_two =$url.'/local/images/nophoto.jpg';
                						}
              						?>
              						<div class="avatar">
              							<a href="<?php echo $url;?>/profile/<?php echo $vendor->id;?>/<?php echo $vendor->post_slug;?>">
              								<img alt="" src="<?php echo $path_two;?>">
              							</a>
              						</div>

              						<div class="info">
              							<div class="title"> <!-- Marcello :: Troca de name para name_business -->
              								<a href="<?php echo $url;?>/profile/<?php echo $vendor->id;?>/<?php echo $vendor->post_slug;?>">
              									<?php 

              										if(!empty($vendor->name_business))
            											{ 
            												echo utf8_decode($vendor->name_business);
            											}else{ 
            												echo "N/A";
            											}
          											?>
          										</a>
          									</div>
          									<?php 

          										if($count_product==0 or $count_product==1)
        											{ 
        												$prod_txt = __('languages.product'); 
        											} else { 
        												$prod_txt = __('languages.products'); 
        											} 
      											?>
      											<div class="height10"></div>
      											<div class="fontsize14 black"><?php echo $count_product.' '.$prod_txt;?> </div>
      											<div class="height10"></div>
      											<?php 

    													if(!empty($star_count))
  														{ 
  															echo $rateus_new_01; 
  														} else { 
  															echo $rateus_empty_01; 
  														}
														?>
													</div>
													<!-- .info -->
													<div class="bottom">
														<a class="btn-upper btn btn-primary" href="<?php echo $url;?>/profile/<?php echo $vendor->id;?>/<?php echo $vendor->post_slug;?>">
															@lang('languages.view_profile')
														</a>
													</div>

												</div>
												<!-- .card hovercard --> 
											</div>
											<!-- .col-lg-3 col-sm-6 -->
											<?php } // .end foreach ?> </div> <?php } // .end foreach ?>
										</div>
										<!-- .grider -->
										<div class="clear height30"></div>
										<div class="grid_page"></div>
									</div>
									<!-- .container-fluid -->
								</div>
								<!-- .row -->
							<?php } // .end if(!empty($user_count)) ?>
						</div>	
						<!-- .table-responsive -->		
					</div>
					<!-- .col-md-12 my-wishlist -->
				</div>
				<!-- .row -->
			</div>
			<!-- .my-wishlist-page -->
		</div>
		<!-- .container-fluid -->
	</div>
	<!-- .body-content -->

	<div class="height30"></div>

	@include('footer')

</body>
</html>

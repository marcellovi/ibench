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

<html class="no-js" lang="en">

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

						<div class="col-md-6">
							<div class="heading-title" style="border-bottom:none !important;">Clientes em Espera:
								(<?php echo $viewcount_waiting;?>)</div>
						</div>
						<div class="col-md-6 text-right">


							<!-- Marcello Add Product
                   <a href="#" class="btn-upper btn btn-primary">@lang('languages.add_product')</a> 
                 -->
							<!-- Marcello Hide Import   
                    <a href="<?php echo $url;?>/importExport" class="btn-upper btn btn-primary">@lang('languages.goto_import_export')</a>
                  -->
						</div>

						<div class="height20 clearfix"></div>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>

										<th class="item">@lang('languages.username')</th>
										<th class="item">@lang('languages.product_name')</th>
										<th class="item">@lang('languages.status')</th>
										<th class="item">Data/Hora</th>
										<th class="item">@lang('languages.action')</th>

									</tr>
								</thead><!-- /thead -->

								<tbody>

									<?php if(!empty($viewcount_waiting)){ ?>
									<?php foreach($viewproduct_waiting as $item){		
																	
											$user = DB::table('users')
																->where('id','=',$item->user_id)
																->get();	
																						
											$product = DB::table('product')
																->where('prod_token','=',$item->product_id)
																->get();
											
											$product_waiting_list = DB::table('waiting_list') // Cristiano -  adicionado conexão para remover item
																->where('id','=',$item->id)
																->get();

											$product_img_count = DB::table('product_images')
																->where('prod_token','=',$item->product_id)
																->count();
												
									?>
									<tr>
										<td class="item" style="text-align: center;"><?= $user[0]->name ?></td>
										<td class="item" style="text-align: center;">
											<?php
              									if(!empty($product_img_count)){					
												$product_img = DB::table('product_images')
													->where('prod_token','=',$item->product_id)
													->orderBy('prod_img_id','asc')
													->get();
											?>
											<a href="<?php echo $url;?>/edit-product/<?php echo $product[0]->prod_token;?>">
												<img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>"
													alt="" class="img_responsive">
											</a>
											<?php } else { ?>
											<a
												href="<?php echo $url;?>/edit-product/<?php echo $product[0]->prod_token;?>">
												<img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt=""
													class="img_responsive">
											</a>
											<?php } ?>
											&nbsp;
											<a
												href="<?php echo $url;?>/edit-product/<?php echo $product[0]->prod_token;?>">
												<?= $product[0]->prod_name; ?>
											</a>

										</td>
										<td class="item" style="text-align: center;">
											<?php if($item->waiting == 1){
												echo('<p style="color:red;">Em Espera</p>');
										} else {
												echo('<p style="color:green;">Cliente Notificado!</p>');

												}
											?>

										</td>
										<td class="item" style="text-align: center;">
											<?php echo date("d/m/Y", strtotime($item->datetime)); ?></td> 
											<!-- Cristiano -  Modificado datatime por datetime -->

											<td class="romove-item">
											<a href="<?php echo $url;?>/waiting-list/delete/<?php echo $product_waiting_list[0]->id;?>" title="@lang('languages.tooldelete')" class="icon" onClick="return confirm('@lang('languages.are_you_sure')');"><i class="fa fa-trash-o"></i></a>
										</td>
										<!-- Cristiano -  adicionado botão remover -->
									</tr>
									<?php }} ?>
								</tbody><!-- /tbody -->
							</table><!-- /table -->
						</div>
					</div>

				</div>
			</div>

			<hr>
			<div class="row ">
				<div class="shopping-cart">
					<div class="shopping-cart-table">
						<div class="col-md-6">
							<div class="heading-title" style="border-bottom:none !important;">Clientes Notificados:
								(<?php echo $viewcount_no_waiting;?>)</div>
						</div>

						<div class="height20 clearfix"></div>
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>

										<th class="item">@lang('languages.username')</th>
										<th class="item">@lang('languages.product_name')</th>
										<th class="item">@lang('languages.status')</th>
										<th class="item">Data/Hora</th>
										<th class="item">@lang('languages.action')</th>

									</tr>
								</thead><!-- /thead -->

								<tbody>

									<?php if(!empty($viewcount_no_waiting)){ ?>
									<?php foreach($viewproduct_no_waiting as $item){		
																	
											$user = DB::table('users')
																->where('id','=',$item->user_id)
																->get();	
																						
											$product = DB::table('product')
																->where('prod_token','=',$item->product_id)
																->get();

											$product_waiting_list = DB::table('waiting_list') // Cristiano - adicionado conexão para remover item
																->where('id','=',$item->id)
																->get();

											$product_img_count = DB::table('product_images')
																->where('prod_token','=',$item->product_id)
																->count();
												
									?>
									<tr>
										<td class="item" style="text-align: center;"><?= $user[0]->name ?></td>
										<td class="item" style="text-align: center;">
											<?php
												if(!empty($product_img_count)){					
													$product_img = DB::table('product_images')
														->where('prod_token','=',$item->product_id)
														->orderBy('prod_img_id','asc')
														->get();
											?>
											<a
												href="<?php echo $url;?>/edit-product/<?php echo $product[0]->prod_token;?>">
												<img src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>"
													alt="" class="img_responsive">
											</a>
											<?php } else { ?>
											<a
												href="<?php echo $url;?>/edit-product/<?php echo $product[0]->prod_token;?>">
												<img src="<?php echo $url;?>/local/images/noimage_box.jpg" alt=""
													class="img_responsive">
											</a>
											<?php } ?>
											&nbsp;
											<?= $product[0]->prod_name; ?>

										</td>
										<td class="item" style="text-align: center;">
											<?php if($item->waiting == 1){
													echo('<p style="color:red;">Em Espera</p>');
												} else {
													echo('<p style="color:green;">Cliente Notificado!</p>');

											}?>

										</td>
										<td class="item" style="text-align: center;">
											<?php echo date("d-m-Y", strtotime($item->datetime)); ?></td>

											<td class="romove-item">
												<a href="<?php echo $url;?>/waiting-list/delete/<?php echo $product_waiting_list[0]->id;?>" title="@lang('languages.tooldelete')" class="icon" onClick="return confirm('@lang('languages.are_you_sure')');"><i class="fa fa-trash-o"></i></a>
										</td>
										<!-- Cristiano - adicionado botão remover -->
									</tr>

									<?php }} ?>
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
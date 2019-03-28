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
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">Clientes em Espera: (<?php echo $viewcount;?>)</div></div>
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
                    
				</tr>
			</thead><!-- /thead -->
			
			<tbody>
          
			<?php if(!empty($viewcount)){?>
                                <?php foreach($viewproduct as $item){		
																	
																	$user = DB::table('users')
																	->where('id','=',$item->user_id)
																	->get();	
																	
																	$product = DB::table('product')
																	->where('prod_token','=',$item->product_id)
																	->get();
							
								?>
				<tr>
																	<td class="item"><?= $user[0]->name ?></td>
																	<td class="item"><?= $product[0]->prod_name?></td>
																	<td class="item"><?php if($item->waiting == 1){
																			echo('Em Espera');
																		} else {
																			echo('Email Envaido');

																		}?></td>

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

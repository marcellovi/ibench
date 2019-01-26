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
				<li class='active'>@lang('languages.my_shopping')</li>
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
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_shopping')</div></div>
                <div class="col-md-6 text-right"></div>
                
                <div class="height20 clearfix"></div>
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th class="item">@lang('languages.purchase_id')</th>
					<th class="item">@lang('languages.payment_id')</th>
                    <th class="item">@lang('languages.payment_type')</th>
					<th class="item">@lang('languages.total_amount')</th>
					
                    <th class="item">@lang('languages.payment_status')</th>
                    
                    
					<th class="item">@lang('languages.payment_date')</th>
					<th class="item">@lang('languages.view_more')</th>
					
                    
				</tr>
			</thead><!-- /thead -->
			
			<tbody>
            
           <?php if(!empty($viewcount)){?>
                                <?php foreach($viewproduct as $product){
								
								 
													
								
								?>
            
                    <tr>
					
					<td class="cart-product-name-info">
                    						
                        <?php echo $product->purchase_token;?>
                                                        
                                                        
                       
                                                                                
					</td>
                    
                                                                        
					<td class="cart-product-name-info">
						<?php echo $product->payment_token;?>
						
						
					</td>
					<td class="cart-product-edit"><?php echo $setts[0]->site_currency.' '.number_format($product->total,2,",",".");?></td>
                    
                    
                                        <!-- Marcello Pagamento Alterado -->
					<td class="cart-product-quantity">
                                            <?php if($product->payment_type == "cash-on-delivery"){ ?>                                            
						Pagamento na entrega
                                            <?php } else { ?>
                                                <?php echo $product->payment_type;?>
                                            <?php } ?>
		            </td>
                                        <!-- Status Marcello Alterado -->
                                        <td style="text-align:center;
                                            <?php   if($product->payment_status=="completed"){?>
                                                            color:#006600;">Finalizado
                                                    <?php } 
                                                    else if($product->payment_status=="pending"){?>
                                                            color:#FF0000;">Pendente
                                                    <?php } 
                                                    
                                                    else {?> color:#006600;">Incompleto 
                                                    <?php }?>
                                        
                                        </td>
                                    
                                        <!-- Status Marcello Original 
                                        <td style="text-align:center;
                                            <?php   if($product->payment_status=="completed"){?>color:#006600;<?php } 
                                                    if($product->payment_status=="pending"){?>color:#FF0000;<?php } ?>">
                                                        <?php echo $product->payment_status;?></td>
                                    -->
                    
                                        <!-- Data do Pagamento -->
					<td class="cart-product-sub-total"><?php echo $product->payment_date;?></td>
                                        
                                        <!-- Visualize -->
					<td class="cart-product-grand-total"><a href="<?php echo $url;?>/view-shopping/<?php echo $product->purchase_token;?>" style="color:#000099;">@lang('languages.view_more')</a></td>
                    
                    
                    
                    
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

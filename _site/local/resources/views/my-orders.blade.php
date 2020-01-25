<?php

use Illuminate\Support\Facades\Route;

$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid = 1;
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
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_orders') (<?php echo $viewcount[0]->total_orders;?>)</div></div>
                <div class="col-md-6 text-right"></div>              
                <div class="height20 clearfix"></div>
               
                
	<div class="table-responsive">
        
        <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Informativos - Total de <?php echo $viewcount[0]->total_orders;?> Pedidos</div>
        <div class="panel-body">
            <p><b>IMPORTANTE!</b><br> Assim que realizar a entrega do pedido, nos informe para que possamos 
                liberar o pagamento na sua conta na Wirecard. 
                <br>Escreva para ibench@ibench.com.br</p>
        </div>
        
	<table class="table ">
		<thead>
			<tr>
			<th class="item">@lang('languages.name')</th>
			<th class="item">@lang('languages.purchase_id')</th>
			<th class="item">@lang('languages.total_items')</th>
			<th class="item">@lang('languages.total_shipping')</th>
			<th class="item">@lang('languages.total')</th>
			<th class="item"></th>
			<!-- <th class="item">@lang('languages.payment_status')</th> -->
                        </tr>
			</thead><!-- /thead -->
			
	<tbody> 
        <?php foreach($viewproduct as $product){ ?>
        <tr>
	<td class="cart-product-grand-total">Marcello Vieira
           </td>
        <td class="cart-product-name-info"> <a href="<?php echo $url;?>/purchaseorder/<?php echo $product->purchase_token;?>">
                <?php echo $product->purchase_token;?></a></td>
	<td class="cart-product-sub-total"><?php echo $product->quantity; ?></td>
        <td class="cart-product-grand-total"><?php echo $setts[0]->site_currency.' '.number_format($product->shipping_price,2,",",".");?></td>                    
	<td class="cart-product-grand-total"><?php echo $setts[0]->site_currency.' '.number_format($product->total,2,",",".");?></td>
	<td class="cart-product-edit">  
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Mais Detalhes" >
            <a href="<?php echo $url;?>/purchaseorder/<?php echo $product->purchase_token;?>"> 
                <span class="glyphicon glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span></a>
            </button>
            <!--
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Upload NF">
            <span class="glyphicon glyphicon glyphicon-open" aria-hidden="true"></span>
            </button>
            
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Enviar MSG" >
            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
            </button>
            -->
        </td>          
        </tr>
        <?php } ?>
        
								
	</tbody><!-- /tbody -->
	</table><!-- /table -->
        <span class="pull-right">{{ $viewproduct->links() }}</span>
        
        </div>
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

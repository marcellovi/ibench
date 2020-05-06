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
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
				<li class='active'>@lang('languages.order_details')</li>
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
    
    <!-- Loop for information -->
    
    
     <?php if(!empty($viewcount)){		
		
		$viewuser_cnt = DB::table('users')
		                ->where('id', '=', $viewproduct[0]->user_id)
				->count();
		
		if(!empty($viewuser_cnt))
		{
		$viewuser = DB::table('users')
		            ->where('id', '=', $viewproduct[0]->user_id)
                            ->get();
		$customer_name = utf8_decode($viewuser[0]->full_name);
		$customer_email = $viewuser[0]->email;
		$customer_phone = $viewuser[0]->phone;
		$customer_slug = $viewuser[0]->post_slug;
		$customer_gender = $viewuser[0]->gender;
		$customer_country = utf8_decode($viewuser[0]->country);
		}
		else
		{
		  $customer_name = "";
		  $customer_email = "";
		  $customer_phone = "";
		  $customer_slug = "";
		  $customer_gender = "";
		  $customer_country = "";
		}		
		
		$view_cnt = DB::table('product_checkout')
		                ->where('purchase_token', '=', $viewproduct[0]->purchase_token)
				->count();
		if(!empty($view_cnt))
		{
		  $vieww = DB::table('product_checkout')
		                ->where('purchase_token', '=', $viewproduct[0]->purchase_token)
				->get();
			$purchase_date = $vieww[0]->payment_date;			 
		}
		else
		{
		$purchase_date = "";
		}		
		
		$view_bill_count = DB::table('product_checkout')
		                ->where('purchase_token', '=', $viewproduct[0]->purchase_token)
				->count();
		if(!empty($view_bill_count))
		{
		   $view_bill = DB::table('product_checkout')
		                ->where('purchase_token', '=', $viewproduct[0]->purchase_token)
				->get();
						 
			$bill_firstname = utf8_decode($view_bill[0]->bill_firstname);
			$bill_lastname = utf8_decode($view_bill[0]->bill_lastname);
			$bill_companyname = utf8_decode($view_bill[0]->bill_companyname);
			$bill_email = utf8_decode($view_bill[0]->bill_email);
			$bill_phone = $view_bill[0]->bill_phone;
			$bill_country = utf8_decode($view_bill[0]->bill_country); 
			$bill_address = utf8_decode($view_bill[0]->bill_address); 
			$bill_city = utf8_decode($view_bill[0]->bill_city);
                        $bill_district = utf8_decode($view_bill[0]->bill_district);
			$bill_state = utf8_decode($view_bill[0]->bill_state);
			$bill_postcode = $view_bill[0]->bill_postcode;
			
			
			$ship_firstname = utf8_decode($view_bill[0]->ship_firstname);
			$ship_lastname = utf8_decode($view_bill[0]->ship_lastname);
			$ship_companyname = utf8_decode($view_bill[0]->ship_companyname);
			$ship_email = $view_bill[0]->ship_email;
			$ship_phone = $view_bill[0]->ship_phone;
			$ship_country = utf8_decode($view_bill[0]->ship_country);
			$ship_address = utf8_decode($view_bill[0]->ship_address);
			$ship_city = utf8_decode($view_bill[0]->ship_city);
                        $ship_district = utf8_decode($view_bill[0]->ship_district);
			$ship_state = utf8_decode($view_bill[0]->ship_state);
			$ship_postcode = $view_bill[0]->ship_postcode;
			
			$other_notes = utf8_decode($view_bill[0]->other_notes);		
			$payment_type = $view_bill[0]->payment_type;
		}
		else
		{
		
		    $bill_firstname = "";
			$bill_lastname = "";
			$bill_companyname = "";
			$bill_email = "";
			$bill_phone = "";
			$bill_country = ""; 
			$bill_address = ""; 
			$bill_district= "";
                        $bill_city = "";
			$bill_state = "";
			$bill_postcode = "";
			
			
			$ship_firstname = "";
			$ship_lastname = "";
			$ship_companyname = "";
			$ship_email = "";
			$ship_phone = "";
			$ship_country = "";
			$ship_address = "";
			$ship_district = "";
                        $ship_city = "";
			$ship_state = "";
			$ship_postcode = "";
			$other_notes = "";
			$payment_type = "";
		}
            ?>
    
	<div class="row ">
		<div class="shopping-cart">
		<div class="shopping-cart-table ">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.order_details') <?php echo $viewproduct[0]->purchase_token; ?> </div></div>
                <div class="col-md-6 text-right">                    
	
		<div class="heading-title text-right" style="border-bottom:none !important; text-align:right !important;">
                    <a href="<?php echo $url; ?>/my-orders" class="btn-upper btn btn-primary">@lang('languages.back_to_my_orders')</a> 
                   <!-- <a href="javascript:window.print();" class="btn-upper btn btn-primary" target="_blank">@lang('languages.print_btn')</a> -->
                </div>

                </div>              
                <div class="height20 clearfix"></div>
                
	<div class="table-responsive">
        
        <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Todos Detalhes da Compra</div>
        
        <div class="panel-body">
            
<div class="body-content">
  <div class="container-fluid">
    <div class="contact-page">
    
        <!-- Preenchendo com informacoes do Pedido -->
                    
	<div class="col-md-6 contact-form">  
    
    <div class="col-md-12"><h4>@lang('languages.order_details')</h4></div>
     <div class="height20 clearfix"></div>
	<div class="col-md-6 ">       
        
		<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.purchase_id'): </strong> <?php echo $viewproduct[0]->purchase_token; ?></label>
		    
		</div>		
	</div>
	
	<!-- -->
    <?php if(!empty($viewproduct[0]->payment_token)){?>
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.payment_id'): lllll</strong> <?php $viewproduct[0]->payment_token; ?></label>
	</div>
    </div>
    <?php  } ?> 
 
    
    <div class="col-md-6 ">
	<div class="form-group">
	    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.payment_type'): </strong> <?php echo $payment_type; ?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
        <div class="form-group">
	    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.purchase_date'): </strong> <?php echo $purchase_date; ?></label>
	</div>
    </div>    
    
    <div class="col-md-6 ">
	<div class="form-group"><!-- $viewproduct[0]->shipping_price -->
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.shipping_price'): </strong> <?php echo number_format($all_costs[0]->full_shipping,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
	</div>
    </div> 
    
    <div class="col-md-6 ">
	<div class="form-group"> 
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.subtotal'): </strong> <?php echo number_format($all_costs[0]->full_subtotal,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
        </div> 
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.total'): </strong> <?php echo number_format($all_costs[0]->full_total,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
	 </div>       
    </div>
</div>

        <!-- Preenchendo informacoes do cliente -->
        
        
<div class="col-md-6 contact-form">
    <!--
	<div class="col-md-12 contact-title text-right">
		<div class="heading-title text-right" style="border-bottom:none !important; text-align:right !important;">
                    <a href="<?php //echo $url; ?>/my-orders" class="btn-upper btn btn-primary">@lang('languages.back_to_my_orders')</a> 
                    <a href="javascript:window.print();" class="btn-upper btn btn-primary" target="_blank">@lang('languages.print_btn')</a>
                </div>
	</div>
    -->
   
    <div class="col-md-12"><h4>@lang('languages.customer_details') </h4></div>
        
    <div class="height20 clearfix"></div>
    
   <div class="col-md-6 ">		
        <div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.name'): </strong> <a href="<?php echo $url; ?>/profile/<?php echo $viewproduct[0]->user_id; ?>/<?php echo $customer_slug; ?>" class="theme_color"><?php echo $customer_name; ?></a></label>
        </div>
    </div>
    
	
    <div class="col-md-6 ">
	<div class="form-group">
	  <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php echo $customer_email; ?></label>
	</div>
    </div>    
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php echo $customer_phone; ?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
		
  	
	</div>
    
    
    
    <div class="col-md-6 ">
	<div class="form-group">
		<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php echo $customer_country; ?></label>
	</div>
    </div>

</div>
        
        
        <!-- Preenchendo informacoes de cobranca -->
                    
    </div>
      
      
    <!-- Billing Details -->
            
<div class="contact-page">
    <div class="col-md-6 contact-form">
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.billing_details') </h4> </div>
     <div class="height20 clearfix"></div>
	<div class="col-md-6 ">
            <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>Nome / Raz&atilde;o Social: </strong> <?php echo $bill_firstname; ?></label>
            </div>		
	</div>
    
     <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>Sobrenome / Inscri&ccedil;&atilde;o Estadual:</strong> <?php echo $bill_lastname; ?></label>
	</div>
    </div> 
     
      <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>CPF / CNPJ:</strong> <?php //if(strlen($viewproduct[0]->cpf_cnpj)==11){ echo mask($viewproduct[0]->cpf_cnpj,'###.###.###-##'); }else if(strlen($viewproduct[0]->cpf_cnpj)==14){ echo mask($viewproduct[0]->cpf_cnpj,'##.###.###/####-##'); }else{  echo $viewproduct[0]->cpf_cnpj; } ?></label>
	</div>
    </div> 
    
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php echo $bill_email; ?></label>
	</div>
    </div>  
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php echo $bill_phone; ?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php echo $bill_country; ?></label>
        </div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.address'): </strong> <?php echo $bill_address; ?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
        <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.city'): </strong> <?php echo $bill_city; ?></label>
	</div>
    </div>
     
    <div class="col-md-6 ">
	<div class="form-group">
		<label class="fontnromal" for="exampleInputName"><strong>Bairro: </strong> <?php echo $bill_district; ?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.state'): </strong> <?php echo $bill_state; ?></label>
	</div>
	</div>
    
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.postcode'): </strong> <?php echo $bill_postcode; ?></label>
	</div>
	</div>
</div>
 
<div class="col-md-6 contact-form">
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.shipping_details')</h4></div>
    
    <div class="height20 clearfix"></div>
	
    <div class="col-md-6 ">
	<div class="form-group">
                    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.first_name'): </strong> <?php if(!empty($ship_firstname)){echo $ship_firstname;}else{ echo $bill_firstname; } ?></label>
	</div>
    </div>
	
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.last_name'): </strong> <?php if(!empty($ship_lastname)){echo $ship_lastname;}else{ echo $bill_lastname; }  ?></label>
    </div>
    </div>   
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php if(!empty($ship_email)){echo $ship_email;}else{ echo $bill_email; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php if(!empty($ship_phone)){echo $ship_phone;}else{ echo $bill_phone; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php if(!empty($ship_country)){echo $ship_country;}else{ echo $bill_country; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.address'): </strong> <?php if(!empty($ship_address)){echo $ship_address;}else{ echo $bill_address; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.city'): </strong> <?php if(!empty($ship_city)){echo $ship_city;}else{ echo $bill_city; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>Bairro: </strong> <?php if(!empty($ship_district)){echo $ship_district;}else{ echo $bill_district; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.state'): </strong> <?php if(!empty($ship_state)){echo $ship_state;}else{ echo $bill_state; }  ?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.postcode'): </strong> <?php if(!empty($ship_postcode)){echo $ship_postcode;}else{ echo $bill_postcode; }  ?></label>
    </div>
    </div>    
</div>
        
        
    <div class="col-md-6 contact-form">
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.other_details')</h4></div>
     
    <div class="height20 clearfix"></div>
	
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.other_notes'): </strong> <?php echo $other_notes;?></label>
    </div>
    </div>
    </div>
</div>
 
    
    
  </div>
     </div> <?php } ?> <!-- fim loop -->
        </div>        
        
        
	<table class="table ">
		<thead>
			<tr>
			<th class="item">@lang('languages.product_name')</th>
			<th class="item">@lang('languages.quantity')</th>
			<th class="item">@lang('languages.price_unit')</th>
                        <th class="item">@lang('languages.subtotal')</th>
			<th class="item">@lang('languages.brand')</th>
			</tr>
			</thead><!-- /thead -->
			
	<tbody> 
        <?php foreach($viewproduct as $product){ ?>
        <tr>
            <td class="cart-product-grand-total"><?php echo utf8_decode($product->prod_name);?></td>
        <td class="cart-product-name-info"> <?php echo $product->quantity;?></td>
        <td class="cart-product-sub-total"><?php echo $product->price; ?></td>
	<td class="cart-product-sub-total"><?php echo $product->subtotal; ?></td>
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
                        $prod_type = DB::table('product_attribute_type')
					->where('delete_status','=','')
					->where('status','=',1)
					->where('attr_id','=', $prod_value[0]->attr_id)
					->get();	
                        $value_namer .='<b class="black">'.$prod_type[0]->attr_name.'</b> - '.$prod_value[0]->attr_value.', ';	
                    }				
                }	
                $attri_name = rtrim($value_namer,', ');
	?>
        <td class="cart-product-grand-total">
           <?php if(!empty($product->prod_attribute)){ ?>
            (<?php echo utf8_decode($attri_name);?>)
           <?php } ?>
        </td>                    
	</tr>
        <?php } ?>
        
								
	</tbody><!-- /tbody -->
	</table><!-- /table -->
                
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

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

<div class="body-content">
  <div class="container-fluid">
    <div class="contact-page">
    
    <div class="row">
         @if(Session::has('success'))
	    <div class="alert alert-success">
	      <p>{{ Session::get('success') }}</p>
	    </div>
	@endif
	
	
 	@if(Session::has('error'))
	    <div class="alert alert-danger">
	      <p>{{ Session::get('error') }}</p>
	    </div>
	@endif
        
    </div>   
     <?php if(!empty($viewcount)){		
		
		$viewuser_cnt = DB::table('users')
		                ->where('id', '=', $viewproduct[0]->user_id)
				->count();
		
		if(!empty($viewuser_cnt))
		{
		$viewuser = DB::table('users')
		            ->where('id', '=', $viewproduct[0]->user_id)
                            ->get();
		$customer_name = utf8_decode($viewuser[0]->name);
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
            
	<div class="col-md-6 contact-form">
            <div class="col-md-12 contact-title">
                    <div class="heading-title" style="border-bottom:none !important;">@lang('languages.view_orders')</div>
            </div>
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.order_details')</h4></div>
     <div class="height20 clearfix"></div>
	<div class="col-md-6 ">       
        
		<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.purchase_id'): </strong> <?php echo $viewproduct[0]->purchase_token;?></label>
		    
		</div>		
	</div>
	
	
    <?php if(!empty($viewproduct[0]->payment_token)){?>
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.payment_id'): </strong> <?php echo $viewproduct[0]->payment_token;?></label>
	</div>
    </div>
     <?php } ?> 
    
    <div class="col-md-6 ">
	<div class="form-group">
	    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.payment_type'): </strong> <?php echo $payment_type;?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
        <div class="form-group">
	    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.purchase_date'): </strong> <?php echo $purchase_date;?></label>
	</div>
    </div>    
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.shipping_price'): </strong> <?php echo number_format($viewproduct[0]->shipping_price,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
	</div>
    </div> 
    
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.subtotal'): </strong> <?php echo number_format($viewproduct[0]->subtotal,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
        </div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.total'): </strong> <?php echo number_format($viewproduct[0]->total,2,",",".");?> <?php echo $setting[0]->site_currency;?></label>
	 </div>
    </div>
</div>

<div class="col-md-6 contact-form">
	<div class="col-md-12 contact-title text-right">
		<div class="heading-title text-right" style="border-bottom:none !important; text-align:right !important;"><a href="<?php echo $url;?>/my-orders" class="btn-upper btn btn-primary">@lang('languages.back_to_my_orders')</a> <a href="javascript:window.print();" class="btn-upper btn btn-primary" target="_blank">@lang('languages.print_btn')</a></div>
	</div>
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.customer_details') </h4></div>
        
    <div class="height20 clearfix"></div>
    
   <div class="col-md-6 ">		
        <div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.name'): </strong> <a href="<?php echo $url;?>/profile/<?php echo $viewproduct[0]->user_id;?>/<?php echo $customer_slug;?>" class="theme_color"><?php echo $customer_name;?></a></label>
        </div>
    </div>
	
    <div class="col-md-6 ">
	<div class="form-group">
	  <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php echo $customer_email;?></label>
	</div>
    </div>    
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php echo $customer_phone;?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
		
       
         <!--
			<div class="form-group">
		   <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.gender'): </strong> <?php echo $customer_gender;?></label> 
		    
		  </div>
	-->	
	</div>
    
    
    
    <div class="col-md-6 ">
	<div class="form-group">
		<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php echo $customer_country;?></label>
	</div>
    </div>

</div>
</div>
        
        
<div class="contact-page">
    <div class="col-md-6 contact-form">
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.billing_details') - cpf/cnpj : <?php if(strlen($viewproduct[0]->cpf_cnpj)==11){ echo mask($viewproduct[0]->cpf_cnpj,'###.###.###-##'); }else{ echo mask($viewproduct[0]->cpf_cnpj,'##.###.###/####-##'); };?></h4> </div>
     <div class="height20 clearfix"></div>
	<div class="col-md-6 ">
            <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>Nome / Raz&atilde;o Social: </strong> <?php echo $bill_firstname;?></label>
            </div>		
	</div>
    
     <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>Sobre Nome / Inscri&ccedil;&atilde;o Estadual:</strong> <?php echo $bill_lastname;?></label>
	</div>
    </div> 
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php echo $bill_email;?></label>
	</div>
    </div>  
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php echo $bill_phone;?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php echo $bill_country;?></label>
        </div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
            <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.address'): </strong> <?php echo $bill_address;?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
        <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.city'): </strong> <?php echo $bill_city;?></label>
	</div>
    </div>
     
    <div class="col-md-6 ">
	<div class="form-group">
		<label class="fontnromal" for="exampleInputName"><strong>Bairro: </strong> <?php echo $bill_district;?></label>
	</div>
    </div>
    
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.state'): </strong> <?php echo $bill_state;?></label>
	</div>
	</div>
    
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.postcode'): </strong> <?php echo $bill_postcode;?></label>
	</div>
	</div>
</div>
        
<div class="col-md-6 contact-form">
    
    <div class="height20 clearfix"></div>
    
    <div class="col-md-12"><h4>@lang('languages.shipping_details')</h4></div>
    
    <div class="height20 clearfix"></div>
	
    <div class="col-md-6 ">
	<div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.first_name'): </strong> <?php echo $ship_firstname;?></label>
	</div>
    </div>
	
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.last_name'): </strong> <?php echo $ship_lastname;?></label>
    </div>
    </div>   
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.email'): </strong> <?php echo $ship_email;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.phone'): </strong> <?php echo $ship_phone;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.country'): </strong> <?php echo $ship_country;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.address'): </strong> <?php echo $ship_address;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
	<label class="fontnromal" for="exampleInputName"><strong>@lang('languages.city'): </strong> <?php echo $ship_city;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>Bairro: </strong> <?php echo $ship_district;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.state'): </strong> <?php echo $ship_state;?></label>
    </div>
    </div>
    
    <div class="col-md-6 ">
    <div class="form-group">
		    <label class="fontnromal" for="exampleInputName"><strong>@lang('languages.postcode'): </strong> <?php echo $ship_postcode;?></label>
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
        
<?php } ?>

  </div>
</div>

<div class="height30"></div>

 @include('footer')
 <?php
 function mask($val, $mask)
{
 $maskared = '';
 $k = 0;
 for($i = 0; $i<=strlen($mask)-1; $i++)
 {
 if($mask[$i] == '#')
 {
 if(isset($val[$k]))
 $maskared .= $val[$k++];
 }
 else
 {
 if(isset($mask[$i]))
 $maskared .= $mask[$i];
 }
 }
 return $maskared;
}
 ?>
 </body>
</html>
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
<body  class="cnt-home">
  
    @include('header')
<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.payment_confirmation')</li>
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
        <div class="col-md-12 row"><div class="heading-title">@lang('languages.payment_confirmation')</div></div>
        <div class="height20 clearfix"></div>
        <div class="table-responsive">
        <div class="text-center"> 
            
	<div class="clearfix height30"></div>
        
	<div class="h4 black">
    <label class="black">@lang('languages.total')</label> : <?php echo number_format($amount,2,",","."); ?> <?php echo $currency; ?>
	</div>
	<div class="clear height20"></div>    
    
    <?php if($payment_type=="paytm"){	
	$user_details = DB::table('users')
		->where('id', '=', Auth::user()->id)
		->get();
	?>
     <?php if($currency=="INR"){?>
    <form action="{{ url('paytm') }}" class="form-image-upload" method="POST" enctype="multipart/form-data">
    {!! csrf_field() !!}    
                    <input type="hidden" name="name" value="<?php echo $user_details[0]->name;?>">                   
                    <input type="hidden" name="mobile_number" value="<?php echo $user_details[0]->phone;?>">                        
                    <input type="hidden" name="email" value="<?php echo $user_details[0]->email;?>">
                    <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
                    <input type="hidden" name="currency" value="<?php echo $currency; ?>"/>                   
                    <input type="hidden" name="address" value="<?php echo $user_details[0]->address;?>">
                    <input type="hidden" name="order_id" value="<?php echo $order_no;?>"/> 
                    <input type="hidden" name="tipopagto" value="<?php echo $tipopagto;?>"/> 
                    
        <?php if($check_qty_ord == 1){ ?> 
            <p style="color:red;">*There are products without enough stock in your cart!</p>
            <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
        <?php }else{ ?>         
            <input type="submit" name="submit" value="Pay Now" class="btn-upper btn btn-primary">
        <?php } ?>
    </form>
    <?php } else {?>
    <span class="red">( Paytm Indian Rupees Only Supported )</span>  
    <?php } } ?>
    
    <?php if($payment_type=="razorpay"){?>   
    
    <?php if($currency=="INR"){?>
      
        <?php if($check_qty_ord == 1){ ?> 
          <p style="color:red;">*There are products without enough stock in your cart!</p>
          <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
        <?php }else{ ?>
          <input type="button" name="submit" value="Pay Now" id="rzp-button1" class="btn-upper btn btn-primary">
        <?php } ?>
      
    <?php }else{ ?>
      <span class="red">( Razorpay Indian Rupees Only Supported )</span> 
    <?php } ?>

	<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    
    <form class="register-form" name='razorpayform' role="form" method="POST" action="{{ route('razorpay_verify') }}" id="formID" enctype="multipart/form-data">
    {{ csrf_field() }}
    
        <input type="hidden" name="order_id" value="<?php echo $order_no;?>"/>
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
        
    </form>
    <script>
    
    var options = <?php echo $json_value?>;   
    
    options.handler = function (response){
	     
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.razorpayform.submit();
    };    
    
    options.theme.image_padding = false;
    
    options.modal = {
        ondismiss: function() {
            console.log("This code runs when the popup is closed");
        },        
        escape: true,       
        backdropclose: false
    };    
    var rzp = new Razorpay(options);    
    document.getElementById('rzp-button1').onclick = function(e){
        rzp.open();
        e.preventDefault();
    }
    </script>  
    
    <?php } ?>   
    
   <?php if($payment_type=="ccavenue"){
	
	$user_details = DB::table('users')
		->where('id', '=', Auth::user()->id)
		->get();
	if(!empty($user_details[0]->name)){ $bill_name = $user_details[0]->name;} else { $bill_name = ""; }
	if(!empty($user_details[0]->address)){ $address = $user_details[0]->address;} else { $address = "test"; }
	if(!empty($user_details[0]->country)){ $country = $user_details[0]->country;} else { $country = ""; }
	if(!empty($user_details[0]->phone)){ $phone = $user_details[0]->phone;} else { $phone = ""; }
	if(!empty($user_details[0]->email)){ $email = $user_details[0]->email;} else { $email = ""; }
	?>
    <script>
	window.onload = function() {
		var d = new Date().getTime();
		document.getElementById("tid").value = d;
	};
   </script>
    <form class="register-form" role="form" method="POST" action="{{ route('ccavRequestHandler') }}" id="formID" enctype="multipart/form-data">
    {{ csrf_field() }}
   
        
        <input type="hidden" name="tid" id="tid" readonly />
				<input type="hidden" name="merchant_id" value="<?php echo $setts[0]->ccavenue_merchant_id;?>"/>
				<input type="hidden" name="order_id" value="<?php echo $order_no;?>"/>
				<input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
				<input type="hidden" name="currency" value="<?php echo $currency; ?>"/>
				<input type="hidden" name="redirect_url" value="<?php echo $url;?>/ccavResponseHandler"/>
				<input type="hidden" name="cancel_url" value="<?php echo $url;?>/ccavResponseHandler"/>
			 	<input type="hidden" name="language" value="EN"/>
				<input type="hidden" name="billing_name" value="<?php echo $bill_name;?>"/>
		       <input type="hidden" name="billing_address" value="<?php echo $address;?>"/>
		       <input type="hidden" name="billing_city" value="<?php echo $country;?>"/>
               <input type="hidden" name="billing_state" value="<?php echo $country;?>"/>
               <input type="hidden" name="billing_zip" value="000000"/>
               <input type="hidden" name="billing_country" value="<?php echo $country;?>"/>
               <input type="hidden" name="billing_tel" value="<?php echo $phone;?>"/>
               <input type="hidden" name="billing_email" value="<?php echo $email;?>"/>
               <input type="hidden" name="integration_type" value="iframe_normal"/>
               
        <?php if($check_qty_ord == 1){ ?> 
          <p style="color:red;">*There are products without enough stock in your cart!</p>
          <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
        <?php }else{ ?>
          <input type="submit" name="submit" value="Pay Now" class="btn-upper btn btn-primary">
        <?php } ?>
		
    <?php } ?>
    
    <?php if($payment_type=="payhere"){
	
	  if($setts[0]->payhere_mode=="test") { $payurl = "https://sandbox.payhere.lk/pay/checkout"; }
	  if($setts[0]->payhere_mode=="live") { $payurl = "https://www.payhere.lk/pay/checkout"; }
	
	
	$merchants = $setts[0]->payhere_merchant_id;
	?>
    <form method="post" action="<?php echo $payurl;?>">   
    <input type="hidden" name="merchant_id" value="<?php echo $merchants;?>">
    <input type="hidden" name="return_url" value="<?php echo $url;?>/payhere_success/<?php echo $order_no;?>">
    <input type="hidden" name="cancel_url" value="<?php echo $url;?>/cancel">
    <input type="hidden" name="notify_url" value="<?php echo $url;?>/cancel">  
    
    <input type="hidden" name="order_id" value="<?php echo $order_no;?>">
    <input type="hidden" name="items" value="<?php echo  utf8_decode($product_names);?>">
    <input type="hidden" name="currency" value="<?php echo $currency; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>"> 
     
    
    <input type="hidden" name="first_name" value="<?php echo Auth::user()->name;?>">
    <input type="hidden" name="last_name" value="<?php echo Auth::user()->name;?>">
    <input type="hidden" name="email" value="<?php echo Auth::user()->email;?>">
    <input type="hidden" name="phone" value="<?php echo Auth::user()->phone;?>">
    <input type="hidden" name="address" value="<?php echo Auth::user()->address;?>">
    <input type="hidden" name="city" value="<?php echo Auth::user()->country;?>">
    <input type="hidden" name="country" value="<?php echo Auth::user()->country;?>"> 
        
    <?php if($check_qty_ord == 1){ ?> 
        <p style="color:red;">*There are products without enough stock in your cart!</p>
        <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
    <?php }else{ ?>
        <input type="submit" value="Buy Now" class="btn-upper btn btn-primary"> 
    <?php } ?>      
    </form>    
    
    <?php } ?>   
    
    <?php
        if ($payment_type=='wirecard'){
            $extra_data = "";
            if (isset($raw_data)){
            foreach (unserialize($raw_data) as $key => $value){
                if ($key != '_token')
                    $extra_data .= "<input type='hidden' name='{$key}' value='{$value}'>\n";
                }
                }
//                print_r($wirecardcontroller);
                ?>
        {!!Html::script('local/resources/views/mj-wirecard.js')!!}
        {!!Html::style('local/resources/views/mj-wirecard.css?ver=1')!!}
<?php
        echo '<div class="container" xmlns="http://www.w3.org/1999/html">
    <div class="row text-center">
    <div class="col-md-5"><div class="row"><div class="col-xs-12 col-md-12 text-center">
                                <form method="POST" action="' .route("wirecard-shop-success").'" enctype="multipart/form-data" accept-charset="utf-8">
                                 '.csrf_field().'
<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Pagar com Cart&atilde;o de Cr&eacute;dito 
                    </h3>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="order_no" value="'.$order_no.'">
                    <input type="hidden" name="amount" value="'.$amount.'">
                    <input type="hidden" name="currency" value="'.$currency.'">
                    '.$extra_data.'
                    <div class="form-group">
                        <label for="cardNumber">NOME DO TITULAR</label>
                        <div class="input-group">
                            <input type="text" name="cc_holder" class="form-control cc-holder" id="cc-holder" placeholder="Nome do Titular no Cart&atilde;o de Cr&eacute;dito"
                                required autofocus />
                            <span class="input-group-addon"><span class="glyphicon glyphicon-edit"></span></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">N&Uacute;MERO DO CART&Atilde;O</label>
                        <div class="input-group">
                            <input maxlength="16" minlength="16" type="tel" name="cc_number" class="form-control cc-number identified" id="cc-number" placeholder="N&uacute;mero V&aacute;lido do Cart&atilde;o de Cr&eacute;dito"
                                required autofocus />
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-7 col-md-7">
                            <div class="form-group">
                                <label for="expityMonth">DATA VALIDADE</label>
                                <div class="row">
                                <div class="col-xs-6 col-lg-6 pl-ziro">
                                    <!--<input type="tel" name="cc_exp_m" class="form-control" id="cc-exp-m" placeholder="MM" required />-->
                                    <select name="cc_exp_m" id="cc-exp-m" class="custom-select form-control cc-exp">
									      <option value="" selected="selected">M&ecirc;s</option>
									      <option value="01">01</option>
									      <option value="02">02</option>
									      <option value="03">03</option>
									      <option value="04">04</option>
									      <option value="05">05</option>
									      <option value="06">06</option>
									      <option value="07">07</option>
									      <option value="08">08</option>
									      <option value="09">09</option>
									      <option value="10">10</option>
									      <option value="11">11</option>
									      <option value="12">12</option>
									    </select>
                                </div>
                                <div class="col-xs-6 col-lg-6 pl-ziro">
                                    <input maxlength="2" minlength="2" data-min="'.date("y").'" pattern="[0-9]{2}" type="tel" name="cc_exp_y" class="form-control" id="cc-exp-y" placeholder="AA" required />
                                 </div>
                                 </div>
                            </div>
                        </div>
                        <div class="col-xs-5 col-md-5 pull-right">
                            <div class="form-group">
                                <label for="cvCode">C&Oacute;DIGO SEGURAN&Ccedil;A</label>
                                <input maxlength="4" minlength="3" pattern="\d+" type="tel" name="ccv" class="form-control" id="ccv" placeholder="CV" required />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><button type="submit"><span class="badge pull-right"><!--<span class="glyphicon glyphicon-usd"></span>-->'.$amount.' '.$currency.'</span>Confirmar Pagamento</button>
                </li>
            </ul>
           </form>
 </div></div></div>
    <div class="col-md-2"><div style="vertical-align: middle;align-items: center;font-weight: bold;font-size: xx-large">OR</div></div>
    <div class="col-md-5"><div class="row"><div class="col-xs-12 col-md-12 text-center">
                                <form method="POST" action="'.route("wirecard-boleto-shop-success").'" enctype="multipart/form-data" accept-charset="utf-8">
                                 '.csrf_field().'
<div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Pagamento Via Boleto
                    </h3>
                </div>
                <div class="panel-body">
                    <input type="hidden" name="tipopagto" value="'.$tipopagto.'"/> 
                    <input type="hidden" name="order_no" value="'.$order_no.'">
                    <input type="hidden" name="amount" value="'.$amount.'">
                    <input type="hidden" name="listcompanies" value="'.utf8_decode($listcompanies).'">
                    <input type="hidden" name="currency" value="'.$currency.'">
                    '.$extra_data.'

                <button class="btn btn-success btn-block" type="submit">Gerar Boleto</button>
                </div></div></form></div>
</div></div>
    </div>
</div>
';


                ?>

        <?php
            }
    ?>
    
    
    
    
    
    <?php if($payment_type=="paypal"){?>
    <form action="<?php echo $paypal_url; ?>" method="post">

        <!-- Identify your business so that you can collect the payments. -->
        <input type="hidden" name="business" value="<?php echo $paypal_id; ?>">
        
        <!-- Specify a Buy Now button. -->
        <input type="hidden" name="cmd" value="_xclick">
        
        <!-- Specify details about the item that buyers will purchase. -->
        <input type="hidden" name="item_name" value="<?php echo  utf8_decode($product_names);?>">
        <input type="hidden" name="item_number" value="<?php echo $order_no;?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
        
        <!-- Specify URLs -->
        <input type='hidden' name='cancel_return' value='<?php echo $url;?>/cancel'>
		<input type='hidden' name='return' value='<?php echo $url;?>/shop_success/<?php echo $order_no;?>'>

    <?php if($check_qty_ord == 1){ ?> 
        <p style="color:red;">*There are products without enough stock in your cart!</p>
        <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
    <?php }else{ ?>
		  <input type="submit" name="submit" value="Confirmar" class="btn-upper btn btn-primary">
    <?php } ?> 
    
    </form>
	<?php } if($payment_type=="stripe"){
		$fprice = $amount * 100;
		?>
        
        <form action="{{ route('stripe_shop_success') }}" method="POST">
	{{ csrf_field() }}
	
	<input type="hidden" name="cid" value="<?php echo $order_no;?>">
	<input type="hidden" name="amount" value="<?php echo $fprice; ?>">
	<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
	<input type="hidden" name="item_name" value="<?php echo utf8_decode($product_names);?>">
		<script src="https://checkout.stripe.com/checkout.js" 
		class="stripe-button" 
		<?php if($setts[0]->stripe_mode=="test") { ?>
		data-key="<?php echo $setts[0]->test_publish_key; ?>" <?php } ?>
		<?php if($setts[0]->stripe_mode=="live") {  ?>
		data-key="<?php echo $setts[0]->live_publish_key; ?>" 
		<?php }?> 
		data-image="<?php echo $url.'/local/images/media/'.$setts[0]->site_logo;?>" 
		data-name="<?php echo utf8_decode($product_names);?>" 
		data-description="<?php echo $setts[0]->site_name;?>"
		data-amount="<?php echo $fprice; ?>"
		data-currency="<?php echo $currency; ?>"
		/>
		</script>
	</form>
	<?php } if($payment_type=="cash-on-delivery"){ ?>
    
    
    <form class="register-form" role="form" method="POST" action="{{ route('cash-on-delivery') }}" id="formID" enctype="multipart/form-data">
    {{ csrf_field() }}
    
    <input type="hidden" name="cid" value="<?php echo $order_no;?>">
    <!-- Marcello Botao Pagamento -->
    <?php if($check_qty_ord == 1){ ?> 
        <p style="color:red;">*Existe(m) produto(s) sem Estoque suficiente em seu Carrinho!</p>
        <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Voltar ao Carrinho</a>
    <?php }else{ ?>
        <input type="submit" name="submit" value="@lang('languages.btn_pay_now')" class="btn-upper btn btn-primary">
    <?php } ?>

  </form>

	<?php } if($payment_type=="wallet-balance"){ ?>
    
    
    <form class="register-form" role="form" method="POST" action="{{ route('wallet-balance') }}" id="formID" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="amount" value="<?php echo $amount; ?>"/>
    <input type="hidden" name="cid" value="<?php echo $order_no;?>">
    <?php if($check_qty_ord == 1){ ?> 
        <p style="color:red;">*There are products without enough stock in your cart!</p>
        <a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Back Cart</a>
    <?php }else{ ?>
      <input type="submit" name="submit" value="Pay Now" class="btn-upper btn btn-primary">
    <?php } ?>
    
    </form>
    
    <?php } ?>
    
    
        <div class="clear height50"></div>
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

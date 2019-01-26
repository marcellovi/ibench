<!DOCTYPE html>
<html lang="en">
<head>

    <title>Cancellation & Refund Request</title>

  
	




</head>
<body>

   

    
    

	
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<div class="container">
	 <h1><?php echo $site_name;?> - @lang('languages.cancellation_refund_request')</h1>
	 
	 
	
	 
	 
	 <div class="clearfix"></div>
	 
	 <div class="row profile shop">
		<div class="col-md-6">
	 
	 
	
	<div id="outer" style="width: 100%;margin: 0 auto;background-color:#cccccc; padding:10px;">  
	
	
	<div id="inner" style="width: 80%;margin: 0 auto;background-color: #fff;font-family: Open Sans,Arial,sans-serif;font-size: 13px;
	font-weight: normal;line-height: 1.4em;color: #444;margin-top: 10px; padding:10px;">
			<div style="background:#22313F;padding:15px 10px 10px 10px;">
			<?php if(!empty($site_logo)){?>
			<div align="center"><img src="<?php echo $site_logo;?>" border="0" alt="logo" /></div>
			<?php } else { ?>
			<div align="center"><h2><?php echo $site_name;?></h2></div>
			<?php } ?>
            </div>
			
			<h3> @lang('languages.order_details')</h3>
            
            
			<p> @lang('languages.product_name') - <?php echo utf8_decode($prod_name);?></p>
			<p> @lang('languages.purchase_id') - <?php echo $purchase_token;?></p>
           <p> @lang('languages.order_id') - <?php echo $order_id;?></p>
            <p> @lang('languages.product_url') - <a href="<?php echo $url;?>/product/<?php echo $prod_id;?>/<?php echo utf8_decode($prod_slug);?>" target="_blank"><?php echo $url;?>/product/<?php echo $prod_id;?>/<?php echo utf8_decode($prod_slug);?></a></p> 
           <p> @lang('languages.payment_date') - <?php echo $payment_date;?></p>
           <p> @lang('languages.payment_type') - <?php echo $payment_type;?></p>
           <p> @lang('languages.amount') - <?php echo $currency.' '.$payment;?></p>  
             	
			<br/>			
			<h3> @lang('languages.buyer_details')</h3>
            
            <p> @lang('languages.name') - <?php echo $buyer_name;?></p>
            <p> @lang('languages.email') - <?php echo $buyer_email;?></p>
            <p> @lang('languages.profile_url') - <a href="<?php echo $url;?>/profile/<?php echo $buyer_id;?>/<?php echo $buyer_slug;?>" target="_blank"><?php echo $url;?>/profile/<?php echo $buyer_id;?>/<?php echo $buyer_slug;?></a></p>
	
    
    
           <br/>			
			<h3> @lang('languages.vendor_details')</h3>
            
            <p> @lang('languages.name') - <?php echo $vendor_name;?></p>
            <p> @lang('languages.email') - <?php echo $vendor_email;?></p>
            <p> @lang('languages.profile_url') - <a href="<?php echo $url;?>/profile/<?php echo $vendor_id;?>/<?php echo $vendor_slug;?>" target="_blank"><?php echo $url;?>/profile/<?php echo $vendor_id;?>/<?php echo $vendor_slug;?></a></p>
            
            
            <br/>			
			<h3> @lang('languages.refund_reason')</h3>
            <p> @lang('languages.subject') - <?php echo $subjected;?></p>
            <p> @lang('languages.comment') - <?php echo $messaged;?></p>
            
            
	
	</div>
	</div>
	 
	 
	 
	
	 
	 
	
	
	
	
	 
	 
	 
	 
	 
	 <div class="height30"></div>
	 <div class="row">
	
	
	
	
	
	</div>
	
	</div>
	

      
</body>
</html>
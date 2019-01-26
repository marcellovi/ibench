<!DOCTYPE html>
<html lang="en">
<head>

    <title>Payment credited to your wallet</title>

  
	




</head>
<body>

   

    
    

	
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<div class="container">
	 <h1><?php echo $site_name;?> - Payment credited to your wallet</h1>
	 
	 
	
	 
	 
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
			
			
			
			
			<h3> Payment credited to your wallet.Thank You!!</h3>
            <p> Total Amount - <?php echo $currency.' '.number_format($fine_amount,2);?></p>
			 <p> Admin Amount  - <?php echo $currency.' '.number_format($admin_amount,2);?></p>
              <p> Your Amount (With Shipping Charge) - <?php echo $currency.' '.number_format($vendor_commission,2);?></p>
            
            
            	
			<p> Thanks,</p> 	
			<p style="height:10px;"></p>
            <p>Admin</p>	
				
			
			
			
	
	
	
	
	</div>
	</div>
	 
	 
	 
	
	 
	 
	
	
	
	
	 
	 
	 
	 
	 
	 <div class="height30"></div>
	 <div class="row">
	
	
	
	
	
	</div>
	
	</div>
	

      
</body>
</html>
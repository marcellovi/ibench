<!DOCTYPE html>
<html lang="en">
<head>

    <title>Esqueceu Senha</title>

  
	




</head>
<body>

   

    
    

	
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<div class="container">
	 <h1><?php echo $site_name;?> - @lang('languages.forgot_password')</h1>
	 
	 
	
	 
	 
	 <div class="clearfix"></div>
	 
	 <div class="row profile shop">
		<div class="col-md-6">
	 
	 
	
	<div id="outer" style="width: 100%;margin: 0 auto;background-color:#cccccc; padding:10px;">  
	
	
	<div id="inner" style="width: 80%;margin: 0 auto;background-color: #fff;font-family: Open Sans,Arial,sans-serif;font-size: 13px;
	font-weight: normal;line-height: 1.4em;color: #444;margin-top: 10px; padding:10px;">
			<!-- Marcello 
			<div style="background:#22313F;padding:15px 10px 10px 10px;">
                      -->
                      <div style="padding:15px 10px 10px 10px;">
			<?php if(!empty($site_logo)){?>
			<div align="center"><img src="<?php echo $site_logo;?>" border="0" alt="logo" /></div>
			<?php } else { ?>
			<div align="center"><h2><?php echo $site_name;?></h2></div>
			<?php } ?>
			</div>
            
            <br/><br/>
            <div>
			<h2> @lang('languages.hello')</h2>
			<div style="font-size:16px;"><p> @lang('languages.receive_txt')

</p>
			<br/><br/><p align="center"><a href="<?php echo $url;?>/reset-password/<?php echo $token;?>" style="font-weight: 400;
    font-size: 13px;
    line-height: 15px;
    margin-right: 10px;
    text-align: center;
    padding: 15px 20px;
    white-space: nowrap;
    letter-spacing: 1px;
    display: inline-block;
    border: none;
    text-transform: uppercase;
    -webkit-animation-delay: 2s;
    animation-delay: 2s;
    -webkit-transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
    transition: background 0.3s ease-in-out, color 0.3s ease-in-out; color:#fff; background:#22313F; text-decoration:none;">@lang('languages.reset_password')</a>
				</p><br/><br/>
	
	<p>@lang('languages.did_not_request')</p>
    <br/><br/>
    <p>@lang('languages.regards')<br/><br/>@lang('languages.admin')</p>
    
    </div>
    
    </div>
    
	
	</div>
	</div>
	 
	 
	 
	
	 
	 
	
	
	
	
	 
	 
	 
	 
	 
	 <div class="height30"></div>
	 <div class="row">
	
	
	
	
	
	</div>
	
	</div>
	

      
</body>
</html>
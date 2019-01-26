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
				<li class='active'>@lang('languages.my_balance')</li>
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
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_balance')</div></div>
                <div class="col-md-6 text-right"></div>
                
                <div class="height20 clearfix"></div>
	
    
    
    <div class="container-fluid">

                	
	 
	 
	 
	 
	 	
	
	
		
	<div class="height20 clearfix"></div>
                    
                    
                    
	<div class="col-md-9">
	
	
	
	 
	
	
	
	
	
	
	
	<div class="col-md-12 wallet_border">
	
    
    
	<div class="gallerybox_new clearfix">
			
            
            
            
            
            
			
			<div class="para_pads">
			
			<div class="bottombordr">
            
            
            <div class="col-md-8 text-center review_bottom">
            <div class="height30"></div>
			<span class="min_with black">@lang('languages.minimum_withdraw') : <?php echo $site_setting[0]->site_currency;?> <?php if(!empty($site_setting[0]->withdraw_amt)){ echo $site_setting[0]->withdraw_amt; } else { ?>0<?php } ?></span>
			</div>
            
            
			<div class="col-md-4 text-center review_bottom_two">
			<span class="fontsize35 black"><?php echo $get_users_stage1[0]->earning;?> <?php echo $site_setting[0]->site_currency;?></span>
			<div class="re_text fontsize20 black">@lang('languages.available_balance')</div>
			<div class="smalltxt fontsize11 black">@lang('languages.cleared_funds')</div>
			
			</div>
			
			
			
			
			<div class="clearfix"></div>
			</div>
			
			
			
			
			
			
			
			</div>
		
		
		
		</div>
	
	
	
	</div>
	
	
	
	
	
	</div>
	
	
	
	<div class="col-md-3">
	<form role="form" method="POST" action="{{ route('my-balance') }}" id="sellerID" class="register-form" enctype="multipart/form-data">
        {{ csrf_field() }}
         <div class="form-group">
            <p class="black">@lang('languages.withdraw_amount') :</p>
            <input type="text" class="form-control unicase-form-control validate[required] radiusoff" id="withdraw_amount" name="withdraw_amount">
          </div>
          
          <input type="hidden" name="available_amount" value="<?php echo $get_users_stage1[0]->earning;?>">
          
          <div class="form-group">
            <p class="black">@lang('languages.withdraw_option') :</p>
            
            <select id="withdraw_type" name="withdraw_type" class="form-control unicase-form-control radiusoff validate[required]" onChange="javascript:withdraw_checking(this.value);">
					<option value="">Select</option>
						<?php 
						
						foreach($site_setting as $row)
						{
							$catid=$row->withdraw_option;
							$selected= explode(",",$catid); 
							$length= count($selected);
							for($i=0;$i<$length;$i++)
							{
								 $ader_cat= $selected[$i];
							
						?>
						<option value="<?php echo $ader_cat; ?>" ><?php echo $ader_cat; ?></option>
						<?php 
						} }
						?> 
					</select>
            
          </div>
          
          
          
          <div class="form-group" id="paypal" style="display:none;">
            <p class="black">@lang('languages.enter_paypal_id') :</p>
          
             <input type="text" class="form-control unicase-form-control validate[required] text-input" id="paypal_id" name="paypal_id">	
          </div>
          
          
          
           <div class="form-group" id="stripe" style="display:none;">
            <p class="black">@lang('languages.enter_stripe_id') :</p>
          
             <input type="text" class="form-control unicase-form-control validate[required] text-input" id="stripe_id" name="stripe_id">	
          </div>
          
          
          
          <div id="localbank" style="display:none;">
           
             <div class="form-group">
				<p class="black">@lang('languages.bank_account_no')</p>
					<input type="text" class="form-control unicase-form-control validate[required] text-input" id="bank_acc_no" name="bank_acc_no">
					
                    </div>
                    
                     <div class="form-group">
                    			
					
					<p class="black">@lang('languages.bank_name_and_address')</p>
					<input type="text" class="form-control unicase-form-control validate[required] text-input" id="bank_name" name="bank_name">
									
                              </div>
                              
                               <div class="form-group">      
                                    
					
					<p class="black">@lang('languages.ifsc_code')</p>
					<input type="text" class="form-control unicase-form-control validate[required] text-input" id="ifsc_code" name="ifsc_code">	
										

			</div>
            
            </div>
          
          
          
          
	 <div class="form-group">
          <input type="submit" name="submit" class="btn-upper btn btn-primary" value="@lang('languages.send')">
          </div>          
    </form>
	</div>
	
	
	
	
                    
                    
	
    
    
	</div>
    
    
    
    
    <div class="clearfix height50"></div>
    
    
    
    
    
    
    <div class="container-fluid">

                	<div class="col-md-12"> <div class="fontsize24 black">@lang('languages.pending_withdrawal')</div></div>
     <div class="clearfix height20"></div>
    <div class="col-md-12">
                                <div class="table-responsive">
		<table class="table">
                                    <thead>
                                        <tr class="balance_heading">
                                            <th>@lang('languages.sno')</th>
											<th>@lang('languages.amount')</th>
											<th>@lang('languages.payment_type')</th>
											<th>@lang('languages.paypal_id')</th>
                                            <th>@lang('languages.stripe_id')</th>
                                            <th>@lang('languages.bank_account_no')</th>
                                            <th>@lang('languages.bank_info')</th>
                                            <th>@lang('languages.ifsc_code')</th>
                                            <th>@lang('languages.status')</th>
										
                                        </tr>
                                    </thead>
									<tbody>
										<?php if(!empty($pending_withdraw_cnt)){?>
                                        <?php 
										$i=1;
										foreach($pending_withdraw as $view_withdraw){?>								
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $view_withdraw->withdraw_amount.' '.$site_setting[0]->site_currency;?></td>
											<td><?php echo $view_withdraw->withdraw_payment_type;?></td>	
											<td><?php echo $view_withdraw->paypal_id;?></td>	
												
											<td><?php echo $view_withdraw->stripe_id;?></td>											
											<td><?php echo $view_withdraw->bank_account_no;?></td>
                                            <td><?php echo $view_withdraw->bank_info;?></td>
                                            <td><?php echo $view_withdraw->bank_ifsc;?></td>
                                            <td style="color:#FF0000;"><?php echo $view_withdraw->withdraw_status;?></td>
										</tr>
                                       <?php $i++; } ?> 
									<?php } ?>			
									</tbody>
															
                                </table>
                                </div>
                            </div>
    
    </div>
    
    
    <div class="clearfix height50"></div>
    
    
    
    <div class="container-fluid">

                	<div class="col-md-12"> <div class="fontsize24 black">@lang('languages.completed_withdrawal')</div></div>
     <div class="clearfix height20"></div>
    <div class="col-md-12">
                                <div class="table-responsive">
		                         <table class="table">
                                    <thead>
                                        <tr class="balance_heading">
                                            <th>@lang('languages.sno')</th>
											<th>@lang('languages.amount')</th>
											<th>@lang('languages.payment_type')</th>
											<th>@lang('languages.paypal_id')</th>
                                            <th>@lang('languages.stripe_id')</th>
                                            <th>@lang('languages.bank_account_no')</th>
                                            <th>@lang('languages.bank_info')</th>
                                            <th>@lang('languages.ifsc_code')</th>
                                            <th>@lang('languages.status')</th>
										
                                        </tr>
                                    </thead>
									<tbody>
										<?php if(!empty($complete_withdraw_cnt)){?>
                                        <?php 
										$i=1;
										foreach($complete_withdraw as $view_withdraw){?>								
										<tr>
											<td><?php echo $i;?></td>
											<td><?php echo $view_withdraw->withdraw_amount.' '.$site_setting[0]->site_currency;?></td>
											<td><?php echo $view_withdraw->withdraw_payment_type;?></td>	
											<td><?php echo $view_withdraw->paypal_id;?></td>	
												
											<td><?php echo $view_withdraw->stripe_id;?></td>											
											<td><?php echo $view_withdraw->bank_account_no;?></td>
                                            <td><?php echo $view_withdraw->bank_info;?></td>
                                            <td><?php echo $view_withdraw->bank_ifsc;?></td>
                                            <td style="color:#006600;"><?php echo $view_withdraw->withdraw_status;?></td>
										</tr>
                                       <?php $i++; } ?> 
									<?php } ?>			
									</tbody>
															
                                </table>
                            </div>
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

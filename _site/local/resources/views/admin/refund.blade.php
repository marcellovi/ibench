<!DOCTYPE html>
<html lang="en">
  <head>
   
   @include('admin.title')
    
    @include('admin.style')
    @include('admin.table-style')
    
  </head>

  <body>
  @include('admin.top')

@include('admin.menu')
<?php $url = URL::to("/"); ?>





<div id="content">
  <div id="content-header">
    <div id="breadcrumb">  </div>
    <h1>Dispute Refund Request</h1>
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
      @if(Session::has('error'))
      <div class="alert alert-error">
              <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
              {{ Session::get('error') }}
              </div>
      @endif
      
      @if(Session::has('success'))

	           
        <div class="alert alert-success">
              <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
               {{ Session::get('success') }}
              </div>

	@endif



                 
<div class="widget-box">
           

          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Dispute Refund Request</h5>
          </div>
          
          
          <div class="widget-content nopadding">
         
            <table class="table table-bordered data-table" id="datatable-responsive">
              <thead>
                <tr>
                                           
                          
          
                        
                          <th>Sno</th>
						  <th>Purchase id</th>
                          <th>Request date</th>
                          <th>Order id</th>
                          <th>Product</th>
                          <th style="width:70px;">Payment date</th>
                          <th>Contact buyer</th>
                          <th>Contact vendor</th>
                          <th style="width:70px;">Amount</th>
                          <th style="width:100px;">Payment type</th>
                          <th style="width:100px;">Subject</th>
                          <th style="width:200px;">Comment or Reason</th>
                          <th style="width:300px;">Payment Action</th>
                          
                         </tr>
                         
                         
                         
                         
                         
                         
              </thead>
              <tbody>
             <?php 
					  $i=1;
					  foreach ($refund as $request) {
					  
					  $buyer_count = DB::table('users')
		                            ->where('id','=',$request->buyer_id)
					                ->count();
					  if(!empty($buyer_count))
					  {				
					  $buyer_details = DB::table('users')
		                            ->where('id','=',$request->buyer_id)
					                ->get();
					    $buyer_namer = $buyer_details[0]->name;
						$buyer_slug =  $buyer_details[0]->post_slug;
						
					  }
					  else
					  {
					     $buyer_namer = "";
						 $buyer_slug = "";
					  } 
					  
					  
					  
					  $vendor_count = DB::table('users')
		                            ->where('id','=',$request->vendor_id)
					                ->count();
					  if(!empty($vendor_count))
					  {				
					  $vendor_details = DB::table('users')
		                            ->where('id','=',$request->vendor_id)
					                ->get();
					    $vendor_namer = $vendor_details[0]->name;
						$vendor_slug =  $vendor_details[0]->post_slug;
					  }
					  else
					  {
					     $vendor_namer = "";
						 $vendor_slug = "";
					  } 
					  
					  
					  
					  
					  
					  $prod_count = DB::table('product')
		                            ->where('prod_id','=',$request->prod_id)
					                ->count();
					  
					  if(!empty($prod_count))
					  {				
					  $prod_details = DB::table('product')
		                            ->where('prod_id','=',$request->prod_id)
					                ->get();
					    $prod_namer = $prod_details[0]->prod_name;
						$prod_slug = $prod_details[0]->prod_slug;
					  }
					  else
					  {
					     $prod_namer = "";
						 $prod_slug = "";
					  } 
					  
					  
					 $commission_mode = $setts[0]->commission_mode;
		              $commission_amt = $setts[0]->commission_amt; 
					  
					  if($commission_mode=="percentage")
				   {
					   $commission_amount = ($commission_amt * $request->payment) / 100;
				   }
				   if($commission_mode=="fixed")
				   {
					    if($request->payment < $commission_amt)
						{
							$commission_amount = 0;
						}
						else
						{
							$commission_amount = $commission_amt;
						}
				   }
				   
				   
				   
				   
				   $vendor_commission = $request->payment - $commission_amount;
				   
				   $admin_commission = $commission_amount;
					   ?>
    
						
                        <tr>
                         
                        
						 <td><?php echo $i;?></td>
                         
                         
						
                         
                         
                         
                         
                          <td><?php echo $request->purchase_token;?></td>
                          <td><?php echo $request->request_date;?></td>
                          
                         <td><?php echo $request->order_id;?></td>
                         <td><a href="<?php echo $url;?>/product/<?php echo $request->prod_id;?>/<?php echo $prod_slug;?>" style="color:#009900; text-decoration:underline;" target="_blank"><?php echo $prod_namer;?></a></td>
                         <td><?php echo $request->payment_date;?></td>
                         <td><a href="<?php echo $url;?>/profile/<?php echo $request->buyer_id;?>/<?php echo $buyer_slug;?>" style="color:#0000FF; text-decoration:underline;" target="_blank"><?php echo $buyer_namer;?></a></td>
                          <td><a href="<?php echo $url;?>/profile/<?php echo $request->vendor_id;?>/<?php echo $vendor_slug;?>" style="color:#CC0066; text-decoration:underline;" target="_blank"><?php echo $vendor_namer;?></a></td>
                          <td><?php echo $setts[0]->site_currency.' '.$request->payment;?></td>
                          <td><?php echo $request->payment_type;?></td>
                          <td><?php echo $request->subject;?></td>
                          <td><?php echo $request->message;?></td>
						  
						  <td>
						  <?php if(empty($request->dispute_status)){?>
						   <?php if(config('global.demosite')=="yes"){?>
				  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-primary btndisable" style="margin-bottom:10px;">Release to vendor</a>
                  
                  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-primary btndisable" style="margin-bottom:10px;">Refund to buyer</a>  
				  <?php } else { ?>
				  <a href="<?php echo $url;?>/admin/refund/<?php echo $request->dispute_id;?>/<?php echo $request->order_id;?>/<?php echo $request->purchase_token;?>/<?php echo $admin_commission;?>/<?php echo $vendor_commission;?>" class="btn btn-primary" style="margin-bottom:10px;">Release to vendor</a>
                  <a href="<?php echo $url;?>/admin/refund/<?php echo $request->dispute_id;?>/<?php echo $request->order_id;?>/<?php echo $request->purchase_token;?>/<?php echo $request->payment;?>" class="btn btn-primary" style="margin-bottom:10px;">Refund to buyer</a>
				  <?php } ?>
                  
                  <?php } else { ?>
                  
                  <span style="color:#FF3300;"><?php echo $request->dispute_status;?></span>
                  <?php } ?>
				   
						  </td>
                        </tr>
                        <?php $i++;} ?>
                                
              </tbody>
            </table>
           
          </div>
          
        </div>
   
  
  
  
   
		 </div>
         </div>
         </div>
         
         
         </div>
         
         
         
  

    
	@include('admin.footer')
  </body>
</html>

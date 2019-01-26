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
	
		
        
        <div class="col-md-12 row"><div class="heading-title">@lang('languages.thank_you')</div></div>
                
       
        
        
        <div class="height20 clearfix"></div>
        
        <div class="table-responsive">
        
       
        <div class="text-center">   
	<div class="clearfix height30"></div>
	<div class="h4 black">
    <label class="black">@lang('languages.payment_thankyou')</label>
	</div>
    
    <?php if(!empty($cid)){?>
    <div class="h4 black">
    <label class="black">@lang('languages.your_order_id') : </label> <?php echo $cid;?>
	</div>
    <?php } ?>
    
	<div class="clear height20"></div>
    
    
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

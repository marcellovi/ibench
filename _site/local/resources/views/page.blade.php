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
				<li class='active'><?php echo $page_title;?></li>
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
            <?php if(!empty($page_cnt)){?>
				<div class="col-md-12 my-wishlist">
	
		
        <div class="row">
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;"><?php echo $page_title;?></div></div>
                <div class="col-md-6 text-right"></div>
        </div>
        
        
        <div class="height10 clearfix"></div>
        
        <div class="table-responsive">
        
        <?php if(!empty($show_photo)){?>
                                        <figure>
                                            
                                            
                                            <?php if(!empty($page_photo)){?>
                       
                       
                        	<img src="<?php echo $url;?>/local/images/media/<?php echo $page_photo;?>" alt="" class="page_banner"/>
                      
                       
                        <?php } ?>
                                        </figure>
                                        <?php } ?>
                                        
                                        
                                         <div class="height20 clearfix"></div>
                                        
                                        <div class="blog-detail marB30">
                                        
                                        
                                        <p><?php echo $page_desc;?>
                                        </p>
                                    </div>
        
        
        
        
	</div>
</div>			
 <?php } ?>








</div>
		</div>
		</div>
</div>


<div class="height30"></div>

@include('footer')
</body>
</html>

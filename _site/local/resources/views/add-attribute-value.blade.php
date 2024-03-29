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
<body class="cnt-home">
       @include('header')
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.add_attribute_value')</li>
			</ul>
		</div>
	</div>
</div>  
       
        <div class="body-content outer-top-xs">
	<div class="container-fluid">
            
                <div id="profileSetting" class="tabcontents">
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
        <div class="col-md-12 col-sm-12">                      
                        
        @if (count($errors) > 0)
	<div class="alert alert-danger">
		@lang('languages.some_problem')
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
            </ul> 
             </div>
        @endif
                              
        </div>     
        </div>   
                    
        <div class="row ">
		<div class="shopping-cart">
		<div class="shopping-cart-table ">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.add_attribute_value')</div></div>
                <div class="col-md-6 text-right"><a class="btn-upper btn btn-primary" href="<?php echo $url;?>/attribute-value">@lang('languages.view_my_attribute_value')</a></div>
                
                <div class="height20 clearfix"></div>
	<div class="table-responsive">
                    
                    <form class="register-form" role="form" method="POST" action="{{ route('add-attribute-value') }}" id="formID" enctype="multipart/form-data" accept-charset="utf-8">
                    {{ csrf_field() }}
                    
                        <div class="col-md-6 contact-form">
                            
                            <div class="billing-fields row">
                                <p class="form-row col-sm-12">
                                    <label for="product_name">@lang('languages.type')<abbr title="required" class="required">*</abbr></label>
                                  
                        <select name="attribute_type" id="attribute_type" class="form-control unicase-form-control validate[required]">
				<option value="">Select</option>
                        <?php if(!empty($type_count)){?>
                        <?php foreach($attribute_type as $type){?>
				<option value="<?php echo $type->attr_id;?>"><?php echo $type->attr_name;?></option>
				<?php } ?>
                            <?php } ?>
 			</select>
                        </p>
                               
                        <p class="form-row col-sm-12">
                            <label for="product_name">@lang('languages.value')<abbr title="required" class="required">*</abbr></label>
                                   
                            <input type="text" name="attribute_name" id="attribute_name" class="form-control unicase-form-control validate[required]">
                        </p>
                        
                        <p class="form-row col-sm-12">
                                 <?php if(config('global.demosite')=="yes"){?><button type="submit" class="custombtn">@lang('languages.submit')</button> 
					<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
						  
                            <button id="send" type="submit" class="btn-upper btn btn-primary">@lang('languages.submit')</button>
				<?php } ?>   
                        </p>   
                                
                        </div>
                            <!-- /.billing-fields -->
                        </div>
                    </form>
        
	</div>
</div>
</div>
        </div>
</div>
                
        </div>
        </div>
        <!--//================Account End ==============//-->  
        <div class="clear height30"></div>
        <!--//================Footer STARTS==============//-->  
         @include('footer')      
</body>
</html>
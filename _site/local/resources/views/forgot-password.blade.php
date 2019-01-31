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
				<li class='active'>@lang('languages.forgot_password')</li>
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
	
		
        
        <div class="col-md-12 row"><div class="heading-title">@lang('languages.forgot_password')</div></div>
                
       
        
        
        <div class="height50 clearfix"></div>
        <div class="col-md-2"></div>
        <div class="table-responsive col-md-8">
        
       
        <form class="register-form" role="form" method="POST" action="{{ route('forgot-password') }}" id="formID">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label para black">@lang('languages.email_address')</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control unicase-form-control validate[required,custom[email]] text-input radiusoff" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
<div class="height10 clearfix"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    @lang('languages.send')
                                </button>
                            </div>
                        </div>
                    </form>
        
        
        
	</div>
    <div class="col-md-2"></div>
    <div class="height50 clearfix"></div>
    
</div>			









</div>
		</div>
		</div>
</div>


<div class="height30"></div>

@include('footer')
</body>
</html>

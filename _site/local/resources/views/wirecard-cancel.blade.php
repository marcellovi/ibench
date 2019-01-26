<?php

	use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
		$headertype = $settings->header_type;
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
                <li class='active'>Wirecard Transaction Canceled</li>
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

                    <div class="col-md-12 row"><div class="heading-title">Your Wirecard Transaction has been canceled.</div></div>

                    <div class="height20 clearfix"></div>

                    <div class="table-responsive">

                        <div class="text-center">
                            <div class="clear height50"></div>
                            <div>
                                <h2>Your Wirecard Transaction has been canceled.</h2>
                                <p>Due to : @if(@isset($reason)) {{ $reason }} @endif </p>
                            </div>
                            <div class="clear height100"></div>




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
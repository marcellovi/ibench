<?php

use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$headertype = $settings->header_type;

header('Content-Type: text/html; charset=utf-8');
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
                <li class='active'>@lang('languages.payment_success')</li>
            </ul>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="container-fluid">
        <div class="my-wishlist-page">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @if(@isset($success))
                        <p class="alert alert-success">

                            {{ $success }}

                        </p>
                    @endif
                    @if(@isset($error))

                        <p class="alert alert-danger">
                            Ops! Ocorreu um erro na confirmacao do pagamento. <br>
                            Favor tentar novamente ou entre em contato conosco : ibench@ibench.com.br
                           <!-- {{ $error }} -->

                        </p>
                    @endif
                </div>
            </div>
            @if(@isset($wirecard_payment_token))

            <div class="row">
                <div class="col-md-12 my-wishlist">
                    <div class="col-md-12 row"><div class="heading-title">@lang('languages.payment_success')</div></div>
                    <div class="height20 clearfix"></div>

                    <div class="table-responsive">
                        <div class="text-center">
                            <div class="clear height50"></div>
                            <div>
                                <h2>@lang('languages.payment_thankyou')</h2>
                            </div>

                            <div class="col-md-12" align="center">
                                <h1 class="h3 black text-center">@lang('languages.payment_id') - {{ $wirecard_payment_token }}</h1>

                                @if(@isset($wirecard_boleto_href))
                                    <a href="{{ $wirecard_boleto_href }}" target="_blank" class="btn btn-success">View Boleto</a>
                                @endif
                                @if(@isset($wirecard_boleto_print_href))
                                    <a href="{{ $wirecard_boleto_print_href }}" target="_blank" class="btn btn-primary">Print Boleto</a>
                                @endif
                            </div>
                            <div class="clear height100"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>


<div class="height30"></div>

@include('footer')
</body>
</html>
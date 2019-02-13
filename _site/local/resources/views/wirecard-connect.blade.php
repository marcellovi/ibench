
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
<!-- Marcello Tiny Textarea -->
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=cae5v8ohqq2y45yurx2yqba3ng4rqukel679jhibsfg3gk4r"></script>
<script> tinymce.init({ selector:'textarea' });</script>
<body class="cnt-home">
@include('header')
<div class="breadcrumb">
    <div class="container-fluid">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
                <li class='active'>@lang('languages.connect_wirecard_title')</li>
            </ul>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="container-fluid">
        <div class="contact-page">

            <div class="row">
                <div class="col-md-12 col-sm-12">
                    @if(@isset($success))

                        <p class="alert alert-success">

                            {{ $success }}

                        </p>

                    @endif




                    @if(@isset($error))

                        <p class="alert alert-danger">

                            {{ $error }}

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


            <div class="col-md-12 row">
                <div class="heading-title">
                    @lang('languages.connect_wirecard_title')
                </div>
            </div>
            <div class="height10 clearfix"></div>
            <?php
            $disabled = "";
            if ($is_app_exists === true){
                $disabled = " disabled";
            }

            ?>

            <?php
            if ($is_app_exists === true){
                echo '<div class="alert alert-info">';
                echo "<strong>Detalhes da sua conta Wirecard</strong><hr/>";
                $wirecard_app_data = unserialize($wirecard_app_data);
                foreach ($wirecard_app_data as $key => $val) {
                    if (in_array($key,array('moipAccount','expires_in'))){
                    if (is_object($val)):
                        printf("%s : %s <br/>",$key,$val->id);
                    else:
                        printf("%s : %s <br/>",ucwords($key),$val);
                    endif;
                    }
                }
                echo "</div>";
            }

            ?>

            <h4>@lang('languages.connect_wirecard_info')</h4>
            <a href="<?=$auth_link;?>" class="btn btn-success btn-lg<?=$disabled;?>"<?=$disabled;?>>@lang('languages.connect_wirecard')</a>

        </div>
    </div>
</div>
</div>

<div class="height30"></div>

@include('footer')

</body>
</html>

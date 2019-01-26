
<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.title')
    @include('admin.style')
</head>
<body>
@include('admin.top')
<!--close-top-serch-->
<!--sidebar-menu-->
@include('admin.menu')
<div id="content">
    <div id="content-header">
        <div id="breadcrumb">  </div>
        <h1>Wirecard App</h1>
    </div>
    <div class="container-fluid"><hr>
        <div class="row-fluid">
            <div class="span12">
                @if(@isset($error))
                    <div class="alert alert-error">
                        <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                        {{ $error }}
                    </div>
                @endif

                @if(@isset($success))


                    <div class="alert alert-success">
                        <button class="close" data-dismiss="alert"><i class="icon-off"></i></button>
                        {{ $success }}
                    </div>

                @endif

                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                        <h5>Wirecard App</h5>
                    </div>
                    <div class="widget-content nopadding">
                        <?php
                        $url = URL::to("/");
                        $disabled = "";
                        if ($is_app_exists === true){
                            $disabled = " disabled";
                        }

                        ?>

                            <?php
                                if ($is_app_exists === true){
                                    echo '<div class="alert alert-success">';
                                    echo "<strong>Wirecard App Details</strong><hr/>";
                                    $wirecard_app_data = unserialize($wirecard_app_data);
                                    foreach ($wirecard_app_data as $key => $val) {
                                        printf("<strong>%s</strong> : %s <br/>",$key,$val);
                                }
                                echo "</div>";
                                }


                            ?>
                            @if(Session::has('raw'))
                                {{ Session::get('raw') }}

                                @endif
                            <form class="form-horizontal" method="post" action="{{ route('admin.wirecard-app') }}" enctype="multipart/form-data" name="basic_validate" id="formID" novalidate="novalidate">
                            {{ csrf_field() }}
                            <h4>Create Wirecard App</h4>
                            <div class="control-group">
                                <label class="control-label">Wirecard App Name</label>
                                <div class="controls">
                                    <input id="wirecard_name" type="text" name="wirecard_name" value="<?php echo $settings->site_name; ?>"  class="validate[required] span8">
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Wirecard App Desc</label>
                                <div class="controls">
                                    <textarea id="wirecard_desc" name="wirecard_desc" style="margin: 0px; width: 65.81196581196582%; height: 153px;"><?php echo $settings->site_desc; ?></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="span8">
                                    <a href="<?php echo $url;?>/admin/payment-settings" class="btn btn-primary">Cancel</a>
                                    <?php if(config('global.demosite')=="yes"){?><button type="button" class="btn btn-success btndisable">Submit</button>
                                    <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
                                    <button id="send" type="submit" class="btn btn-success<?=$disabled;?>"<?=$disabled;?>>Submit</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('admin.footer')
</body>
</html>

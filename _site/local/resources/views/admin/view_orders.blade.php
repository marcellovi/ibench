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
                <h1>@lang('languages.order_details')</h1>
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

                        <div align="left">

                            <?php if (config('global.demosite') == "yes") { ?>
                                <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span> <a href="#" class="btn btn-primary btndisable">Back</a> 
                            <?php } else { ?>
                                <a href="<?php echo $url; ?>/admin/orders" class="btn btn-primary">@lang('languages.back')</a>
                            <?php } ?>
                        </div>
                        
                        <div class="widget-box">
                            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                                <h5>@lang('languages.order_details')</h5>
                            </div>

                            <div class="widget-content nopadding">
                                <table class="table table-bordered data-table" id="datatable-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sno</th>
                                            <th>@lang('languages.purchase_id')</th>
                                            <th>@lang('languages.order_id')</th>
                                            <th>@lang('languages.buyer_name')</th>
                                            <th>@lang('languages.vendors')</th>
                                            <th>@lang('languages.products')</th>
                                            <th>@lang('languages.quantity') & @lang('languages.attributes')</th>
                                            <th>@lang('languages.shipping_price')</th>
                                            <th>@lang('languages.subtotal')<br/>(@lang('languages.price') x @lang('languages.quantity'))</th>
                                            <th>@lang('languages.total')</th>
                                            <th>@lang('languages.vendor_amount')</th>
                                            <th>@lang('languages.admin_amount')</th>
                                            <th>@lang('languages.approved')?</th>
                                            <th>@lang('languages.payment_status')</th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($productt_count)) {
                                            $i = 1;
                                            foreach ($productt as $product) {
                                        ?>
                                        <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $product->purchase_token; ?></td>
                                        <td><?php echo $product->ord_id; ?></td>
        <?php
        $user_count = DB::table('users')
                        ->where('id', '=', $product->user_id)
                        ->count();
                if (!empty($user_count)) {
                    $user = DB::table('users')
                            ->where('id', '=', $product->user_id)
                            ->get();
                    $username = $user[0]->name;
                    $email = $user[0]->email;
                    $phone = $user[0]->phone;
                } else {
                    $username = "";
                    $email = "";
                    $phone = "";
                }

                $products_count = DB::table('product')
                        ->where('prod_id', '=', $product->prod_id)
                        ->count();
                if (!empty($products_count)) {
                    $products = DB::table('product')
                            ->where('prod_id', '=', $product->prod_id)
                            ->get();
                    $prod_names = $products[0]->prod_name;
                } else {
                    $prod_names = "";
                }

                $cats = explode(",", $product->prod_attribute);
                $value_namer = "";

                foreach ($cats as $cat) {

                    $prod_value_count = DB::table('product_attribute_value')
                            ->where('value_id', '=', $cat)
                            ->count();
                    $prod_value = DB::table('product_attribute_value')
                            ->where('value_id', '=', $cat)
                            ->get();
                    if (!empty($prod_value_count)) {
                        ?>

                <?php
                $prod_type = DB::table('product_attribute_type')
                        ->where('attr_id', '=', $prod_value[0]->attr_id)
                        ->get();

                $value_namer .= '<b class="black">' . $prod_type[0]->attr_name . '</b> - ' . $prod_value[0]->attr_value . ', ';
            }
            ?>

            <?php
        }

        $attri_name = rtrim($value_namer, ', ');
        $prod_user_id = $product->prod_user_id;

        $product_vendor = DB::table('users')
                ->where('id', '=', $prod_user_id)
                ->count();
        
        if (!empty($product_vendor)) {
            $get_vendor = DB::table('users')
                    ->where('id', '=', $prod_user_id)
                    ->get();
            $vendor_name = $get_vendor[0]->name;
        } else {
            $vendor_name = "";
        }
        ?>
            <td><?php echo utf8_decode($username); ?></td>
            <td><?php echo utf8_decode($vendor_name); ?></td>
            <td><?php echo utf8_decode($prod_names); ?>
                        
            <?php
                if (!empty($products_count)) {
                    if ($products[0]->prod_type == "digital") {
            ?>
            <br/>
                <span style="color:#FF0000;">Download File:</span> <a style="color:#0033CC;" href="<?php echo $url; ?>/local/images/media/<?php echo $products[0]->prod_zipfile; ?>" download><?php echo $products[0]->prod_zipfile; ?></a>
            <?php
                    }
                }
            ?>
            </td>
            
            <td>
                <strong>Qty:</strong> <?php echo $product->quantity; ?><br/>

                        <?php if (!empty($product->prod_attribute)) { ?>
                            <?php echo utf8_decode($attri_name); ?>
                        <?php } ?>
                    </td>

            <td>
            <?php echo $setts[0]->site_currency . ' ' . number_format($product->shipping_price, 2); ?>
            </td>

            <td>
            <?php echo $setts[0]->site_currency . ' ' . number_format($product->subtotal, 2); ?>
            </td>

            <td>
            <?php echo $setts[0]->site_currency . ' ' . number_format($product->total, 2); ?>
            </td>

        <?php
            $commission_mode = $setts[0]->commission_mode;
            $commission_amt = $setts[0]->commission_amt;

                if ($commission_mode == "percentage") {
                    $commission_amount = ($commission_amt * $product->total) / 100;
                }
                if ($commission_mode == "fixed") {
                    if ($product->total < $commission_amt) {
                        $commission_amount = 0;
                    } else {
                        $commission_amount = $commission_amt;
                    }
                }

            $vendor_commission = $product->total - $commission_amount;
            $admin_commission = $commission_amount;
        ?>

        <td><?php echo $setts[0]->site_currency . ' ' . number_format($vendor_commission, 2); ?></td>
        <td><?php echo $setts[0]->site_currency . ' ' . number_format($admin_commission, 2); ?></td>
        <td>N/A
            <!-- Marcello : Temporariamente Comentado 
                    <?php
                    $get_checker = DB::table('product_refund')
                            ->where('order_id', '=', $product->ord_id)
                            ->where('purchase_token', '=', $product->purchase_token)
                            ->count();
                    if (empty($get_checker)) {
                        ?>

                        <?php if (config('global.demosite') == "yes") { ?>
                            <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
                        <?php } else { ?>
                            <?php if ($product->payment_status == "completed" && $product->order_status == "completed") { ?><a href="<?php echo $url; ?>/admin/orders/<?php echo $product->ord_id; ?>/<?php echo $product->purchase_token; ?>/<?php echo $admin_commission; ?>/<?php echo $vendor_commission; ?>" class="btn btn-success">Waiting For Approval</a><?php } ?>
                        <?php } ?>
                        <?php if ($product->order_status != "pending" && $product->order_status != "completed") { ?> <span style="color:#FF3300;"><?php echo $product->order_status; ?></span>
                                    <?php } ?>

                                    <?php
                                } else {
                                    $get_checker_view = DB::table('product_refund')
                                            ->where('order_id', '=', $product->ord_id)
                                            ->where('purchase_token', '=', $product->purchase_token)
                                            ->get();
                                    ?>
                                    <span style="color:#FF3300;"><?php
                                        if ($get_checker_view[0]->dispute_status == "") {
                                            echo "Buyer refund for payment request";
                                        } else {
                                            echo $get_checker_view[0]->dispute_status;
                                        }
                                        ?></span>
        <?php } ?>
                
                        Fim do Comentario -->
        </td>

                <?php
                if ($product->payment_status == "") {
                    $pne_status = "pending";
                    $clr = "red";
                } else {
                    $pne_status = $product->payment_status;
                    $clr = "green";
                }
                ?>
        <td style="color:<?php echo $clr; ?>"><?php echo $pne_status; ?></td>

                <?php /* ?><td>

                  <?php if(config('global.demosite')=="yes"){?>
                  <a href="#" class="<?php echo $btn;?> btndisable"><?php echo $text;?></a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
                  <?php } else { ?>

                  <a href="<?php echo $url;?>/admin/product/action/{{ $product->prod_id }}/{{ $sid }}/{{ $product->user_id }}" class="<?php echo $btn;?>"><?php echo $text;?></a>

                  <?php } ?>

                  </td><?php */ ?>

        </tr>
            <?php
                $i++;
                }
            }
            ?>

            </tbody>
                </table>
                </div>
                </div>
                </div>

                    <div class="span12">

                        <?php
                        if (!empty($product_check)) {

                            $gets_userid = $product_wn[0]->user_id;

                            $bill_ship = DB::table('product_checkout')
                                    ->where('purchase_token', '=', $purchase_token)
                                    ->get();
                            ?>

                            <div style="float:left; width:50%;" class="form-horizontal">
                                <h3>Billing Details</h3>

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">First Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                            <?php echo utf8_decode($bill_ship[0]->bill_firstname); ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Last Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                <?php echo utf8_decode($bill_ship[0]->bill_lastname); ?>
                                    </div>
                                </div>              

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Company Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo utf8_decode($bill_ship[0]->bill_companyname); ?>
                                    </div>
                                </div>         

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Billing Email : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo $bill_ship[0]->bill_email; ?>
                                    </div>
                                </div>         

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Billing Phone : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo $bill_ship[0]->bill_phone; ?>
                                    </div>
                                </div> 

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Country : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo utf8_decode($bill_ship[0]->bill_country); ?>
                                    </div>
                                </div>  

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Address : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo utf8_decode($bill_ship[0]->bill_address); ?>
                                    </div>
                                </div>      

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">City : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo utf8_decode($bill_ship[0]->bill_city); ?>
                                    </div>
                                </div> 

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">State : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo utf8_decode($bill_ship[0]->bill_state); ?>
                                    </div>
                                </div>    

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Postcode : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php echo $bill_ship[0]->bill_postcode; ?>
                                    </div>
                                </div> 
                            </div>

                            <div style="float:left; width:50%;" class="form-horizontal">
                                <h3>Detalhes do Frete</h3>

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">First Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_firstname)) { ?><?php echo utf8_decode($bill_ship[0]->ship_firstname); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Last Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_lastname)) { ?><?php echo utf8_decode($bill_ship[0]->ship_lastname); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>              

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Company Name : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_companyname)) { ?><?php echo utf8_decode($bill_ship[0]->ship_companyname); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>            

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Shipping Email : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_email)) { ?><?php echo $bill_ship[0]->ship_email; ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>         

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Shipping Phone : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_phone)) { ?><?php echo $bill_ship[0]->ship_phone; ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div> 

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Country : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_country)) { ?><?php echo utf8_decode($bill_ship[0]->ship_country); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>  


                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Address : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_address)) { ?><?php echo utf8_decode($bill_ship[0]->ship_address); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>       

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">City : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_city)) { ?> <?php echo utf8_decode($bill_ship[0]->ship_city); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div> 

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">State : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_state)) { ?><?php echo utf8_decode($bill_ship[0]->ship_state); ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>   

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Postcode : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->ship_postcode)) { ?><?php echo $bill_ship[0]->ship_postcode; ?><?php } else { ?>-<?php } ?>
                                    </div>
                                </div>          

                            </div>

                            <div style="clear:both; height:10px;"></div>
                            <div style="width:100%; float:left;" class="form-horizontal">

                                <div class="control-group">
                                    <label class="control-label" style="text-align:left; width:150px;">Other Notes : </label>
                                    <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                        <?php if (!empty($bill_ship[0]->other_notes)) { ?> <?php echo utf8_decode($bill_ship[0]->other_notes); ?> <?php } else { ?>-<?php } ?>
                                    </div>
                                </div>
                            </div>

    <?php if ($bill_ship[0]->payment_type == "cash-on-delivery" && $bill_ship[0]->payment_status == "pending") { ?>

                        <div style="clear:both; height:10px;"></div>

        <?php /* ?><div style="width:100%; float:left;" class="form-horizontal">

                                              <div class="control-group">
                                              <label class="control-label" style="text-align:left; width:150px;">Click to change payment status : </label>
                                              <div class="controls" style="padding-left:0px; margin-top:5px; margin-left:0px;">
                                              <a href="<?php echo $url;?>/admin/view_orders/<?php echo $bill_ship[0]->purchase_token;?>/status" style="color:#0000FF; text-decoration:underline;">click to completed</a>

                                              </div>

                                              </div>
                                              </div><?php */ ?>

    <?php } ?>

    <?php } ?>

                    </div>
                </div>
            </div>
        </div>
        @include('admin.footer')
    </body>
</html>
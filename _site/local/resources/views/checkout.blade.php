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
<body class="cnt-home">




    @include('header')


<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.checkout')</li>
			</ul>
		</div>
	</div>
</div>



<div class="body-content">
	<div class="container-fluid">
    <div class="contact-page">

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
    <?php
    $nameFull = explode(' ',Auth::user()->full_name);
    $sum = count($nameFull);
    ?>
    
    <form autocomplete="nope" method="POST" action="{{ route('payment-details') }}" id="formID" class="form-chackout" enctype="multipart/form-data">
                {{ csrf_field() }}

		<div class="row">

				 <div class="col-md-12">
        <div class="col-md-12"><div class="heading-title">@lang('languages.checkout')</div></div>

        </div>

        <div class="height20 clearfix"></div>



				<div class="col-md-6 contact-form">
	<div class="col-md-12 contact-title">
		<h3 class="marB20 marT20 fontsize20">@lang('languages.billing_details')</h3>
	</div>

    <div class="clearfix height20"></div>

	<div class="col-md-6 ">

			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.first_name') <span>*</span></label>

            <!--<input type="text" name="bill_firstname" autocomplete="nope" id="bill_firstname" class="form-control unicase-form-control validate[required]" value="<?php //echo utf8_decode($nameFull[0]); ?>">-->
            <input type="text" name="bill_firstname" autocomplete="nope" id="bill_firstname" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputEmail1">@lang('languages.last_name') <span>*</span></label>

            <!--<input type="text" name="bill_lastname" autocomplete="nope" id="bill_lastname" class="form-control unicase-form-control validate[required]" value="<?php //if($sum>1){echo utf8_decode($nameFull[1]); }?>">--> <!-- Marcello {{@$nameFull[1]}} -->

            <input type="text" name="bill_lastname" autocomplete="nope" id="bill_lastname" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputTitle">@lang('languages.company_name') <span>(opcional)</span></label>
        <!--<input type="text" name="bill_companyname" autocomplete="nope" id="bill_companyname" class="form-control unicase-form-control" value="{{Auth::user()->name_business}}">-->
        <input type="text" name="bill_companyname" autocomplete="nope" id="bill_companyname" class="form-control unicase-form-control" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.email_address') <span>*</span></label>
        <!--<input type="text" name="bill_email" autocomplete="nope" id="bill_email" class="form-control unicase-form-control validate[required]" value="{{Auth::user()->email}}">-->
        <input type="text" name="bill_email" autocomplete="nope" id="bill_email" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>


    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.phone') <span>*</span></label>

        <!--<input type="text" name="bill_phone" autocomplete="nope" id="bill_phone" class="form-control unicase-form-control validate[required]" value="{{Auth::user()->phone}}">-->
        <input type="text" name="bill_phone" autocomplete="nope" id="bill_phone" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>





     <?php /*?><div class="col-md-6">
		
			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">Country <span>*</span></label>
		                
            
            
            <select name="bill_country" class="form-control unicase-form-control validate[required]">
							  
							  <option value="">Select Country</option>
							  <?php foreach($countries as $country){?>
                              <option value="<?php echo $country;?>" <?php if(!empty($login_user_count)){?><?php if($login_user[0]->bill_country==$country){?> selected <?php } ?><?php } ?>><?php echo $country;?></option>
                              <?php } ?>
							</select>
		  </div>
		
	</div><?php */?>



    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.address') <span>*</span></label>
        <!--<input type="text" name="bill_address" autocomplete="nope" id="bill_address" placeholder="@lang('languages.address')" class="form-control unicase-form-control validate[required]" value="{{Auth::user()->address}}">-->
        <input type="text" name="bill_address" autocomplete="nope" id="bill_address" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>



    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.town_city') <span>*</span></label>

                    <input type="text" name="bill_city" autocomplete="nope" id="bill_city" class="form-control unicase-form-control validate[required]" value="Rio de Janeiro" readonly>

		  </div>

	</div>






    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.state') <span>*</span></label>

                    <input type="text" placeholder="@lang('languages.state')" name="bill_state" autocomplete="nope" class="form-control unicase-form-control validate[required]" value="Rio de Janeiro" readonly>

		  </div>

	</div>



    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.postcode')  <span>*</span></label>


             <!--<input type="text" name="bill_postcode" autocomplete="nope" id="bill_postcode" placeholder="@lang('languages.postcode')" class="form-control unicase-form-control validate[required]" value="">-->
             <input type="text" name="bill_postcode" autocomplete="nope" id="bill_postcode" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>


    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.country') <span>*</span></label>



            <select name="bill_country" class="form-control unicase-form-control validate[required]" style="height: 40px;">

							  <!-- <option value="">@lang('languages.country')</option> -->
							  <option value="Brasil">Brasil</option>
							  <!-- Marcello :: Somente um pais 
							  <?php //foreach($countries as $country){?>
                              <option value="<?php //echo $country;?>"><?php //echo $country;?></option>
                              <?php //} ?>
                              -->
							</select>
		  </div>

	</div>





</div>















<div class="col-md-6 contact-form">
	<div class="col-md-12">
    <div class="form-group">
		<h3 class="info-title fontsize20" for="exampleInputName"> @lang('languages.different_shipping') <input type="checkbox" value="1" name="enable_ship" class=" unicase-form-control enable_ship" id="enable_ship"   style="margin-left:5px;top:8px;" onChange="valueChanged()"></h3>
        </span>
	</div>
    </div>

    <div class="clearfix height20"></div>

    <div class="ship_details" style="display:none;">

	<div class="col-md-6 ">



			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.first_name') <span>*</span></label>


            <input type="text" name="ship_firstname" autocomplete="nope" id="ship_firstname" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputEmail1">@lang('languages.last_name') <span>*</span></label>


             <input type="text" name="ship_lastname" autocomplete="nope" id="ship_lastname" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputTitle">@lang('languages.company_name') <span>(opcional)</span></label>


            <input type="text" name="ship_companyname" autocomplete="nope" id="ship_companyname" class="form-control unicase-form-control" value="">
		  </div>

	</div>
	<div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.email_address') <span>*</span></label>


            <input type="text" name="ship_email" autocomplete="nope" id="ship_email" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>



    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.phone') <span>*</span></label>

            <input type="text" name="ship_phone" autocomplete="nope" id="ship_phone" class="form-control unicase-form-control validate[required]" value="">
		  </div>

	</div>








    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.address') <span>*</span></label>



            <input type="text" name="ship_address" autocomplete="nope" id="ship_address" placeholder="@lang('languages.address')" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>




    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.town_city') <span>*</span></label>

                        <input type="text" name="ship_city" autocomplete="nope" id="ship_city" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>




    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.state') <span>*</span></label>


                 <input type="text" placeholder="@lang('languages.state')" name="ship_state" autocomplete="nope" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>



    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.postcode') <span>*</span></label>



                 <input type="text" name="ship_postcode" autocomplete="nope" id="ship_postcode" placeholder="@lang('languages.postcode')" class="form-control unicase-form-control validate[required]" value="">

		  </div>

	</div>


    <div class="col-md-6">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.country') <span>*</span></label>


            <select name="ship_country" class="form-control unicase-form-control validate[required]">

							  <option value="">@lang('languages.country')</option>
							  <option value="Brasil">Brasil</option>
							  <!-- Marcello :: Somente o Brasil 
							  <?php foreach($countries as $country){?>
                              <option value="<?php echo $country;?>"><?php echo $country;?></option>
                              <?php } ?>
                              -->
							</select>
		  </div>

	</div>


     </div>


    <div class="col-md-12">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.order_notes') <span>(opcional)</span></label>

                    <!-- Marcello 
                 <textarea cols="10" rows="5" placeholder="@lang('languages.title_order_notes')" id="order_comments" class="form-control unicase-form-control validate[required]" name="order_comments"></textarea>
                    -->
                  <textarea cols="10" rows="5" placeholder="@lang('languages.title_order_notes')" id="order_comments" class="form-control unicase-form-control" name="order_comments">Especifique todas as informa&ccedil;&otilde;es necess&aacute;rias para a correta emiss&atilde;o da nota fiscal.</textarea>


		  </div>

	</div>











</div>







			</div>




            <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-6"></div>

                                <div class="col-md-6 col-sm-6 col-xs-6 padT50 pull-right payment-method">
                                    <div class="">
                                        <div class="col-md-12 marB30 padddingoff">

                                            <div class="clearfix height10"></div>

                                            <div class="order-data ashbg text-left pad15">
                                                <div class="row">
                                                    <span class="col-md-9 col-sm-9 col-xs-6 fontsize17 text-left">@lang('languages.subtotal')</span> <span class="col-md-3 col-sm-3 col-xs-6 text-right newfonts black"><?php echo $setts[0]->site_currency.' '.number_format($cart_total,2,",",".").' ';?></span>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>


                                            <div class="clearfix height10"></div>

                                            <div class="order-data ashbg text-left pad15">
                                                <div class="row">
                                                    <span class="col-md-9 col-sm-9 col-xs-6 fontsize17 text-left">@lang('languages.shipping_charge')</span> <span class="col-md-3 col-sm-3 col-xs-6 newfonts text-right black">
                                                        <?php                                                         
                                                        echo $setts[0]->site_currency.' '.number_format($ship_price,2,",",".").' '; ?></span>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>

                                             <div class="clearfix height10"></div>

                                             <!-- Marcello Process Fee
                                            <div class="order-data ashbg text-left pad15">
                                                <div class="row">
                                                    <span class="col-md-9 col-sm-9 col-xs-6  fontsize17 text-left">@lang('languages.processing_fee')</span> <span class="col-md-3 col-sm-3 col-xs-6 newfonts text-right black"><?php echo $setts[0]->site_currency.' '.number_format($processing_fee,2,",",".").' ';?></span>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                
                                             -->
                                             <!--
                                            <?php $total = $cart_total + $ship_price + $processing_fee; ?>
                                          -->
                                                                                
                                          <?php                                            
                                                $total = $cart_total + $ship_price + $processing_fee;                                     
?>                                          
                                            <div class="clearfix height10"></div>
                                            <div class="order-data ashbg text-left pad15">
                                                <div class="row">
                                                    <span class="col-md-9 col-sm-9 col-xs-6 fontsize17 text-left">@lang('languages.total_order')</span> <span class="col-md-3 col-sm-3 col-xs-6 text-right newfonts black"><?php echo $setts[0]->site_currency.' '.number_format($total,2,",",".").' ';?></span>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="order_id" value="<?php echo $order_ids;?>">
                                        <input type="hidden" name="sub_total" value="<?php echo $cart_total;?>">
                                        <input type="hidden" name="shipping_fee" value="<?php echo $ship_price;?>">
                                        <input type="hidden" name="shipping_fee_separate" value="<?php echo $ship_separate;?>">
                                        <input type="hidden" name="processing_fee" value="<?php echo $processing_fee;?>">
                                        <input type="hidden" name="total" value="<?php echo $total;?>">


                                        <input type="hidden" name="product_names" value="<?php echo utf8_decode($product_names);?>">
                                        <div class="col-md-12 marB20">
                                            <div class="order-data text-left padTB20">
                                               <!-- <h3 class="text-left">@lang('languages.select_payment_method')</h3> -->
                                                <h3 class="text-left">Confirme para finalizar suas compras</h3>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <?php
						$option = explode (",", $setts[0]->payment_option);
					?>
                                        <?php
					$i=1;
					foreach($option as $withdraw){?>
                                        <div class="form-row col-sm-6 marB30">

                                            <input type="radio" id="method<?php echo $i;?>" name="payment_type" class="validate[required]" value="<?php echo $withdraw;?>">
                                            <label for="method<?php echo $i;?>" class="radio-label fontsize16">
                                                <?php if($withdraw=="wirecard"){?>WireCard<?php } ?>
                                                    <?php if($withdraw=="localbank"){?>Bank transfer<?php } ?>
                                                        <?php if($withdraw=="paypal"){?>Paypal<?php } ?>
                                                            <?php if($withdraw=="stripe"){?>Stripe<?php } ?>
                                                                <?php if($withdraw=="cash-on-delivery"){?>Pagamento na Entrega<?php } ?>
                                                                    <?php if($withdraw=="payhere"){?>Payhere<?php } ?>
                                                                        <?php if($withdraw=="ccavenue"){?>Ccavenue<?php } ?>
                                                                            <?php if($withdraw=="razorpay"){?>Razorpay<?php } ?>
                                                                                <?php if($withdraw=="paytm"){?>Paytm<?php } ?>
                                                                                    <?php if(Auth::user()->earning >= $total) {?>
                                                                                        <?php if($withdraw=="wallet-balance"){?>Wallet Balance ( <?php echo $setts[0]->site_currency.' '.number_format(Auth::user()->earning,2);?> )<?php } ?>
                                                                                            <?php } ?></label>
                                            <span class="check"><span class="inside"></span></span>

                                        </div>
                                         <?php $i++; } ?>

                                        <div class="col-md-12 col-sm-12">



                                         <?php if(config('global.demosite')=="yes"){?><button type="submit" class="btn-upper btn btn-primary">@lang('languages.place_order')</button>
								<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>

                            <?php if($check_qty_ord == 1){ ?> 
															<a href="<?php echo $url;?>/cart" class="btn-upper btn btn-primary"><i class="icon fa fa-shopping-cart"></i> &nbsp; Voltar ao Carrinho</a>
                        		<?php }else{ ?>
															<button id="send" type="submit" class="btn-upper btn btn-primary">@lang('languages.place_order')</button>
															<?php } ?>
                    				<?php } ?>
                            


							


                                        </div>


                                    </div>
                                </div>
                            </div>



            </form>




         <!--<div class="col-md-12 outer-bottom-small m-t-20">
		<button type="submit" class="btn-upper btn btn-primary checkout-page-button">Send Message</button>
	</div>  -->

		</div>


</div>

<div class="height30"></div>
 @include('footer')

 </body>
</html>
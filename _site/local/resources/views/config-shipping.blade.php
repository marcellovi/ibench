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
                            <li><a href="<?php echo $url;?>/dashboard">@lang('languages.home')</a></li>
                                <li><a href="<?php echo $url;?>/config-shipping">Configurar Frete</a></li>
				<!--  <li class='active'>@lang('languages.my_dashboard') </li> Marcello -->
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
     

    <div class="height10 clearfix"></div>

        @if(@Auth::user()->admin == 2)
        <div class="control-group">
            <a href="{{ $url }}/dashboard" class="btn btn-primary"> Voltar </a>

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
        @endif


    <form class="register-form" role="form" method="POST" action="{{ route('dashboard') }}" id="formID" enctype="multipart/form-data" accept-charset="utf-8">
        {{ csrf_field() }}

    <div class="tab-content" style="padding-left:0">
    <div class="tab-pane active m-t-20" id="profileSetting">

    <div class="height20 clearfix"></div>

    <div class="col-md-6 contact-form">

        <div class="col-md-12">
            <div class="form-group">

            <label class="info-title" for="exampleInputName">Acre</label>
            <input type="text" placeholder="Acre" name="Acre" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->name;?>">
            </div>
        </div>

        <div class="col-md-12">
        <div class="form-group">

            <label class="info-title" for="exampleInputName">Alagoas</label>
            <input type="text" placeholder="Alagoas" class="form-control unicase-form-control" name="Alagoas" value="<?php echo $editprofile[0]->phone;?>">

        </div>
        </div>
                
        
        <div class="col-md-12">
            <div class="form-group">
                <label class="info-title" for="exampleInputName">Amapá</label>
                <input type="text" placeholder="Amapa" name="Amapa" value="" class="form-control unicase-form-control">
            </div>
        </div>

        <div class="col-md-12">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputName">Amazonas</label

                        <input type="text" placeholder="Amazonas" name="Amazonas" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->name_business); ?>">
                    </div>
        </div>

        <div class="col-md-12">
                <div class="form-group">

                    <label class="info-title" for="exampleInputName">Bahia</label>
                    <input type="text" placeholder="Bahia" name="Bahia" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->cpf_cnpj; ?>" disabled="true">
                </div>
            </div>

        <div class="col-md-12">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputName">Ceará</label>
                        <input type="text" placeholder="Ceara" name="Ceara" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->local_shipping_price; ?>">
                    </div>
        </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputName">Distrito Federal</label>

                        <input type="text" placeholder="DistritoFederal" name="DistritoFederal" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->email; ?>" >

                    </div>
                </div>
                               
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">Espírito Santo</label>
                            <input type="text" placeholder="EspiritoSanto" name="EspiritoSanto" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->min_value; ?>">

                        </div>
                </div>


    </div>

    <div class="col-md-6 contact-form">


        <div class="col-md-12">
            <div class="form-group">
                <label class="info-title" for="exampleInputName">Goiás</label>
                <input type="text" placeholder="Goias" name="Goias" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->full_name); ?>" >
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">

                <label class="info-title" for="exampleInputName">Maranhão</label>
                <input type="text" placeholder="Maranhao" name="Maranhao" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->address); ?>">
            </div>
        </div>



            <div class="col-md-12">
                <div class="form-group">

 
                    <label class="info-title" for="exampleInputName">Mato Grosso</label>
                    <input type="text" placeholder="Mato Grosso" name="MatoGrosso" class="form-control unicase-form-control" >
                </div>
            </div>

     

            <div class="col-md-12">
                <div class="form-group">

                    <label class="info-title" for="exampleInputName">Mato Grosso do Sul</label>
                    <input type="text" placeholder="Mato Grosso do Sul" name="MatoGrossoSul" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_address; ?><?php } ?>">

                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">

                    <label class="info-title" for="exampleInputName">Minas Gerais</label>
                    <input type="text" placeholder="MG" name="MG" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_address; ?><?php } ?>">

                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">Pará</label>
                            <input type="text" placeholder="PA" name="PA" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_address; ?><?php } ?>">
                        </div>
                    </div>

                    
                    
                </div>
            </div>
        </div>
    </div>

            <div class="tab-pane m-t-20" id="billingDetail" style="display:none;">
                <div class="height20 clearfix"></div>

                <div class="col-md-6 contact-form">

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.first_name') </label>
                            <input type="text" placeholder="First Name" name="bill_firstname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_firstname; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.last_name') </label>
                            <input type="text" placeholder="Last Name" name="bill_lastname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_lastname; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.company_name') </label>
                            <input type="text" placeholder="Company Name" name="bill_companyname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_companyname; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.email') </label>
                            <input type="text" placeholder="Email" name="bill_email" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_email; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.phone')</label>
                            <input type="text" placeholder="Phone" name="bill_phone" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_phone; ?><?php } ?>">
                        </div>
                    </div>
                    
                </div>
                <div class="col-md-6 contact-form">

               <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.address') </label>
                            <input type="text" placeholder="@lang('languages.address')" name="bill_address" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_address; ?><?php } ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.address') </label>
                            <input type="text" placeholder="@lang('languages.address')" name="bill_address" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_address; ?><?php } ?>">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.city') </label>
                            <input type="text" placeholder="City" name="bill_city" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_city; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.state') </label>
                            <input type="text" placeholder="State" name="bill_state" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_state; ?><?php } ?>">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">@lang('languages.postcode') </label>
                            <input type="text" placeholder="Postcode" name="bill_postcode" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->bill_postcode; ?><?php } ?>">
                        </div>
                    </div>

                </div>
            </div>

    <div class="tab-pane m-t-20" id="shippingDetail" style="display:none;">
    <div class="height20 clearfix"></div>

         <div class="col-md-6 contact-form">
             <div class="col-md-12">
                 <div class="form-group">
                     <label class="info-title" for="exampleInputName">@lang('languages.first_name') </label>
                     <input type="text" placeholder="First Name" name="ship_firstname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_firstname; ?><?php } ?>">
                 </div>
             </div>


             <div class="col-md-12">
                 <div class="form-group">
                     <label class="info-title" for="exampleInputName">@lang('languages.last_name') </label>
                     <input type="text" placeholder="Last Name" name="ship_lastname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_lastname; ?><?php } ?>">
                 </div>
             </div>

             <div class="col-md-12">
                 <div class="form-group">
                     <label class="info-title" for="exampleInputName">@lang('languages.company_name') </label>
                     <input type="text" placeholder="Company Name" name="ship_companyname" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_companyname; ?><?php } ?>">
                 </div>
             </div>

             <div class="col-md-12">
                 <div class="form-group">
                     <label class="info-title" for="exampleInputName">@lang('languages.email') </label>
                     <input type="text" placeholder="Email" name="ship_email" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_email; ?><?php } ?>">
                 </div>
             </div>
             
             <div class="col-md-12">
                 <div class="form-group">
                     <label class="info-title" for="exampleInputName">@lang('languages.phone') </label>
                     <input type="text" placeholder="Phone" name="ship_phone" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_phone; ?><?php } ?>">
                 </div>
             </div>

          </div>
          <div class="col-md-6 contact-form">

              <div class="col-md-12">
                  <div class="form-group">
                      <label class="info-title" for="exampleInputName">@lang('languages.address') </label>
                      <input type="text" placeholder="@lang('languages.address')" name="ship_address" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_address; ?><?php } ?>">
                  </div>
              </div>
       
              <div class="col-md-12">
                  <div class="form-group">
                      <label class="info-title" for="exampleInputName">@lang('languages.address') </label>
                      <input type="text" placeholder="@lang('languages.address')" name="ship_address" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_address; ?><?php } ?>">
                  </div>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      <label class="info-title" for="exampleInputName">@lang('languages.city') </label>
                      <input type="text" placeholder="City" name="ship_city" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_city; ?><?php } ?>">
                  </div>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      <label class="info-title" for="exampleInputName">@lang('languages.state') </label>
                      <input type="text" placeholder="State" name="ship_state" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_state; ?><?php } ?>">
                  </div>
              </div>

              <div class="col-md-12">
                  <div class="form-group">
                      <label class="info-title" for="exampleInputName">@lang('languages.postcode') </label>
                      <input type="text" placeholder="Postcode" name="ship_postcode" class="form-control unicase-form-control" value="<?php if (!empty($edited_count)) { ?><?php echo $edited[0]->ship_postcode; ?><?php } ?>">

                      <input type="hidden" name="savepassword" value="<?php echo $editprofile[0]->password; ?>">

                  </div>
              </div>

            <input type="hidden" name="id" value="<?php echo $editprofile[0]->id; ?>">
            <input type="hidden" name="enable_ship" value="1">

          </div>
	</div>
    </div>

        <div class="col-md-12 outer-bottom-small m-t-20">
            <?php if (config('global.demosite') == "yes") { ?><button type="submit" class="btn-upper btn btn-primary">@lang('languages.update')</button>
                <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span><?php } else { ?>
                <button id="send" type="submit" class="btn-upper btn btn-primary">Atualizar Frete</button>
            <?php } ?>
        </div>

    </form>
</div>
</div>
</div>
</div>

<div class="height30"></div>
 @include('footer')
</body>
</html>
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

     <div class="col-md-12 row">
      <?php 
          if(Auth::user()->admin==2){
            if($waiting_count > 0) { ?>
              <p class="alert alert-success">Voc&ecirc; possu&iacute; <?= $waiting_count; ?> cliente(s) esperando por produto(s). <a href="<?php echo $url;?>/waiting-list">Clique Aqui.</a></p>
      <?php }  }?>
      
       <div class="heading-title"><!-- @lang('languages.dashboard')  - Marcello -->
           <?php if ($editprofile[0]->admin == 0) { echo 'Labor&aacute;torio';
                }else if($editprofile[0]->admin == 2 ){ 
                    echo 'Fornecedor';
                    if($editprofile[0]->delete_status == 'inactive'){
                        echo "- <span style='color:red;'>ATEN&Ccedil;&Atilde;O! LOJA DESATIVADA </span>";
                    
                    }else if($editprofile[0]->delete_status == 'blocked'){
                        echo "- <span style='color:red;'>&ldquo;ATEN&Ccedil;&Atilde;O! LOJA FECHADA/EM AN&Aacute;LISE&rdquo;</span>";
                    }
                }
           ?>

       </div>
     </div>

    <div class="height10 clearfix"></div>

        @if(@Auth::user()->admin == 2)
        <div class="control-group">
            <a href="{{ $url }}/wirecard-connect" class="btn btn-primary"> Conectar com conta Wirecard </a>
            <!-- <a href="{{ $url }}/config-shipping" class="btn btn-primary"> Configurar Frete </a> -->
            <?php if($editprofile[0]->delete_status == 'inactive'){   ?>
                <a href="{{ route('dashboard') }}/<?php echo $editprofile[0]->id; ?>/1" class="btn btn-primary" style="background-color: green;"> Ativar Loja </a>
            <?php }else if($editprofile[0]->delete_status == ''){?>
                <a href="{{ route('dashboard') }}/<?php echo $editprofile[0]->id; ?>/0" class="btn btn-primary" style="background-color: #f31414;"> Desativar Loja </a>
            <?php } ?>

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

    <div class="col-md-12" style="display:none;">
	<ul class="nav nav-tabs">
	  <li class="active"><a href="#profileSetting" data-toggle="tab">Profile setting</a></li>
	  <li><a href="#billingDetail" data-toggle="tab">Billing detail</a></li>
      <li><a href="#shippingDetail" data-toggle="tab">Shipping detail</a></li>
	</ul>
    </div>

    <form class="register-form" role="form" method="POST" action="{{ route('dashboard') }}" id="formID" enctype="multipart/form-data" accept-charset="utf-8">
        {{ csrf_field() }}

	<div class="tab-content" style="padding-left:0">
    <div class="tab-pane active m-t-20" id="profileSetting">

    <div class="height20 clearfix"></div>

    <div class="col-md-6 contact-form">

            <div class="col-md-12">
            <div class="form-group">

            <label class="info-title" for="exampleInputName">@lang('languages.username') </label>
            <input type="text" placeholder="Username" name="name" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->name;?>" readonly>
            @if ($errors->has('name'))
            <span class="help-block">
            <strong>{{ $errors->first('name') }}</strong>
            </span>
            @endif
         </div>
        </div>

        <div class="col-md-12">
        <div class="form-group">

            <label class="info-title" for="exampleInputName">@lang('languages.phone') </label>
            <input type="text" placeholder="Phone" class="form-control unicase-form-control" name="phone" value="<?php echo $editprofile[0]->phone;?>">
            @if ($errors->has('phone'))
            <p class="help-block red">
                {{ $errors->first('phone') }}
            </p>
            @endif

        </div>
        </div>
                               
        <div class="col-md-12">
            <div class="form-group">
            <label class="info-title" for="exampleInputName">@lang('languages.country') </label>

            <select name="country" class="form-control unicase-form-control">
		  <option value="">Selecione</option>
		  <?php foreach($countries as $country){?>
                  <option value="<?php echo $country;?>" <?php if($editprofile[0]->country==$country){?> selected <?php } ?>><?php echo $country;?></option>
                  <?php } ?>
	    </select>
            </div>                      
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                <label class="info-title" for="exampleInputName">@lang('languages.password') </label>
                <input type="text" placeholder="Password" name="password" value="" class="form-control unicase-form-control">
            </div>
        </div>

            <?php if(Auth::user()->admin==2){ ?>

            <!-- Marcello Inclusao de Nome Empresa (ou) Tipo Cliente -->
            <div class="col-md-12">
                    <div class="form-group">

                        <label class="info-title" for="exampleInputName">@lang('languages.name_business') </label>

                        <input type="text" placeholder="@lang('languages.name_business')" name="name_business" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->name_business); ?>">
                    </div>
                </div>
            <?php } ?>

            <!-- Marcello Inclusao de CPF & CNPJ -->
            <div class="col-md-12">
                <div class="form-group">

                    <label class="info-title" for="exampleInputName">@lang('languages.cpf_cnpj') </label>

                    <input type="text" placeholder="@lang('languages.cpf_cnpj')" name="cpf_cnpj" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->cpf_cnpj; ?>" disabled="true">
                </div>
            </div>

            <?php if(Auth::user()->admin==2){?>
                           
            <div class="col-md-12">
                    <div class="form-group">


                        <label class="info-title" for="exampleInputName">@lang('languages.local_shipping_price') </label>
                        <input type="number" placeholder="Local shipping price" name="local_shipping_price" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->local_shipping_price; ?>">
                    </div>
                </div>
                        

            <!-- Marcello :: retirado
                <div class="col-md-12">
                    <div class="form-group">

                        <label class="info-title" for="exampleInputName">@lang('languages.world_shipping_price') </label>
                        <input type="number" placeholder="World shipping price" name="world_shipping_price" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->world_shipping_price; ?>">
                    </div>
                </div>-->
                            

            <?php } else {?>
                <input type="hidden" name="local_shipping_price" value="0">
                <input type="hidden" name="world_shipping_price" value="0">
            <?php } ?>



                <div class="col-md-12">
                    <div class="form-group">
                        <label class="info-title" for="exampleInputName">@lang('languages.email') </label>

                        <input type="text" placeholder="Email" name="email" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->email; ?>" readonly>
                        @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <?php if (Auth::user()->admin == 0) { ?>
                        <div class="form-group">
                            <label class="info-title">Lista de Espera - Produtos</label>
                            <?php if (empty($customer_waiting_list)) { ?>
                                <p style="color:blue;">Sem produto(s) na lista de espera.</p>
                            <?php } else { ?>

                                <table class="table">
                                    <thead>
                                        <tr>

                                            <th class="item">@lang('languages.product_name')</th>
                                            <th class="item">@lang('languages.status')</th>

                                        </tr>
                                    </thead><!-- /thead -->

                                    <tbody>
                                        <?php
                                        foreach ($customer_waiting_list as $item) {
                                            $product_waiting_list = DB::table('product')
                                                    ->where('prod_token', '=', $item->product_id)
                                                    ->get();
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php if ($product_waiting_list[0]->delete_status == "active" || $product_waiting_list[0]->delete_status == "inactive") { ?>

                                                        <?= $product_waiting_list[0]->prod_name; ?>

            <?php } else { ?>

                                                        <a href="<?php echo $url; ?>/product/<?php echo $product_waiting_list[0]->prod_id; ?>/<?php echo utf8_decode($product_waiting_list[0]->prod_slug); ?>" >
                <?= $product_waiting_list[0]->prod_name; ?>
                                                        </a>

            <?php } ?>

                                                </td>
                                                <td>
                                                    <?php if ($product_waiting_list[0]->delete_status == "active" || $product_waiting_list[0]->delete_status == "inactive") { ?>
                                                        <p style="color:red;">Produto Indispon&iacute;vel</p>
                                                    <?php } else { ?>

                                                        <?php
                                                        if ($item->waiting == 1) {
                                                            echo('<p style="color:red;">Sem Estoque</p>');
                                                        } else {
                                                            echo('<p style="color:green;">Produto Dispon&iacute;vel!</p>');
                                                        }
                                                        ?>

                                            <?php } ?>
                                                </td>
                                            </tr>
        <?php } ?>
                                    </tbody><!-- /tbody -->
                                </table>

                        <?php } ?>

                        </div>
                <?php } ?>
                </div>
        
        <?php if(Auth::user()->admin==2){?>
                               
                <div class="col-md-12">
                        <div class="form-group">
                            <label class="info-title" for="exampleInputName">Valor de Compra M&iacute;nima (valor m&iacute;nimo exigido para cada pedido) </label>

                            <input type="text" placeholder="Valor de Compra M&iacute;nimo" name="min_value" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->min_value; ?>">

                        </div>
                </div>
        <?php } ?>

    </div>

    <div class="col-md-6 contact-form">

        <!-- Marcello Tipo da Area do Pesquisador
        <?php if ($editprofile[0]->admin == 0) { ?>

            <div class="col-md-12">
            <div class="form-group">

                <label class="info-title" for="exampleInputName">@lang('languages.name_place') </label>
                <select name="name_place" class="form-control unicase-form-control">

                    <option value="">@lang('languages.name_place')</option>
                    <option value="universidade" <?php if ($editprofile[0]->name_place == "universidade") { ?> selected <?php } ?>>Universidade</option>
                    <option value="centro de pesquisa" <?php if ($editprofile[0]->name_place == "centro de pesquisa") { ?> selected <?php } ?>>Centro de Pesquisa</option>
                </select>

            </div>
            </div>
        <?php } ?>
        -->

        <div class="col-md-12">
            <div class="form-group">
                <label class="info-title" for="exampleInputName">@lang('languages.fullname') </label>
                <input type="text" placeholder="@lang('languages.fullname')" name="fullname" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->full_name); ?>" readonly>

                <!-- Marcello : Retirar Gender
                 <label class="info-title" for="exampleInputName">@lang('languages.gender') </label>
               <select name="gender" class="form-control unicase-form-control">

                                             <option value="">@lang('languages.gender')</option>
                                              <option value="male" <?php if ($editprofile[0]->gender == "male") { ?> selected <?php } ?>>Masculino</option>
                                              <option value="female" <?php if ($editprofile[0]->gender == "female") { ?> selected <?php } ?>>Feminino</option>
                                           </select>
                -->
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">

                <label class="info-title" for="exampleInputName">@lang('languages.address') </label>
                <input type="text" placeholder="@lang('languages.address')" name="address" class="form-control unicase-form-control" value="<?php echo utf8_decode($editprofile[0]->address); ?>">
            </div>
        </div>

        <!-- Marcello Adding CUSTOMER ID DO Wirecard -->
        <?php if (Auth::user()->admin == 2) { ?>
            <div class="col-md-12">
                <div class="form-group">

                    <!-- Marcello : Removido temporariamente
                    <label class="info-title" for="exampleInputName">@lang('languages.customer_id') </label>
                    -->
                    <label class="info-title" for="exampleInputName">Integra&ccedil;&atilde;o Wirecard &amp; IBench </label>
                    <input type="text" placeholder="Favor conectar com Wirecard" name="customer_id" class="form-control unicase-form-control" value="<?php if (!empty($editprofile[0]->wirecard_app_data)) {
            echo "Conectado";
        }; ?>" disabled="true">
                </div>
            </div>

        <!-- Marcello Adding CPF OU CNPJ
           <div class="col-md-12">
           <div class="form-group">

               <label class="info-title" for="exampleInputName">@lang('languages.cpf_cnpj') </label>

               <input type="text" placeholder="@lang('languages.cpf_cnpj')" name="cpf_cnpj" class="form-control unicase-form-control" value="<?php echo $editprofile[0]->cpf_cnpj; ?>">
               </div>
               </div>
            -->
        <?php } ?>

            <div class="col-md-12">
                <div class="form-group">

                    <label class="info-title" for="exampleInputName">@lang('languages.about') </label>


                    <textarea placeholder="@lang('languages.about')" name="about" class="form-control unicase-form-control" style="min-height:150px;"><?php if (!empty($editprofile[0])) {
                echo utf8_decode($editprofile[0]->about);
            } ?></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                <?php 
                    if(Auth::user()->admin==2){ ?>
                    <label class="info-title" for="exampleInputName">Imagem da Logo dos Produtos</label>
                <?php } else { ?>
                    <label class="info-title" for="exampleInputName">@lang('languages.profile_photo') </label>
                <?php } ?>
                    <div class="col-md-3 col-sm-3">
                        <p><?php
                            $userphoto = "/media/";
                            $path = '/local/images' . $userphoto . $editprofile[0]->photo;
                            if ($editprofile[0]->photo != "") {
                                ?>
                                <img src="<?php echo $url . $path; ?>" class="img_responsive round profile_size" alt="">
                            <?php } else { ?>
                                <img src="<?php echo $url . '/local/images/nophoto.jpg'; ?>" class="img_responsive round profile_size" alt="">
                            <?php } ?></p>
                    </div>
                    <div class="col-md-9 col-sm-9">

                        <input type="file" id="photo" name="photo" class="pic_photo" class="form-control unicase-form-control">
                        <p>( @lang('languages.upload_size') : 200px X 200px (1024Kb Max) )</p>
                        @if ($errors->has('photo'))
                        <span class="help-block" style="color:red;">
                            <strong>{{ $errors->first('photo') }}</strong>
                        </span>
                        @endif
                    </div>

                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="info-title" for="exampleInputName">@lang('languages.profile_banner') </label>

                    <div class="col-md-3 col-sm-3">
                        <p><?php
                            $userphoto_two = "/media/";
                            $path_two = '/local/images' . $userphoto_two . $editprofile[0]->profile_banner;
                            if ($editprofile[0]->profile_banner != "") {
                                ?>
                                <img src="<?php echo $url . $path_two; ?>" class="img_responsive banner_size" alt="">
                            <?php } else { ?>
                                <img src="<?php echo $url . '/local/images/noimage.jpg'; ?>" class="img_responsive banner_size" alt="">
                            <?php } ?></p>
                    </div>
                    <div class="col-md-9 col-sm-9">

                        <input type="file" id="profile_banner" name="profile_banner" class="pic_photo">
                        <p>( @lang('languages.upload_size') : 1140px X 370px (1024Kb Max) )</p>
                        @if ($errors->has('profile_banner'))
                        <span class="help-block" style="color:red;">
                            <strong>{{ $errors->first('profile_banner') }}</strong>
                        </span>
                        @endif
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
                            <label class="info-title" for="exampleInputName">@lang('languages.select') @lang('languages.country') </label>

                            <select name="bill_country" class="form-control unicase-form-control">
                                <option value="">@lang('languages.select') @lang('languages.country')</option>
                                <?php foreach ($countries as $country) { ?>
                                    <option value="<?php echo $country; ?>" <?php if (!empty($edited_count)) { ?><?php if ($edited[0]->bill_country == $country) { ?> selected <?php } ?><?php } ?>><?php echo $country; ?></option>
                                <?php } ?>
                            </select>

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
                      <label class="info-title" for="exampleInputName">@lang('languages.select') @lang('languages.country') </label>

                      <select name="ship_country" class="form-control unicase-form-control">
                          <option value="">@lang('languages.select') @lang('languages.country')</option>
                          <?php foreach ($countries as $country) { ?>
                              <option value="<?php echo $country; ?>" <?php if (!empty($edited_count)) { ?><?php if ($edited[0]->ship_country == $country) { ?> selected <?php } ?><?php } ?>><?php echo $country; ?></option>
                          <?php } ?>
                      </select>

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
                      <input type="hidden" name="currentphoto" value="<?php echo $editprofile[0]->photo; ?>">
                      <input type="hidden" name="currentbanner" value="<?php echo $editprofile[0]->profile_banner; ?>">

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
                <button id="send" type="submit" class="btn-upper btn btn-primary">@lang('languages.update')</button>
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
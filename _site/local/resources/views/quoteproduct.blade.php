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
				<li class='active'><?php echo "Cota&ccedil;&atilde;o"; ?></li>
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

				<div class="col-md-12 contact-map outer-bottom-vs">
					<div class="row">
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">Cota&ccedil;&atilde;o iBenchNow</div></div>
                <div class="col-md-6 text-right"></div>
        </div>
                    </div>
                 <form class="register-form" role="form" method="POST" action="{{ route('quote_mailsend') }}" id="formID" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            
                            <input type="hidden" value="<?php echo utf8_decode($qproduct[0]->name_business); ?>" name="hid_seller">
                            <input type="hidden" value="<?php echo utf8_decode($qproduct[0]->prod_name); ?>" name="hid_product_name">
                        
				<div class="col-md-8 contact-form">


    <div class="col-md-12">
    <p class="info-title"><?php //echo $contact[0]->page_desc;?></p>
    </div>

    <div class="height30 clearfix"></div>

	<div class="col-md-4 ">



			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.your_name') <span>*</span></label>
		    <input type="text" name="name" class="form-control unicase-form-control validate[required] text-input" id="exampleInputName" value="<?php echo $user[0]->full_name; ?>">
		  </div>

	</div>
	<div class="col-md-4">

			<div class="form-group">
		    <label class="info-title" for="exampleInputEmail1">@lang('languages.email_address') <span>*</span></label>

            <input type="text" placeholder="" name="email" class="form-control unicase-form-control validate[required,custom[email]] text-input" value="<?php echo $user[0]->email; ?>">

		  </div>

	</div>
	<div class="col-md-4">

			<div class="form-group">
		    <label class="info-title" for="exampleInputTitle">@lang('languages.phone_no') <span>*</span></label>

            <input type="text" name="phone_no" class="form-control unicase-form-control validate[required] text-input" value="<?php echo $user[0]->phone; ?>">
		  </div>

	</div>
	<div class="col-md-12">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.your_message') <span>*</span></label>

            <textarea name="msg" rows="10" class="form-control unicase-form-control validate[required] text-input">{{ old('msg') }}</textarea>
		  </div>

	</div>
	<div class="col-md-12 outer-bottom-small m-t-20">
   

		<button type="submit" class="btn-upper btn btn-primary checkout-page-button">Solicitar Cota&ccedil;&atilde;o</button>
	</div>


</div>
 </form>
<div class="col-md-4 contact-info">
	<div class="contact-title">
		<div class="fontsize20">@lang('languages.information') do Produto</div>
	</div>
  <div class="height20"></div>
	<div class="clearfix address">
		<span class="contact-i"><i class="fa fa-flask"></i></span>
		<span class="contact-span" style="margin-top: 7px;"><?php echo utf8_decode($qproduct[0]->prod_name); ?></span>
		
	</div>
	<div class="clearfix phone-no">
		<span class="contact-i"><i class="fa fa-university"></i></span>
                <span class="contact-span" style="margin-top: 7px;"><?php echo utf8_decode($qproduct[0]->name_business); ?></span>
	</div>

        <!--
	<div class="clearfix email">
		<span class="contact-i"><i class="fa fa-envelope"></i></span>
		<span class="contact-span" style="margin-top: 7px;"><a href="mailto:<?php //echo $users[0]->email;?>"><?php //echo $users[0]->email;?></a></span>
	</div> -->
</div>			</div>
		</div>


</div>
 @include('footer')
 </body>
</html>

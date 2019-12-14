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
				<li class='active'><?php echo $contact[0]->page_title;?></li>
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
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.contact_us')</div></div>
                <div class="col-md-6 text-right"></div>
        </div>
                    <!--
                    <?php if(!empty($setting[0]->site_address)){?>
			<iframe src="https://www.google.com/maps/embed/v1/place?key=<?php echo $setting[0]->site_map_api;?>&q=<?php echo $setting[0]->site_address;?>" style="border:0" height="400"  allowfullscreen></iframe>
		<?php } ?>
                   -->
				</div>
                 <form class="register-form" role="form" method="POST" action="{{ route('contact-us') }}" id="formID" enctype="multipart/form-data">
                            {{ csrf_field() }}
				<div class="col-md-8 contact-form">


    <div class="col-md-12">
    <p class="info-title"><?php echo $contact[0]->page_desc;?></p>
    </div>

    <div class="height30 clearfix"></div>

	<div class="col-md-4 ">



			<div class="form-group">
		    <label class="info-title" for="exampleInputName">@lang('languages.your_name') <span>*</span></label>
		    <input type="text" name="name" class="form-control unicase-form-control validate[required] text-input" id="exampleInputName">
		  </div>

	</div>
	<div class="col-md-4">

			<div class="form-group">
		    <label class="info-title" for="exampleInputEmail1">@lang('languages.email_address') <span>*</span></label>

            <input type="text" placeholder="" name="email" class="form-control unicase-form-control validate[required,custom[email]] text-input">
		  </div>

	</div>
	<div class="col-md-4">

			<div class="form-group">
		    <label class="info-title" for="exampleInputTitle">@lang('languages.phone_no') <span>*</span></label>

            <input type="text" name="phone_no" class="form-control unicase-form-control validate[required] text-input">
		  </div>

	</div>
	<div class="col-md-12">

			<div class="form-group">
		    <label class="info-title" for="exampleInputComments">@lang('languages.your_message') <span>*</span></label>

            <textarea name="msg" rows="10" class="form-control unicase-form-control validate[required] text-input"></textarea>
		  </div>

	</div>
	<div class="col-md-12 outer-bottom-small m-t-20">
    {{-- Google reCaptcha --}}
    <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
          <div>
            {!! Recaptcha::render() !!}
            @if ($errors->has('g-recaptcha-response'))
            <span class="help-block">
                <strong>@lang('languages.msg_captcha_invalid')</strong>
            </span>
            @endif
        </div>
    </div>

		<button type="submit" class="btn-upper btn btn-primary checkout-page-button">@lang('languages.send_message')</button>
	</div>


</div>
 </form>
<div class="col-md-4 contact-info">
	<div class="contact-title">
		<div class="fontsize20">@lang('languages.information')</div>
	</div>
  <div class="height20"></div>
	<div class="clearfix address">
		<span class="contact-i"><i class="fa fa-map-marker"></i></span>
		<span class="contact-span" style="margin-top: 7px;">Rua do Catete, 243 - Catete - Rio de Janeiro/RJ</span>
		<!--<span class="contact-span"><?php echo $setting[0]->site_address;?></span>-->
	</div>
	<div class="clearfix phone-no">
		<span class="contact-i"><i class="fa fa-mobile"></i></span>
		<span class="contact-span" style="margin-top: 7px;">+55 21 98271-0963</span>
	</div>

    <!-- Marcello Phone retirado temporariamente
	<div class="clearfix phone-no">
		<span class="contact-i"><i class="fa fa-mobile"></i></span>
		<span class="contact-span"><?php echo $users[0]->phone;?></span>
	</div>
    -->
	<div class="clearfix email">
		<span class="contact-i"><i class="fa fa-envelope"></i></span>
		<span class="contact-span" style="margin-top: 7px;"><a href="mailto:<?php echo $users[0]->email;?>"><?php echo $users[0]->email;?></a></span>
	</div>
</div>			</div>
		</div>


</div>


 @include('footer')

 </body>
</html>

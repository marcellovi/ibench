@extends('layouts.app')

@section('content')

	

<?php $url = URL::to("/"); ?>


     
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">Home</a></li>
				<li class='active'>Login</li>
			</ul>
		</div>
	</div>
</div>









    
        
       <div class="body-content">
	<div class="container-fluid">
		<div class="my-wishlist-page_new">
        
        <div class="row">
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">Login</div></div>
                <div class="col-md-6 text-right"></div>
        </div>
        <div class="height50 clearfix"></div>
        
        <div class="col-md-3"></div>
        
        <div class="col-md-6">
        
        <div class="">
	
	
	 @if(Session::has('success'))

	    <div class="alert alert-success">

	      {{ Session::get('success') }}

	    </div>

	@endif


	
	
 	
	
	
        
        @if(Session::has('error'))

	    <div class="alert alert-danger">

	      {{ Session::get('error') }}

	    </div>

	@endif
    </div>
        
        
            <div class="panel panel-default ">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="register-form" role="form" method="POST" action="{{ route('login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">


                   




                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4">Usu&aacute;rio / Email</label>

                            <div class="col-md-8">
                                <input id="username" type="text" class="form-control unicase-form-control" name="username" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
<div class="height10 clearfix"></div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4">@lang('languages.password')</label>

                            <div class="col-md-8">
                                <input id="password" type="password" class="form-control unicase-form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label class="para black">
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Lembrar Senha
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                               
                                <input name="" type="submit" value="Login" class="btn-upper btn btn-primary">

                                <a class="btn btn-link para gold" href="{{ route('forgot-password') }}">
                                    Esqueceu sua Senha?
                                </a>
                            </div>
                        </div>
                      <?php /*?><div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                            <a href="{{ url('/login/facebook') }}"><i class="fa fa-facebook-official" aria-hidden="true"></i> Facebook</a>
        <a href="{{ url('/login/twitter') }}"><i class="fa fa-twitter" aria-hidden="true"></i> Twitter</a>
        <a href="{{ url('/login/github') }}"><i class="fa fa-github" aria-hidden="true"></i> Github</a>
   <a href="{{ url('/login/google') }}" class="">google-plus</i></a>
                             
                             
                             <a href="<?php echo $url;?>/login/facebook" class="">facebook</i></a>
<a href="<?php echo $url;?>/login/twitter" class="">twitter</i></a>
<a href="<?php echo $url;?>/login/google" class="">google-plus</i></a>
<a href="<?php echo $url;?>/login/linkedin" class="">linkedin</i></a>
<a href="<?php echo $url;?>/login/github" class="">github</i></a>
</div>
                        </div><?php */?> 
                        
                        <div class="height10 clearfix"></div>
                        <div class="form-group">
                            
                            <div class="col-md-1"></div>
                            <div class="col-md-10" align="center">
                            
                            <div class="ffleft" style="margin-right:5px;">
  <!-- FB Marcello                              
                            <a href="{{ url('/login/facebook') }}">

<img src="<?php echo $url;?>/local/images/facebook_btn.png" border="0" alt="facebook login" class="img-responsive"  />

</a>
-->
</div>
        
       <div class="ffleft">
           
<!-- Google Marcello 
   <a href="{{ url('/login/google') }}" class="">

<img src="<?php echo $url;?>/local/images/google_btn.png" border="0" alt="google plus login" class="img-responsive" />
</a>
-->
</div>
</div>
                            

                     <div class="col-md-1"></div>   
                                       
                      </form>
                </div>
            </div>
            
            </div>
            
            <div class="col-md-3"></div>
            
            
            
        </div>
        
        </div>
   
    </div>

<div class="height50"></div>



	
	
	
@include('footer')
@endsection

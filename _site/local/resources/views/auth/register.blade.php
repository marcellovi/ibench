@extends('layouts.app')

@section('content')


<?php $url = URL::to("/"); ?>


        
        
        
        <div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">Home</a></li>
				<li class='active'>Cadastro</li>
			</ul>
		</div>
	</div>
</div>














<div class="body-content">
	<div class="container-fluid">
		<div class="my-wishlist-page_new">
        
        <div class="row">
        <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">Cadastro</div></div>
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
    


    
        
       


        
            <div class="panel panel-default">
                <div class="panel-heading">Cadastro</div>
                
				<div class="panel-body">
                    <form class="register-form" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        
          
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label para black">Usu&aacute;rio</label>

                            <div class="col-md-6">
                                
                                
                                @if(!empty($name))

<input id="name" type="text" class="form-control unicase-form-control" name="name" value="{{$name}}" required autofocus>

@else

<input id="name" type="text" class="form-control unicase-form-control" name="name" value="{{ old('name') }}" required autofocus>

@endif
                                
                                
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="height10 clearfix"></div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label para black">E-Mail</label>

                            <div class="col-md-6">
                               
                                
                                
                                @if(!empty($email))

<input id="email" type="email" class="form-control unicase-form-control" name="email" value="{{$email}}" required>

@else

<input id="email" type="email" class="form-control unicase-form-control" name="email" value="{{ old('email') }}" required>

@endif
                                
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        
                        <div class="height10 clearfix"></div>
                        
                        

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label para black">@lang('languages.password')</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control unicase-form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        
                        <div class="height10 clearfix"></div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label para black">@lang('languages.msg_confirm_pwd')</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control unicase-form-control" name="password_confirmation" required>
                            </div>
                        </div>
						
						<div class="height10 clearfix"></div>
						
						 <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phoneno" class="col-md-4 control-label para black">Telefone/Celular</label>

                            <div class="col-md-6">
                                <input id="phone" type="text" class="form-control unicase-form-control" name="phone" required>
								@if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
				<!--		
						<div class="height10 clearfix"></div>
                           -- Marcello CPF / CNPJ   :: Antigo sem validacao --                   
                          <div class="form-group">
                            <label for="cpf_cnpj" class="col-md-4 control-label para black">@lang('languages.cpf_cnpj')</label>

                            <div class="col-md-6">
                                <input id="cpf_cnpj" type="text" class="form-control unicase-form-control" name="cpf_cnpj" required>
                            </div>
                        </div>-->  
                                        
                          <!-- Marcello CPF / CNPJ -->  
                          <div class="height10 clearfix"></div>
						
						 <div class="form-group {{ $errors->has('cpf_cnpj') ? ' has-error' : '' }}">
                            <label for="cpf_cnpj" class="col-md-4 control-label para black">@lang('languages.cpf_cnpj')</label>

                            <div class="col-md-6">
                                <input id="cpf_cnpj" type="text" class="form-control unicase-form-control" name="cpf_cnpj" required>
								@if ($errors->has('cpf_cnpj'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cpf_cnpj') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                          
                          
				<!-- Marcello Retirar o Sexo 		
						<div class="form-group">                                                  
                            
                            <label for="gender" class="col-md-4 control-label para black">Sexo</label>

                            <div class="col-md-6">
							<select name="gender" class="form-control unicase-form-control" required>
							  
							  <option value=""></option>
							   <option value="male">Masculino</option>
							   <option value="female">Feminino</option>
							</select>
                               
                            </div>
                        </div>
                                 -->
                       
						<div class="height10 clearfix"></div>
                        
                        <?php
						$countries = array(
	'Afghanistan',
	'Albania',
	'Algeria',
	'American Samoa',
	'Andorra',
	'Angola',
	'Anguilla',
	'Antarctica',
	'Antigua and Barbuda',
	'Argentina',
	'Armenia',
	'Aruba',
	'Australia',
	'Austria',
	'Azerbaijan',
	'Bahamas',
	'Bahrain',
	'Bangladesh',
	'Barbados',
	'Belarus',
	'Belgium',
	'Belize',
	'Benin',
	'Bermuda',
	'Bhutan',
	'Bolivia',
	'Bosnia and Herzegowina',
	'Botswana',
	'Bouvet Island',
	'Brazil',
	'British Indian Ocean Territory',
	'Brunei Darussalam',
	'Bulgaria',
	'Burkina Faso',
	'Burundi',
	'Cambodia',
	'Cameroon',
	'Canada',
	'Cape Verde',
	'Cayman Islands',
	'Central African Republic',
	'Chad',
	'Chile',
	'China',
	'Christmas Island',
	'Cocos (Keeling) Islands',
	'Colombia',
	'Comoros',
	'Congo',
	'Congo, the Democratic Republic of the',
	'Cook Islands',
	'Costa Rica',
	'Cote d\'Ivoire',
	'Croatia (Hrvatska)',
	'Cuba',
	'Cyprus',
	'Czech Republic',
	'Denmark',
	'Djibouti',
	'Dominica',
	'Dominican Republic',
	'East Timor',
	'Ecuador',
	'Egypt',
	'El Salvador',
	'Equatorial Guinea',
	'Eritrea',
	'Estonia',
	'Ethiopia',
	'Falkland Islands (Malvinas)',
	'Faroe Islands',
	'Fiji',
	'Finland',
	'France',
	'France Metropolitan',
	'French Guiana',
	'French Polynesia',
	'French Southern Territories',
	'Gabon',
	'Gambia',
	'Georgia',
	'Germany',
	'Ghana',
	'Gibraltar',
	'Greece',
	'Greenland',
	'Grenada',
	'Guadeloupe',
	'Guam',
	'Guatemala',
	'Guinea',
	'Guinea-Bissau',
	'Guyana',
	'Haiti',
	'Heard and Mc Donald Islands',
	'Holy See (Vatican City State)',
	'Honduras',
	'Hong Kong',
	'Hungary',
	'Iceland',
	'India',
	'Indonesia',
	'Iran (Islamic Republic of)',
	'Iraq',
	'Ireland',
	'Israel',
	'Italy',
	'Jamaica',
	'Japan',
	'Jordan',
	'Kazakhstan',
	'Kenya',
	'Kiribati',
	'Korea, Democratic People\'s Republic of',
	'Korea, Republic of',
	'Kuwait',
	'Kyrgyzstan',
	'Lao, People\'s Democratic Republic',
	'Latvia',
	'Lebanon',
	'Lesotho',
	'Liberia',
	'Libyan Arab Jamahiriya',
	'Liechtenstein',
	'Lithuania',
	'Luxembourg',
	'Macau',
	'Macedonia, The Former Yugoslav Republic of',
	'Madagascar',
	'Malawi',
	'Malaysia',
	'Maldives',
	'Mali',
	'Malta',
	'Marshall Islands',
	'Martinique',
	'Mauritania',
	'Mauritius',
	'Mayotte',
	'Mexico',
	'Micronesia, Federated States of',
	'Moldova, Republic of',
	'Monaco',
	'Mongolia',
	'Montserrat',
	'Morocco',
	'Mozambique',
	'Myanmar',
	'Namibia',
	'Nauru',
	'Nepal',
	'Netherlands',
	'Netherlands Antilles',
	'New Caledonia',
	'New Zealand',
	'Nicaragua',
	'Niger',
	'Nigeria',
	'Niue',
	'Norfolk Island',
	'Northern Mariana Islands',
	'Norway',
	'Oman',
	'Pakistan',
	'Palau',
	'Panama',
	'Papua New Guinea',
	'Paraguay',
	'Peru',
	'Philippines',
	'Pitcairn',
	'Poland',
	'Portugal',
	'Puerto Rico',
	'Qatar',
	'Reunion',
	'Romania',
	'Russian Federation',
	'Rwanda',
	'Saint Kitts and Nevis',
	'Saint Lucia',
	'Saint Vincent and the Grenadines',
	'Samoa',
	'San Marino',
	'Sao Tome and Principe',
	'Saudi Arabia',
	'Senegal',
	'Seychelles',
	'Sierra Leone',
	'Singapore',
	'Slovakia (Slovak Republic)',
	'Slovenia',
	'Solomon Islands',
	'Somalia',
	'South Africa',
	'South Georgia and the South Sandwich Islands',
	'Spain',
	'Sri Lanka',
	'St. Helena',
	'St. Pierre and Miquelon',
	'Sudan',
	'Suriname',
	'Svalbard and Jan Mayen Islands',
	'Swaziland',
	'Sweden',
	'Switzerland',
	'Syrian Arab Republic',
	'Taiwan, Province of China',
	'Tajikistan',
	'Tanzania, United Republic of',
	'Thailand',
	'Togo',
	'Tokelau',
	'Tonga',
	'Trinidad and Tobago',
	'Tunisia',
	'Turkey',
	'Turkmenistan',
	'Turks and Caicos Islands',
	'Tuvalu',
	'Uganda',
	'Ukraine',
	'United Arab Emirates',
	'United Kingdom',
	'United States',
	'United States Minor Outlying Islands',
	'Uruguay',
	'Uzbekistan',
	'Vanuatu',
	'Venezuela',
	'Vietnam',
	'Virgin Islands (British)',
	'Virgin Islands (U.S.)',
	'Wallis and Futuna Islands',
	'Western Sahara',
	'Yemen',
	'Yugoslavia',
	'Zambia',
	'Zimbabwe'
);
?>
                        
                        
                        <div class="form-group">
                            <label for="gender" class="col-md-4 control-label para black">Pa&iacute;s</label>

                            <div class="col-md-6">
							<select name="country" class="form-control unicase-form-control" required>
							  
							  <option value=""></option>
							  <?php foreach($countries as $country){?>
                              <option value="<?php echo $country;?>"><?php echo $country;?></option>
                              <?php } ?>
							</select>
                               
                            </div>
                        </div>
                        
                        
                        <div class="height10 clearfix"></div>
                        
                        
                        <div class="form-group">
                            <label for="usertype" class="col-md-4 control-label para black">Perfil Usu&aacute;rio</label>

                            <div class="col-md-6">
							<select name="usertype" class="form-control unicase-form-control" required>
							  
							  <option value=""></option>
							   <option value="0">Comprador</option>
							   <option value="2">Fornecedor</option> 
							</select>
                               
                            </div>
                        </div>
                        
                        <div class="height10 clearfix"></div>
						
                    
                        
                        <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                         
                            
                            <label for="gender" class="col-md-4 control-label para black">Captcha</label>

                            <div class="col-md-6">
						{!! NoCaptcha::display() !!}
						@if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>@lang('languages.msg_captcha_invalid')</strong>
                            </span>
                        @endif
						 </div>
                        </div>
						
						
						
						<div class="height10 clearfix"></div>
						

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn-upper btn btn-primary">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                                                <br>
                        <div class="panel-heading"><span style="color: #ff6633"><b>
                            <input type="checkbox" name="myCheck" value="1" required >Eu aceito o  <a href="<?php echo $url;?>/local/images/media/ibench_termos_condicoes.pdf" target="_blank" style="color: #ff6633"><u>Termo de uso e condi&ccedil;&otilde;es</u></a> do iBench Market</b></span> 
                            <!-- Marcello Termo de Uso -->                            
                            @if ($errors->has('cktermuse'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('cktermuse') }}</strong>
                                    </span>
                                @endif</div>                          
                                                
                    </form>
                                
                </div>
                		
				
				
				
				
            </div>
            
            
            
            
            
            
            
       
       
        <div class="col-md-3"></div>
            
            
            
        </div>
        
        </div>
   
    </div>

<div class="height50"></div>

@include('footer')
<!-- Marcello :: Prevenir Espacos em Branco -->
<script>
var field = document.querySelector('[name="name"]');

field.addEventListener('keypress', function ( event ) {  
   var key = event.keyCode;
    if (key === 32) {
      event.preventDefault();
    }    
});

$("input#name").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});
</script>
@endsection


@extends('layouts.app')
@section('content')
<?php $url = URL::to("/"); ?>        
        
<div class="breadcrumb">
    <div class="container-fluid">
        <div class="breadcrumb-inner">
            <ul class="list-inline list-unstyled">
                <li><a href="<?php echo $url; ?>">Home</a></li>
                <li class='active'>Cadastro</li>
            </ul>
        </div>
    </div>
</div>
<div class="body-content">
    <div class="container-fluid">
        <div class="my-wishlist-page_new">

            <div class="row">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">Cadastro</div>
                </div>
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
                <div class="panel-heading"><b>Cadastro</b>
                </div>                 
				<div class="panel-body">
                                    <form class="register-form" id="ccSelectForm"  role="form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" accept-charset="utf-8">
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
                                        <!-- Marcello Inclusao de FullName -->
                                        <div class="form-group {{ $errors->has('full_name') ? ' has-error' : '' }}">
                                            <label for="full_name" class="col-md-4 control-label para black">Nome Completo</label>

                                            <div class="col-md-6">
                                                <input id="full_name" type="text" class="form-control unicase-form-control" name="full_name" required>
                                                @if ($errors->has('full_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('full_name') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        <!-- end -->

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

                                        <!-- Marcello CPF / CNPJ -->  
                                        <div class="height10 clearfix"></div>

                                        <div class="form-group {{ $errors->has('cpf_cnpj') ? ' has-error' : '' }}">
                                            <label for="cpf_cnpj" class="col-md-4 control-label para black">@lang('languages.cpf_cnpj')</label>

                                            <div class="col-md-6">
                                                <input id="cpf_cnpj" type="text" class="form-control cpfcnpj unicase-form-control" name="cpf_cnpj" required>
                                                @if ($errors->has('cpf_cnpj'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('cpf_cnpj') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="height10 clearfix"></div>

                                        <?php
                                        $countries = array('Brazil');
                                        ?>

                                        <div class="form-group">
                                            <label for="gender" class="col-md-4 control-label para black">Pa&iacute;s</label>

                                            <div class="col-md-6">
                                                <select name="country" class="form-control unicase-form-control" required>

                                                    <option value=""></option>
                                                    <?php foreach ($countries as $country) { ?>
                                                        <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="height10 clearfix"></div>

                                        <div class="form-group">
                                            <label for="password-confirm" class="control-label para orange">Selecione o perfil <b>COMPRADOR</b> caso queira comprar atrav&eacute;s do iBench Market</label>
                                            <label for="password-confirm" class="control-label para orange">Selecione o perfil <b>FORNECEDOR</b> caso queira vender atrav&eacute;s do iBench Market</label>
                                        </div>
                                        <div class="height10 clearfix"></div>

                                        <div class="form-group">
                                            <label for="usertype" class="col-md-4 control-label para black">Perfil Usu&aacute;rio</label>

                                            <div class="col-md-6">
                                                <select id="usertype" name="usertype" class="form-control unicase-form-control" required>

                                                    <option>Preencha o CPF/CNPJ</option>
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
                                                <button type="submit" class="btn-upper btn btn-primary" id="btnsub">
                                                    Cadastrar
                                                </button>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="panel-heading"><span style="color: #ff6633"><b>
                                                    <input type="checkbox" name="myCheck" value="1" required >Eu aceito o  <a href="<?php echo $url; ?>/local/images/media/ibench_termos_condicoes.pdf" target="_blank" style="color: #ff6633"><u>Termo de uso e condi&ccedil;&otilde;es</u></a> do iBench Market</b></span> 
                                            <!-- Marcello Termo de Uso -->                            
                                            @if ($errors->has('cktermuse'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('cktermuse') }}</strong>
                                            </span>
                                            @endif
                                        </div>                          

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

var cpfMascara = function (val) {
        if(val.length == 14){
           // $('#usertype').removeAttr('disabled');
           // $("#usertype option[value=0]").attr('selected', 'selected');
           // $("#usertype option[value=2]").attr('selected', false);
           $('#usertype')
                .find('option')
                .remove()
                .end()
                .append('<option value="0" selected>Comprador</option>')
                .val('0')
            ;
        }else if(val.length == 18){
           // $('#usertype').removeAttr('disabled');
           // $("#usertype option[value=2]").attr('selected', 'selected');
            //$("#usertype option[value=0]").attr('selected', false);
            $('#usertype')
                .find('option')
                .remove()
                .end()
                .append('<option value="2" selected>Fornecedor</option>')
                .val('2')
            ;
        }else{
           // $("#usertype option[value=2]").attr('selected', 'selected');
            //$("#usertype").attr("readonly", true);
            //$("#usertype option[value=0]").attr('selected', false);
            //$("#usertype option[value=2]").attr('selected', false);
            $('#usertype')
                .find('option')
                .remove()
                .end()
                .append('<option value="">Preencha o Campo CPF/CNPJ</option>')
                .val('')
            ;
        }
   return val.replace(/\D/g, '').length > 11 ? '00.000.000/0000-00' : '000.000.000-009';
},
cpfOptions = {
   onKeyPress: function(val, e, field, options) {
      field.mask(cpfMascara.apply({}, arguments), options);
   }
};
$('.cpfcnpj').mask(cpfMascara, cpfOptions);
</script>

<script>
     $(document).ready(function($){
    var maskBehavior = function (val) {
        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
      },
      options = {onKeyPress: function(val, e, field, options) {
              field.mask(maskBehavior.apply({}, arguments), options);
          }
      };
      $('#phone').mask(maskBehavior, options);
    });
 </script> 

@endsection


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
      <div id="breadcrumb"> </div>
      <h1>Add User</h1>
    </div>
    <div class="container-fluid">
      <hr>
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

          <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
              <h5>Add User</h5>
            </div>
            <div class="widget-content">
              <?php $url = URL::to("/"); ?>
              <form method="post" action="{{ route('admin.adduser') }}"
                enctype="multipart/form-data" accept-charset="utf-8" name="basic_validate" id="formID"
                novalidate="novalidate">
                {{ csrf_field() }}

                <div class="form-group">
                  <label class="control-label">Usu&aacute;rio</label>
                  <div class="controls">
                    <input id="name" class="validate[required] form-control" name="name" value="" type="text">
                    @if ($errors->has('name'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">@lang('languages.fullname')</label>
                  <div class="controls">
                    <input id="full_name" type="text" class="validate[required] form-control" name="full_name">
                    @if ($errors->has('full_name'))
                    <span class="help-block">
                      <strong>{{ $errors->first('full_name') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Email</label>
                  <div class="controls">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required="required"
                      class="validate[required,custom[email]] form-control">
                    @if ($errors->has('email'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">@lang('languages.cpf_cnpj')</label>
                  <div class="controls">
                    <input id="cpf_cnpj" class="validate[required] form-control" name="cpf_cnpj" value=""
                      type="text" placeholder="( 999.999.999-99 ) / ( 99.999.999/9999-99 )">
                    @if ($errors->has('cpf_cnpj'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('cpf_cnpj') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label">Senha</label>
                  <div class="controls">
                    <input id="password" type="password" name="password" class="validate[required] form-control">
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Telefone</label>
                  <div class="controls">
                    <input type="tel" id="phone" name="phone" data-validate-length-range="8,20"
                      class="validate[required] form-control">
                      @if ($errors->has('phone'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Pa&iacute;s</label>
                  <div class="controls">
                    <select name="country" class="validate[required] form-control">
                      <option value="">Selecione</option>
                      <?php foreach($countries as $country){?>
                      <option value="<?php echo $country;?>"><?php echo utf8_decode($country);?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="custom-file col-md-4 mb-3">
                  <label class="custom-file-label">Foto</label>
                  <div class="controls">
                    <input type="file" id="photo" name="photo" class="custom-file-input">

                    @if ($errors->has('photo'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                    @endif

                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Tipo Usu&aacute;rio</label>
                  <div class="controls">

                    <select name="usertype" class="validate[required] form-control">
                      <option value="">Selecione</option>
                      <option value="0">Laborat&oacute;rio</option>
                      <option value="2">Fonecedor</option>
                    </select>

                  </div>
                </div>
                <?php /*?><input type="hidden" name="usertype" value="2"> <?php */?>
                <div class="form-actions">
                  <div>
                    <?php if (config('global.demosite') == "yes") { ?><button type="button"
                      class="btn btn-success btndisable">Submit</button>
                    <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span><?php } else { ?>
                    <button id="send" type="submit" class="btn btn-success">Submit</button>
                    <?php } ?>
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

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
      <h1>Editar
        <?php if($users[0]->admin==0){?>Comprador<?php } else if($users[0]->admin==2){?>Fornecedor<?php } else { ?>User<?php } ?>
      </h1>
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
              <h5>Editar Informa&ccedil;&otilde;es</h5>
            </div>
            <div class="widget-content">
              <?php $url = URL::to("/"); ?>
              <form method="post" action="{{ route('admin.edituser') }}"
                enctype="multipart/form-data" name="basic_validate" id="formID" novalidate="novalidate">
                {{ csrf_field() }}


                <div class="form-group">
                  <label class="control-label">Usuario</label>
                  <div class="controls">
                    <input id="name" class="validate[required] form-control" name="name"
                      value="<?php echo $users[0]->name; ?>" type="text">
                    @if ($errors->has('name'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('name') }}</strong>
                    </span>
                    @endif

                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label">Nome</label>
                  <div class="controls">
                    <input id="full_name" class="validate[required] form-control" name="full_name"
                      value="<?php echo $users[0]->full_name; ?>" type="text">
                    @if ($errors->has('full_name'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('full_name') }}</strong>
                    </span>
                    @endif

                  </div>
                </div>


                <div class="form-group">
                  <label class="control-label">Email</label>
                  <div class="controls">


                    <input type="email" id="email" name="email" value="<?php echo $users[0]->email; ?>"
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
                    <input id="cpf_cnpj" class="validate[required] form-control" name="cpf_cnpj"
                      value="<?php echo $users[0]->cpf_cnpj; ?>" type="text"
                      placeholder="( 999.999.999-99 ) / ( 99.999.999/9999-99 )">
                    @if ($errors->has('cpf_cnpj'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('cpf_cnpj') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>

                <?php if($userid==1) {?>
                <div class="form-group">
                  <label class="control-label">Password</label>
                  <div class="controls">

                    <input id="password" type="text" name="password" value="" class="">
                  </div>
                </div>
                <?php } ?>
                <input type="hidden" name="savepassword" value="<?php echo $users[0]->password;?>">

                <div class="form-group">
                  <label class="control-label">Telefone</label>
                  <div class="controls">

                    <input type="tel" id="phone" name="phone" data-validate-length-range="8,20"
                      class="validate[required] form-control" value="<?php echo $users[0]->phone; ?>">
                      @if ($errors->has('phone'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                    @endif
                  </div>
                </div>
                <input type="hidden" name="id" value="<?php echo $users[0]->id; ?>">


                <div class="form-group">
                  <label class="control-label">Pa&iacute;s</label>
                  <div class="controls">
                    <select name="country" class="validate[required] form-control">

                      <option value="">Select Country</option>
                      <?php foreach($countries as $country){?>
                      <option value="<?php echo $country;?>" <?php if($users[0]->country==$country){?> selected
                        <?php } ?>><?php echo $country;?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="custom-file col-md-4 mb-3">
                  <label class="custom-file-label">Foto/Imagem</label>
                  <div class="controls">
                    <input type="file" id="photo" name="photo" class="custom-file-input">
                    @if ($errors->has('photo'))
                    <span class="help-block" style="color:red;">
                      <strong>{{ $errors->first('photo') }}</strong>
                    </span>
                    @endif

                  </div>
                </div>
                <?php $url = URL::to("/"); ?>
                <?php
                  $userphoto="/media/";
                  $path ='/local/images'.$userphoto.$users[0]->photo;
                  if($users[0]->photo!=""){
                ?>
                <div class="form-group">
                  <div class="controls">
                    <div class="">
                      <img src="<?php echo $url.$path;?>" class="thumb" width="100">
                    </div>
                  </div>
                </div>
                <?php } else { ?>
                <div class="form-group">
                  <div class="controls">
                    <div class="">
                      <img src="<?php echo $url.'/local/images/nophoto.jpg';?>" class="thumb" width="100">
                    </div>
                  </div>
                </div>
                <?php } ?>


                <input type="hidden" name="currentphoto" value="<?php echo $users[0]->photo;?>">

                <?php if($users[0]->admin!=1){?>
                <div class="form-group">
                  <label class="control-label">Status</label>
                  <div class="controls">
                    <select name="usertype" class="validate[required] form-control">
                      <option value="">Select</option>
                      <option value="0" <?php if($users[0]->admin==0){?> selected="selected" <?php } ?>>Comprador
                      </option>
                      <option value="2" <?php if($users[0]->admin==2){?> selected="selected" <?php } ?>>Fornecedor
                      </option>
                    </select>

                  </div>
                </div>

                <?php /*?> <input type="hidden" name="usertype" value="2"> <?php */?>

                <?php } ?>


                <?php if($users[0]->admin==1){?>

                <input type="hidden" name="usertype" value="1">

                <?php /*?> <input type="hidden" name="usertype" value="<?php echo $users[0]->admin;?>"><?php */?>

                <?php } ?>



                <?php if($users[0]->admin==2){?>
                <div class="form-group">
                  <label class="control-label">Ganhos R$ : </label>
                  <div class="controls">

                    <input id="name" class="form-control" name="earning" value="<?php echo $users[0]->earning;?>"
                      type="text">
                  </div>
                </div>

                <?php } ?>

                <div class="form-actions">
                  <div>

                    <a href="<?php echo $url;?>/admin/users" class="btn btn-primary">Voltar</a>


                    <?php if(config('global.demosite')=="yes"){?><button type="button"
                      class="btn btn-success btndisable">Submit</button>
                    <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>


                    <button id="send" type="submit" class="btn btn-success">Editar</button>
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

<!DOCTYPE html>
<html lang="en">
    <head>
        @include('admin.title')
        @include('admin.style')
        @include('admin.table-style')
    </head>

    <body>
        @include('admin.top')
        @include('admin.menu')
        <?php $url = URL::to("/"); ?>

        <div id="content">
            <div id="content-header">
                <div id="breadcrumb">  </div>
                <h1>Fornecedores</h1>
            </div>

            <div class="container-fluid"><hr>
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

    <form action="{{ route('admin.users') }}" method="post">

        {{ csrf_field() }}
        <div align="right">

            <?php if (config('global.demosite') == "yes") { ?>

                <a href="#" class="btn btn-danger btndisable">Delete All</a>  <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
            <?php } else { ?>
                <input type="submit" value="Deletar Todos" class="btn btn-danger" id="checkBtn" onClick="return confirm('Tem certeza que quer deletar?');">
            <?php } ?>


            <?php if (config('global.demosite') == "yes") { ?>
                <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span> <a href="#" class="btn btn-primary btndisable">Adicionar Usuario</a>
            <?php } else { ?>
                <a href="<?php echo $url; ?>/admin/adduser" class="btn btn-primary">Adicionar Usuario</a>
            <?php } ?>
        </div>
        <div class="widget-box">


            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                <h5>Fornecedores</h5>
            </div>

            <div class="widget-content nopadding">
                <table class="table table-bordered data-table" id="datatable-responsive">
                    <thead>
                        <tr>
                            <th data-orderable="false">
                              <input type="checkbox" id="selectAll" class="main">
                            </th>
                            <th>Sno</th>
                            <th>Imagem</th>
                            <th>Usuario</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>CNPJ</th>
                            <th>Telefone</th>
                            <th>Ganhos</th>
                            <th>N. Prod Inativo</th>
                            <th>Status</th>
                            <th>Wirecard</th>
                            <th>Data de Cria&ccedil;&atilde;o</th>
                            <th>A&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($users_cnt)) {
                            $i = 1;
                            foreach ($users as $user) {

                                $sta = $user->admin;
                                if ($sta == 1) {
                                    $viewst = "Admin";
                                } else if ($sta == 2) {
                                    $viewst = "Vendor";
                                } else if ($sta == 0) {
                                    $viewst = "Customer";
                                }
                                ?>
                                <tr class="gradeX">
                                    <td align="center"><input type="checkbox" name="userid[]" value="<?php echo $user->id; ?>"/></td>

                                    <td><?php echo $i; ?></td>
                                    <?php
                                    $userphoto = "/media/";
                                    $path = '/local/images' . $userphoto . $user->photo;
                                    if ($user->photo != "") {
                                        ?>
                                        <td><img src="<?php echo $url . $path; ?>" class="thumb" width="40" align="middle" style="display: block; margin-left: auto; margin-right: auto; z-index: 1;" >
                                        </td>
                    <?php } else { ?>
                                        <td><img src="<?php echo $url . '/local/images/nophoto.jpg'; ?>" class="thumb" width="40" align="middle" style="display: block; margin-left: auto; margin-right: auto; z-index: 1;" >
                                        </td>
                                    <?php } ?>
                                    <td><?php echo $user->name; ?></td>
                                    <td><?php echo $user->full_name; ?></td>
                                    <td><?php echo $user->email; ?></td>
                                    <td><?php echo $user->cpf_cnpj; ?></td>
                                    <td><?php echo $user->phone; ?></td>

                                        <?php if ($user->provider == "") {
                                            $logintype = "normal";
                                        } else {
                                            $logintype = $user->provider;
                                        } ?>
                                    <td><?php echo $user->earning; ?> <?php echo $setts[0]->site_currency; ?></td>
                                    <td><?php
                                        //print_r($prod_cnt_inactive); exit();
                                        $found = false;
                                        foreach ($prod_cnt_inactive as $user_prod_inactive) {
                                            if ($user->id == $user_prod_inactive->user_id) {
                                                echo $user_prod_inactive->qtd_prod_inactive;
                                                $found = true;
                                            }
                                        }if (!$found) {
                                            echo "0";
                                        }
                                        ?></td>
                                    <td><?php if ($user->delete_status == "") {
                                    echo "Liberado";
                                } else if ($user->delete_status == "inactive") {
                                    echo "Inativo";
                                } else {
                                    echo "Bloqueado";
                                } ?></td>
                                    <td><?php if ($user->wirecard_app_data != "") {
                                    echo "Conectado";
                                } else {
                                    echo "N/A";
                                } ?></td>

                                <td><?php echo $user->created_at;?></td>

                                    <td>
        <?php if ($user->provider == "") { ?>
            <?php if (config('global.demosite') == "yes") { ?>
                  <a href="#" class="btn btn-success btndisable">Editar</a>  <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
            <?php } else { ?>
                  <a href="<?php echo $url; ?>/admin/edituser/{{ $user->id }}" >
                      <img src="<?php echo $url;?>/local/images/editar.png" alt="Editar" title="Editar"></a>
            <?php } ?>
        <?php } ?>

        <?php if (config('global.demosite') == "yes") { ?>
                 <a href="#" class="btn btn-danger btndisable">Deletar</a>  <span class="disabletxt">( <?php echo config('global.demotxt'); ?> )</span>
        <?php } else { ?>
                @if($sta!=1)
                 <a href="<?php echo $url; ?>/admin/users/{{ $user->id }}" onClick="return confirm('Tem certeza que quer deletar?')">
                      <img src="<?php echo $url;?>/local/images/deletar.png" alt="Deletar" title="Deletar"></a>
            <?php if ($user->delete_status == "") { ?>
                  <a href="<?php echo $url; ?>/admin/authorizeseller/{{ $user->id }}/0"  onClick="return confirm('Tem certeza que quer Bloquear o fornecedor?')">
                      <img src="<?php echo $url;?>/local/images/user-blocked.png" alt="Bloquear" title="Bloquear"></a>
            <?php } else { ?>
                  <a href="<?php echo $url; ?>/admin/authorizeseller/{{ $user->id }}/1" onClick="return confirm('Tem certeza que quer liberar o fornecedor?')">
                      <img src="<?php echo $url;?>/local/images/unlock.png" alt="Liberar" title="Liberar"></a>
            <?php } ?>
                 @endif
        <?php } ?>
                                </td>
                            </tr>
        <?php $i++;    }} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('admin.footer')
</body>
</html>

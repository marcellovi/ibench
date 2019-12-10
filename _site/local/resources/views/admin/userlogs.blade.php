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
                <h1>Log de Erros</h1>
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
           <input type="submit" value="Deletar Todos" class="btn btn-danger" id="checkBtn" onClick="return confirm('Tem certeza que quer deletar?');">
        </div>
        <div class="widget-box">


            <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
                <h5>Log de Erro dos Usuarios</h5>
            </div>

            <div class="widget-content nopadding">
                <table class="table table-bordered data-table" id="datatable-responsive">
                    <thead>
                        <tr>
                            <th data-orderable="false"><input type="checkbox" id="selectAll" class="main"></th>
                            <th>Usuario</th>
                            <th>Dt.Registro</th>
                            <th>Ip</th>
                            <th>Device</th>
                            <th>OS</th>
                            <th>Browser</th>
                            <th>Tipo Erro</th>
                            <th>Msg Erro</th>
                            <th>A&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($user_logs_cnt)) {
                            $i = 1;
                            foreach ($user_logs as $user_log) {                               
                        ?>
                                <tr class="gradeX">
                                    <td align="center"><input type="checkbox" name="userid[]" value="<?php echo $user_log->id; ?>"/></td>
                                    <td><?php echo $user_log->name; ?></td>
                                    <td><?php $date = date_create($user_log->error_created_at); echo date_format($date, 'Y-m-d g:i A'); ?></td>
                                    <td><?php echo $user_log->ip; ?></td>
                                    <td><?php echo $user_log->device; ?></td>
                                    <td><?php echo $user_log->os; ?></td>
                                    <td><?php echo $user_log->browser; ?></td>  
                                    <td><?php echo $user_log->type_error;?></td>
                                    <td><?php echo $user_log->msg_error;?></td>
                                    <td>    
                                <a href="<?php echo $url; ?>/admin/users/log/{{ $user_log->id }}" onClick="return confirm('Tem certeza que quer deletar?')">
                                <button type="button" class="btn btn-outline-danger" alt="Deletar" title="Deletar"><i class="icon-fixed-width icon-trash"></i></button>
                                </a>             
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

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
    <h1>Relatorio de Usuarios Deletados</h1>
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

<form action="{{ route('admin.reports') }}" method="post">
                 
                 {{ csrf_field() }}
                 <div align="right"> <!--
                  <?php if(config('global.demosite')=="yes"){?>
					
				   <a href="#" class="btn btn-danger btndisable">Deletar Todos</a>  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span>
				  <?php } else { ?>
				   <input type="submit" value="Deletar Todos" class="btn btn-danger" id="checkBtn" onClick="return confirm('Tem certeza que quer deletar?');">
				  <?php } ?>
                  
                 
				  <?php if(config('global.demosite')=="yes"){?>
				  <span class="disabletxt">( <?php echo config('global.demotxt');?> )</span> <a href="#" class="btn btn-primary btndisable">Adicionar Usuario</a> 
				  <?php } else { ?>
				  <a href="<?php echo $url;?>/admin/adduser" class="btn btn-primary">Adicionar Usuario</a>
				  <?php } ?>
                  -->
                 </div>
<div class="widget-box">           

          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>Usuarios</h5>
          </div>          
          
          <div class="widget-content nopadding">
         
            <table class="table table-bordered data-table" id="datatable-responsive">
              <thead>
                <tr>
                <th data-orderable="false"><input type="checkbox" id="selectAll" class="main"></th>
                <th>Tipo</th>
		<th>Usuario</th>
                <th>Total de Produtos</th>
                <th>Total Gasto</th>
                <th>Compras Completa</th>
                <th>Compras Pendente</th>		
                <th>Dt. Criado</th>
                <th>Dt. Deletado</th>
                <th>Demais Informacoes</th>
                </tr>
              </thead>
              <tbody>
              <?php if(!empty($hreport_cnt)){
		  $i=1;
		  foreach ($hreport as $user) { $sta=$user->h_type_user; if($sta==1){ $viewst="Admin"; } else if($sta==2) { $viewst="Fornecedor"; } else if($sta==0) { $viewst="Comprador"; }?>
                
                <tr class="gradeX">
                        
                        <td align="center"><input type="checkbox" name="userid[]" value="<?php echo $user->h_id;?>"/></td>                        
			<!-- <td><?php echo $i;?></td> -->
                        <td><?php echo $viewst;?></td>
			<td><?php echo $user->h_name;?></td>
                        <td><?php echo $user->h_tot_product;?></td>
                        <td><?php echo $user->h_total_payment;?></td>
                        <td><?php echo $user->h_tot_complete_ord;?></td>
                        <td><?php echo $user->h_tot_incomplete_ord;?></td>
			<td><?php echo date('d-m-Y', strtotime($user->h_created_at)); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($user->h_deleted_at));?></td>
                        <td>Razao: <?php echo $user->h_reason;?><br><br>Info.Extra: <?php echo $user->h_payment_details;?></td>
                            <?php /*?><td><?php echo $user->earning;?> <?php echo $setts[0]->site_currency;?></td><?php */?>
                    </tr>
                
                <?php $i++; } } ?>
                                
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

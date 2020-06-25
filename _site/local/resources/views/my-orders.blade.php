<?php

use Illuminate\Support\Facades\Route;

$currentPaths = Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid = 1;
$setts = DB::table('settings')
        ->where('id', '=', $setid)
        ->get();
$headertype = $setts[0]->header_type;
?>
<!DOCTYPE html>
<html class="no-js"  lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   @include('style')
</head>
<body class="cnt-home">   
  @include('header')
  
<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">
				<li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.my_orders')</li>
			</ul>
		</div>
	</div>
</div>

<div class="body-content outer-top-xs">
<div class="container-fluid">
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
	<div class="row ">
		<div class="shopping-cart">
		<div class="shopping-cart-table ">
                <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.my_orders') (<?php echo $viewcount[0]->total_orders;?>)</div></div>
                <div class="col-md-6 text-right"></div>              
                <div class="height20 clearfix"></div>
               
                
	<div class="table-responsive">
        
        <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Informativos - Total de <?php echo $viewcount[0]->total_orders;?> Pedidos</div>
        <div class="panel-body">
            <p><b>IMPORTANTE!</b><br> Assim que realizar a entrega do pedido, nos informe para que possamos 
                liberar o pagamento na sua conta na Wirecard. 
                <br>Escreva para ibench@ibench.com.br</p>
        </div>
        
	<table class="table ">
		<thead>
			<tr>
			<th class="item">@lang('languages.name')</th>
			<th class="item">@lang('languages.purchase_id')</th>
			<th class="item">@lang('languages.total_items')</th>
			<th class="item">@lang('languages.total_shipping')</th>
			<th class="item">@lang('languages.total')</th>
			<th class="item">NF / A&ccedil;&otilde;es </th>
			<!-- <th class="item">@lang('languages.payment_status')</th> -->
                        </tr>
			</thead><!-- /thead -->
			
	<tbody> 
	<?php 
        $viewtheuser = DB::table('users')
                        ->select('id','full_name')
                        ->get();
            
        ?>
	
        <?php foreach($viewproduct as $product){ ?>
        <tr>
	<td class="cart-product-grand-total"><?php 
            foreach($viewtheuser as $theuser){
               if($theuser->id == $product->user_id){
                   echo $theuser->full_name; 
               }
            }?>
           </td>
        <td class="cart-product-name-info"> <a href="<?php echo $url;?>/purchaseorder/<?php echo $product->purchase_token;?>">
                <?php echo $product->purchase_token;?></a></td>
	<td class="cart-product-sub-total"><?php echo $product->quantity; ?></td>
        <td class="cart-product-grand-total"><?php echo $setts[0]->site_currency.' '.number_format($product->shipping_price,2,",",".");?></td>                    
	<td class="cart-product-grand-total"><?php echo $setts[0]->site_currency.' '.number_format($product->total,2,",",".");?></td>
	<td class="cart-product-edit">  
            <?php if($product->nf){ ?>
            <button type="button" class="btn btn-default" aria-label="Left Align" title="NF Enviada" >
            <span class="glyphicon glyphicon glyphicon glyphicon-saved green" aria-hidden="true"></span>
            </button>
            <?php }else{ ?>
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Incluir NF" data-toggle="modal"  data-target="#exampleModal"  data-id="<?php echo $product->purchase_token; ?>" >
            <span class="glyphicon glyphicon glyphicon glyphicon-open red" aria-hidden="true"></span>
            </button>
            <?php } ?>
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Mais Detalhes" >
            <a href="<?php echo $url;?>/purchaseorder/<?php echo $product->purchase_token;?>"> 
                <span class="glyphicon glyphicon glyphicon glyphicon-th-list" aria-hidden="true"></span></a>
            </button>
            
            <!--
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Upload NF">
            <span class="glyphicon glyphicon glyphicon-open" aria-hidden="true"></span>
            </button>
            
            <button type="button" class="btn btn-default" aria-label="Left Align" title="Enviar MSG" >
            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
            </button>
            -->
        </td>          
        </tr>
        <?php } ?>
        
								
	</tbody><!-- /tbody -->
	</table><!-- /table -->
        <span class="pull-right">{{ $viewproduct->links() }}</span>
        
        </div>
            <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Pedido N.</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
      <div class="modal-body">
          <!--
        <form  method='get' action='{{ @url("/upload_nf") }}' enctype="multipart/form-data"> -->
        <form class="register-form" role="form" method="POST" action="{{ route('upload-nf') }}" id="formID" enctype="multipart/form-data" accept-charset="utf-8">
                    {{ csrf_field() }}
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Upload NF:</label>
            <input id="hidnf" type="hidden" name="hid_purchase_token" >
            <!-- <input type="file" class="form-control" id="recipient-name" name='nf' > -->
            <input type="file" placeholder="" name="nf" class="form-control unicase-form-control">
          </div><!--
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="message-text"></textarea>
          </div> -->
          <button type="submit" id="process" class="btn btn-primary">Salvar</button> 
        </form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        
</div>
	</div>
        </div>
</div>
            <!-- end modal -->
    </div>
</div>
</div>
	</div>
        </div>
</div>
<div class="height30"></div>
@include('footer')
<!-- Modal Javascript -->
<script>
$('#exampleModal').on('show.bs.modal', function (event) {  
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('id') // Extract info from data-* attributes
  $('#hidnf').val(recipient)
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  var modal = $(this)
  modal.find('.modal-title').text('Pedido N. ' + recipient)
  //modal.find('.modal-body hidden').val(button.data('id'))
})

 $(document).ready(function() {
   $('#process').submit(function(event) {
     $('#hidnf').val('hello world')
   });
 });
</script>
<!-- end modal jv -->
</body>
</html>

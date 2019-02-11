<!DOCTYPE html>
<html lang="en">
<head>

    <title>Pedido realizado com sucesso!</title>

  
	




</head>
<body>

   

    
    

	
    
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<div class="container"> <!-- Marcello @lang('languages.payment_received') -->
	 <h1><?php echo $site_name;?> Market - Dados do Pedido </h1>
	 
	 
	
	 
	 
	 <div class="clearfix"></div>
	 
	 <div class="row profile shop">
		<div class="col-md-6">
	 
	 
	
	<div id="outer" style="width: 100%;margin: 0 auto;background-color:#cccccc; padding:10px;">  
	
	
	<div id="inner" style="width: 80%;margin: 0 auto;background-color: #fff;font-family: Open Sans,Arial,sans-serif;font-size: 13px;
	font-weight: normal;line-height: 1.4em;color: #444;margin-top: 10px; padding:10px;">
			
			<!-- Marcello 
			<div style="background:#B98B4B;padding:15px 10px 10px 10px;">
                    -->
                    <div style="padding:15px 10px 10px 10px;">
			<?php if(!empty($site_logo)){?>
			<div align="center"><img src="<?php echo $site_logo;?>" border="0" alt="logo" /></div>
			<?php } else { ?>
			<div align="center"><h2><?php echo $site_name;?></h2></div>
			<?php } ?>
			</div>
			
			
			<h3><!-- Marcello @lang('languages.payment_received') --> Dados do Pedido </h3>
			
            
            <!-- Marcello 
            <p> @lang('languages.order_id') - <?php echo $order_id;?></p> 	
			<p> @lang('languages.name') - <?php echo $name;?></p> 	
			<p> @lang('languages.email') - <?php echo $email;?></p> 	
			<p> @lang('languages.phone') - <?php echo $phone;?></p> 
                        <p> @lang('languages.amount') - <?php echo number_format($amount,2,",",".");?></p> 
		--> 
                <p> N&uacute;mero do Pedido: <?php echo $order_id;?></p> 	
			<p> Cliente: <?php echo $name;?></p> 	
            <p> 
                Mais informa&ccedil;&otilde;es sobre o pedido podem ser vistas na sua conta no site iBench Market!
                Comprador, visualize acessando &ldquo;Minha Conta&rdquo; e depois &ldquo;Minhas Compras&rdquo;.
                Fornecedor, visualize acessando &ldquo;Minha Conta&rdquo; e depois &ldquo;Minhas Vendas&rdquo;.
            </p>
            <p><b>Informa&ccedil;&otilde;es Importante:</b>
            </p>
            <p>
                <b> - Comprador:</b> o prazo de entrega come&ccedil;a a contar a partir da data de confirma&ccedil;&atilde;o de pagamento. 
                Informe atrav&eacute;s do e-mail&nbsp;<a href="mailto:ibench@ibench.com.br" target="_blank">ibench@ibench.com.br</a>
                &nbsp;o recebimento do seu pedido ou, em at&eacute; 2 (dois) dias ap&oacute;s o t&eacute;rmino do prazo de entrega, 
                informe o n&atilde;o recebimento dos produtos. Caso o Fornecedor informe ao site iBench Market sobre a entrega do 
                pedido antes do Comprador, o site iBench Market entrar&aacute; em contato com o Comprador atrav&eacute;s de e-mail 
                para confirmar o recebimento. O Comprador ter&aacute; at&eacute; 2 (dois) dias &uacute;teis para responder, caso 
                n&atilde;o haja resposta a entrega ser&aacute; considerada confirmada e o valor da venda poder&aacute; ser disponibilizado 
                ao Fornecedor contado o prazo j&aacute; estabelecido. Os produtos de diferentes fornecedores podem ser entregues em datas 
                diferentes.
            </p>
            <p>
                <b> - Fornecedor:</b> para sua seguran&ccedil;a, aguarde a confirma&ccedil;&atilde;o do pagamento para enviar o pedido. 
                Uma vez confirmado o pagamento, a libera&ccedil;&atilde;o do pagamento pela Wirecard Brasil S.A. pode levar at&eacute; 30 
                (trinta) dias para pagamentos com cart&atilde;o de cr&eacute;dito ou at&eacute; 4 (quatro) dias &uacute;teis para 
                pagamentos com boleto banc&aacute;rio.   &agrave;s vendas realizadas, estando liberado pela Wirecard 
                Brasil S.A., ser&aacute; disponibilizado em sua conta na Wirecard Brasil S.A. no prazo de at&eacute; 8 (oito) dias 
                ap&oacute;s a confirma&ccedil;&atilde;o da entrega do pedido. Informe a data de entrega do pedido em at&eacute; 2 (dois) 
                dias &uacute;teis atrav&eacute;s de envio de e-mail para&nbsp;<a href="mailto:ibench@ibench.com.br" target="_blank">ibench@ibench.com.br</a>&nbsp;
                com assunto &ldquo;Entrega de Pedido&rdquo; incluindo no corpo do e-mail: n&uacute;mero do pedido, nome e CPF do cliente, 
                informa&ccedil;&otilde;es sobre os produtos entregues (c&oacute;digos, especifica&ccedil;&otilde;es e quantidades) e data 
                da entrega. O site iBench Market entrar&aacute; em contato com o comprador para confirmar o recebimento e o mesmo ter&aacute; 
                at&eacute; 2 (dois) dias &uacute;teis para responder, caso n&atilde;o haja resposta a entrega ser&aacute; considerada confirmada 
                e o valor da venda poder&aacute; ser disponibilizado ao Fornecedor contado o prazo j&aacute; estabelecido.
            </p> 
			
				
				
			
			
			
	
	
	
	
	</div>
	</div>
	 
	 
	 
	
	 
	 
	
	
	
	
	 
	 
	 
	 
	 
	 <div class="height30"></div>
	 <div class="row">
	
	
	
	
	
	</div>
	
	</div>
	

      
</body>
</html>
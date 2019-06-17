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
<head>
   @include('style')
</head>
<body  class="cnt-home">   
   @include('header')
   
<!-- ============================================== HEADER : END ============================================== -->
<div class="breadcrumb">
	<div class="container-fluid">
		<div class="breadcrumb-inner">
			<ul class="list-inline list-unstyled">                
                <li><a href="<?php echo $url;?>">@lang('languages.home')</a></li>
				<li class='active'>@lang('languages.product_import_export')</li>
			</ul>
		</div>
	</div>
</div>

<div class="body-content">
    <div class="container-fluid">
        <div class="my-wishlist-page">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    
                    @if(Session::has('success'))
                    <p class="alert alert-success">
                        {{ Session::get('success') }}
                    </p>
                    @endif	
	
                    @if(Session::has('error'))
                    <p class="alert alert-danger">
                        {!! Session::get('error') !!}
                    </p>
                    @endif
    </div>
    </div>    
    
    <div class="row">
    <div class="col-md-6"><div class="heading-title" style="border-bottom:none !important;">@lang('languages.product_import_export')</div></div>
    <div class="col-md-6 text-right"></div>
    </div>  
    
    <div class="row">           
    <div class="clearfix height20"></div>
   
    <div class="col-md-12">
        <a href="https://www.benchfind.com/_site/local/images/planilha_importacao.xls"><button class="btn btn-success">Baixar Modelo em Branco</button></a>
        <!--
        <a href="{{ URL::to('downloadExcelModel') }}"><button class="btn btn-success">Baixar Modelo em Branco</button></a>
        <a href="{{ URL::to('downloadExcel/xls') }}"><button class="btn btn-success">Baixar Modelo em .xls</button></a>
        <a href="{{ URL::to('downloadExcel/xlsx') }}"><button class="btn btn-success">Baixar Modelo em .xlsx</button></a>
        <a href="{{ URL::to('downloadExcel/csv') }}"><button class="btn btn-success">Baixar Modelo em CSV</button></a>
        -->
	<form style="border: 1px solid #ccc;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input type="file" name="import_file" required/><br/>		
        
        <?php if(config('global.demosite')=="yes"){?><a href="#" class="btn-upper btn btn-primary notclick">@lang('languages.import_file')</a> 
		<span class="disabletxt">( <?php echo config('global.demotxt');?> )</span><?php } else { ?>
						  
                <button id="send" type="submit" class="btn-upper btn btn-primary">@lang('languages.import_model_file')</button>
	<?php } ?>          
	</form>
        
        <div class="widget-content nopadding">
           <table> <!--  Get all -->
           <tr>
           <td style="vertical-align:top;">
               <table class="table-bordered" id="datatable-responsive" >
              <thead>
                <tr>
                <th style="vertical-align:top;padding: 10px 25px 5px 25px;">Informa&ccedil;&otilde;es &amp; Regras sobre Importa&ccedil;&atilde;o de Produtos em Massa</th>
                <th style="vertical-align:top;padding: 10px 25px 5px 25px;">Passo a Passo no nosso Canal no <a href="https://www.youtube.com/channel/UCn8tLRoAciBslD-vhSa72cw" target="_blank" title="iBench - Youtube">YouTube</a></th>
		 <!--<th>Lista de Marcas</th>
                <th>Observacoes Sobre a Importacao</th> -->
                </tr>
              </thead>      
                <tr>   
                    <td style="vertical-align:top;padding: 10px 25px 5px 25px;">
                        <br />
                        <b> 1.</b> Campos Obrigat&oacute;rios: Nome | Categoria | Marca | Descri&ccedil;&atilde;o | Quantidade | Pre&ccedil;o <br /><br />
                         <b> 2. </b>Preencha os campos Pre&ccedil;o, Pre&ccedil;o Promocional &amp; Quantidade com valores n&uacute;mericos. O valor decimal deve ser separado por &quot;.&quot; ponto ao inv&eacute;s de v&iacute;rgula.<br /><br />
                         <b> 3.</b> Pre&ccedil;o Oferecido deve ser preenchido caso o produto esteja sendo vendido com desconto. <br /><br />
                         <b> 4. </b>Os produtos cadastrados em massa ter&atilde;o como imagem a logo da sua empresa (Minha Conta &gt;&gt; Meu Perfil &gt;&gt; Campo &quot;Imagem da Logo&quot;). Caso a logo n&atilde;o esteja cadastrada, ser&aacute; utilizada uma imagem padr&atilde;o do iBench Market.<br /> <br />
                         <b> 5.</b> Cada produto deve ser preenchido em uma linha da planilha modelo. <u>N&Atilde;O DEIXE LINHAS EM BRANCO ENTRE OS PRODUTOS</u>. Caso haja alguma linha em branco, o sistema interromper&aacute; a leitura e os produtos  abaixo da linha em branco n&atilde;o ser&atilde;o cadastrados.<br /><br />
                         <b>6.</b> Ao final da importa&ccedil;&atilde;o ser&aacute; exibido o n&uacute;mero total de produtos cadastrados. Verifique se corresponde ao n&uacute;mero de produtos listados na planilha importada. Caso n&atilde;o corresponda, &eacute; poss&iacute;vel que algum dos produtos n&atilde;o tenha sido cadastrado com sucesso.<br /><br />
                         <b>7.</b> Caso seja identificado algum erro no preenchimento da planilha, ser&aacute; exibido o n&uacute;mero da linha onde o erro se encontra e qual erro foi identificado para auxiliar na corre&ccedil;&atilde;o. IMPORTANTE: produto desta linha n&atilde;o ser&aacute; cadastrado.<br /><br />
                        <b>8.</b> Esteja atento para n&atilde;o cadastrar o mesmo produto mais de uma vez!<br /><br />
                        <b>9.</b> Caso tenha alguma d&uacute;vida, assista ao v&iacute;deo ao lado ou entre em contato conosco: <a href="mailto:ibench@ibench.com.br " target="_blank" title="iBench"><b>ibench@ibench.com.br</b></a> ou whatsapp (21) 98271-0963. <br /><br />
                        <b>10.</b> Regras para a formata&ccedil;&atilde;o do texto de Descri&ccedil;&atilde;o : <br /><br />
                          &lt;strong&gt; Texto &lt;/strong&gt; : Ficar em Negrito.<br />
                          &lt;p&gt; Texto &lt;/p&gt; : Criar um paragrafo. <br />
                          Texto &lt;br&gt; : Pula uma linha ap&oacute;s o texto. <br />
                          &lt;em&gt; Texto &lt;/em&gt; : Ficar em It&aacute;lico.<br />
                          &lt;h1&gt; Texto &lt;/h1&gt; : Maior tamanho poss&iacute;vel para um texto. <br />
                          &lt;h2&gt; Texto &lt;/h2&gt; : Segundo maior tamanho para um texto.<br />
                          &lt;h3&gt;&lt;h4&gt; : O mesmo que acima em menor escala. <br />
                          <br />
                          *Observa&ccedil;&atilde;o : N&atilde;o &eacute; necess&aacute;rio  nenhum dessas TAGS para atualizar a descri&ccedil;&atilde;o, mas para ter uma formata&ccedil;&atilde;o clara &eacute; recomend&aacute;vel  utilizar o paragrafo &lt;p&gt; ou pulo de linha &lt;br&gt; para separar os textos. 
                          <p>** Para ficar mais em evidencia recomendamos as TAGS &lt;strong&gt; ou &lt;h1&gt;&lt;h2&gt; para destacar t&iacute;tulos  ou palavras. <br />

                    </td>
                    
                    <td style="vertical-align:top;padding: 10px 25px 5px 25px;">
                        <iframe width="600" height="406" src="https://www.youtube.com/embed/V8dlHf7Rj34" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </td>  
                </tr>                         
            </table> 
           </td>
        </tr>
        
        </table>
          </div>  
        
        
   </div>
</div>
</div>
</div>
</div>

<div class="height30"></div>

@include('footer')
</body>
</html>

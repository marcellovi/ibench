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
                        {{ Session::get('error') }}
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
        <a href="{{ URL::to('downloadExcelModel') }}"><button class="btn btn-success">Baixar Modelo em Branco</button></a>
        <!--
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
         
            <table class="table table-bordered data-table" id="datatable-responsive">
              <thead>
                <tr>
                 <th>Lista de Categorias</th>
		<th>Lista de Marcas</th>
                <th>Observacoes Sobre a Importacao</th>
                </tr>
              </thead>
              <tbody>
              <?php //if(!empty($hreport_cnt)){
		 // $i=1;
		 // foreach ($hreport as $user) { $sta=$user->h_type_user; if($sta==1){ $viewst="Admin"; } else if($sta==2) { $viewst="Fornecedor"; } else if($sta==0) { $viewst="Comprador"; }?>
                
                <tr class="gradeX">                        
                    <td style="vertical-align:top;"><?php foreach($category as $cat){ echo utf8_decode($cat->subcat_name).'<br>'; } ?></td>
                        <td style="vertical-align:top;"><?php foreach($type as $value){ echo utf8_decode($value->attr_value).'<br>'; }?></td>
                        <td style="vertical-align:top;"></td>
                    </tr>
                
                <?php //$i++; } } ?>
                                
              </tbody>
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

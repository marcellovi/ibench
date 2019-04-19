<?php 
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
$ncurrentPath= Route::getFacadeRoot()->current()->uri();
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
$users = DB::select('select * from users where id = ?',[$setid]);	


$aid=1;
		$admindetails = DB::table('users')
		 ->where('id', '=', $aid)
		 ->first();
		
		$admin_email = $admindetails->email;							
?>


 <?php if(session()->has('message')){?>
    <script type="text/javascript">
        alert("<?php echo session()->get('message');?>");
		</script>
    </div>
	 <?php } ?>
     

<?php 

$pages = DB::table('pages')
		            
					
					->orderBy('page_title','asc')
					->get();
	$pages_cnt = DB::table('pages')
		            ->orderBy('page_title','asc')
					->count();



$cates_cnt = DB::table('category')
             ->where('delete_status','=','')
			 ->where('status','=',1)
			 ->take(5)
		     ->orderBy('id','asc')
			 ->count();



?>




<script src="<?php echo $url;?>/local/resources/views/theme/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="<?php echo $url;?>/local/resources/views/theme/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
	</script>
	<script>
		jQuery(document).ready(function(){
			
			jQuery("#formID").validationEngine('attach', { promptPosition: "topLeft" });
			jQuery("#formIfooter").validationEngine('attach', { promptPosition: "topLeft" });
			jQuery("#sellerID").validationEngine('attach', { promptPosition: "topLeft" });
			jQuery("#RATEID").validationEngine('attach', { promptPosition: "topLeft" });
		});
		
		
		
		
		function withdraw_checking(str)
    {	

        document.getElementById("localbank").style.display="none";
		document.getElementById("paypal").style.display="none";
		document.getElementById("stripe").style.display="none";
		
	if(str=="paypal")
	{	
		document.getElementById("localbank").style.display="none";
		document.getElementById("paypal").style.display="block";
		document.getElementById("stripe").style.display="none";
		
	}
	else if(str=="localbank")
	{
		document.getElementById("paypal").style.display="none";
		document.getElementById("localbank").style.display="block";
		document.getElementById("stripe").style.display="none";
		
	}
	else if(str=="stripe")
	{
		document.getElementById("paypal").style.display="none";
		document.getElementById("localbank").style.display="none";
		document.getElementById("stripe").style.display="block";
		
	}
	
	
}



		
    </script>
    
    
    
    
   

<script type="text/javascript" src="<?php echo $url;?>/local/resources/views/theme/js/jquery.simplePagination.min.js"></script>
            <script type="text/javascript">
		$(function(){
			var perPage = <?php echo $setts[0]->site_post_per;?>;
			var opened = 1;
			var onClass = 'on';
			var paginationSelector = '.pagess';
			$('.bloglist').simplePagination(perPage, opened, onClass, paginationSelector);
		});
		
		
		
		$(function(){
			var perPage = <?php echo $setts[0]->site_product_per;?>;
			var opened = 1;
			var onClass = 'on';
			var paginationSelector = '.grid_prodss';
			$('.gridlist').simplePagination(perPage, opened, onClass, paginationSelector);
		});
		
		
		
		
		$(function(){
			var perPage = <?php echo $setts[0]->site_product_per;?>;
			var opened = 1;
			var onClass = 'on';
			var paginationSelector = '.list_prodss';
			$('.list_list').simplePagination(perPage, opened, onClass, paginationSelector);
		});
		
		
		
		$(function(){
			var perPage = <?php echo $setts[0]->site_vendor_per;?>;
			var opened = 1;
			var onClass = 'on';
			var paginationSelector = '.grid_page';
			$('.grider').simplePagination(perPage, opened, onClass, paginationSelector);
		});
		
	</script>
    
    
    
    
    
   
    
    
    
        
        
       
		
		
       
 
  
  
  <footer id="footer" class="footer color-bg">
  <div class="footer-bottom">
    <div class="container">
      <div class="row">
        
        
        
        <div class="col-xs-12 col-sm-6 col-md-3">
          <div class="module-heading">
            <h4 class="module-title">@lang('languages.category')</h4>
          </div>
          
          
          <div class="module-body">
            <ul class='list-unstyled'>
              
              
              <?php
								if(!empty($cates_cnt))
								{			 
								$cates_get = DB::table('category')
											 ->where('delete_status','=','')
											 ->where('status','=',1)
											 ->take(5)
											 ->orderBy('id','asc')
											 ->get();
								foreach($cates_get as $viee){?>
									<li><a href="<?php echo $url;?>/shop/cat/<?php echo $viee->id;?>/<?php echo $viee->post_slug;?>"><?php echo $viee->cat_name;?></a></li>
									<?php } } ?>
              
            </ul>
          </div>
         
        </div>
        
        
        <div class="col-xs-12 col-sm-6 col-md-3">
          <div class="module-heading">
            <h4 class="module-title">@lang('languages.quick_links')</h4>
          </div>
          
          
          <div class="module-body">
            <ul class='list-unstyled'>
                <!-- Marcello Blog Retirado
              <li><a href="<?php echo $url;?>/blog">@lang('languages.blog')</a></li> -->
                                    <!-- Marcello Vendedores Wish List -->
									<li><a href="<?php echo $url;?>/vendors">@lang('languages.all_vendors')</a></li>
									
                                    <li><a href="/my-wishlist">@lang('languages.wish_list')</a></li>
                                    <li><a href="<?php echo $url;?>/contact-us">@lang('languages.contact_us')</a></li>
                                    <li><a href="<?php echo $url;?>/local/images/media/ibench_termos_condicoes.pdf" target="_blank">Termo de Uso</a></li>
            </ul>
          </div>
         
        </div>
        
        
        
        
        
        
        
        <div class="col-xs-12 col-sm-6 col-md-3">
          <div class="module-heading">
            <h4 class="module-title">@lang('languages.contact_us')</h4>
          </div>
          
          
          <div class="module-body">
            <ul class="toggle-footer  row" style="">
            	<li class="media">
                <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i class="fa fa-phone fa-stack-1x fa-inverse"></i> </span> </div>
                <div class="media-body">
                  <p style="margin-top: 7px;">+55 21 98271-0963</p>
                </div>
              </li>
              <!--
              <li class="media">
                <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i class="fa fa-map-marker fa-stack-1x fa-inverse"></i> </span> </div>
                <div class="media-body">
                  <p><?php echo $setts[0]->site_address;?></p>
                </div>
              </li>
							-->

              <!-- 
              <li class="media">
                <div class="pull-left"> 
                    <span class="icon fa-stack fa-lg"> <i class="fa fa-mobile fa-stack-1x fa-inverse"></i> </span> </div>
                    Marcello Retirado temporarimante o telefone 
                <div class="media-body">
                    
                  <p><a href="tel:<?php echo $setts[0]->site_phone;?>" class="white_ash"> <?php echo $setts[0]->site_phone;?></a>
                    </p>
                    
                </div>
              </li>-->
              <li class="media">
                <div class="pull-left"> <span class="icon fa-stack fa-lg"> <i class="fa fa-envelope fa-stack-1x fa-inverse"></i> </span> </div>
                <div class="media-body" style="margin-top: 2px;"> 
                	<span><a href="mailto:<?php echo $setts[0]->site_email;?>"> <?php echo $setts[0]->site_email;?></a></span> 
                </div>
              </li>
            </ul>
          </div>
          <!-- /.module-body --> 
        </div>
        
        
        
        
        <div class="col-xs-12 col-sm-6 col-md-3">
          <div class="module-heading">
            <h4 class="module-title">@lang('languages.follow_us')</h4>
          </div>
          
          
          <div class="social">
           <!-- Marcello LinkedIn -->
               <?php if(!empty($setts[0]->site_facebook)){?>            
          <a href="<?php echo $setts[0]->site_facebook;?>" target="_blank"><i class="fa fa-facebook"></i></a>
          <a href="<?php echo $setts[0]->site_twitter;?>" target="_blank"><i class="fa fa-linkedin"></i></a>
          <?php } ?>
        <!-- Marcello Social 
          <?php if(!empty($setts[0]->site_facebook)){?>            
          <a href="<?php echo $setts[0]->site_facebook;?>" target="_blank"><i class="fa fa-facebook"></i></a><?php } ?>
                                    
                                    <?php if(!empty($setts[0]->site_twitter)){?><a href="<?php echo $setts[0]->site_twitter;?>" target="_blank"><i class="fa fa-twitter"></i></a><?php } ?>
                                    <?php if(!empty($setts[0]->site_gplus)){?><a href="<?php echo $setts[0]->site_gplus;?>" target="_blank"><i class="fa fa-google-plus"></i></a><?php } ?>
									<?php if(!empty($setts[0]->site_pinterest)){?><a href="<?php echo $setts[0]->site_pinterest;?>" target="_blank"><i class="fa fa-pinterest"></i></a><?php } ?>
									<?php if(!empty($setts[0]->site_instagram)){?><a href="<?php echo $setts[0]->site_instagram;?>" target="_blank"><i class="fa fa-instagram"></i></a><?php } ?>
       -->
          </div>
          
        </div>
        
        
      </div>
    </div>
  </div>
  <div class="copyright-bar">
    <div class="container-fluid">
      <div class="col-xs-12 col-sm-12 no-padding social text-center footertxt">
        <p>@lang('languages.copyright') <?php echo date('Y');?>  @lang('languages.all_right_reserved') <br> CNPJ: 22.632.205/0001-22</p>
      </div>
      
    </div>
  </div>
</footer>



 
<script src="<?php echo $url;?>/local/resources/views/theme/js/bootstrap.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/bootstrap-hover-dropdown.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/owl.carousel.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/echo.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/jquery.easing-1.3.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/bootstrap-slider.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/jquery.rateit.min.js"></script> 
<script type="text/javascript" src="<?php echo $url;?>/local/resources/views/theme/js/lightbox.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/bootstrap-select.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/wow.min.js"></script> 
<script src="<?php echo $url;?>/local/resources/views/theme/js/scripts.js"></script>

<script src="<?php echo $url;?>/local/resources/views/theme/popup_image/lumos.js"></script>

	


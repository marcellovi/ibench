<?php
use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();	
$url = URL::to("/");
$setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		
if($currentPaths=="/")
 {
 $activemenu = "/";
 }
 else 
 {
  $activemenu = $currentPaths;
 }
 
 
 
if($activemenu == "/"){ $active_home = "active"; } else { $active_home =""; }
if($activemenu == "gallery") { $active_gallery = "active"; } else { $active_gallery = ""; }


if($activemenu == "blog" or $activemenu == "blog/{id}") { $active_blog = "active"; } else { $active_blog = ""; }
if($activemenu == "contact-us") { $active_contact = "active"; } else { $active_contact = ""; }

if($activemenu == "register"){ $active_register = "active"; } else { $active_register = ""; }
if($activemenu == "dashboard" or $activemenu == "my-comments"){ $active_dashboard = "active"; } else { $active_dashboard = ""; }

$pages_cnt = DB::table('pages')
		            ->orderBy('page_title','asc')
					->count();
if(!empty($pages_cnt))
{					
$pages = DB::table('pages')
					->orderBy('page_title','asc')
					->get();
}	
					
					
$cate_cnt = DB::table('category')
             ->where('delete_status','=','')
			 ->where('status','=',1)
			 ->take(4)
		     ->orderBy('id','asc')
			 ->count();
if(!empty($cate_cnt))
{			 
$cate_get = DB::table('category')
             ->where('delete_status','=','')
			 ->where('status','=',1)
			 ->take(4)
		     ->orderBy('id','asc')
			 ->get();				 					
}





if(Auth::check()) {
	   $log_id = Auth::user()->id;
	   
	   $cart_views_count = DB::table('product_orders')
		
		->where('user_id', '=', $log_id)
		->where('order_status', '=', 'pending')
		
		->count();
	   
	   
	   $cart_views = DB::table('product_orders')
		
		->where('user_id', '=', $log_id)
		->where('order_status', '=', 'pending')
		
		->get();
		
		}
		else
		{
		$cart_views_count = 0;
		$cart_views = "";
		
		}
		
?>


<?php if($setts[0]->site_loading==1){?>

<div class="avigher_loader"></div>

<?php } ?>


<header class="header-style-1">


  <div class="top-bar animate-dropdown">
    <div class="container-fluid">


    </div>

  </div>


  <div class="container-fluid showing">
    <div class="row">
      <div id="m_nav_container" class="m_nav">

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mobilemenu">


          <div class="navbar-collapse" id="navbar1">

            <ul class="navbar-nav ml-auto">
              <li class="active dropdown yamm-fw"> <a href="<?php echo $url;?>" data-hover="dropdown"
                  class="dropdown-toggle disabled" data-toggle="dropdown">@lang('languages.home')</a> </li>


              <li>
                <a href="<?php echo $url;?>/shop">@lang('languages.all')</a>

              </li>

              <?php
					$cate_cnts = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->where('display_menu','=',1)
								 ->orderBy('cat_name','asc')
								 ->count();
					if(!empty($cate_cnts))
					{
					
					$views_category = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->where('display_menu','=',1)
								 ->orderBy('cat_name','asc')
								 ->get();	
					foreach($views_category as $views){
					
					$subcat_cnt = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									
									->where('cat_id','=',$views->id)
									
									->orderBy('subid','asc')
									->count();			 		 
					?>
              <li> <a href="<?php echo $url;?>/shop/cat/<?php echo $views->id;?>/<?php echo $views->post_slug;?>"
                  class="dropdown-toggle <?php if(empty($subcat_cnt)){?>disabled<?php } ?>"
                  data-toggle="dropdown"><?php echo $views->cat_name;?> <?php if(!empty($subcat_cnt)){?><i
                    class="fa fa-angle-down" aria-hidden="true"></i><?php } ?></a>

                <?php
					  
					  if(!empty($subcat_cnt))
					  {	
					  
					  $viewsub = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									
									->where('cat_id','=',$views->id)
									
									->orderBy('subid','asc')
									->get();
					  
					  		
					  ?>



                <ul class="dropdown-menu">
                  <?php foreach($viewsub as $subs){?>
                  <li><a
                      href="<?php echo $url;?>/shop/subcat/<?php echo $subs->subid;?>/<?php echo $subs->post_slug;?>"><?php echo $subs->subcat_name;?></a>
                  </li>
                  <?php } ?>
                </ul>



              </li>
              <?php } } } ?>



              <li class="nav-item dropdown">
                <a href="javascript:void(0)" class="nav-link  dropdown-toggle"
                  data-toggle="dropdown">@lang('languages.pages')</a>


                <ul class="dropdown-menu">



                  <?php if(!empty($pages_cnt)){?>
                  <?php foreach($pages as $page){
								if($page->page_id==4){ $pagerurl = $url.'/'.'contact-us'; }
								
								else { $pagerurl = $url.'/page/'.$page->page_id.'/'.$page->post_slug; }
								?>

                  <li><a href="<?php echo $pagerurl; ?>"><?php echo $page->page_title;?></a></li>
                  <?php } } ?>





                </ul>
              </li>


              <!-- Marcello Retirada Blog Aba 
												<li>
                                                    <a href="<?php echo $url;?>/blog">@lang('languages.blog')</a> 
                                                     
                                                </li> -->
              <!-- Marcello Vendedores
                                                <li>
                                                    <a href="<?php echo $url;?>/vendors">@lang('languages.vendors')</a> 
                                                     
                                                </li>
                                                -->






            </ul>







          </div>
        </nav>



      </div>
    </div>
  </div>



  <div class="main-header">
    <div class="container-fluid">
      <div class="row">

        <div class="col-xs-4 col-sm-4 col-md-6">

        </div>

        <div class="col-xs-8 col-sm-8 col-md-6">
          <div class="top-bar animate-dropdown">
            <div class="header-top-inner">


              <div class="cnt-block">
                <ul class="list-unstyled list-inline">

                  <!-- Internacionalizacao Marcello 
            <li class="dropdown dropdown-small"> <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="fa fa-language" aria-hidden="true"></i><span class="value">@lang('languages.english') </span><b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo $url;?>/<?php echo Lang::get('setlocale/en');?>">@lang('languages.english')</a></li>
				<?php /* ?><li><a href="<?php echo $url;?>/<?php echo Lang::get('setlocale/ar');?>">@lang('languages.arabic')</a></li><?php */?>
                <li><a href="<?php echo $url;?>/<?php echo Lang::get('setlocale/es');?>">@lang('languages.spanish')</a></li>
                <li><a href="<?php echo $url;?>/<?php echo Lang::get('setlocale/fr');?>">@lang('languages.french')</a></li>
                <li><a href="<?php echo $url;?>/<?php echo Lang::get('setlocale/de');?>">@lang('languages.german')</a></li>
              </ul>
            </li>
            -->


                  <li class="dropdown dropdown-small"> <a href="<?php echo $url;?>/dashboard" class="dropdown-toggle"
                      data-hover="dropdown" data-toggle="dropdown"><i class="icon fa fa-user"></i><span
                        class="value">@lang('languages.my_account')</span><b class="caret"></b></a>
                    <?php if(Auth::guest()) { ?>
                    <ul class="dropdown-menu customheight">
                      <li><a href="<?php echo $url;?>/login">@lang('languages.login')</a></li>
                      <li><a href="<?php echo $url;?>/register">@lang('languages.register')</a></li>

                    </ul>

                    <?php } else { ?>

                    <ul class="dropdown-menu customheight">
                      <li><a href="<?php echo $url;?>/dashboard">@lang('languages.my_dashboard')</a></li>

                      <?php if(Auth::user()->admin==0){?>
                      <li><a href="<?php echo $url;?>/my-shopping">@lang('languages.my_shopping')</a></li>
                      <?php } ?>

                      <?php if(Auth::user()->admin==2){?>
                      <li><a href="<?php echo $url;?>/my-product">@lang('languages.my_product')</a></li>
                      <li><a href="<?php echo $url;?>/waiting-list">Lista de Espera</a></li>

                      <li><a href="<?php echo $url;?>/my-orders">@lang('languages.my_orders')</a></li>
                      <li><a href="<?php echo $url;?>/my-shopping">@lang('languages.my_shopping')</a></li>
                      <!-- Meus atributos Marcello
                                                        <li class="submenu_new" >
                                                            <a href="<?php echo $url;?>/attribute-type" class="dropdown-toggle">@lang('languages.my_attributes') <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                                            <ul class="dropdown-menu">
                                                                <li><a href="<?php echo $url;?>/attribute-type">@lang('languages.attribute_type')</a></li>
                                                                <li><a href="<?php echo $url;?>/attribute-value">@lang('languages.attribute_value')</a></li>
                                                            </ul>
                                                        </li>
                                                        -->
                      <!--   Meu Financeiro - Marcello 
                                                        <li><a href="<?php echo $url;?>/my-balance">@lang('languages.my_balance')</a></li>
                                                    -->
                      <?php } ?>
                      <li><a href="https://wa.me/5521980774859?text=Envie%20sua%20pergunta%20ao%20Suporte%20iBench."
                          target="_blank"> Suporte Chat</a></li>
                      <li><a href="{{ route('logout') }}"
                          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          @lang('languages.logout')</a></li>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                      </form>
                    </ul>
                    <?php } ?>
                  </li>
                </ul>













              </div>



              <div class="cnt-account hider">
                <ul class="list-unstyled">
                  <!-- Hid Compare & WishList Marcello 
           <li><a href="<?php echo $url;?>/compare"><i class="icon fa fa-compress"></i>@lang('languages.compare')</a></li>
            MARCELLO -->
                  <li>
                    <a href="<?php echo $url;?>/my-wishlist"><i
                        class="icon fa fa-heart"></i>@lang('languages.wishlist')</a></li>

                  <li><a href="<?php echo $url;?>/cart"><i
                        class="icon fa fa-shopping-cart"></i>@lang('languages.my_cart')</a></li>


                </ul>
              </div>




              <div class="clearfix"></div>
            </div>

          </div>

        </div>







        <div class="col-xs-6 col-sm-6 col-md-2 logo-holder">


          <div class="logo">

            <?php if(!empty($setts[0]->site_logo)){?>

            <a href="<?php echo $url;?>/.."><img src="<?php echo $url.'/local/images/media/'.$setts[0]->site_logo;?>"
                alt="<?php echo $setts[0]->site_name;?>" /></a>
            <?php } else {?>
            <a href="<?php echo $url;?>"><?php echo $setts[0]->site_name;?></a>
            <?php } ?>
          </div>
        </div>











        <div class="col-xs-6 col-sm-6 col-md-7 top-search-holder">


          <div id="toggle_m_nav" href="#" class="showing">
            <div id="m_nav_menu" class="m_nav">
              <div class="m_nav_ham" id="m_ham_1"></div>
              <div class="m_nav_ham" id="m_ham_2"></div>
              <div class="m_nav_ham" id="m_ham_3"></div>
            </div>
          </div>







        </div>



        <div class="col-xs-12 col-sm-12 col-md-7 top-search-holder">

          <div class="search-area">

            <form class="register-form" role="form" method="POST" action="{{ route('shop') }}" id="formIDwel"
              enctype="multipart/form-data" accept-charset="utf-8">
              {{ csrf_field() }}
              <div class="control-group">
                <ul class="categories-filter animate-dropdown">


                  <select name="category">
                    <!-- Marcello <option value=""></option> -->
                    <option value="all" <?php if(!empty($category_field)){?> <?php if($category_field=="all"){?>
                      selected="selected" <?php } } ?>>Todas Categorias</option>
                    <?php
					$cate_cnt = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->orderBy('cat_name','asc')
								 ->count();
					if(!empty($cate_cnt))
					{
					
					$view_category = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->orderBy('cat_name','asc')
								 ->get();	
					foreach($view_category as $views){			 		 
					?>
                    <option value="<?php echo $views->id;?>_cat" <?php if(!empty($category_field)){?>
                      <?php if($category_field==$views->id.'_cat'){?> selected="selected" <?php } } ?>>
                      <?php echo $views->cat_name;?>


                      <?php
					  $subcat_cnt = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									->where('cat_id','=',$views->id)
									
									->orderBy('subid','asc')
									->count();
					  if(!empty($subcat_cnt))
					  {	
					  
					  $viewsub = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									->where('cat_id','=',$views->id)
									
									->orderBy('subid','asc')
									->get();
					  
					  foreach($viewsub as $subs){			
					  ?>

                    <option value="<?php echo $subs->subid;?>_subcat" <?php if(!empty($category_field)){?>
                      <?php if($category_field==$subs->subid.'_subcat'){?> selected="selected" <?php } } ?>>&nbsp; -
                      <?php echo $subs->subcat_name;?></option>



                    <?php } ?>
                    </option>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  </select>


                </ul>
                <input class="search-field" name="search_text" <?php if(!empty($search_txt)){?>
                  value="<?php echo utf8_encode($search_txt);?>" <?php } ?>
                  placeholder="@lang('languages.search_here')" />

                <button type="submit" class="search-button">

                </button>
              </div>
            </form>
          </div>
        </div>


















        <?php if(!empty($cart_views_count)){
		  
		  $price_val = 0;
		  foreach($cart_views as $product)
		  { 
		     $price_val += $product->price * $product->quantity;
		  } 
		  $price_values = $price_val; 
		  }
		  else
		  {
		  $price_values = 0;
		  }
		  ?>

        <div class="col-xs-12 col-sm-12 col-md-3 animate-dropdown top-cart-row">

          <div class="dropdown dropdown-cart"> <a href="<?php echo $url;?>/cart" class="dropdown-toggle lnk-cart"
              data-toggle="dropdown">
              <div class="items-cart-inner">
                <div class="basket"> <i class="glyphicon glyphicon-shopping-cart"></i> </div>
                <div class="basket-item-count"><span class="count"><?php echo $cart_views_count;?></span></div>
                <div class="total-price-basket"> <span class="total-price"> <span
                      class="sign"><?php echo $setts[0]->site_currency;?></span> <span
                      class="value"><?php echo number_format($price_values,2,",",".");?></span> </span> </div>
              </div>
            </a>
            <ul class="dropdown-menu">



              <?php if(!empty($cart_views_count)){?>
              <li>

                <?php 
								$price_val = 0;
								foreach($cart_views as $product){
								
								 $prod_id = $product->prod_token; 
								 $product_img_count = DB::table('product_images')
													->where('prod_token','=',$prod_id)
													->count();
													
								$view_product = DB::table('product')
													->where('prod_token','=',$prod_id)
													->get();					
													?>
                <div class="cart-item product-summary">
                  <div class="row">
                    <div class="col-xs-4">
                      <div class="image">

                        <?php
														if(!empty($product_img_count)){					
														$product_img = DB::table('product_images')
																			->where('prod_token','=',$prod_id)
																			->orderBy('prod_img_id','asc')
																			->get();
																		
														
														?>
                        <a
                          href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo $view_product[0]->prod_slug;?>"><img
                            src="<?php echo $url;?>/local/images/media/<?php echo $product_img[0]->image;?>" alt=""></a>
                        <?php } else { ?>
                        <a
                          href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo $view_product[0]->prod_slug;?>"><img
                            src="<?php echo $url;?>/local/images/noimage_box.jpg" alt=""></a>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-xs-7">
                      <h3 class="name"><a
                          href="<?php echo $url;?>/product/<?php echo $product->prod_id;?>/<?php echo $view_product[0]->prod_slug;?>"><?php echo utf8_decode($view_product[0]->prod_name);?></a>
                      </h3>
                      <div class="price">
                        <?php echo $setts[0]->site_currency.' '.number_format($product->price,2,",",".").' ';?> X
                        <?php echo $product->quantity;?></div>
                    </div>
                    <?php $price_val += $product->price * $product->quantity; ?>
                    <div class="col-xs-1 action"> <a href="<?php echo $url;?>/cart/<?php echo $product->ord_id;?>"
                        onClick="return confirm('@lang('languages.are_you_sure')');"><i class="fa fa-trash"></i></a>
                    </div>
                  </div>
                </div>

                <?php } ?>

                <div class="clearfix"></div>
                <hr>
                <div class="clearfix cart-total">
                  <div class="pull-right"> <span class="text">@lang('languages.subtotal') :</span><span
                      class='price'><?php echo $setts[0]->site_currency.' '.number_format($price_val,2,",",".").' ';?></span>
                  </div>
                  <div class="clearfix"></div>
                  <a href="<?php echo $url;?>/cart"
                    class="btn btn-upper btn-primary btn-block m-t-20">@lang('languages.cart')</a>
                </div>


              </li>


              <?php } ?>

              <?php if(empty($cart_views_count)){?>
              <li>
                <div align="center">@lang('languages.your_cart_is_empty')</div>
              </li>
              <?php } ?>

            </ul>

          </div>

        </div>









      </div>


    </div>


  </div>
    
    <div class="collapse navbar-collapse justify-content-md-center" style="background-color:#FE8F18">
        <div style="padding-top: 5px">
           <p style="color: white; text-align: center; padding-top: 5px;font-size: 20px">Temporariamente: entregas s&oacute; podem ser realizadas na cidade do Rio de Janeiro</p> 
        </div>
        
        <!--
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Centered nav only <span class="sr-only">(current)</span></a>
      </li>
    </ul>
        -->
  </div>
    <br>




  <div class="header-nav animate-dropdown">
    <div class="container-fluid">
      <div class="yamm navbar navbar-default" role="navigation">
        <div class="navbar-header hider">
          <button data-target="#mc-horizontal-menu-collapse" data-toggle="collapse" class="navbar-toggle collapsed"
            type="button">
            <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span>
            <span class="icon-bar"></span> </button>
        </div>
        <div class="nav-bg-class">
          <div class="navbar-collapse collapse" id="mc-horizontal-menu-collapse">
            <div class="nav-outer">
              <ul class="nav navbar-nav">
                <li class="active dropdown yamm-fw"> <a href="<?php echo $url;?>/.." data-hover="dropdown"
                    class="dropdown-toggle disabled" data-toggle="dropdown">@lang('languages.home')</a> </li>
                <!-- Marcello Aba All.
                <li>
                                                    <a href="<?php echo $url;?>/shop">@lang('languages.all')</a> 
                                                     
                                                </li>
                -->

                <?php
					$cate_cnts = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->where('display_menu','=',1)
								 ->orderBy('cat_name','asc')
								 ->count();
					if(!empty($cate_cnts))
					{
					
					$views_category = DB::table('category')
								 ->where('delete_status','=','')
								 ->where('status','=',1)
								 ->where('display_menu','=',1)
								 ->orderBy('cat_name','asc')
								 ->get();	
					foreach($views_category as $views){			 		 
					?>
                <li class="dropdown"> <a
                    href="<?php echo $url;?>/shop/cat/<?php echo $views->id;?>/<?php echo $views->post_slug;?>"
                    class="dropdown-toggle  disabled" data-hover="dropdown"
                    data-toggle="dropdown"><?php echo utf8_decode($views->cat_name);?></a>

                  <?php // Marcello - Foi trocado o subid para subcat_name para organizar os submenus por ordem alfabetica
					  $subcat_cnt = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									
									->where('cat_id','=',$views->id)
									
									->orderBy('subcat_name','asc')
									->count();
					  if(!empty($subcat_cnt))
					  {	
					  
					  $viewsub = DB::table('subcategory')
									->where('delete_status','=','')
									->where('status','=',1)
									
									->where('cat_id','=',$views->id)
									
									->orderBy('subcat_name','asc')
									->get();
					  
					  		
					  ?>
                  <ul class="dropdown-menu pages">
                    <li>
                      <div class="yamm-content">
                        <div class="row">
                          <div class="col-xs-12 col-menu">
                            <ul class="links">
                              <?php foreach($viewsub as $subs){?>
                              <li><a
                                  href="<?php echo $url;?>/shop/subcat/<?php echo $subs->subid;?>/<?php echo $subs->post_slug;?>"><?php echo utf8_decode($subs->subcat_name);?></a>
                              </li>
                              <?php } ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </li>
                  </ul>
                </li>
                <?php } } } ?>

                <li>
                    <!-- Marcello :: Menu Fornecedores -->
                    <a href="<?php echo $url;?>/vendors">@lang('languages.vendors')</a>
                </li>
                
                <li>
                    <!-- Marcello :: Menu IBENCH NOW -->
                    <a href="https://docs.google.com/forms/d/e/1FAIpQLSdT_WCuYqI7VRJ74hC6scDi1zXRixU6gpbsQ_jtaMonu1NpLg/viewform" target="_blank">iBench Now</a>
                </li>
                
                <li>
                    <!-- Marcello :: Menu Fornecedores -->
                    <a href="<?php echo $url;?>/contact-us">fale Conosco</a>
                </li>

                <!--
                <li class="dropdown"> <a href="javascript:void(0)" class="dropdown-toggle  disabled"
                    data-hover="dropdown" data-toggle="dropdown">@lang('languages.pages')</a>
                

                  <ul class="dropdown-menu pages">
                    <li>
                      <div class="yamm-content">
                        <div class="row">
                          <div class="col-xs-12 col-menu">
                            <ul class="links">
                              <?php if(!empty($pages_cnt)){?>
                              <?php foreach($pages as $page){
								if($page->page_id==4){ $pagerurl = $url.'/'.'contact-us'; }
								
								else { $pagerurl = $url.'/page/'.$page->page_id.'/'.$page->post_slug; }
								?>

                              <li><a href="<?php echo $pagerurl; ?>"><?php echo utf8_decode($page->page_title);?></a>
                              </li>
                              <?php } } ?>
                             

                              <li>
                                <a href="<?php echo $url;?>/blog">@lang('languages.blog')</a>                       
                              </li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </li>


                  </ul>
                </li>
                -->
                <!-- Retirada do Blog Marcello
                                                
												<li>
                                                    <a href="<?php echo $url;?>/blog">@lang('languages.blog')</a> 
                                                     
                                                </li>
                                               -->
                <!-- Marcello Vendedores
                                                <li>
                                                    <a href="<?php echo $url;?>/vendors">@lang('languages.vendors')</a> 
                                                     
                                                </li>
                                                -->




              </ul>

              <div class="clearfix"></div>
            </div>

          </div>


        </div>

      </div>

    </div>


  </div>


</header>
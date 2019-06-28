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
<html lang="en">

<head>
  @include('style')
</head>

<body class="cnt-home">
  @include('header')

  <div class="breadcrumb">
    <div class="container-fluid">
      <div class="breadcrumb-inner">
        <ul class="list-inline list-unstyled">
          <li> <a href="<?php echo $url; ?>">@lang('languages.home')</a></li>
          <li class='active'>@lang('languages.shop')</li>
          <?php if (!empty($search_txt)) { ?>
            <li class='active'><?php echo utf8_decode($search_txt); ?></li>
          <?php } ?>
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
          @endif @if(Session::has('error'))
          <p class="alert alert-danger">
            {{ Session::get('error') }}
          </p>
          @endif
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 sidebar">
          <form class="register-form" role="form" name="formID" method="POST" action="{{ route('shoping') }}" id="formID" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="sidebar-module-container">
              <div class="sidebar-filter">
                <div class="sidebar-widget wow fadeInUp">
                  <h3 class="section-title">@lang('languages.shop_by')</h3>
                  <div class="widget-header">
                    <h4 class="widget-title">@lang('languages.category')</h4>
                  </div>
                  <div class="sidebar-widget-body">
                    <select name="inside_category" class="form-control unicase-form-control">
                      <?php if (!empty($category_cnt)) { ?>
                        <option value="all">@lang('languages.all_category')</option>
                        <?php foreach ($category as $catery) {
                          $total_sub_cnt = 0;
                          $wellcount = DB::table('product')
                            ->where('delete_status', '=', '')
                            ->where('prod_status', '=', 1)
                            ->where('prod_category', '=', $catery->id)
                            ->where('prod_cat_type', '=', 'cat')
                            ->count();

                          $total_sub_cnt += $wellcount;
                          $subcat_cnt = DB::table('subcategory')
                            ->where('delete_status', '=', '')
                            ->where('status', '=', 1)
                            ->where('cat_id', '=', $catery->id)
                            ->orderBy('subid', 'asc')
                            ->count();
                          if (!empty($subcat_cnt)) {
                            $subcat_get22 = DB::table('subcategory')
                              ->where('delete_status', '=', '')
                              ->where('status', '=', 1)
                              ->where('cat_id', '=', $catery->id)
                              ->orderBy('subcat_name', 'asc')
                              ->get();
                            foreach ($subcat_get22 as $subcat22) {

                              $total_sub_cnt += DB::table('product')
                                ->where('delete_status', '=', '')
                                ->where('prod_status', '=', 1)
                                ->where('prod_category', '=', $subcat22->subid)
                                ->where('prod_cat_type', '=', 'subcat')
                                ->count();
                            }
                          }
                          ?>
                          <option value="<?php echo $catery->id; ?>_cat" <?php if ($id == $catery->id) { ?> selected <?php } ?>><?php echo utf8_decode($catery->cat_name); ?> [<?php echo $total_sub_cnt; ?>]

                            <?php
                            if (!empty($subcat_cnt)) {
                              $subcat_get = DB::table('subcategory')
                                ->where('delete_status', '=', '')
                                ->where('status', '=', 1)
                                ->where('cat_id', '=', $catery->id)
                                ->orderBy('subcat_name', 'asc')
                                ->get();
                              foreach ($subcat_get as $subcat) {
                                $wellcount_two = DB::table('product')
                                  ->where('delete_status', '=', '')
                                  ->where('prod_status', '=', 1)
                                  ->where('prod_category', '=', $subcat->subid)
                                  ->where('prod_cat_type', '=', 'subcat')
                                  ->count();
                                ?>
                              <option value="<?php echo $subcat->subid; ?>_subcat" <?php if ($id == $subcat->subid) { ?> selected <?php } ?>> - <?php echo utf8_decode($subcat->subcat_name); ?> [<?php echo $wellcount_two; ?>]</option>

                            <?php }
                        } ?>

                          </option>

                        <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="sidebar-widget wow fadeInUp">
                  <div class="sidebar-widget-body">
                    <?php if (!empty($typers_count)) { ?>
                      <?php foreach ($typers as $type) {

                        $value_cnt = DB::table('product_attribute_value')
                          ->where('delete_status', '=', '')
                          ->where('status', '=', 1)
                          ->where('attr_id', '=', $type->attr_id)
                          ->orderBy('attr_value', 'asc')->count();

                        $value = DB::table('product_attribute_value')
                          ->where('delete_status', '=', '')
                          ->where('status', '=', 1)
                          ->where('attr_id', '=', $type->attr_id)
                          ->orderBy('attr_value', 'asc')->get();
                        ?>
                        <div>
                          <h4 class="widget-title"><?php echo utf8_decode($type->attr_name); ?></h4>
                        </div>
                        <!--//==Product Price List Start==// -->

                        <ul class="list">
                          <?php if (!empty($value_cnt)) { ?>
                            <?php foreach ($value as $values) { ?>

                              <?php
                              // Mark the attribute from Marca
                              if (!empty($name)) {
                                $valcheck = explode(",", $name); // Separate all ids in to an array
                                $flag = false;
                                foreach ($valcheck as $marked) {
                                  if ($marked == $values->value_id) {
                                    ?>
                                    <li><input type="checkbox" name="attribute[]" class="unicase-form-control" value="<?php echo $values->value_id; ?>" checked="true"><label class="radio-label"><?php echo utf8_decode($values->attr_value); ?> <span class="pull-right"></span></label><span class=""></span></li>
                                    <?php
                                    $flag = true; // found it
                                    break;
                                  }
                                  ?>

                                <?php  } // end foreach
                              if (!$flag) {
                                ?>
                                  <li><input type="checkbox" name="attribute[]" class="unicase-form-control" value="<?php echo $values->value_id; ?>"><label class="radio-label"><?php echo utf8_decode($values->attr_value); ?> <span class="pull-right"></span></label><span class=""></span></li>
                                <?php } ?>


                              <?php
                            } else { ?>
                                <li><input type="checkbox" name="attribute[]" class="unicase-form-control" value="<?php echo $values->value_id; ?>"><label class="radio-label"><?php echo utf8_decode($values->attr_value); ?> <span class="pull-right"></span></label><span class=""></span></li>
                              <?php }
                            ?>


                            <?php } ?>
                          <?php } ?>
                        </ul>

                      <?php }
                  } ?>
                  </div>
                  <div class="sidebar-widget-body">
                    <?php if (!empty($sellers_count)) { ?>
                      <div>
                        <h4 class="widget-title">Fornecedores</h4>
                      </div>
                      <ul class="list">
                        <?php foreach ($sellers as $user) { ?>

                          <?php
                          // Mark the checkbox Fornecedores
                          if (!empty($sellers_id)) {
                            $valcheck = explode(",", $sellers_id); // Separate all ids in to an array
                            $flag = false;
                            foreach ($valcheck as $marked) {
                              if ($marked == $user->id) {
                                ?>
                                <li>
                                  <input id="checkbox2" type="checkbox" name="seller[]" class="unicase-form-control" value="<?php echo $user->id; ?>" checked="true">
                                  <label class="radio-label" for="checkbox2"><?php echo utf8_decode(mb_convert_case($user->name_business, MB_CASE_TITLE, "UTF-8")); ?> <span class="pull-right"></span></label><span class=""></span>
                                </li>
                                <?php
                                $flag = true; // found it
                                break;
                              }
                              ?>

                            <?php  } // end foreach
                          if (!$flag) {
                            ?>
                              <li>
                                <input id="checkbox2" type="checkbox" name="seller[]" class="unicase-form-control" value="<?php echo $user->id; ?>">
                                <label class="radio-label" for="checkbox2"><?php echo utf8_decode(mb_convert_case($user->name_business, MB_CASE_TITLE, "UTF-8")); ?> <span class="pull-right"></span></label><span class=""></span>
                              </li>
                            <?php } ?>


                          <?php
                        } else { ?>
                            <li>
                              <input id="checkbox2" type="checkbox" name="seller[]" class="unicase-form-control" value="<?php echo $user->id; ?>">
                              <label class="radio-label" for="checkbox2"><?php echo utf8_decode(mb_convert_case($user->name_business, MB_CASE_TITLE, "UTF-8")); ?> <span class="pull-right"></span></label><span class=""></span>
                            </li>
                          <?php }
                        ?>


                        <?php } ?>
                      </ul>

                    <?php }  ?>
                  </div>
                  <div class="widget-header">
                    <h4 class="widget-title">@lang('languages.price')</h4>
                  </div>
                  <div class="sidebar-widget-body m-t-10">

                    <ul class="list">
                      <li><input id="checkbox5" type="radio" name="price" class="unicase-form-control" value="all" <?php if ($price == 'all') {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>><label class="radio-label" for="checkbox5">Todos<span class="pull-right"></span></label><span class=""></span></li>
                      <li><input id="checkbox6" type="radio" name="price" class="unicase-form-control" value="0_50" <?php if ($price == '0_50') {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>><label class="radio-label" for="checkbox6">@lang('languages.under') <?php echo $setts[0]->site_currency; ?> 50 <span class="pull-right"></span></label><span class=""></span></li>
                      <li><input id="checkbox7" type="radio" name="price" class="unicase-form-control" value="50_100" <?php if ($price == '50_100') {
                                                                                                                        echo 'checked';
                                                                                                                      } ?>><label class="radio-label" for="checkbox7"><?php echo $setts[0]->site_currency; ?> 50 - <?php echo $setts[0]->site_currency; ?> 100<span class="pull-right"></span></label><span class=""></span></li>
                      <li><input id="checkbox8" type="radio" name="price" class="unicase-form-control" value="100_200" <?php if ($price == '100_200') {
                                                                                                                          echo 'checked';
                                                                                                                        } ?>><label class="radio-label" for="checkbox8"><?php echo $setts[0]->site_currency; ?> 100 - <?php echo $setts[0]->site_currency; ?> 200 <span class="pull-right"></span></label><span class=""></span></li>
                      <li><input id="checkbox9" type="radio" name="price" class="unicase-form-control" value="200_500" <?php if ($price == '200_500') {
                                                                                                                          echo 'checked';
                                                                                                                        } ?>><label class="radio-label" for="checkbox9"><?php echo $setts[0]->site_currency; ?> 200 - <?php echo $setts[0]->site_currency; ?> 500 <span class="pull-right"></span></label><span class=""></span></li>
                      <li><input id="checkbox0" type="radio" name="price" class="unicase-form-control" value="500_10000" <?php if ($price == '500_10000') {
                                                                                                                            echo 'checked';
                                                                                                                          } ?>><label class="radio-label" for="checkbox0">@lang('languages.above') <?php echo $setts[0]->site_currency; ?> 500 <span class="pull-right"></span></label><span class=""></span></li>
                    </ul>

                  </div>
                  <div class="clearfix height20"></div>
                  <input type="hidden" id="search_txt" name="search_txt" value="<?php echo $search_txt; ?>">
                  <div>
                    <input type="submit" name="search" class="lnk btn btn-primary" value="@lang('languages.filter')" />
                    <input type="button" id="resetar" class="lnk btn btn-primary" value="Limpar Filtro" />
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-9">
          <div class="row">
            <div class="col-md-12">
              <div class="heading-title" style="border-bottom:none !important;">
                @lang('languages.shop')
              </div>
            </div>
          </div>

          <div class="clearfix filters-container m-t-10">
            <div class="row">
              <div class="col col-sm-2 col-md-2">
                <div class="filter-tabs">
                  <ul id="filter-tabs" class="nav nav-tabs nav-tab-box nav-tab-fa-icon">
                    <li class="active">
                      <a data-toggle="tab" href="#grid-container"><i class="icon fa fa-th-large"></i>@lang('languages.grid')</a>
                    </li>
                    <li>
                      <a data-toggle="tab" href="#list-container"><i class="icon fa fa-th-list"></i>@lang('languages.list')</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col col-sm-10 col-md-10 text-right">
                <i class="fa fa-flask"></i>&nbsp;&nbsp;<?php if (isset($category)) {
                                                          echo 'Total de ' . $viewcount . '  Produtos';
                                                        } ?>


              </div>
            </div>
          </div>
          <div class="search-result-container ">
            <div id="myTabContent" class="tab-content category-list">
              <div class="tab-pane active " id="grid-container">
                <div class="category-product">
                  <div class="row">
                    <div class="col-md-12 gridlist">

                      <?php if (!empty($viewcount)) { ?>
                        <?php
                        $ii = 1;

                        if (is_array($viewproduct)) {
                          $result_each = array_chunk($viewproduct, 3);
                        } else {
                          $result_each = $viewproduct->chunk(3);
                        }

                        foreach ($result_each as $grid) {

                          ?>

                          <div class="row" style="margin-left: 2px;margin-right: -20px;">

                            <?php
                            foreach ($grid as $product) {

                              $prod_id = $product->prod_token;
                              $product_img_count = DB::table('product_images')
                                ->where('prod_token', '=', $prod_id)
                                ->count();
                              ?>
                              <div class="col-sm-4 col-md-4 wow fadeInUp">
                                <div class="products">
                                  <div class="product">
                                    <div class="product-image">
                                      <div class="image"> <a href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>">
                                          <?php
                                          if (!empty($product_img_count)) {
                                            $product_img = DB::table('product_images')
                                              ->where('prod_token', '=', $prod_id)
                                              ->orderBy('prod_img_id', 'asc')
                                              ->get();

                                            if (!empty($product_img[0]->image)) {
                                              ?>
                                              <img src="<?php echo $url; ?>/local/images/media/<?php echo utf8_decode($product_img[0]->image); ?>" alt="" />
                                            <?php } else { ?>
                                              <img src="<?php echo $url; ?>/local/images/noimage_box.jpg" alt="" />
                                            <?php }
                                        } ?>
                                        </a>
                                      </div>
                                      <!-- /.image -->

                                      <?php if ($ii == 1) { ?>
                                        <div class="tag new"><span>@lang('languages.new')</span></div>
                                      <?php } ?>
                                    </div>

                                    <?php

                                    $review_count_03 = DB::table('product_rating')
                                      ->where('prod_id', '=', $product->prod_id)
                                      ->count();

                                    if (!empty($review_count_03)) {
                                      $review_value_03 = DB::table('product_rating')
                                        ->where('prod_id', '=', $product->prod_id)
                                        ->get();

                                      $over_03 = 0;
                                      $fine_value_03 = 0;
                                      foreach ($review_value_03 as $review) {
                                        if ($review->rating == 1) {
                                          $value1 = $review->rating * 1;
                                        } else {
                                          $value1 = 0;
                                        }
                                        if ($review->rating == 2) {
                                          $value2 = $review->rating * 2;
                                        } else {
                                          $value2 = 0;
                                        }
                                        if ($review->rating == 3) {
                                          $value3 = $review->rating * 3;
                                        } else {
                                          $value3 = 0;
                                        }
                                        if ($review->rating == 4) {
                                          $value4 = $review->rating * 4;
                                        } else {
                                          $value4 = 0;
                                        }
                                        if ($review->rating == 5) {
                                          $value5 = $review->rating * 5;
                                        } else {
                                          $value5 = 0;
                                        }

                                        $fine_value_03 += $value1 + $value2 + $value3 + $value4 + $value5;

                                        $over_03 += $review->rating;
                                      }
                                      if (!empty(round($fine_value_03 / $over_03))) {
                                        $roundeding_03 = round($fine_value_03 / $over_03);
                                      } else {
                                        $roundeding_03 = 0;
                                      }
                                    }
                                    if (!empty($review_count_03)) {
                                      if (!empty($roundeding_03)) {
                                        if ($roundeding_03 == 1) {
                                          $rateus_new_03 = '
                        <p class="review-icon">
                        <span>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        </span>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        </p>';
                                        }
                                        if ($roundeding_03 == 2) {
                                          $rateus_new_03 = '
                        <p class="review-icon">
                        <span>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        </span>
                      
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <i class="fa fa-star" aria-hidden="true"></i>
                        </p>';
                                        }

                                        if ($roundeding_03 == 3) {
                                          $rateus_new_03 = '
        <p class="review-icon">
        <span>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        </span>
      
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        </p>';
                                        }

                                        if ($roundeding_03 == 4) {
                                          $rateus_new_03 = '
        <p class="review-icon">
        <span>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        </span>
               
        <i class="fa fa-star" aria-hidden="true"></i>
        </p>';
                                        }

                                        if ($roundeding_03 == 5) {
                                          $rateus_new_03 = '
        <p class="review-icon">
        <span>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        </span>
        </p>';
                                        }
                                      } else if (empty($roundeding_03)) {
                                        $rateus_new_03 = '
        <p class="review-icon">
        <span></span>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        <i class="fa fa-star" aria-hidden="true"></i>
        </p>';
                                      }
                                    }

                                    $rateus_empty_03 = '
              <p class="review-icon">
                          <span></span>
              <i class="fa fa-star" aria-hidden="true"></i>
                          <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              <i class="fa fa-star" aria-hidden="true"></i>
              </p>';

                                    ?>

                                    <?php

                                    $newer_count = DB::table('product_attribute_type')
                                      ->where('delete_status', '=', '')
                                      ->where('status', '=', 1)
                                      ->whereIn('user_id', [1, $product->user_id])
                                      ->orderBy('attr_name', 'asc')->count();

                                    if (!empty($newer_count)) {

                                      $newer = DB::table('product_attribute_type')
                                        ->where('delete_status', '=', '')
                                        ->where('status', '=', 1)
                                        ->whereIn('user_id', [1, $product->user_id])
                                        ->orderBy('attr_name', 'asc')->get();

                                      $brand_product = array();

                                      foreach ($newer as $type) {

                                        $value_cnt = DB::table('product_attribute_value')
                                          ->where('delete_status', '=', '')
                                          ->where('status', '=', 1)
                                          ->whereRaw('FIND_IN_SET(value_id,"' . $product->prod_attribute . '")')
                                          ->where('attr_id', '=', $type->attr_id)
                                          ->orderBy('attr_value', 'asc')->count();

                                        $value = DB::table('product_attribute_value')
                                          ->where('delete_status', '=', '')
                                          ->where('status', '=', 1)
                                          ->whereRaw('FIND_IN_SET(value_id,"' . $product->prod_attribute . '")')
                                          ->where('attr_id', '=', $type->attr_id)
                                          ->orderBy('attr_value', 'asc')->get();

                                        if (!empty($value_cnt)) {

                                          foreach ($value as $values) {
                                            $brand_product[] = $values->attr_value;
                                          }
                                        } else {
                                          $brand_product[] = "N/A";
                                        }
                                      }
                                    } else {
                                      $brand_product[] = "N/A";
                                    }

                                    $sold_id = $product->user_id;
                                    $sold = DB::table('users')
                                      ->where('id', '=', $sold_id)
                                      ->count();

                                    if (!empty($sold)) {
                                      $view_sold = DB::table('users')
                                        ->where('id', '=', $sold_id)
                                        ->get();

                                      $view_store_name = $view_sold[0]->name_business;
                                    } else {
                                      $view_store_name = "N/A";
                                    }
                                    ?>

                                    <div class="product-info text-center product_names">
                                      <h3 class="name"><a href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>"><?php echo utf8_decode($product->prod_name); ?></a></h3>
                                      <p><b>Marca(s): </b> <?php echo utf8_decode(implode(", ", $brand_product)); ?></p>
                                      <p><b>Fornecedor: </b> <?php echo utf8_decode($view_store_name); ?></p>

                                      <div class="product-price"> <?php if (!empty($review_count_03)) {
                                                                    echo $rateus_new_03;
                                                                  } else {
                                                                    echo $rateus_empty_03;
                                                                  } ?> </div>
                                      <p><?php if (!empty($product->prod_offer_price) && $product->prod_offer_price > 0) { ?><span style="text-decoration:line-through; color:#FF0000;" class="fontsize15"><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", ".") . ' '; ?></span> <span class="fontsize15 black"> <?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_offer_price, 2, ",", "."); ?></span> <?php } else { ?> <span class="fontsize15 black"><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", "."); ?></span> <?php } ?></p>


                                    </div>

                                    <div class="cart clearfix animate-effect">
                                      <div class="action">
                                        <ul class="list-unstyled">
                                          <li class="add-cart-button btn-group">
                                            <a data-toggle="tooltip" class="btn btn-primary icon" title="Visualizar Produto" href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>"> <i class="fa fa-shopping-cart"></i> </a>

                                            <a class="btn btn-primary cart-btn" href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>">@lang('languages.add_to_cart')</a>


                                          </li>

                                          <?php if (Auth::guest()) { ?>

                                            <li class="lnk wishlist"><a data-toggle="tooltip" class="add-to-cart" href="javascript:void(0);" onClick="alert('@lang('languages.login_user')');" title="Wishlist"> <i class="icon fa fa-heart"></i> </a></li>
                                          <?php
                                        } else {
                                          if (Auth::user()->id != $product->user_id) {
                                            ?>

                                              <li class="lnk wishlist"><a data-toggle="tooltip" class="add-to-cart" href="<?php echo $url; ?>/wishlist/<?php echo Auth::user()->id; ?>/<?php echo $product->prod_token; ?>" title="Wishlist"> <i class="icon fa fa-heart"></i> </a> </li>

                                            <?php }
                                        } ?>



                                        </ul>
                                      </div>
                                    </div>

                                  </div>

                                </div>
                              </div>

                              <?php $ii++;
                            } ?> </div> <?php } ?>

                      <?php } else { ?>
                        <div class="height100"></div>
                        <div align="center" class="fontsize24 black">@lang('languages.no_data')<br><br>
                          Queremos tornar o iBench Market melhor para voc&ecirc;<br>
                          <a href="<?php echo $url; ?>/contact-us" style="text-decoration: none; border-bottom: 1px solid orange;">Clique aqui</a> para nos informar o que voc&ecirc; precisa!</div>
                      <?php } ?>
                    </div>
                    <?php
                    $anterior = $pc - 1;
                    $proximo = $pc + 1;
                    if (!isset($data['pagina'])) {
                      $data['pagina'] = 1;
                    }
                    ?>
                    <div style="text-align: center">
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <?php if (isset($data['pagina']) && $data['pagina'] > 1) { ?>

                            <li>
                              <a href='?pagina=<?php echo ($anterior) ?>' aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                          <?php } else { ?>

                            <li>
                              <a aria-label="Previous" style="cursor: not-allowed;">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                          <?php } ?>

                          <?php for ($i = 0; $i < $tp; ++$i) { ?>

                            <?php if (isset($data['pagina']) && $data['pagina'] == $i + 1) { ?>

                              <li class="active"><a href='?pagina=<?php echo ($i + 1) ?>'><?php echo ($i + 1) ?></a></li>
                            <?php } else { ?>
                              <li><a href='?pagina=<?php echo ($i + 1) ?>'><?php echo ($i + 1) ?></a></li>

                            <?php }
                        } ?>
                          <?php if (isset($data['pagina']) && $data['pagina'] < $tp) { ?>
                            <li>
                              <a href='?pagina=<?php echo ($proximo) ?>' aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            </li>
                          <?php } else { ?>

                            <li>
                              <a aria-label="Next" style="cursor: not-allowed;">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            </li>
                          <?php } ?>

                        </ul>
                      </nav>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane " id="list-container">
                <div class="category-product">

                  <?php if (!empty($viewcount)) { ?>
                    <?php foreach ($viewproduct as $product) {

                      $prod_id = $product->prod_token;
                      $product_img_count = DB::table('product_images')
                        ->where('prod_token', '=', $prod_id)
                        ->count();


                      $newer_count = DB::table('product_attribute_type')
                        ->where('delete_status', '=', '')
                        ->where('status', '=', 1)
                        ->whereIn('user_id', [1, $product->user_id])
                        ->orderBy('attr_name', 'asc')->count();

                      if (!empty($newer_count)) {

                        $newer = DB::table('product_attribute_type')
                          ->where('delete_status', '=', '')
                          ->where('status', '=', 1)
                          ->whereIn('user_id', [1, $product->user_id])
                          ->orderBy('attr_name', 'asc')->get();

                        $brand_product = array();

                        foreach ($newer as $type) {

                          $value_cnt = DB::table('product_attribute_value')
                            ->where('delete_status', '=', '')
                            ->where('status', '=', 1)
                            ->whereRaw('FIND_IN_SET(value_id,"' . $product->prod_attribute . '")')
                            ->where('attr_id', '=', $type->attr_id)
                            ->orderBy('attr_value', 'asc')->count();

                          $value = DB::table('product_attribute_value')
                            ->where('delete_status', '=', '')
                            ->where('status', '=', 1)
                            ->whereRaw('FIND_IN_SET(value_id,"' . $product->prod_attribute . '")')
                            ->where('attr_id', '=', $type->attr_id)
                            ->orderBy('attr_value', 'asc')->get();

                          if (!empty($value_cnt)) {

                            foreach ($value as $values) {
                              $brand_product[] = $values->attr_value;
                            }
                          } else {
                            $brand_product[] = "N/A";
                          }
                        }
                      } else {
                        $brand_product[] = "N/A";
                      }

                      $sold_id = $product->user_id;
                      $sold = DB::table('users')
                        ->where('id', '=', $sold_id)
                        ->count();

                      if (!empty($sold)) {
                        $view_sold = DB::table('users')
                          ->where('id', '=', $sold_id)
                          ->get();

                        $view_store_name = $view_sold[0]->name_business;
                      } else {
                        $view_store_name = "N/A";
                      }
                      ?>
                      <div class="category-product-inner wow fadeInUp">
                        <div class="products">
                          <div class="product-list product">
                            <div class="row product-list-row" style="margin-left: 10px !important;">
                              <div class="col col-sm-2 col-lg-2">
                                <div class="product-image">
                                  <div class="image">
                                    <a href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>">
                                      <?php
                                      if (!empty($product_img_count)) {
                                        $product_img = DB::table('product_images')
                                          ->where('prod_token', '=', $prod_id)
                                          ->orderBy('prod_img_id', 'asc')
                                          ->get();

                                        if (!empty($product_img[0]->image)) {
                                          ?>
                                          <img src="<?php echo $url; ?>/local/images/media/<?php echo utf8_decode($product_img[0]->image); ?>" alt="" />
                                        <?php } else { ?>
                                          <img src="<?php echo $url; ?>/local/images/noimage_box.jpg" alt="" />
                                        <?php }
                                    } ?>
                                    </a>
                                  </div>

                                </div>
                              </div>
                              <?php


                              $review_count_03 = DB::table('product_rating')
                                ->where('prod_id', '=', $product->prod_id)
                                ->count();

                              if (!empty($review_count_03)) {
                                $review_value_03 = DB::table('product_rating')
                                  ->where('prod_id', '=', $product->prod_id)
                                  ->get();

                                $over_03 = 0;
                                $fine_value_03 = 0;
                                foreach ($review_value_03 as $review) {
                                  if ($review->rating == 1) {
                                    $value1 = $review->rating * 1;
                                  } else {
                                    $value1 = 0;
                                  }
                                  if ($review->rating == 2) {
                                    $value2 = $review->rating * 2;
                                  } else {
                                    $value2 = 0;
                                  }
                                  if ($review->rating == 3) {
                                    $value3 = $review->rating * 3;
                                  } else {
                                    $value3 = 0;
                                  }
                                  if ($review->rating == 4) {
                                    $value4 = $review->rating * 4;
                                  } else {
                                    $value4 = 0;
                                  }
                                  if ($review->rating == 5) {
                                    $value5 = $review->rating * 5;
                                  } else {
                                    $value5 = 0;
                                  }

                                  $fine_value_03 += $value1 + $value2 + $value3 + $value4 + $value5;

                                  $over_03 += $review->rating;
                                }
                                if (!empty(round($fine_value_03 / $over_03))) {
                                  $roundeding_03 = round($fine_value_03 / $over_03);
                                } else {
                                  $roundeding_03 = 0;
                                }
                              }

                              if (!empty($review_count_03)) {
                                if (!empty($roundeding_03)) {

                                  if ($roundeding_03 == 1) {
                                    $rateus_new_03 = '<p class="review-icon">
                                    <span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    </span>
<i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    </p>';
                                  }
                                  if ($roundeding_03 == 2) {
                                    $rateus_new_03 = '
                            <p class="review-icon">
                            <span>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            </span>
          <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
                            </p>';
                                  }

                                  if ($roundeding_03 == 3) {
                                    $rateus_new_03 = '
                                <p class="review-icon">
                                <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
  <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                </p>';
                                  }

                                  if ($roundeding_03 == 4) {
                                    $rateus_new_03 = '
                                <p class="review-icon">
                                <span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                </span>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                </p>';
                                  }

                                  if ($roundeding_03 == 5) {
                                    $rateus_new_03 = '
                                    <p class="review-icon">
                                    <span>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
<i class="fa fa-star" aria-hidden="true"></i>
                                  <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    </span>
</p>';
                                  }
                                } else if (empty($roundeding_03)) {
                                  $rateus_new_03 = '
                                        <p class="review-icon">
                                        <span></span>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
    </p>';
                                }
                              }

                              $rateus_empty_03 = '
<p class="review-icon">
                            <span></span>
<i class="fa fa-star" aria-hidden="true"></i>
                            <i class="fa fa-star" aria-hidden="true"></i>
<i class="fa fa-star" aria-hidden="true"></i>
<i class="fa fa-star" aria-hidden="true"></i>
<i class="fa fa-star" aria-hidden="true"></i>
</p>';


                              ?>
                              <div class="col col-sm-10 col-lg-10">
                                <div class="product-info">
                                  <h3 class="name"><a href="<?php echo $url; ?>/product/<?php echo $product->prod_id; ?>/<?php echo utf8_decode($product->prod_slug); ?>"><?php echo utf8_decode($product->prod_name); ?></a></h3>
                                  <div class="rating rateit-small"></div>
                                  <div class="product-price">
                                    <?php if (!empty($review_count_03)) {
                                      echo $rateus_new_03;
                                    } else {
                                      echo $rateus_empty_03;
                                    } ?>
                                  </div>
                                  <p><?php if (!empty($product->prod_offer_price) && $product->prod_offer_price > 0) { ?><span style="text-decoration:line-through; color:#FF0000;" class="fontsize15"><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", ".") . ' '; ?></span> <span class="fontsize15 black"> <?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_offer_price, 2, ",", "."); ?></span> <?php } else { ?> <span class="fontsize15 black"><?php echo $setts[0]->site_currency . ' ' . number_format($product->prod_price, 2, ",", "."); ?></span> <?php } ?></p>



                                  <p><b>Marca(s): </b> <?php echo utf8_decode(implode(", ", $brand_product)); ?></p>
                                  <p><b>Fornecedor: </b> <?php echo utf8_decode($view_store_name); ?></p>

                                  <div class="cart clearfix animate-effect">
                                    <div class="action">
                                      <ul class="list-unstyled">
                                        <li class="add-cart-button btn-group">
                                          <button class="btn btn-primary icon" data-toggle="dropdown" type="button"> <i class="fa fa-shopping-cart"></i> </button>



                                          <a href="https://www.ibench.com.br/_site/product/3547/Ponteira1-300lSFiltroTransparenteEstril96Unrack" class="btn btn-primary cart-btn">
                                            <span>Compre Agora</span>
                                          </a>
                                        </li>


                                        <!-- Marcello Wishlist -->

                                        <li class="lnk wishlist"><a href="javascript:void(0);" onclick="alert('Login Usuário');" class="add-to-cart"><i class="icon fa fa-heart"></i></a></li>


                                        <!-- Marcello Compare --
                                  
                                          <li class="lnk"> 
                                  
                                                          
                              
                                      <a data-toggle="tooltip" class="add-to-cart" href="javascript:void(0);" onClick="alert('Login Usu&aacute;rio');" title="Compare"> <i class="fa fa-signal" aria-hidden="true"></i> </a> 
                                                              
                                  
                                          </li>                                 
                                      -->

                                      </ul>
                                    </div>
                                  </div>
                                </div>

                              </div>


                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                    <?php
                    $anterior = $pc - 1;
                    $proximo = $pc + 1;
                    if (!isset($data['pagina'])) {
                      $data['pagina'] = 1;
                    }
                    ?>
                    <div style="text-align: center">
                      <nav aria-label="Page navigation">
                        <ul class="pagination">
                          <?php if (isset($data['pagina']) && $data['pagina'] > 1) { ?>

                            <li>
                              <a href='?pagina=<?php echo ($anterior) ?>' aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                          <?php } else { ?>

                            <li>
                              <a aria-label="Previous" style="cursor: not-allowed;">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>
                          <?php } ?>

                          <?php for ($i = 0; $i < $tp; ++$i) { ?>

                            <?php if (isset($data['pagina']) && $data['pagina'] == $i + 1) { ?>

                              <li class="active"><a href='?pagina=<?php echo ($i + 1) ?>'><?php echo ($i + 1) ?></a></li>
                            <?php } else { ?>
                              <li><a href='?pagina=<?php echo ($i + 1) ?>'><?php echo ($i + 1) ?></a></li>

                            <?php }
                        } ?>
                          <?php if (isset($data['pagina']) && $data['pagina'] < $tp) { ?>
                            <li>
                              <a href='?pagina=<?php echo ($proximo) ?>' aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            </li>
                          <?php } else { ?>

                            <li>
                              <a aria-label="Next" style="cursor: not-allowed;">
                                <span aria-hidden="true">&raquo;</span>
                              </a>
                            </li>
                          <?php } ?>

                        </ul>
                      </nav>
                    </div>

                  <?php } else { ?>
                    <div class="height100"></div>
                    <div align="center" class="fontsize24 black">@lang('languages.no_data')<br><br>
                      Queremos tornar o iBench Market melhor para voc&ecirc;<br>
                      <a href="<?php echo $url; ?>/contact-us" style="text-decoration: none; border-bottom: 1px solid orange;">Clique aqui</a> para nos informar o que voc&ecirc; precisa!</div>
                  <?php } ?>




                  <!-- 09 -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="height20"></div>
  @include('footer')

  <script>
    //$(document).ready(function() {
    $(function() {
      $("#resetar").click(function() {
        $(".unicase-form-control").attr("checked", this.checked);
      });
    });
  </script>
</body>

</html>

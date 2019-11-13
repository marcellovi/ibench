<div class="col-md-12 gridlist">

  <?php if (!empty($viewcount)) { ?>
    <?php
    $ii = 1;

    if (is_array($viewproduct)) {
      $result_each = array_chunk($viewproduct, 6);
    } else {
      $result_each = $viewproduct->chunk(6);
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
          <div class="col-sm-6 col-md-2 wow fadeInUp">
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
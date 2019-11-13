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
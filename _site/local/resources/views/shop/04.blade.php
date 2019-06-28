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
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
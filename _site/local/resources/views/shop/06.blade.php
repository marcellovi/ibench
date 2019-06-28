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
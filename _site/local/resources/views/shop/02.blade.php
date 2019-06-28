 <li> <a href="<?php echo $url; ?>">@lang('languages.home')</a></li>
 <li class='active'>@lang('languages.shop')</li>
 <?php if (!empty($search_txt)) { ?>
   <li class='active'><?php echo utf8_decode($search_txt); ?></li>
 <?php } ?>
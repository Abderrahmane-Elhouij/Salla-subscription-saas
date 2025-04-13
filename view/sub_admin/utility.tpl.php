<?php
   /**
    * maintenance
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: maintenance.tpl.php, v1.00 7/8/2023 9:53 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   App::Auth()->checkOwner();
?>
<form method="post" name="wojo_forma">
   <div class="wojo form segment margin-bottom">
      <div class="wojo fields">
         <div class="field three wide">
            <label><?php echo Language::$word->MT_IUSERS; ?></label>
            <select name="days">
               <option value="3">3</option>
               <option value="7">7</option>
               <option value="14">14</option>
               <option value="30">30</option>
               <option value="60">60</option>
               <option value="100">100</option>
               <option value="180">180</option>
               <option value="365">365</option>
            </select>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->DELETE; ?></label>
            <button type="button" data-action="processInactive" name="dosubmit" class="wojo negative button"><?php echo Language::$word->MT_IUBTN; ?></button>
         </div>
      </div>
      <p class="text-size-small"><?php echo Language::$word->MT_IUSERS_T; ?></p>
   </div>
</form>
<form method="post" name="wojo_formb">
   <div class="wojo form segment margin-bottom">
      <div class="wojo fields">
         <div class="field three wide basic">
            <label><?php echo Language::$word->MT_BUSERS; ?></label>
            <p class="text-size-small"><?php echo str_replace('[TOTAL]', '<span class="wojo dark inverted label" id="banned">' . $this->banned . '</span>', Language::$word->MT_BUSERS_T); ?></p>
         </div>
         <div class="field five wide basic">
            <label><?php echo Language::$word->DELETE; ?></label>
            <button type="button" data-action="processBanned" name="dosubmit" class="wojo negative button"><?php echo Language::$word->MT_BUBTN; ?></button>
         </div>
      </div>
   </div>
</form>
<form method="post" name="wojo_formc">
   <div class="wojo form segment">
      <div class="wojo fields">
         <div class="field three wide basic">
            <label><?php echo Language::$word->MT_CART; ?></label>
            <p class="text-size-small"><?php echo Language::$word->MT_CART_T; ?></p>
         </div>
         <div class="field five wide basic">
            <label><?php echo Language::$word->DELETE; ?></label>
            <button type="button" data-action="processCart" name="dosubmit" class="wojo negative button"><?php echo Language::$word->MT_CRBTN; ?></button>
         </div>
      </div>
   </div>
</form>
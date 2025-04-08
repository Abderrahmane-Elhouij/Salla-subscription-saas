<?php
   /**
    * password
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: password.tpl.php, v1.00 7/5/2023 1:51 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo simple segment form">
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->NEWPASS; ?>
               <i class="icon asterisk"></i>
            </label>
         </div>
         <div class="field">
            <input type="text" name="password">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->CONPASS; ?>
               <i class="icon asterisk"></i>
            </label>
         </div>
         <div class="field">
            <input type="text" name="password2">
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('admin/account'); ?>"
            class="wojo simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="updatePassword" name="dosubmit"
                 class="wojo primary button"><?php echo Language::$word->M_PASSUPDATE; ?></button>
      </div>
   </div>
</form>
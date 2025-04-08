<?php
   /**
    * copyPage
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: copyPage.tpl.php, v1.00 7/3/2023 12:36 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="body">
   <div class="wojo small form">
      <form method="post" id="modal_form" name="modal_form">
         <div class="wojo fields">
            <div class="field basic">
               <label class=""><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
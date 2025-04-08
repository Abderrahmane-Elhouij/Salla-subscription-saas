<?php
   /**
    * editRole
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: editRole.tpl.php, v1.00 7/7/2023 11:28 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="body">
   <div class="wojo small form">
      <form method="post" id="modal_form" name="modal_form">
         <div class="wojo block fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?></label>
               <input type="text" value="<?php echo $this->data->name; ?>" name="name">
            </div>
            <div class="basic field">
               <label><?php echo Language::$word->DESCRIPTION; ?></label>
               <textarea name="description"><?php echo $this->data->description; ?></textarea>
            </div>
         </div>
      </form>
   </div>
</div>
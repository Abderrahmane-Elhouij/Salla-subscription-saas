<?php
   /**
    * renameFile
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: renameFile.tpl.php, v1.00 7/7/2023 12:32 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!$this->row) : Message::invalid('ID' . Filter::$id);
      return; endif;
?>
<div class="body">
   <div class="wojo small form">
      <form method="post" id="modal_form" name="modal_form">
         <p class="wojo small semi text"><?php echo Language::$word->NAME; ?>: <?php echo $this->row->name; ?></p>
         <div class="wojo block fields">
            <div class="field">
               <label><?php echo Language::$word->FM_ALIAS; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->FM_ALIAS; ?>" value="<?php echo $this->row->alias; ?>" name="alias">
            </div>
            <div class="basic field">
               <label><?php echo Language::$word->FM_MACCESS; ?></label>
               <div class="row grid screen-2 tablet-2 mobile-2 phone-1">
                  <?php echo Utility::loopOptionsMultiple($this->memberships, 'id', 'title', $this->row->fileaccess, 'fileaccess', 'normal'); ?>
               </div>
            </div>
         </div>
      </form>
   </div>
</div>
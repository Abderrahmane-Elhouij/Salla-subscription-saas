<?php
   /**
    * loadFile
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: loadFile.tpl.php, v1.00 7/7/2023 12:13 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!$this->row) : Message::invalid('ID' . Filter::$id);
      return; endif;
?>
<div class="columns" id="item_<?php echo $this->row->id; ?>">
   <div class="wojo attached card">
      <div class="header divided">
         <div class="text-weight-500 text-size-small truncate"><?php echo $this->row->name; ?></div>
         <p class="text-size-mini"><?php echo Date::doDate('long_date', $this->row->created); ?></p>
      </div>
      <div class="content">
         <div class="wojo small list">
            <div class="item align-middle">
               <img src="<?php echo SITEURL; ?>/assets/images/filetypes/<?php echo File::getFileType($this->row->name); ?>" class="wojo small rounded shadow image" alt="">
               <div class="columns">
                  <p class="header"><?php echo $this->row->alias; ?></p>
                  <p class="text-size-small"><?php echo File::getSize($this->row->filesize); ?></p>
               </div>
            </div>
         </div>
      </div>
      <div class="footer divided">
         <div class="row align-middle">
            <div class="columns">
               <a data-set='{"option":[{"action":"renameFile","id": <?php echo $this->row->id; ?>}], "label":"<?php echo Language::$word->ASSIGN; ?>", "url":"helper.php", "parent":"#item_<?php echo $this->row->id; ?>", "complete":"replace", "modalclass":"normal"}' class="wojo small positive icon inverted button action">
                  <i class="icon pencil"></i>
               </a>
               <a data-set='{"option":[{"delete": "deleteFile","title": "<?php echo Validator::sanitize($this->row->alias, 'chars'); ?>","id":<?php echo $this->row->id; ?>,"name": "<?php echo $this->row->name; ?>"}],"action":"delete", "parent":"#item_<?php echo $this->row->id; ?>"}' class="wojo small negative icon inverted button data">
                  <i class="icon x alt"></i>
               </a>
            </div>
            <div class="columns auto">
               <span class="wojo small light inverted static icon button"><?php echo($this->row->fileaccess > 0? '<i class="icon positive check"></i>' : '<i class="icon negative minus"></i>'); ?></span>
            </div>
         </div>
      </div>
   </div>
</div>
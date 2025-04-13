<?php
   /**
    * file
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: file.tpl.php, v1.00 7/7/2023 11:49 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_files')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<div class="row gutters justify-end">
   <div class="columns auto mobile-100 phone-100">
      <div class="wojo small secondary button stacked uploader" id="drag-and-drop-zone">
         <i class="icon upload"></i>
         <label>
            <?php echo Language::$word->UPLOAD; ?>
            <input type="file" multiple name="files[]">
         </label>
      </div>
   </div>
</div>
<div id="fileList" class="wojo small list margin-small-bottom"></div>
<div class="wojo segment margin-bottom">
   <div class="wojo divided horizontal list">
      <div class="disabled item text-weight-500">
         <?php echo Language::$word->FILTER_O; ?>
      </div>
      <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive('type', false); ?>">
         <?php echo Language::$word->FM_ALL_F; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?type=audio'); ?>" class="item <?php echo Url::isActive('type', 'audio'); ?>">
         <i class="icon musical notes"></i>
         <?php echo Language::$word->FM_AUD_F; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?type=video'); ?>" class="item <?php echo Url::isActive('type', 'video'); ?>">
         <i class="icon movie"></i>
         <?php echo Language::$word->FM_VID_F; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?type=image'); ?>" class="item <?php echo Url::isActive('type', 'image'); ?>">
         <i class="icon photo"></i>
         <?php echo Language::$word->FM_AMG_F; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?type=document'); ?>" class="item <?php echo Url::isActive('type', 'document'); ?>">
         <i class="icon files"></i>
         <?php echo Language::$word->FM_DOC_F; ?>
      </a>
      <a href="<?php echo Url::url(Router::$path, '?type=archive'); ?>" class="item <?php echo Url::isActive('type', 'archive'); ?>">
         <i class="icon book"></i>
         <?php echo Language::$word->FM_ARC_F; ?>
      </a>
   </div>
   <div class="padding-vertical center-align">
      <div class="wojo divided horizontal list">
         <div class="disabled text-weight-500">
            <?php echo Language::$word->SORTING_O; ?>
         </div>
         <a href="<?php echo Url::url(Router::$path); ?>" class="item<?php echo Url::setActive('order', false); ?>">
            <?php echo Language::$word->RESET; ?>
         </a>
         <a href="<?php echo Url::url(Router::$path, '?order=name|DESC'); ?>" class="item<?php echo Url::setActive('order', 'name'); ?>">
            <?php echo Language::$word->NAME; ?>
         </a>
         <a href="<?php echo Url::url(Router::$path, '?order=alias|DESC'); ?>" class="item<?php echo Url::setActive('order', 'alias'); ?>">
            <?php echo Language::$word->FM_ALIAS; ?>
         </a>
         <a href="<?php echo Url::url(Router::$path, '?order=filesize|DESC'); ?>" class="item<?php echo Url::setActive('order', 'filesize'); ?>">
            <?php echo Language::$word->FM_FSIZE; ?>
         </a>
         <a href="<?php echo Url::sortItems(Url::url(Router::$path), 'order'); ?>" class="item">
            <i class="icon caret <?php echo Url::ascDesc('order'); ?> link"></i>
         </a>
      </div>
   </div>
   <div class="center-align">
      <?php echo Validator::alphaBits(Url::url(Router::$path)); ?>
   </div>
</div>
<div class="row grid gutters screen-3 tablet-3 mobile-2 phone-1" id="fileData">
   <?php if ($this->data): ?>
      <?php foreach ($this->data as $row): ?>
         <div class="columns" id="item_<?php echo $row->id; ?>">
            <div class="wojo attached card">
               <div class="header divided">
                  <div class="text-weight-500 text-size-small truncate"><?php echo $row->name; ?></div>
                  <p class="text-size-mini"><?php echo Date::doDate('long_date', $row->created); ?></p>
               </div>
               <div class="content">
                  <div class="wojo small list">
                     <div class="item align-middle">
                        <img src="<?php echo SITEURL; ?>/assets/images/filetypes/<?php echo File::getFileType($row->name); ?>" class="wojo small rounded shadow image" alt="">
                        <div class="columns">
                           <p class="header"><?php echo $row->alias; ?></p>
                           <p class="text-size-small"><?php echo File::getSize($row->filesize); ?></p>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="footer divided">
                  <div class="row align-middle">
                     <div class="columns">
                        <a data-set='{"option":[{"action":"renameFile","id": <?php echo $row->id; ?>}], "label":"<?php echo Language::$word->ASSIGN; ?>", "url":"helper.php", "parent":"#item_<?php echo $row->id; ?>", "complete":"replace", "modalclass":"normal"}' class="wojo small positive icon inverted button action">
                           <i class="icon pencil"></i>
                        </a>
                        <a data-set='{"option":[{"delete": "deleteFile","title": "<?php echo Validator::sanitize($row->alias, 'chars'); ?>","id":<?php echo $row->id; ?>,"name": "<?php echo $row->name; ?>"}],"action":"delete", "parent":"#item_<?php echo $row->id; ?>"}' class="wojo small negative icon inverted button data">
                           <i class="icon x alt"></i>
                        </a>
                     </div>
                     <div class="columns auto">
                        <span data-tooltip="<?php echo($row->fileaccess > 0? Language::$word->ASSIGNED : Language::$word->UNASSIGNED); ?>"><?php echo($row->fileaccess > 0? '<i class="icon positive check"></i>' : '<i class="icon negative minus"></i>'); ?></span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   <?php endif; ?>
</div>
<div class="padding-small-horizontal">
   <div class="row gutters align-middle">
      <div class="columns mobile-100 phone-100">
         <div class="text-size-small text-weight-500"><?php echo Language::$word->TOTAL . ': ' . $this->pager->items_total; ?>
            / <?php echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages; ?></div>
      </div>
      <div class="columns mobile-100 phone-100 auto"><?php echo $this->pager->display(); ?></div>
   </div>
</div>
<script src="<?php echo ADMINVIEW; ?>/js/files.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('#fileData').Manager({
         url: "<?php echo ADMINVIEW;?>",
         surl: "<?php echo SITEURL;?>",
         lang: {
            removeText: "<?php echo Language::$word->REMOVE;?>"
         }
      });
   });
   // ]]>
</script>
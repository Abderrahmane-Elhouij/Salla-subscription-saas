<?php
   /**
    * field
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: field.tpl.php, v1.00 7/5/2023 8:19 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_fields')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->title; ?>" name="title">
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CF_TIP; ?></label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->CF_TIP; ?>" value="<?php echo $this->data->tooltip; ?>" name="tooltip">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->PUBLISHED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="1" <?php echo Validator::getChecked($this->data->active, 1); ?> id="active_1">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="0" <?php echo Validator::getChecked($this->data->active, 0); ?> id="active_0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CF_REQUIRED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="required" type="radio" value="1" <?php echo Validator::getChecked($this->data->required, 1); ?> id="required_1">
                  <label for="required_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="required" type="radio" value="0" <?php echo Validator::getChecked($this->data->required, 0); ?> id="required_0">
                  <label for="required_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/fields'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processField" name="dosubmit" class="wojo primary button"><?php echo Language::$word->CF_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CF_TIP; ?></label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->CF_TIP; ?>" name="tooltip">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->PUBLISHED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="1" checked="checked" id="active_1">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="0" id="active_0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CF_REQUIRED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="required" type="radio" value="1" id="required_1">
                  <label for="required_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="required" type="radio" value="0" checked="checked" id="required_0">
                  <label for="required_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/fields'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processField" name="dosubmit" class="wojo primary button"><?php echo Language::$word->CF_ADD; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->CF_ADD; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i
                 class="icon exclamation triangle"></i><?php echo Language::$word->CF_NOFIELDS; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="wojo sortable framed cards screen-3 tablet-3 mobile-1" id="sortable">
         <?php foreach ($this->data as $row): ?>
            <div class="card" id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
               <div class="header">
                  <div class="row">
                     <div class="columns">
                        <div class="wojo simple label draggable">
                           <i class="icon grip horizontal"></i>
                        </div>
                     </div>
                     <div class="columns auto">
                        <a data-set='{"option":[{"delete": "deleteField","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id": <?php echo $row->id; ?>}],"action":"delete","parent":"#item_<?php echo $row->id; ?>"}'
                           class="wojo negative small inverted icon button data">
                           <i class="icon trash"></i>
                        </a>
                     </div>
                  </div>
               </div>
               <div class="padding-bottom center-align">
                  <h6>
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->title; ?></a>
                  </h6>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/sortable.js"></script>
   <script type="text/javascript">
      // <![CDATA[
      $(document).ready(function () {
         $('#sortable').sortable({
            ghostClass: 'ghost',
            handle: '.label',
            animation: 600,
            onUpdate: function () {
               let order = this.toArray();
               $.ajax({
                  type: 'post',
                  url: "<?php echo ADMINVIEW . '/helper.php';?>",
                  dataType: 'json',
                  data: {
                     iaction: 'sortFields',
                     sorting: order
                  }
               });
            }
         });
      });
      // ]]>
   </script>
   <?php break; ?>
<?php endswitch; ?>

<?php
   /**
    * news
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: news.tpl.php, v1.00 7/5/2023 8:24 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_news')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->title; ?>" name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <textarea class="bodypost" name="body"><?php echo str_replace('[SITEURL]', SITEURL, $this->data->body); ?></textarea>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
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
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/news'); ?>" class="wojo simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processNews" name="dosubmit" class="wojo primary button"><?php echo Language::$word->NW_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <textarea class="bodypost" name="body"></textarea>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
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
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/news'); ?>" class="wojo simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processNews" name="dosubmit" class="wojo primary button"><?php echo Language::$word->NW_SUB2; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->NW_SUB1; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>/images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->NW_NONEWS; ?></p>
         </div>
      </div>
   <?php else: ?>
      <?php foreach ($this->data as $row): ?>
         <div class="wojo compact segment margin-bottom" id="item_<?php echo $row->id; ?>">
            <div class="padding-top padding-horizontal">
               <div class="row gutters">
                  <div class="columns">
                     <p class="text-size-small"><?php echo Date::doDate('short_date', $row->created); ?></p>
                     <a class="text-weight-500" href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->title; ?></a>
                     <p>
                        <small><?php echo Language::$word->BY; ?>: <?php echo $row->author; ?></small>
                     </p>
                  </div>
                  <div class="column auto">
                     <a data-set='{"option":[{"action": "trashNews","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id": <?php echo $row->id; ?>}],"action":"trash","parent":"#item_<?php echo $row->id; ?>"}' class="wojo negative small inverted icon button data">
                        <i class="icon trash"></i>
                     </a>
                  </div>
               </div>
            </div>
         </div>
      <?php endforeach; ?>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>

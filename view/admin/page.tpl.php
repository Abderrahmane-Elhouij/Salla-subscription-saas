<?php
   /**
    * page
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: page.tpl.php, v1.00 7/2/2023 3:52 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_pages')): print Message::msgError(Language::$word->NOACCESS);
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
               <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" value="<?php echo $this->data->title; ?>" name="title">
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->PG_SLUG; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->PG_SLUG; ?>" value="<?php echo $this->data->slug; ?>" name="slug">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <textarea class="bodypost" name="body"><?php echo Url::out_url($this->data->body); ?></textarea>
            </div>
         </div>

         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->METAKEYS; ?></label>
               <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>" name="keywords"><?php echo $this->data->keywords; ?></textarea>
            </div>
            <div class="field">
               <label><?php echo Language::$word->METADESC; ?></label>
               <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>" name="description"><?php echo $this->data->description; ?></textarea>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->DC_SUB3; ?></label>
               <a data-wdropdown="#membership_id" class="wojo secondary right button"><?php echo Language::$word->ADM_MEMBS; ?>
                  <i class="icon chevron down"></i>
               </a>
               <div class="wojo static dropdown small pointing top-left" id="membership_id">
                  <div class="min-width400">
                     <div class="row blocks phone-1 mobile-1 tablet-2 screen-2">
                        <?php echo Utility::loopOptionsMultiple($this->memberships, 'id', 'title', $this->data->membership_id, 'membership_id'); ?>
                     </div>
                  </div>
               </div>
            </div>
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
            <div class="field">
               <label><?php echo Language::$word->PG_IS_HIDE; ?>
                  <span data-tooltip="<?php echo Language::$word->PG_IS_HIDE_I; ?>"><i class="icon  question circle"></i></span>
               </label>
               <div class="wojo checkbox radio inline">
                  <input name="is_hide" type="radio" value="1" <?php echo Validator::getChecked($this->data->is_hide, 1); ?> id="is_hide_1">
                  <label for="is_hide_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="is_hide" type="radio" value="0" <?php echo Validator::getChecked($this->data->is_hide, 0); ?> id="is_hide_0">
                  <label for="is_hide_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/pages'); ?>" class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processPage" name="dosubmit" class="wojo primary button"><?php echo Language::$word->PG_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php case 'new': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->PG_SLUG; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->PG_SLUG; ?>" name="slug">
            </div>
         </div>

         <div class="wojo fields">
            <div class="field">
               <textarea class="bodypost" name="body"></textarea>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->METAKEYS; ?></label>
               <textarea class="small" placeholder="<?php echo Language::$word->METAKEYS; ?>" name="keywords"></textarea>
            </div>
            <div class="field">
               <label><?php echo Language::$word->METADESC; ?></label>
               <textarea class="small" placeholder="<?php echo Language::$word->METADESC; ?>" name="description"></textarea>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->DC_SUB3; ?></label>
               <a data-wdropdown="#membership_id" class="wojo secondary right button"><?php echo Language::$word->ADM_MEMBS; ?>
                  <i class="icon chevron down"></i>
               </a>
               <div class="wojo static dropdown small pointing top-left" id="membership_id">
                  <div class="min-width400">
                     <div class="row blocks phone-1 mobile-1 tablet-2 screen-2">
                        <?php echo Utility::loopOptionsMultiple($this->memberships, 'id', 'title', false, 'membership_id'); ?>
                     </div>
                  </div>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->PUBLISHED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="1" id="active_1" checked="checked">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="0" id="active_0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
            <div class="field">
               <label><?php echo Language::$word->PG_IS_HIDE; ?>
                  <span data-tooltip="<?php echo Language::$word->PG_IS_HIDE_I; ?>"><i class="icon  question circle"></i></span>
               </label>
               <div class="wojo checkbox radio inline">
                  <input name="is_hide" type="radio" value="1" id="is_hide_1">
                  <label for="is_hide_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="is_hide" type="radio" value="0" checked="checked" id="is_hide_0">
                  <label for="is_hide_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/pages'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processPage" name="dosubmit" class="wojo primary button"><?php echo Language::$word->PG_SUB2; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->PG_SUB1; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>/images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->PG_NOPAGE; ?></p>
         </div>
      </div>
   <?php else: ?>
      <table class="wojo responsive table">
         <thead>
         <tr>
            <th class="auto"></th>
            <th><?php echo Language::$word->PG_NAME; ?></th>
            <th><?php echo Language::$word->TYPE; ?></th>
            <th class="right-align"><?php echo Language::$word->ACTIONS; ?></th>
         </tr>
         </thead>
         <tbody id="sortable">
         <?php foreach ($this->data as $row): ?>
            <tr id="item_<?php echo $row->id; ?>" data-id="<?php echo $row->id; ?>">
               <td>
                  <div class="wojo simple label draggable">
                     <i class="icon grip horizontal"></i>
                  </div>
               </td>
               <td>
                  <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->title; ?></a>
               </td>
               <td><?php echo Content::pageType($row->page_type); ?></td>
               <td class="auto">
                  <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>" class="wojo icon primary inverted circular button">
                     <i class="icon pencil"></i>
                  </a>

                  <a data-set='{"option":[{"action":"copyPage","id":<?php echo $row->id; ?>}], "label":"<?php echo Language::$word->COPY; ?>", "url":"helper.php", "parent":"#item_<?php echo $row->id; ?>", "complete":"append", "modalclass":"normal", "redirect":true}' class="wojo circular secondary inverted icon button action">
                     <i class="icon copy"></i>
                  </a>
                  <?php if ($row->page_type == 'normal' or $row->page_type == 'membership'): ?>
                     <a data-set='{"option":[{"action": "trashPage","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id": "<?php echo $row->id; ?>"}],"action":"trash","parent":"#item_<?php echo $row->id; ?>"}' class="wojo icon simple button data">
                        <i class="icon negative trash"></i>
                     </a>
                  <?php endif; ?>
               </td>
            </tr>
         <?php endforeach; ?>
         </tbody>
      </table>
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
                     iaction: 'sortPages',
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
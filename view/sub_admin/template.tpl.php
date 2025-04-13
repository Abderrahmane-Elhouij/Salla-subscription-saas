<?php
   /**
    * template
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: template.tpl.php, v1.00 7/5/2023 8:58 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_email')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <form method="post" id="wojo_form" name="wojo_form">
      <div class="wojo simple segment form margin-bottom">
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->ET_NAME; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->ET_NAME; ?>" value="<?php echo $this->data->name; ?>" name="name">
               </div>
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->ET_SUBJECT; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->ET_SUBJECT; ?>" value="<?php echo $this->data->subject; ?>" name="subject">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="basic field">
               <textarea class="bodypost" name="body">
                  <?php echo str_replace(array('[SITEURL]', '[LOGO]'), array(SITEURL, $this->core->plogo), $this->data->body); ?></textarea>
               <p class="wojo small icon negative text">
                  <i class="icon negative info sign"></i>
                  <?php echo Language::$word->NOTEVAR; ?></p>
            </div>
         </div>
         <div class="wojo divider"></div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->ET_DESC; ?></label>
               <div class="wojo small input">
                  <textarea class="small" placeholder="<?php echo Language::$word->ET_DESC; ?>" name="help"><?php echo $this->data->help; ?></textarea>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/templates'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processTemplate" name="dosubmit" class="wojo primary button"><?php echo Language::$word->ET_UPDATE; ?></button>
      </div>
      <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
   </form>
   <?php break; ?>
<?php default: ?>
   <?php if ($this->data): ?>
      <div class="wojo segment">
         <table class="wojo basic table">
            <thead>
            <tr>
               <th class="disabled center aligned">
                  <i class="icon disabled id"></i>
               </th>
               <th><?php echo Language::$word->ET_NAME; ?></th>
               <th><?php echo Language::$word->ET_SUBJECT; ?></th>
               <th class="disabled center aligned"><?php echo Language::$word->ACTIONS; ?></th>
            </tr>
            </thead>
            <?php foreach ($this->data as $row): ?>
               <tr id="item_<?php echo $row->id; ?>">
                  <td class="auto">
                     <span class="wojo small simple label"><?php echo $row->id; ?></span>
                  </td>
                  <td>
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>">
                        <?php echo $row->name; ?></a>
                  </td>
                  <td><?php echo $row->subject; ?></td>
                  <td class="auto">
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>" class="wojo icon primary inverted circular button">
                        <i class="icon pencil"></i>
                     </a>
                  </td>
               </tr>
            <?php endforeach; ?>
         </table>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>
<?php
   /**
    * coupon
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: coupon.tpl.php, v1.00 7/3/2023 12:22 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_coupons')): print Message::msgError(Language::$word->NOACCESS);
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
               <label><?php echo Language::$word->DC_CODE; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->DC_CODE; ?>" value="<?php echo $this->data->code; ?>" name="code">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->DC_SUB3; ?>
                  <i class="icon asterisk"></i>
               </label>
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
            <div class="field three wide">
               <label><?php echo Language::$word->DC_DISC; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->DC_DISC; ?>" value="<?php echo $this->data->discount; ?>" name="discount">
            </div>
            <div class="field two wide">
               <label><?php echo Language::$word->DC_TYPE; ?></label>
               <select name="type">
                  <option value="p"<?php if ($this->data->type == 'p') {
                     echo ' selected="selected"';
                  } ?>><?php echo Language::$word->DC_TYPE_P; ?></option>
                  <option value="a"<?php if ($this->data->type == 'a') {
                     echo ' selected="selected"';
                  } ?>><?php echo Language::$word->DC_TYPE_A; ?></option>
               </select>
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
         <a href="<?php echo Url::url('/admin/coupons'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processCoupon" name="dosubmit" class="wojo primary button"><?php echo Language::$word->DC_SUB2; ?></button>
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
               <label><?php echo Language::$word->DC_CODE; ?>
                  <i class="icon asterisk"></i>
               </label>
               <div class="wojo input">
                  <input type="text" placeholder="<?php echo Language::$word->DC_CODE; ?>" name="code">
               </div>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->DC_SUB3; ?>
                  <i class="icon asterisk"></i>
               </label>
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
            <div class="field three wide">
               <label><?php echo Language::$word->DC_DISC; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->DC_DISC; ?>" name="discount">
            </div>
            <div class="field two wide">
               <label><?php echo Language::$word->DC_TYPE; ?></label>
               <select name="type">
                  <option value="p"><?php echo Language::$word->DC_TYPE_P; ?></option>
                  <option value="a"><?php echo Language::$word->DC_TYPE_A; ?></option>
               </select>
            </div>
         </div>
         <div class="wojo fields">
            <div class="field">
               <label><?php echo Language::$word->PUBLISHED; ?></label>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="1" id="active_1" checked="checked">
                  <label for="active_1"><?php echo Language::$word->YES; ?></label>
               </div>
               <div class="wojo checkbox radio inline">
                  <input name="active" type="radio" value="0">
                  <label for="active_0"><?php echo Language::$word->NO; ?></label>
               </div>
            </div>
         </div>
      </div>
      <div class="center-align">
         <a href="<?php echo Url::url('/admin/coupons'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
         <button type="button" data-action="processCoupon" name="dosubmit" class="wojo primary button"><?php echo Language::$word->DC_SUB1; ?></button>
      </div>
   </form>
   <?php break; ?>
<?php default: ?>
   <div class="row gutters justify-end">
      <div class="columns auto mobile-100 phone-100">
         <a href="<?php echo Url::url(Router::$path, 'new/'); ?>" class="wojo small secondary fluid button">
            <i class="icon plus alt"></i><?php echo Language::$word->DC_SUB1; ?></a>
      </div>
   </div>
   <?php if (!$this->data): ?>
      <div class="center-align">
         <img src="<?php echo ADMINVIEW; ?>/images/notfound.svg" alt="" class="wojo big inline image">
         <div class="margin-small-top">
            <p class="wojo small icon alert inverted attached compact message">
               <i class="icon exclamation triangle"></i><?php echo Language::$word->DC_NONDISC; ?></p>
         </div>
      </div>
   <?php else: ?>
      <div class="wojo cards screen-3 tablet-3 mobile-1">
         <?php foreach ($this->data as $row): ?>
            <div class="card" id="item_<?php echo $row->id; ?>">
               <div class="content dimmable <?php echo ($row->active == 0)? 'active' : ''; ?>">
                  <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>">
                     <img src="<?php echo ADMINVIEW; ?>/images/coupon.svg" alt="">
                  </a>
                  <p class="center aligned">
                     <a href="<?php echo Url::url(Router::$path, 'edit/' . $row->id); ?>"><?php echo $row->title; ?></a>
                  </p>
               </div>
               <div class="divided footer">
                  <div class="row align middle">
                     <div class="columns">
                        <a data-set='{"option":[{"action": "trashCoupon","title": "<?php echo Validator::sanitize($row->title, 'chars'); ?>","id": <?php echo $row->id; ?>}],"action":"trash","parent":"#item_<?php echo $row->id; ?>"}' class="wojo negative small inverted icon button data">
                           <i class="icon trash"></i>
                        </a>
                     </div>
                     <div class="columns auto">
                        <div class="wojo fitted toggle checkbox is_dimmable" data-set='{"option":[{"action": "couponStatus","id":<?php echo $row->id; ?>}],"parent":"#item_<?php echo $row->id; ?>"}'>
                           <input name="active" type="checkbox" value="1" <?php echo Validator::getChecked($row->active, 1); ?> id="cpn_<?php echo $row->id; ?>">
                           <label for="cpn_<?php echo $row->id; ?>"><?php echo Language::$word->ACTIVE; ?></label>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
   <?php endif; ?>
   <?php break; ?>
<?php endswitch; ?>

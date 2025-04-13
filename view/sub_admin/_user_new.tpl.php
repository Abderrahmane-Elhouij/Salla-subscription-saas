<?php
   /**
    * _users_new
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: _users_new.tpl.php, v1.00 7/6/2023 4:10 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   if (!Auth::hasPrivileges('add_user')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo segment form">
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_FNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>" name="fname">
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_LNAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <div class="wojo large basic input">
               <input type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>" name="lname">
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_EMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" name="email">
         </div>
         <div class="field">
            <label><?php echo Language::$word->NEWPASS; ?></label>
            <input type="text" name="password">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field">
            <label><?php echo Language::$word->M_SUB8; ?></label>
            <select name="membership_id">
               <option value="0">-/-</option>
               <?php echo Utility::loopOptions($this->memberships, 'id', 'title'); ?>
            </select>
         </div>
         <div class="field auto">
            <label>&nbsp;</label>
            <div class="wojo checkbox toggle fitted inline">
               <input name="update_membership" type="checkbox" value="1" id="update_membership">
               <label for="update_membership"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB15; ?></label>
            <div class="wojo icon input" data-datepicker="true">
               <input placeholder="<?php echo Language::$word->TO; ?>" name="mem_expire" type="text"
                      value="<?php echo Date::doDate('calendar', Date::numberOfDays('+ 30 day')); ?>" readonly
                      class="datepick">
               <i class="icon calendar alt"></i>
            </div>
         </div>
         <div class="field auto">
            <label>&nbsp;</label>
            <div class="wojo checkbox toggle fitted inline">
               <input name="extend_membership" type="checkbox" value="1" id="extend_membership">
               <label for="extend_membership"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->M_SUB23; ?></label>
            <div class="wojo fitted inline checkbox">
               <input name="add_trans" type="checkbox" value="1" id="add_trans">
               <label for="add_trans"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <div class="padding-top">
         <h5><?php echo Language::$word->CF_TITLE; ?></h5>
         <?php echo $this->custom_fields; ?></div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_ADDRESS; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_ADDRESS; ?>" name="address">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_CITY; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_CITY; ?>" name="city">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_STATE; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_STATE; ?>" name="state">
         </div>
      </div>
      <div class="wojo fields align-middle">
         <div class="field four wide labeled">
            <label><?php echo Language::$word->M_COUNTRY; ?>/<?php echo Language::$word->M_ZIP; ?></label>
         </div>
         <div class="field">
            <input type="text" placeholder="<?php echo Language::$word->M_ZIP; ?>" name="zip">
         </div>
         <div class="field">
            <select name="country">
               <option value="">-/-</option>
               <?php echo Utility::loopOptions($this->countries, 'abbr', 'name'); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <h5><?php echo Language::$word->M_SUB26; ?></h5>
      <div class="wojo scrollbox height300">
         <?php if ($this->userfiles): ?>
            <div class="row grid small gutters screen-4 tablet-3 mobile-1 phone-1">
               <?php foreach ($this->userfiles as $file): ?>
                  <div class="columns">
                     <div class="wojo checkbox inline fitted">
                        <input type="checkbox" name="user_files[]" value="<?php echo $file->id; ?>" id="user_files_<?php echo $file->id; ?>">
                        <label for="user_files_<?php echo $file->id; ?>"><?php echo Validator::truncate($file->alias, 30); ?></label>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->STATUS; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="y" id="active_y" checked="checked">
               <label for="active_y"><?php echo Language::$word->ACTIVE; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="n" id="active_n">
               <label for="active_n"><?php echo Language::$word->INACTIVE; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="t" id="active_t">
               <label for="active_t"><?php echo Language::$word->PENDING; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="active" type="radio" value="b" id="active_b">
               <label for="active_b"><?php echo Language::$word->BANNED; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->M_SUB9; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="staff" id="staff" checked="checked">
               <label for="staff"><?php echo Language::$word->STAFF; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="editor" id="editor">
               <label for="editor"><?php echo Language::$word->EDITOR; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="type" type="radio" value="member" id="member">
               <label for="member"><?php echo Language::$word->MEMBER; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB10; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="newsletter" type="radio" value="1" id="newsletter_1">
               <label for="newsletter_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="newsletter" type="radio" value="0" id="newsletter_0" checked="checked">
               <label for="newsletter_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->M_SUB13; ?></label>
            <div class="wojo checkbox toggle inline">
               <input name="notify" type="checkbox" value="1" id="notify_0">
               <label for="notify_0"><?php echo Language::$word->YES; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <textarea placeholder="<?php echo Language::$word->M_SUB11; ?>" name="notes"></textarea>
         </div>
      </div>
   </div>
   <div class="center-align margin-top">
      <a href="<?php echo Url::url('/admin/users'); ?>"
         class="wojo simple small button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-action="processUser" name="dosubmit"
              class="wojo primary button"><?php echo Language::$word->M_TITLE5; ?></button>
   </div>
</form>
<?php
   /**
    * profile
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: profile.tpl.php, v1.00 7/17/2023 12:49 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-vertical">
      <div class="wojo-grid">
         <div class="center-align margin-vertical">
            <div class="wojo stacked buttons">
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard'); ?>"><?php echo Language::$word->ADM_MEMBS; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'history'); ?>"><?php echo Language::$word->HISTORY; ?></a>
               <a class="wojo secondary button active passive"><?php echo Language::$word->M_SUB18; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'downloads'); ?>"><?php echo Language::$word->DOWNLOADS; ?></a>
               <a class="wojo negative icon button" href="<?php echo Url::url('/logout'); ?>">
                  <i class="icon power"></i>
               </a>
            </div>
         </div>
         <form method="post" id="wojo_form" name="wojo_form">
            <div class="wojo segment form">
               <div class="margin-bottom">
                  <input type="file" name="avatar" data-type="image" data-exist="<?php echo ($this->data->avatar)? UPLOADURL . '/avatars/' . $this->data->avatar : UPLOADURL . '/avatars/default.svg'; ?>" accept="image/png, image/jpeg">
               </div>
               <div class="wojo fields">
                  <div class="field five wide">
                     <label><?php echo Language::$word->M_FNAME; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <input type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>" value="<?php echo $this->data->fname; ?>" name="fname">
                  </div>
                  <div class="field five wide">
                     <label><?php echo Language::$word->M_LNAME; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <input type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>" value="<?php echo $this->data->lname; ?>" name="lname">
                  </div>
               </div>
               <div class="wojo fields">
                  <div class="field five wide">
                     <label><?php echo Language::$word->M_EMAIL; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" value="<?php echo $this->data->email; ?>" name="email">
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->NEWPASS; ?></label>
                     <input type="password" name="password">
                  </div>
               </div>
               <div class="wojo fields justify-center">
                  <div class="field auto">
                     <label><?php echo Language::$word->MEMBERSHIP; ?></label>
                     <?php echo ($this->data->membership_id)? $this->data->mtitle . '<small> @' . Date::doDate('short_date', $this->data->mem_expire) . '</small>' : Language::$word->NONE; ?>
                  </div>
               </div>
               <?php if ($this->custom_fields): ?>
                  <?php echo $this->custom_fields; ?>
               <?php endif; ?>
               <?php if ($this->core->enable_tax): ?>
                  <div class="wojo fields align-middle">
                     <div class="field four wide labeled">
                        <label><?php echo Language::$word->M_ADDRESS; ?></label>
                     </div>
                     <div class="field">
                        <input type="text" placeholder="<?php echo Language::$word->M_ADDRESS; ?>" value="<?php echo $this->data->address; ?>" name="address">
                     </div>
                  </div>
                  <div class="wojo fields align-middle">
                     <div class="field four wide labeled">
                        <label><?php echo Language::$word->M_CITY; ?></label>
                     </div>
                     <div class="field">
                        <input type="text" placeholder="<?php echo Language::$word->M_CITY; ?>" value="<?php echo $this->data->city; ?>" name="city">
                     </div>
                  </div>
                  <div class="wojo fields align-middle">
                     <div class="field four wide labeled">
                        <label><?php echo Language::$word->M_STATE; ?></label>
                     </div>
                     <div class="field">
                        <div class="wojo action input">
                           <input type="text" placeholder="<?php echo Language::$word->M_STATE; ?>" value="<?php echo $this->data->state; ?>" name="state">
                        </div>
                     </div>
                  </div>
                  <div class="wojo fields align-middle">
                     <div class="field four wide labeled">
                        <label><?php echo Language::$word->M_ZIP; ?></label>
                     </div>
                     <div class="field">
                        <input type="text" placeholder="<?php echo Language::$word->M_ZIP; ?>" value="<?php echo $this->data->zip; ?>" name="zip">
                     </div>
                  </div>
                  <div class="wojo fields align-middle">
                     <div class="field four wide labeled">
                        <label><?php echo Language::$word->M_COUNTRY; ?></label>
                     </div>
                     <div class="field">
                        <select name="country">
                           <?php echo Utility::loopOptions($this->countries, 'abbr', 'name', $this->data->country); ?>
                        </select>
                     </div>
                  </div>
               <?php endif; ?>
               <div class="wojo fields align-middle">
                  <div class="field four wide labeled">
                     <label><?php echo Language::$word->M_SUB10; ?></label>
                  </div>
                  <div class="field">
                     <div class="wojo checkbox radio fitted inline">
                        <input name="newsletter" type="radio" value="1" id="newsletter_1" <?php echo Validator::getChecked($this->data->newsletter, 1); ?>>
                        <label for="newsletter_1"><?php echo Language::$word->YES; ?></label>
                     </div>
                     <div class="wojo checkbox radio fitted inline">
                        <input name="newsletter" type="radio" value="0" id="newsletter_0" <?php echo Validator::getChecked($this->data->newsletter, 0); ?>>
                        <label for="newsletter_0"><?php echo Language::$word->NO; ?></label>
                     </div>
                  </div>
               </div>
               <div class="center-align">
                  <button type="button" data-action="profile" name="dosubmit" class="wojo secondary button"><?php echo Language::$word->M_UPDATE; ?></button>
               </div>
            </div>
         </form>
      </div>
   </div>
</main>
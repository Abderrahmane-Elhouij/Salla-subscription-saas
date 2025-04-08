<?php
   /**
    * _contact
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: _contact.tpl.php, v1.00 7/16/2023 2:12 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row justify-center">
   <div class="columns screen-50 tablet-60 mobile-100 phone-100">
      <form method="post" id="wojo_form" name="wojo_form">
         <div class="wojo card form">
            <div class="content">
               <div class="wojo block fields">
                  <div class="field">
                     <label><?php echo Language::$word->CNT_NAME; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <input type="text" placeholder="<?php echo Language::$word->CNT_NAME; ?>" value="<?php echo (App::Auth()->logged_in)? App::Auth()->name : null; ?>" name="name">
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->M_EMAIL; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <input type="text" placeholder="<?php echo Language::$word->M_EMAIL; ?>" value="<?php echo (App::Auth()->logged_in)? App::Auth()->email : null; ?>" name="email">
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->M_PHONE; ?></label>
                     <input type="text" placeholder="<?php echo Language::$word->M_PHONE; ?>" name="phone">
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->ET_SUBJECT; ?></label>
                     <select name="subject">
                        <option value=""><?php echo Language::$word->CNT_SUBJECT_1; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_2; ?>"><?php echo Language::$word->CNT_SUBJECT_2; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_3; ?>"><?php echo Language::$word->CNT_SUBJECT_3; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_4; ?>"><?php echo Language::$word->CNT_SUBJECT_4; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_5; ?>"><?php echo Language::$word->CNT_SUBJECT_5; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_6; ?>"><?php echo Language::$word->CNT_SUBJECT_6; ?></option>
                        <option value="<?php echo Language::$word->CNT_SUBJECT_7; ?>"><?php echo Language::$word->CNT_SUBJECT_7; ?></option>
                     </select>
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->MESSAGE; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <textarea placeholder="<?php echo Language::$word->MESSAGE; ?>" name="notes"></textarea>
                  </div>
                  <div class="field">
                     <label><?php echo Language::$word->CAPTCHA; ?>
                        <i class="icon asterisk"></i>
                     </label>
                     <div class="wojo labeled input">
                        <input name="captcha" placeholder="<?php echo Language::$word->CAPTCHA; ?>" type="text">
                        <div class="wojo simple label"><?php echo Session::captcha(); ?></div>
                     </div>
                  </div>
                  <div class="field">
                     <div class="wojo checkbox">
                        <input name="agree" type="checkbox" value="1" id="agree_1">
                        <label for="agree_1">
                           <a href="<?php echo Url::url('/page', $this->core->page_slugs->privacy[0]->page_type); ?>" target="_blank"><?php echo Language::$word->AGREE; ?></a>
                        </label>
                     </div>
                  </div>
               </div>
               <div class="center-align">
                  <button class="wojo primary button" data-action="contact" name="dosubmit" type="button">
                     <?php echo Language::$word->CNT_SUBMIT; ?>
                  </button>
               </div>
               <figure class="absolute position-bottom position-right max-width200 margin-right-4 margin-bottom-2">
                  <img class="img-fluid" src="<?php echo UPLOADURL; ?>/plane.svg" alt="Image Description">
               </figure>
            </div>
         </div>
      </form>
   </div>
</div>
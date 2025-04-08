<?php
   /**
    * password
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: password.tpl.php, v1.00 7/17/2023 10:08 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="wojo-grid">
      <div class="row justify-center height-full align-middle">
         <div class="columns screen-40 tablet-50 mobile-100 phone-100">
            <div class="logo">
               <a href="<?php echo SITEURL; ?>/"><?php echo ($this->core->logo)? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?></a>
            </div>
            <div class="wojo segment">
               <div class="center-align margin-bottom">
                  <h5><?php echo Language::$word->M_PASSWORD_RES; ?></h5>
                  <div class="wojo normal circular image">
                     <img src="<?php echo UPLOADURL; ?>/avatars/default.svg" id="icon" alt="User Icon">
                  </div>
               </div>
               <div class="wojo form">
                  <form method="post" id="wojo_form" name="wojo_form">
                     <div class="wojo block fields">
                        <div class="field">
                           <label><?php echo Language::$word->NEWPASS; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <input placeholder="<?php echo Language::$word->NEWPASS; ?>" name="password" type="password">
                        </div>
                        <div class="field basic">
                           <button class="wojo primary fluid button" data-action="password" name="dosubmit" type="button"><?php echo Language::$word->SUBMIT; ?></button>
                        </div>
                     </div>
                     <input type="hidden" name="token" value="<?php echo $this->segments[1]; ?>">
                  </form>
               </div>
               <figure class="absolute position-top position-right zindex-1 phone-hide mobile-hide margin-right-4 margin-top-4" style="width: 4rem;">
                  <img class="img-fluid" src="<?php echo FRONTVIEW; ?>/images/pointer-up.svg" alt="Image Description">
               </figure>
               <figure class="absolute position-bottom position-left phone-hide mobile-hide margin-left-5 margin-bottom-4" style="width: 15rem;">
                  <img class="img-fluid" src="<?php echo FRONTVIEW; ?>/images/curved-shape.svg" alt="Image Description">
               </figure>
            </div>
         </div>
      </div>
</main>
<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: index.tpl.php, v1.00 7/12/2023 10:30 AM Gewa Exp $
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
                  <h5><?php echo Utility::sayHello(); ?></h5>
                  <div class="wojo normal circular image">
                     <img src="<?php echo UPLOADURL; ?>/avatars/default.svg" id="icon" alt="User Icon">
                  </div>
               </div>
               <div class="wojo form" id="loginform">
                  <form id="admin_form" name="admin_form" method="post">
                     <div class="wojo block fields">
                        <div class="field">
                           <label><?php echo Language::$word->USERNAME; ?></label>
                           <div class="wojo icon input">
                              <i class="icon envelope"></i>
                              <input type="text" name="username" placeholder="<?php echo Language::$word->USERNAME; ?>">
                           </div>
                        </div>
                        <div class="field">
                           <label class="label"><?php echo Language::$word->M_PASSWORD; ?></label>
                           <div class="wojo icon input">
                              <i class="icon lock"></i>
                              <input type="password" name="password" placeholder="<?php echo Language::$word->M_PASSWORD; ?>">
                              <a class="wojo mini simple icon button" id="showPassword">
                                 <i class="icon eye slash"></i>
                              </a>
                           </div>
                           <p class="right-align">
                              <a id="passreset" class="text-size-small text-weight-500"><?php echo Language::$word->M_PASSWORD_RES; ?>?</a>
                           </p>
                        </div>
                        <div class="field">
                           <button id="doSubmit" type="button" class="wojo primary fluid button" name="submit"><?php echo Language::$word->LOGIN; ?></button>
                        </div>
                        <?php if (App::Core()->reg_allowed): ?>
                           <div class="field basic">
                              <?php echo Language::$word->M_SUB27; ?>
                              <a href="<?php echo Url::url('/register'); ?>">
                                 <?php echo Language::$word->M_SUB28; ?>.
                              </a>
                           </div>
                        <?php endif; ?>
                     </div>
                  </form>
               </div>
               <div id="passform" class="wojo form hide-all">
                  <div class="wojo block fields">
                     <div class="field">
                        <div class="wojo icon input">
                           <i class="icon envelope"></i>
                           <input type="text" name="pEmail" id="pEmail" placeholder="<?php echo Language::$word->M_EMAIL1; ?>">
                        </div>
                     </div>
                     <div class="field">
                        <button id="dopass" type="button" class="wojo fluid primary button" name="doopass"><?php echo Language::$word->SUBMIT; ?></button>
                     </div>
                  </div>
                  <div class="center-align">
                     <a id="backto" class="icon-text">
                        <i class="icon chevron left"></i><?php echo Language::$word->M_SUB14; ?></a>
                  </div>
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
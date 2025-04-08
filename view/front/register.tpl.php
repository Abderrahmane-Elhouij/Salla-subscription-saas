<?php
   /**
    * register
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: register.tpl.php, v1.00 7/13/2023 11:52 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="wojo-grid">
      <div class="row justify-center height-full align-middle">
         <div class="columns screen-50 tablet-70 mobile-100 phone-100">
            <div class="logo">
               <a href="<?php echo SITEURL; ?>/"><?php echo ($this->core->logo)? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?></a>
            </div>
            <div class="wojo segment">
               <form method="post" id="reg_form" name="reg_form">
                  <div class="center-align margin-bottom">
                     <h4><?php echo Language::$word->M_SUB31; ?></h4>
                  </div>
                  <div class="wojo form">
                     <div class="wojo block fields">
                        <div class="field">
                           <label><?php echo Language::$word->M_EMAIL; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <input name="email" type="email" placeholder="<?php echo Language::$word->M_EMAIL; ?>">
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->M_PASSWORD; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo input">
                              <input type="password" name="password" placeholder="********">
                              <a class="wojo mini simple icon button" id="showPassword">
                                 <i class="icon eye slash"></i>
                              </a>
                           </div>
                        </div>
                     </div>
                     <div class="wojo fields">
                        <div class="field">
                           <label><?php echo Language::$word->M_FNAME; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <input name="fname" type="text" placeholder="<?php echo Language::$word->M_FNAME; ?>">
                        </div>
                        <div class="field">
                           <label><?php echo Language::$word->M_LNAME; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <input name="lname" type="text" placeholder="<?php echo Language::$word->M_LNAME; ?>">
                        </div>
                     </div>
                     <?php echo $this->custom_fields; ?>
                     <?php if ($this->core->enable_tax): ?>
                        <div class="wojo block fields">
                           <div class="field">
                              <label><?php echo Language::$word->M_ADDRESS; ?>
                                 <i class="icon asterisk"></i>
                              </label>
                              <input type="text" name="address" placeholder="<?php echo Language::$word->M_ADDRESS; ?>">
                           </div>
                        </div>
                        <div class="wojo fields">
                           <div class="field">
                              <label><?php echo Language::$word->M_CITY; ?>
                                 <i class="icon asterisk"></i>
                              </label>
                              <input type="text" name="city" placeholder="<?php echo Language::$word->M_CITY; ?>">
                           </div>
                           <div class="field">
                              <label><?php echo Language::$word->M_STATE; ?>
                                 <i class="icon asterisk"></i>
                              </label>
                              <input type="text" name="state" placeholder="<?php echo Language::$word->M_STATE; ?>">
                           </div>
                        </div>
                        <div class="wojo fields">
                           <div class="field three wide">
                              <label>
                                 <?php echo Language::$word->M_ZIP; ?>
                                 <i class="icon asterisk"></i>
                              </label>
                              <input type="text" name="zip">
                           </div>
                           <div class="field">
                              <label>
                                 <?php echo Language::$word->M_COUNTRY; ?>
                                 <i class="icon asterisk"></i>
                              </label>
                              <select name="country">
                                 <?php echo Utility::loopOptions($this->countries, 'abbr', 'name'); ?>
                              </select>
                           </div>
                        </div>
                     <?php endif; ?>
                     <div class="wojo block fields">
                        <div class="field">
                           <label><?php echo Language::$word->CAPTCHA; ?>
                              <i class="icon asterisk"></i>
                           </label>
                           <div class="wojo labeled input">
                              <input placeholder="<?php echo Language::$word->CAPTCHA; ?>" name="captcha" type="text">
                              <span class="wojo simple label"><?php echo Session::captcha(); ?></span>
                           </div>
                        </div>
                        <div class="field">
                           <div class="wojo checkbox">
                              <input name="agree" type="checkbox" value="1" id="agree">
                              <label for="agree">
                                 <a href="<?php echo Url::url('/page', $this->core->page_slugs->privacy[0]->page_type); ?>">
                                    <small><?php echo Language::$word->AGREE; ?></small>
                                 </a>
                              </label>
                           </div>
                        </div>
                        <div class="field ">
                           <button class="wojo primary fluid button" data-action="register" name="dosubmit" type="button"><?php echo Language::$word->M_SUB30; ?></button>
                        </div>
                        <div class="field basic">
                           <?php echo Language::$word->M_SUB29; ?>
                           <a href="<?php echo Url::url('/login'); ?>">
                              <span class="text-weight-500"><?php echo Language::$word->LOGIN_1; ?>.</span>
                           </a>

                        </div>
                     </div>
                  </div>
               </form>

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
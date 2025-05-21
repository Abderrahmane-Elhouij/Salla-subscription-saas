<?php
   /**
    * header
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: header.tpl.php, v1.00 7/12/2023 10:35 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!DOCTYPE html>
<html lang="<?php echo Core::$language; ?>">
<head>
   <meta charset="utf-8">
   <title><?php echo isset($this)? $this->title : App::Core()->company; ?></title>
   <?php if (isset($this->keywords)): ?>
      <meta name="keywords" content="<?php echo $this->keywords; ?>">
      <meta name="description" content="<?php echo $this->description; ?>">
   <?php endif; ?>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <meta name="msapplication-TileColor" content="#da532c">
   <meta name="theme-color" content="#ffffff">
   <meta name="dcterms.rights" content="<?php echo $this->core->company; ?> &copy; All Rights Reserved">
   <meta name="robots" content="index">
   <meta name="robots" content="follow">
   <meta name="revisit-after" content="1 day">
   <meta name="generator" content="Powered by CMS pro! v<?php echo $this->core->wojov; ?>">
   <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITEURL; ?>/assets/favicons/apple-touch-icon.png">
   <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL; ?>/assets/favicons/favicon-32x32.png">   <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>/assets/favicons/favicon-16x16.png">
   <link rel="manifest" href="<?php echo SITEURL; ?>/assets/favicons/site.webmanifest">
   <link rel="mask-icon" href="<?php echo SITEURL; ?>/assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
   <link href="<?php echo FRONTVIEW . '/cache/' . Cache::cssCache(array('base.css', 'color.css', 'transition.css', 'label.css', 'form.css', 'dropdown.css', 'input.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'progress.css', 'utility.css', 'style.css', 'responsive-header.css'), FRONTBASE); ?>" rel="stylesheet" type="text/css"/>
   <script src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
   <script src="<?php echo SITEURL; ?>/assets/global.js"></script>
   <?php if (Utility::in_array_any(['dashboard'], $this->segments) and count($this->segments) == 1): ?>
      <script defer src="https://js.stripe.com/v3/"></script>
      <script defer src="https://checkout.razorpay.com/v1/checkout.js"></script>
      <script defer src="https://js.paystack.co/v1/inline.js"></script>
   <?php endif; ?>
</head>
<body>
<header id="header">
   <div class="wojo-grid">
      <div class="row small-gutters align-middle">
         <div class="columns auto phone-order-1 mobile-order-1">
            <a href="<?php echo SITEURL; ?>" class="logo"><?php echo ($this->core->logo)? '<img src="' . UPLOADURL . '/' . $this->core->logo . '" alt=" ' . $this->core->company . '">' : $this->core->company; ?></a>
         </div>
         <div class="columns screen-hide tablet-hide phone-order-2 mobile-order-2 right-align">
            <button type="button" class="wojo icon primary button mobile-menu-toggle" aria-label="Toggle menu">
               <i class="icon list"></i>
            </button>
         </div>
         <div class="columns mobile-100 phone-100 phone-order-4 mobile-order-4 main-nav-container">
            <?php if ($this->pages): ?>
               <nav class="wojo menu main-navigation">
                  <ul class="nav-menu">
                     <?php foreach ($this->pages as $menu): ?>
                        <?php $is_home = ($menu->page_type == 'home')? Url::url('') : Url::url('/page', $menu->slug); ?>
                        <?php if (!$menu->is_hide): ?>
                           <li class="nav-item">
                              <a href="<?php echo $is_home; ?>" class="nav-link"><?php echo $menu->title; ?></a>
                           </li>
                        <?php else: ?>
                           <?php if (Membership::is_valid(explode(',', $menu->membership_id))): ?>
                              <li class="nav-item">
                                 <a href="<?php echo $is_home; ?>" class="nav-link"><?php echo $menu->title; ?></a>
                              </li>
                           <?php endif; ?>
                        <?php endif; ?>
                     <?php endforeach; ?>
                     <li class="nav-item">
                        <a href="<?php echo Url::url('/news'); ?>" class="nav-link"><?php echo Language::$word->NW_TITLE1 ?></a>
                     </li>
                  </ul>
               </nav>
            <?php endif; ?>
         </div>         <div class="columns auto phone-order-3 mobile-order-3 mobile-100 phone-100 auth-buttons">
            <?php if ($this->auth->is_User()): ?>
               <a href="<?php echo Url::url('/dashboard'); ?>" class="user-welcome phone-hide tablet-show">
                  <span class="welcome-text"><?php echo Language::$word->HI; ?></span>
                  <span class="user-name"><?php echo $this->auth->name; ?>!</span>
               </a>
               <a href="<?php echo Url::url('/dashboard'); ?>" class="wojo basic secondary icon button screen-hide tablet-hide mobile-show phone-show">
                  <i class="icon person"></i>
               </a>
            <?php else: ?>
               <div class="auth-actions">
                  <a href="<?php echo Url::url('/sub_admin/login'); ?>" class="wojo secondary button login-btn">
                     <?php echo Language::$word->HOME_LOGIN; ?>
                  </a>
                  <?php if ($this->core->reg_allowed): ?>
                     <a href="<?php echo Url::url('/standalone_register.php'); ?>" class="wojo primary button get-started-btn">
                        <?php echo Language::$word->HOME_GET_STARTED; ?>
                     </a>
                  <?php endif; ?>
               </div>
            <?php endif; ?>
         </div>      </div>
   </div>
</header>

<!-- Add JavaScript for mobile menu toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
   const menuToggle = document.querySelector('.mobile-menu-toggle');
   const mainNav = document.querySelector('.main-navigation');
   
   if (menuToggle && mainNav) {
      menuToggle.setAttribute('aria-expanded', 'false');
      
      menuToggle.addEventListener('click', function() {
         mainNav.classList.toggle('menu-open');
         this.setAttribute('aria-expanded', 
            this.getAttribute('aria-expanded') === 'true' ? 'false' : 'true'
         );
      });
      
      // Close menu when clicking outside
      document.addEventListener('click', function(event) {
         const isClickInsideNav = mainNav.contains(event.target);
         const isClickOnToggle = menuToggle.contains(event.target);
         
         if (!isClickInsideNav && !isClickOnToggle && mainNav.classList.contains('menu-open')) {
            mainNav.classList.remove('menu-open');
            menuToggle.setAttribute('aria-expanded', 'false');
         }
      });
      
      // Close menu when pressing Escape key
      document.addEventListener('keydown', function(event) {
         if (event.key === 'Escape' && mainNav.classList.contains('menu-open')) {
            mainNav.classList.remove('menu-open');
            menuToggle.setAttribute('aria-expanded', 'false');
         }      });
   }
});
</script>
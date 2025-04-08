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
   <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITEURL; ?>/assets/favicons/favicon-32x32.png">
   <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITEURL; ?>/assets/favicons/favicon-16x16.png">
   <link rel="manifest" href="<?php echo SITEURL; ?>/assets/favicons/site.webmanifest">
   <link rel="mask-icon" href="<?php echo SITEURL; ?>/assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
   <link href="<?php echo FRONTVIEW . '/cache/' . Cache::cssCache(array('base.css', 'color.css', 'transition.css', 'label.css', 'form.css', 'dropdown.css', 'input.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'progress.css', 'utility.css', 'style.css'), FRONTBASE); ?>" rel="stylesheet" type="text/css"/>
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
            <button type="button" class="wojo icon small primary button mobile-button">
               <i class="icon list"></i>
            </button>
         </div>
         <div class="columns mobile-100 phone-100 phone-order-4 mobile-order-4">
            <?php if ($this->pages): ?>
               <nav class="wojo menu">
                  <ul>
                     <?php foreach ($this->pages as $menu): ?>
                        <?php $is_home = ($menu->page_type == 'home')? Url::url('') : Url::url('/page', $menu->slug); ?>
                        <?php if (!$menu->is_hide): ?>
                           <li>
                              <a href="<?php echo $is_home; ?>"><?php echo $menu->title; ?></a>
                           </li>
                        <?php else: ?>
                           <?php if (Membership::is_valid(explode(',', $menu->membership_id))): ?>
                              <li>
                                 <a href="<?php echo $is_home; ?>"><?php echo $menu->title; ?></a>
                              </li>
                           <?php endif; ?>
                        <?php endif; ?>
                     <?php endforeach; ?>
                     <li>
                        <a href="<?php echo Url::url('/news'); ?>"><?php echo Language::$word->NW_TITLE1 ?></a>
                     </li>
                  </ul>
               </nav>
            <?php endif; ?>
         </div>
         <div class="columns auto phone-order-3 mobile-order-3 mobile-100 phone-100 center-align">
            <?php if ($this->auth->is_User()): ?>
               <a href="<?php echo Url::url('/dashboard'); ?>" class="phone-hide">
                  <?php echo Language::$word->HI; ?>
                  <?php echo $this->auth->name; ?>!
               </a>
               <a href="<?php echo Url::url('/dashboard'); ?>" class="wojo small basic secondary icon button screen-hide tablet-hide mobile-hide phone-show">
                  <i class="icon person"></i>
               </a>
            <?php else: ?>
               <a href="<?php echo Url::url('/login'); ?>" class="wojo white button spaced">
                  <?php echo Language::$word->HP_SUB1; ?>
               </a>
               <?php if ($this->core->reg_allowed): ?>
                  <a href="<?php echo Url::url('/register'); ?>" class="wojo secondary button">
                     <?php echo Language::$word->M_SUB16; ?>
                  </a>
               <?php endif; ?>
            <?php endif; ?>
         </div>
      </div>
   </div>
</header>
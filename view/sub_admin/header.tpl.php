<?php
   /**
    * header
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.20: header.tpl.php, v1.00 7/1/2023 10:33 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!App::Auth()->is_SubAdmin()) {
      Url::redirect(SITEURL . '/sub_admin/login/');
      exit;
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title><?php echo $this->title; ?></title>
   <link href="<?php echo ADMINVIEW . '/cache/' . Cache::cssCache(array(
       'base.css', 'transition.css', 'label.css', 'form.css', 'dropdown.css', 'input.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'flags.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'menu.css', 'progress.css', 'utility.css', 'style.css'
     ), ADMINBASE); ?>?ver=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
</head>
<body>
<header class="main"<?php echo Session::getCookie('CMSA_USERBG')? ' style="background-image:url(' . ADMINVIEW . '/images/' . Session::getCookie('CMSA_USERBG') . '.jpg)"' : null; ?>>
   <div class="wojo-grid">
      <div class="row small-horizontal-gutters align-middle" id="mainRow">
         <div class="columns auto phone-order-1 mobile-order-1">
            <a href="<?php echo SITEURL . '/sub_admin'; ?>" class="logo">
               <?php echo ($this->core->logo)? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?></a>
         </div>
         <div class="columns phone-order-5 mobile-order-5 mobile-100 phone-100">
            <nav class="wojo menu">
               <ul>
                  <!-- Only keeping content options relevant to subadmins -->
                  
                  <li>
                     <a<?php echo Utility::isActive('memberships', $this->segments); ?> href="<?php echo Url::url('/sub_admin/memberships'); ?>"><?php echo Language::$word->ADM_MEMBS; ?></a>
                  </li>

                  <li>
                     <a<?php echo Utility::isActive('users', $this->segments); ?> href="<?php echo Url::url('/sub_admin/users'); ?>"><?php echo Language::$word->ADM_USERS; ?></a>
                  </li>
                  
                  <li>
                     <a<?php echo Utility::isActive('subscriptions', $this->segments); ?> href="<?php echo Url::url('/sub_admin/subscriptions'); ?>">Subscriptions</a>
                  </li>

               </ul>
            </nav>
         </div>
         <div class="columns auto phone-order-2 mobile-order-2">
            <div class="wojo buttons" data-wdropdown="#dropdown-uMenu" id="uName">
               <div class="wojo transparent button tablet-hide phone-hide"><?php echo $this->auth->name; ?></div>
               <div class="wojo transparent icon button is-alone"><?php echo Utility::getInitials($this->auth->name); ?></div>
            </div>
            <div class="wojo dropdown top-left" id="dropdown-uMenu">
               <div class="wojo small circular center image">
                  <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo ($this->auth->avatar)? : 'default.svg'; ?>" alt="">
               </div>
               <h5 class="text-size-small dimmed-text center-align"><?php echo $this->auth->name; ?></h5>
               <a class="item" href="<?php echo Url::url('/sub_admin/account'); ?>">
                  <i class="icon person"></i>
                  <?php echo Language::$word->M_MYACCOUNT; ?></a>
               <a class="item" href="<?php echo Url::url('/sub_admin/account/password'); ?>">
                  <i class="icon lock"></i>
                  <?php echo Language::$word->M_SUB2; ?></a>
               <div class="divider"></div>
               <a class="item" href="<?php echo Url::url('/sub_admin/logout'); ?>">
                  <i class="icon power"></i>
                  <?php echo Language::$word->LOGOUT; ?></a>
            </div>
         </div>
         
         <div class="columns auto phone-order-4 mobile-order-4 right-align">
            <button type="button" class="wojo icon white button mobile-button">
               <i class="icon list"></i>
            </button>
         </div>
      </div>
   </div>

   <div class="toolbar">
      <div class="wojo-grid">
         <div class="wojo small breadcrumb">
            <i class="icon house"></i><?php echo Url::crumbs(($this->crumbs ?? $this->segments), '//', Language::$word->HOME); ?>
         </div>
         <?php if ($this->caption or $this->subtitle): ?>
            <div class="caption">
               <?php if ($this->caption): ?>
                  <h4><?php echo $this->caption; ?></h4>
               <?php endif; ?>
               <?php if ($this->subtitle): ?>
                  <p><?php echo $this->subtitle; ?></p>
               <?php endif; ?>
            </div>
         <?php endif; ?>
      </div>
      <div class="shape">
         <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
            <path fill="#ebecee" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
         </svg>
      </div>
   </div>
</header>
<main>
   <div class="wojo-grid">
      <div class="mainContainer">
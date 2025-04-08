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

   if (!App::Auth()->is_Admin()) {
      Url::redirect(SITEURL . '/admin/login/');
      exit;
   }
?>
<!DOCTYPE html>
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
            <a href="<?php echo ADMINURL; ?>" class="logo">
               <?php echo ($this->core->logo)? '<img src="' . SITEURL . '/uploads/' . $this->core->logo . '" alt="' . $this->core->company . '">' : $this->core->company; ?></a>
         </div>
         <div class="columns phone-order-5 mobile-order-5 mobile-100 phone-100">
            <nav class="wojo menu">
               <ul>
                  <li<?php echo Utility::isActiveMulti(['templates', 'menus', 'pages', 'language', 'fields', 'coupons', 'mailer'], $this->segments); ?>>
                     <a href="#"><?php echo Language::$word->ADM_CONTENT; ?></a>
                     <ul>
                        <?php if (Auth::hasPrivileges('manage_pages')): ?>
                           <li>
                              <a<?php echo Utility::isActive('pages', $this->segments); ?> href="<?php echo Url::url('/admin/pages'); ?>"><?php echo Language::$word->ADM_PAGES; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_news')): ?>
                           <li>
                              <a<?php echo Utility::isActive('news', $this->segments); ?> href="<?php echo Url::url('/admin/news'); ?>"><?php echo Language::$word->ADM_NEWS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_coupons')): ?>
                           <li>
                              <a<?php echo Utility::isActive('coupons', $this->segments); ?> href="<?php echo Url::url('/admin/coupons'); ?>"><?php echo Language::$word->ADM_COUPONS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_languages')): ?>
                           <li>
                              <a<?php echo Utility::isActive('language', $this->segments); ?> href="<?php echo Url::url('/admin/language'); ?>"><?php echo Language::$word->ADM_LNGMNG; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_fields')): ?>
                           <li>
                              <a<?php echo Utility::isActive('fields', $this->segments); ?> href="<?php echo Url::url('/admin/fields'); ?>"><?php echo Language::$word->ADM_CFIELDS; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_email')): ?>
                           <li>
                              <a<?php echo Utility::isActive('templates', $this->segments); ?> href="<?php echo Url::url('/admin/templates'); ?>"><?php echo Language::$word->ADM_EMTPL; ?></a>
                           </li>
                        <?php endif; ?>
                        <?php if (Auth::hasPrivileges('manage_newsletter')): ?>
                           <li>
                              <a<?php echo Utility::isActive('mailer', $this->segments); ?> href="<?php echo Url::url('/admin/mailer'); ?>"><?php echo Language::$word->ADM_NEWSL; ?></a>
                           </li>
                        <?php endif; ?>
                     </ul>
                  </li>

                  <?php if (Auth::hasPrivileges('manage_memberships')): ?>
                     <li>
                        <a<?php echo Utility::isActive('memberships', $this->segments); ?> href="<?php echo Url::url('/admin/memberships'); ?>"><?php echo Language::$word->ADM_MEMBS; ?></a>
                     </li>
                  <?php endif; ?>

                  <?php if (Auth::hasPrivileges('manage_users')): ?>
                     <li>
                        <a<?php echo Utility::isActive('users', $this->segments); ?> href="<?php echo Url::url('/admin/users'); ?>"><?php echo Language::$word->ADM_USERS; ?></a>
                     </li>
                  <?php endif; ?>

                  <?php if (Auth::hasPrivileges('manage_files')): ?>
                     <li>
                        <a<?php echo Utility::isActive('files', $this->segments); ?> href="<?php echo Url::url('/admin/files'); ?>"><?php echo Language::$word->ADM_FILES; ?></a>
                     </li>
                  <?php endif; ?>

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
               <a class="item" href="<?php echo Url::url('/admin/account'); ?>">
                  <i class="icon person"></i>
                  <?php echo Language::$word->M_MYACCOUNT; ?></a>
               <a class="item" href="<?php echo Url::url('/admin/account/password'); ?>">
                  <i class="icon lock"></i>
                  <?php echo Language::$word->M_SUB2; ?></a>
               <div class="divider"></div>
               <a class="item" href="<?php echo Url::url('/admin/logout'); ?>">
                  <i class="icon power"></i>
                  <?php echo Language::$word->LOGOUT; ?></a>
            </div>
         </div>

         <?php if (Auth::checkAcl('owner')): ?>
            <div class="columns auto phone-order-3 mobile-order-3">
               <a data-wdropdown="#dropdown-aMenu" class="wojo transparent icon button">
                  <i class="icon gears"></i>
               </a>
               <div class="wojo dropdown menu top-<?php echo (in_array(Core::$language, array('he', 'ae', 'ir')))? 'left' : 'right'; ?>" id="dropdown-aMenu">
                  <a class="item" href="<?php echo Url::url('/admin/configuration'); ?>">
                     <i class="icon gears"></i>
                     <?php echo Language::$word->ADM_CONFIG; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/roles'); ?>">
                     <i class="icon lock"></i>
                     <?php echo Language::$word->ADM_PERMS; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/transactions'); ?>">
                     <i class="icon wallet"></i>
                     <?php echo Language::$word->ADM_TRANS; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/utilities'); ?>">
                     <i class="icon sliders vertical alt"></i>
                     <?php echo Language::$word->ADM_MTNC; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/countries'); ?>">
                     <i class="icon globe"></i>
                     <?php echo Language::$word->ADM_CNTR; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/system'); ?>">
                     <i class="icon laptop"></i>
                     <?php echo Language::$word->SYS_TITLE; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/backup'); ?>">
                     <i class="icon server"></i>
                     <?php echo Language::$word->ADM_BACKUP; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/gateways'); ?>">
                     <i class="icon credit card"></i>
                     <?php echo Language::$word->ADM_GATE; ?></a>
                  <a class="item" href="<?php echo Url::url('/admin/trash'); ?>">
                     <i class="icon trash"></i>
                     <?php echo Language::$word->ADM_TRASH; ?></a>
               </div>
            </div>
         <?php endif; ?>
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
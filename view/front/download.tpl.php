<?php
   /**
    * download
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: download.tpl.php, v1.00 7/17/2023 1:34 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-vertical">
      <div class="center-align">
         <img src="<?php echo UPLOADURL; ?>/avatars/<?php echo (App::Auth()->avatar)? App::Auth()->avatar : 'default.svg'; ?>" alt="" class="wojo normal inline rounded image">
      </div>
      <div class="wojo-grid">
         <div class="center-align margin-vertical">
            <div class="wojo stacked buttons">
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard'); ?>"><?php echo Language::$word->ADM_MEMBS; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'history'); ?>"><?php echo Language::$word->HISTORY; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'profile'); ?>"><?php echo Language::$word->M_SUB18; ?></a>
               <a class="wojo secondary button active passive"><?php echo Language::$word->DOWNLOADS; ?></a>
               <a class="wojo negative icon button" href="<?php echo Url::url('/logout'); ?>">
                  <i class="icon power"></i>
               </a>
            </div>
         </div>
         <?php if (!$this->data and !$this->userfiles): ?>
            <div class="center-align">
               <img src="<?php echo ADMINVIEW; ?>images/notfound.svg" alt="" class="wojo big inline image">
               <div class="margin-small-top">
                  <p class="wojo small icon info inverted attached compact message">
                     <i class="icon exclamation triangle"></i>
                     <?php echo Language::$word->AD_NO_DOWN; ?></p>
               </div>
            </div>
         <?php else: ?>
            <?php if ($this->data): ?>
               <p class="right aligned"><span class="wojo simple label"><?php echo count($this->data); ?>
                     <?php echo Language::$word->FM_FILES; ?></span>
               </p>
               <div class="row gutters">
               <?php foreach ($this->data as $i => $row): ?>
                  <?php if (!($i % 2) && $i > 0): ?>
                     </div>
                     <div class="row gutters">
                  <?php endif; ?>
                  <div class="columns screen-50 tablet-50 mobile-100 phone-100">
                     <div class="wojo relaxed celled list">
                        <div class="item align-middle">
                           <img src="<?php echo SITEURL; ?>/assets/images/filetypes/<?php echo File::getFileType($row->name); ?>" class="wojo small rounded shadow image" alt="">
                           <div class="columns">
                              <p class="header"><?php echo $row->alias; ?></p>
                              <span class="text-size-small"><?php echo Date::doDate('long_date', $row->created); ?></span>
                              <p class="text-size-small"><?php echo File::getSize($row->filesize); ?> -
                                 <a href="<?php echo FRONTVIEW; ?>/controller.php?action=download&amp;token=<?php echo $row->token; ?>"><?php echo Language::$word->DOWNLOAD; ?></a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
               </div>
            <?php endif; ?>
            <?php if ($this->userfiles): ?>
               <p class="right aligned"><span class="wojo simple label"><?php echo count($this->userfiles); ?>
                     <?php echo Language::$word->FM_FILES; ?></span>
               </p>
               <div class="row gutters">
               <?php foreach ($this->userfiles as $i => $row): ?>
                  <?php if (!($i % 2) && $i > 0): ?>
                     </div>
                     <div class="row gutters">
                  <?php endif; ?>
                  <div class="columns screen-50 tablet-50 mobile-100 phone-100">
                     <div class="wojo relaxed celled list">
                        <div class="item align-middle">
                           <img src="<?php echo SITEURL; ?>/assets/images/filetypes/<?php echo File::getFileType($row->name); ?>" class="wojo small rounded shadow image" alt="">
                           <div class="columns">
                              <p class="header"><?php echo $row->alias; ?></p>
                              <span class="text-size-small"><?php echo Date::doDate('long_date', $row->created); ?></span>
                              <p class="text-size-small"><?php echo File::getSize($row->filesize); ?> -
                                 <a href="<?php echo FRONTVIEW; ?>/controller.php?action=download&amp;token=<?php echo $row->token; ?>"><?php echo Language::$word->DOWNLOAD; ?></a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
               </div>
            <?php endif; ?>
         <?php endif; ?>
      </div>
   </div>
</main>
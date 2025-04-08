<?php
   /**
    * dashboard
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: dashboard.tpl.php, v1.00 7/17/2023 11:02 AM Gewa Exp $
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
               <a class="wojo secondary button active passive"><?php echo Language::$word->ADM_MEMBS; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'history'); ?>"><?php echo Language::$word->HISTORY; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'profile'); ?>"><?php echo Language::$word->M_SUB18; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'downloads'); ?>"><?php echo Language::$word->DOWNLOADS; ?></a>
               <a class="wojo negative icon button" href="<?php echo Url::url('/logout'); ?>">
                  <i class="icon power"></i>
               </a>
            </div>
         </div>
         <?php if ($this->data) : ?>
            <div class="wojo cards screen-2 tablet-2 mobile-1 phone-1 gutters">
               <?php foreach ($this->data as $row) : ?>
                  <div class="card<?php echo $this->user->membership_id == $row->id? ' active' : null; ?>" id="item_<?php echo $row->id; ?>">
                     <div class="content">
                        <div class="center-align">
                           <?php if ($row->thumb) : ?>
                              <img src="<?php echo UPLOADURL; ?>/memberships/<?php echo $row->thumb; ?>" alt="" class="wojo large image inline">
                           <?php else : ?>
                              <img src="<?php echo UPLOADURL; ?>/memberships/default.svg" alt="" class="wojo large image inline">
                           <?php endif; ?>
                        </div>
                        <div class="center-align margin-small-vertical">
                           <h4><?php echo Utility::formatMoney($row->price); ?>
                              <?php echo $row->title; ?></h4>
                           <p class="text-size-small basic"><?php echo $row->description; ?></p>
                           <p class="text-size-small basic"><?php echo Language::$word->MEM_REC1; ?>
                              <?php echo ($row->recurring)? Language::$word->YES : Language::$word->NO; ?></p>
                           <p class="text-size-small"><?php echo $row->days; ?>@<?php echo Date::getPeriodReadable($row->period); ?></p>
                        </div>
                        <?php echo Url::out_url($row->body); ?>
                        <?php if ($this->user->membership_id != $row->id) : ?>
                           <div class="center-align">
                              <a class="wojo small primary button add-cart" data-id="<?php echo $row->id; ?>"><?php echo ($row->price <> 0)? Language::$word->M_SUB19 : Language::$word->M_SUB20; ?></a>
                           </div>
                        <?php endif; ?>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
         <div id="mResult"></div>
      </div>
   </div>
</main>

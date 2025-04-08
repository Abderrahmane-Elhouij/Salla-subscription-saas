<?php
   /**
    * index
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: index.tpl.php, v1.00 7/15/2023 8:26 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main class="overflow-hidden">
   <div class="padding-big-vertical">
      <div class="wojo-grid">
         <?php echo Url::out_url($this->row->body); ?>
         <?php if ($this->memberships): ?>
            <div class="wojo simple compact segment">
               <div class="wojo cards screen-2 tablet-2 mobile-1 phone-1">
                  <?php foreach ($this->memberships as $row): ?>
                     <div class="card">
                        <div class="content">
                           <h6><?php echo $row->title; ?></h6>
                           <p><?php echo $row->description; ?></p>
                           <div class="row">
                              <div class="columns align-self-bottom auto">
                                 <span class="text-size-huge text-weight-500 short-text"><?php echo Utility::formatMoney($row->price); ?></span>
                              </div>
                              <div class="columns align-self-bottom">
                                 <span class="margin-small-left"><?php echo $this->core->currency; ?> | <?php echo $row->days; ?>/<?php echo Date::getPeriodReadable($row->period); ?></span>
                              </div>
                           </div>
                           <div class="margin-top">
                              <?php echo $row->body; ?>
                           </div>
                           <div class="right-align">
                              <a class="wojo primary button" href="<?php echo ($this->auth->is_User())? Url::url('/dashboard') : Url::url('/login'); ?>"><?php echo ($this->auth->is_User())? Language::$word->MEM_START : Language::$word->LOGIN_R7; ?></a>
                           </div>
                        </div>
                        <figure class="absolute position-top position-right margin-small-top margin-mini-right">
                           <?php if ($row->thumb): ?>
                              <img src="<?php echo UPLOADURL; ?>/memberships/<?php echo $row->thumb; ?>" alt="" class="wojo small image">
                           <?php else: ?>
                              <img src="<?php echo UPLOADURL; ?>/memberships/default.svg" alt="" class="wojo small image">
                           <?php endif; ?>
                        </figure>
                     </div>
                  <?php endforeach; ?>
               </div>
               <figure class="absolute position-top position-right zindex-1 phone-hide mobile-hide margin-right-4 margin-top-4" style="width: 4rem;">
                  <img class="img-fluid" src="<?php echo FRONTVIEW; ?>/images/pointer-up.svg" alt="Image Description">
               </figure>
               <figure class="absolute position-bottom position-left phone-hide mobile-hide margin-left-5 margin-bottom-4" style="width: 15rem;">
                  <img class="img-fluid" src="<?php echo FRONTVIEW; ?>/images/curved-shape.svg" alt="Image Description">
               </figure>
            </div>
         <?php endif; ?>
      </div>
   </div>
</main>
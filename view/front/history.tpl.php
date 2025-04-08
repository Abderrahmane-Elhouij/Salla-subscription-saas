<?php
   /**
    * history
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: history.tpl.php, v1.00 7/17/2023 12:28 PM Gewa Exp $
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
               <a class="wojo secondary button active passive"><?php echo Language::$word->HISTORY; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'profile'); ?>"><?php echo Language::$word->M_SUB18; ?></a>
               <a class="wojo secondary button" href="<?php echo Url::url('/dashboard', 'downloads'); ?>"><?php echo Language::$word->DOWNLOADS; ?></a>
               <a class="wojo negative icon button" href="<?php echo Url::url('/logout'); ?>">
                  <i class="icon power"></i>
               </a>
            </div>
         </div>
         <?php if ($this->data): ?>
            <table class="wojo simple segment table">
               <thead>
               <tr>
                  <th><?php echo Language::$word->NAME; ?></th>
                  <th><?php echo Language::$word->MEM_ACT; ?></th>
                  <th><?php echo Language::$word->MEM_EXP; ?></th>
                  <th><?php echo Language::$word->MEM_REC1; ?></th>
                  <th class="auto"></th>
               </tr>
               </thead>
               <?php foreach ($this->data as $mrow): ?>
                  <tr>
                     <td><?php echo $mrow->title; ?></td>
                     <td><?php echo Date::doDate('long_date', $mrow->activated); ?></td>
                     <td><?php echo Date::doDate('long_date', $mrow->expire); ?></td>
                     <td class="center aligned"><?php echo Utility::isPublished($mrow->recurring); ?></td>
                     <td class="center aligned">
                        <a href="<?php echo FRONTVIEW; ?>/controller.php?action=invoice&amp;id=<?php echo $mrow->transaction_id; ?>">
                           <i class="icon download"></i>
                        </a>
                     </td>
                  </tr>
               <?php endforeach; ?>
            </table>
            <div class="wojo small primary passive inverted button"><?php echo Language::$word->TRX_TOTAMT; ?>
               <?php echo Utility::formatMoney($this->totals); ?></div>
         <?php endif; ?>
      </div>
   </div>
</main>
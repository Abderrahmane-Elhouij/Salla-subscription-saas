<?php
   /**
    * page
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: page.tpl.php, v1.00 7/16/2023 1:54 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical">
      <div class="wojo-grid">
         <?php if ($this->row->page_type == 'membership'): ?>
            <?php if (Membership::is_valid(explode(',', $this->row->membership_id))): ?>
               <?php echo Url::out_url($this->row->body); ?>
            <?php else: ?>
               <div class="wojo negative relaxed icon message align-middle">
                  <i class="icon white lock"></i>
                  <div class="content">
                     <p><?php echo Language::$word->PG_MERROR_2; ?>:</p>
                  </div>
               </div>
               <?php if ($this->packages): ?>
                  <div class="wojo segment">
                     <ul class="wojo list">
                        <?php foreach ($this->packages as $row): ?>
                           <li class="item">
                              <i class="icon asterisk"></i><?php echo $row->title; ?></li>
                        <?php endforeach; ?>
                     </ul>
                  </div>
               <?php endif; ?>
            <?php endif; ?>
         <?php else: ?>
            <?php echo Url::out_url($this->row->body); ?>
            <?php if ($this->row->page_type == 'contact'): ?>
               <?php include_once '_contact.tpl.php'; ?>
            <?php endif; ?>
         <?php endif; ?>
      </div>
   </div>
</main>
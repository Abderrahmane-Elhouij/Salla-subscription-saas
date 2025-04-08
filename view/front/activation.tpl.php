<?php
   /**
    * activation
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: activation.tpl.php, v1.00 7/17/2023 10:20 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical">
      <div class="wojo-grid">
         <?php if (Validator::get('done')): ?>
            <?php echo Message::msgOk(Language::$word->M_INFO9 . '<a href="' . Url::url('/login') . '" class="white">' . Language::$word->M_INFO9_1 . '</a>'); ?>
         <?php else: ?>
            <?php echo Message::msgError(Language::$word->M_INFO10); ?>
         <?php endif; ?>
      </div>
   </div>
</main>

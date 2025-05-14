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
      </div>
   </div>
</main>
<?php
   /**
    * error
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: error.tpl.php, v1.00 7/3/2023 8:15 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="wojo-grid">
      <div class="wojo negative message"><?php echo $this->error; ?></div>
   </div>
</main>
<?php
   /**
    * 404.tpl.php
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: 404.tpl.php, v1.00 7/3/2023 12:30 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="wojo-grid">
      <div id="notFound">
         <div>
            <h1>404</h1>
            <h4><?php echo Language::$word->META_ERROR1; ?></h4>
            <p><?php echo Language::$word->META_ERROR1; ?> :(</p>
            <p><?php echo Language::$word->META_ERROR2; ?></p>
         </div>
         <img src="<?php echo ADMINVIEW; ?>/images/404.svg" alt="">
      </div>
   </div>
</main>
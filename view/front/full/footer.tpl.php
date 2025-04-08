<?php
   /**
    * footer
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: footer.tpl.php, v1.00 7/13/2023 10:12 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<script type="text/javascript" src="<?php echo FRONTVIEW; ?>/js/master.js"></script>
<?php Debug::displayInfo(); ?>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Master({
         url: "<?php echo FRONTVIEW;?>",
         surl: "<?php echo SITEURL;?>",
         loginCheck: <?php echo (App::Auth()->logged_in and $this->core->one_login)? 1 : 0;?>,
         lang: {
            button_text: "<?php echo Language::$word->BROWSE;?>",
            empty_text: "<?php echo Language::$word->NOFILE;?>",
         }
      });
   });
   // ]]>
</script>
</body>
</html>
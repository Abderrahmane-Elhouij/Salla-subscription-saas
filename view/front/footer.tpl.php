<?php
   /**
    * footer
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: footer.tpl.php, v1.00 7/12/2023 10:35 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!-- Footer -->
<footer>
   <div class="wrapper">
      <div class="wojo-grid">Copyright &copy;<?php echo date('Y') . ' ' . $this->core->company; ?> | Powered by
         <small>[wojo::works] v.<?php echo $this->core->wojov; ?></small>
      </div>
   </div>
</footer>
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
      <?php if($this->core->eucookie):?>
      $('body').wCookies({
         title: '&#x1F36A; <?php echo Language::$word->EU_W_COOKIES;?>?',
         message: '<?php echo Language::$word->EU_NOTICE;?>',
         delay: 600,
         expires: 360,
         cookieName: "WCDP_GDPR",
         link: '<?php echo Url::url('/page', $this->core->page_slugs->privacy[0]->page_type);?>',
         cookieTypes: [
            {
               type: '<?php echo Language::$word->EU_PREFS;?>',
               value: 'preferences',
               description: '<?php echo Language::$word->EU_PREFS_I;?>'
            },
            {
               type: '<?php echo Language::$word->EU_ANALYTICS;?>',
               value: 'analytics',
               description: '<?php echo Language::$word->EU_ANALYTICS_I;?>'
            },
            {
               type: '<?php echo Language::$word->EU_MARKETING;?>',
               value: 'marketing',
               description: '<?php echo Language::$word->EU_MARKETING_I;?>'
            }
         ],
         uncheckBoxes: true,
         acceptBtnLabel: '<?php echo Language::$word->EU_ACCEPT;?>',
         advancedBtnLabel: '<?php echo Language::$word->EU_CUSTOMISE;?>',
         moreInfoLabel: '<?php echo Language::$word->PRIVACY;?>',
         cookieTypesTitle: '<?php echo Language::$word->EU_SELCOOKIES;?>',
         fixedCookieTypeLabel: '<?php echo Language::$word->EU_ESSENTIALS;?>',
         fixedCookieTypeDesc: '<?php echo Language::$word->EU_ESSENTIALS_I;?>'
      });
      <?php endif;?>
   });
   // ]]>
</script>
</body>
</html>
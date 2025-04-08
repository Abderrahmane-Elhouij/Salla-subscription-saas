<?php
   /**
    * footer
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: footer.tpl.php, v1.00 7/1/2023 10:33 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!-- Footer -->
</div>
</div>
</main>
<footer><p class="icon-text"><i class="icon wojologo"></i>Copyright &copy;<?php echo date('Y') . ' ' . $this->core->company; ?> | Powered by <small> [wojo::works]v.<?php echo $this->core->wojov; ?></small></p></footer>
<?php Debug::displayInfo(); ?>
<script src="<?php echo SITEURL; ?>/assets/editor/editor.js"></script>
<script src="<?php echo SITEURL; ?>/assets/editor/alignment.js"></script>
<script src="<?php echo SITEURL; ?>/assets/editor/fontcolor.js"></script>
<script src="<?php echo SITEURL; ?>/assets/editor/fullscreen.js"></script>
<script src="<?php echo ADMINVIEW; ?>/js/master.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $.Master({
         weekstart: <?php echo($this->core->weekstart);?>,
         ampm: <?php echo ($this->core->time_format) == 'HH:mm'? 0 : 1;?>,
         url: "<?php echo ADMINVIEW;?>",
         aurl: "<?php echo ADMINURL;?>",
         surl: "<?php echo SITEURL;?>",
         lang: {
            button_text: "<?php echo Language::$word->BROWSE;?>",
            empty_text: "<?php echo Language::$word->NOFILE;?>",
            monthsFull: [ <?php echo Date::monthList(false);?> ],
            monthsShort: [ <?php echo Date::monthList(false, false);?> ],
            weeksFull: [ <?php echo Date::weekList(false); ?> ],
            weeksShort: [ <?php echo Date::weekList(false, false);?> ],
            weeksMed: [ <?php echo Date::weekList(false, false, true);?> ],
            dateFormat: "<?php echo $this->core->calendar_date;?>",
            today: "<?php echo Language::$word->TODAY;?>",
            now: "<?php echo Language::$word->NOW;?>",
            selPic: "<?php echo Language::$word->SELPIC;?>",
            clear: "<?php echo Language::$word->CLEAR;?>",
            clsBtn: "<?php echo Language::$word->CLOSE;?>",
            delBtn: "<?php echo Language::$word->DELETE_REC;?>",
            trsBtn: "<?php echo Language::$word->MTOTRASH;?>",
            restBtn: "<?php echo Language::$word->RFCOMPLETE;?>",
            canBtn: "<?php echo Language::$word->CANCEL;?>",
            delMsg1: "<?php echo Language::$word->DELCONFIRM1;?>",
            delMsg2: "<?php echo Language::$word->DELCONFIRM2;?>",
            delMsg3: "<?php echo Language::$word->TRASH;?>",
            delMsg5: "<?php echo Language::$word->DELCONFIRM4;?>",
            delMsg6: "<?php echo Language::$word->DELCONFIRM6;?>",
            delMsg7: "<?php echo Language::$word->DELCONFIRM10;?>",
            delMsg8: "<?php echo Language::$word->DELCONFIRM3;?>",
            delMsg9: "<?php echo Language::$word->DELCONFIRM11;?>",
            working: "<?php echo Language::$word->WORKING;?>"
         }
      });
   });
   // ]]>
</script>
</body>
</html>

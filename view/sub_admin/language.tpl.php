<?php
   /**
    * language
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.20: language.tpl.php, v1.00 7/4/2023 5:17 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (!Auth::hasPrivileges('manage_languages')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<div class="wojo form">
   <div class="row gutters align-middle justify-center">
      <div class="columns screen-30 mobile-100 phone-a00">
         <div class="wojo action icon input">
            <input id="filter" type="text" placeholder="<?php echo Language::$word->SEARCH; ?>">
            <i class="icon search"></i>
         </div>
      </div>
      <div class="columns screen-30 mobile-100 phone-100">
         <select name="pgroup" id="pgroup" data-abbr="<?php echo Core::$language; ?>">
            <option data-type="all" value="all"><?php echo Language::$word->LG_SUB4; ?></option>
            <?php foreach ($this->sections as $rows): ?>
               <option data-type="filter" value="<?php echo $rows; ?>"><?php echo $rows; ?></option>
            <?php endforeach; ?>
            <?php unset($rows); ?>
         </select>
      </div>
   </div>
</div>
<?php $i = 0; ?>
<table class="wojo responsive table" id="editable">
   <thead>
   <tr>
      <th><?php echo Language::$word->NAME; ?></th>
      <th class="auto right-align"><?php echo Language::$word->LG_KEY; ?></th>
   </tr>
   </thead>
   <?php foreach ($this->data as $key => $row) : ?>
      <?php $i++; ?>
      <tr>
         <td>
            <span data-editable="true" data-set='{"action": "editPhrase", "id": <?php echo $i; ?>,"key":"<?php echo $key; ?>", "path":"<?php echo Core::$language; ?>.lang.json"}'><?php echo $row; ?></span>
         </td>
         <td class="auto right-align">
            <span class="wojo mini secondary label"><?php echo $key; ?></span>
         </td>
      </tr>
   <?php endforeach; ?>
</table>
<script src="<?php echo ADMINVIEW;?>/js/language.js"></script>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function() {
      $.Language({
         url: "<?php echo ADMINVIEW;?>",
      });
   });
   // ]]>
</script>
<?php
   /**
    * membership
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: membership.tpl.php, v1.00 7/5/2023 7:34 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   if (!Auth::hasPrivileges('manage_memberships')): print Message::msgError(Language::$word->NOACCESS);
      return;
   endif;
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_membership_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_membership_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start history -->
<?php case 'history': ?>
   <?php include '_membership_history.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_membership_grid.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>
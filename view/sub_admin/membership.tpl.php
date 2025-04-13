<?php
  /**
   * Membership
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2025
   * @version 1.00: membership.tpl.php, v1.00 4/13/2025 2:41 PM
   *
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch (Url::segment($this->segments)): case 'edit': ?>
   <!-- Start edit -->
   <?php include '_membership_edit.tpl.php'; ?>
   <?php break; ?>
   <!-- Start new -->
<?php case 'new': ?>
   <?php include '_membership_new.tpl.php'; ?>
   <?php break; ?>
   <!-- Start default -->
<?php default: ?>
   <?php include '_membership_grid.tpl.php'; ?>
   <?php break; ?>
<?php endswitch; ?>
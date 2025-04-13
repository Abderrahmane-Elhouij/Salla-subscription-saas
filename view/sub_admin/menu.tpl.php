<?php
/**
 * Menu
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: menu.tpl.php, v1.00 4/13/2025 2:41 PM
 *
 */
if (!defined("_WOJO")) {
    die('Direct access to this location is not allowed.');
}

$pagename = Url::segment($this->segments, 0);
?>
<div class="wojo-menu" id="menu">
    <nav class="wojo amenu"></nav>
    <ul class="mtabs">
        <li<?php echo (!$pagename or $pagename == "index") ? ' class="active"' : ''; ?>>
            <a href="<?php echo SITEURL; ?>/sub_admin/" class="black">
                <i class="icon chart pie"></i>
                <span class="tooltip" data-content="<?php echo Language::$word->SUB_ADMIN_DASHBOARD; ?>"></span>
            </a>
        </li>
        <li<?php echo ($pagename == "users") ? ' class="active"' : ''; ?>>
            <a href="<?php echo Url::url("/sub_admin/users"); ?>">
                <i class="icon users"></i>
                <span class="tooltip" data-content="<?php echo Language::$word->SUB_ADMIN_USERS; ?>"></span>
            </a>
        </li>
        <li<?php echo ($pagename == "memberships") ? ' class="active"' : ''; ?>>
            <a href="<?php echo Url::url("/sub_admin/memberships"); ?>">
                <i class="icon membership"></i>
                <span class="tooltip" data-content="<?php echo Language::$word->SUB_ADMIN_MEMBERSHIPS; ?>"></span>
            </a>
        </li>
    </ul>
</div>
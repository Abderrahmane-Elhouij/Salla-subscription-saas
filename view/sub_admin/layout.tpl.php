<?php
/**
 * Layout
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: layout.tpl.php, v1.00 4/13/2025 2:41 PM
 *
 */
if (!defined("_WOJO")) {
    die('Direct access to this location is not allowed.');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $this->title; ?></title>
    <meta name="keywords" content="<?php echo $this->keywords; ?>">
    <meta name="description" content="<?php echo $this->description; ?>">
    <link href="../assets/cache/master_style.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../assets/jquery.js"></script>
    <script type="text/javascript" src="../assets/global.js"></script>
    <?php /*
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicons/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicons/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="../assets/favicons/favicon.ico">
    */ ?>
</head>
<body class="wojoAdmin">
<header id="mmpro">
    <div class="topwidget">
        <a class="logo" href="<?php echo SITEURL; ?>/sub_admin/">
            <img src="../assets/images/logo.svg" alt="<?php echo $this->core->company; ?>"></a>
        <div class="wojo small horizontal list">
            <div class="item">
                <div class="wojo simple icon dropdown mainButton">
                    <i class="icon mini people"></i>
                    <div class="menu left">
                        <a class="item" href="<?php echo SITEURL; ?>/sub_admin/account">
                            <i class="icon user"></i> <?php echo Language::$word->PROFILE; ?></a>
                        <a class="item" href="<?php echo SITEURL; ?>/sub_admin/password">
                            <i class="icon lock"></i> <?php echo Language::$word->M_SUB2; ?></a>
                        <div class="divider"></div>
                        <a class="item" href="<?php echo SITEURL; ?>/sub_admin/logout">
                            <i class="icon power"></i> <?php echo Language::$word->LOGOUT; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("menu.tpl.php"); ?>
</header>
<main>
    <div class="wojo-grid">
        <?php echo Content::printBreadCrumbs($this->crumbs); ?>
        <?php if ($this->error): ?>
            <div class="wojo negative icon message">
                <i class="icon exclamation circle"></i>
                <div class="content">
                    <div class="header"> <?php echo Language::$word->ERROR; ?></div>
                    <p><?php echo $this->error; ?></p>
                    <a class="wojo basic button" href="javascript:void(0);" onclick="history.go(-1);"><?php echo Language::$word->BACK; ?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php include_once(BASEPATH . "view/" . $this->dir . Url::segment($this->segments, 0) . '.tpl.php'); ?>
    </div>
</main>
<footer>
    <div class="wojo-grid">
        <div class="vertical-margin">
            <div class="content-center">Copyright &copy;<?php echo date('Y') . ' ' . $this->core->company; ?>
                <span class="wojo divider"></span>All rights reserved.
            </div>
        </div>
    </div>
</footer>
<script type="text/javascript" src="../assets/cache/core.admin.js"></script>
</body>
</html>
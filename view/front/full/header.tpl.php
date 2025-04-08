<?php
   /**
    * header
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: header.tpl.php, v1.00 7/13/2023 10:12 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!DOCTYPE html lang="en">
<head>
   <meta charset="utf-8">
   <title><?php echo isset($this)? $this->title : App::Core()->company; ?></title>
   <?php if (isset($this->keywords)): ?>
      <meta name="keywords" content="<?php echo $this->keywords; ?>">
      <meta name="description" content="<?php echo $this->description; ?>">
   <?php endif; ?>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <meta name="apple-mobile-web-app-capable" content="yes">
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
   <link href="<?php echo FRONTVIEW . '/cache/' . Cache::cssCache(array('base.css', 'color.css', 'transition.css', 'label.css', 'form.css', 'dropdown.css', 'input.css', 'button.css', 'message.css', 'image.css', 'list.css', 'table.css', 'icon.css', 'card.css', 'modal.css', 'editor.css', 'tooltip.css', 'progress.css', 'utility.css', 'style.css'), FRONTBASE); ?>" rel="stylesheet" type="text/css"/>
</head>
<body class="full">
<?php
   /**
    * login
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: login.tpl.php, v1.00 7/1/2023 9:26 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   if (is_dir(BASEPATH . 'setup'))
      : die("<div style='text-align:center'>"
     . "<span style='padding: 5px; border: 1px solid #999; background-color:#EFEFEF;"
     . "font-family: Verdana; font-size: 11px; margin-left:auto; margin-right:auto'>"
     . '<b>Warning:</b> Please delete the setup directory!</span></div>');
   endif;
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title><?php echo $this->title; ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <link href="<?php echo ADMINVIEW; ?>/css/base.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>/css/transition.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>/css/progress.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>/css/icon.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>/css/message.css" rel="stylesheet" type="text/css">
   <link href="<?php echo ADMINVIEW; ?>/css/login.css" rel="stylesheet" type="text/css">
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
</head>
<body>
<div class="wrap">
   <div id="formContent">
      <h2 class="center-align"><?php echo Utility::sayHello(); ?></h2>
      <div class="fadeIn first">
         <img src="<?php echo UPLOADURL; ?>/avatars/default.svg" id="avatar" alt="User Icon"/>
      </div>
      <div id="loginform">
         <form id="admin_form" name="admin_form" method="post">
            <input type="text" class="fadeIn second" name="username" placeholder="<?php echo Language::$word->USERNAME; ?>">
            <input type="password" class="fadeIn third" name="password" placeholder="<?php echo Language::$word->M_PASSWORD; ?>">
            <button id="doSubmit" type="button" name="submit"
                    class="fadeIn fourth"><?php echo Language::$word->LOGIN; ?></button>
         </form>
         <div class="formFooter">
            <a id="passreset" class="underlineHover"><?php echo Language::$word->M_PASSWORD_RES; ?>?</a>
            <?php if(strpos($_SERVER['REQUEST_URI'], 'sub_admin') !== false || (isset($this->section) && $this->section == 'sub_admin')): ?>
            <div style="margin-top:10px;">
               <a href="<?php echo SITEURL; ?>/sub_admin_register.php" class="wojo small secondary button">Register as Sub Admin</a>
            </div>
            <?php endif; ?>
         </div>
      </div>
      <div id="passform" class="hide-all">
         <input type="text" name="pEmail" id="pEmail" class="input-container"
                placeholder="<?php echo Language::$word->M_EMAIL; ?>">
         <button id="dopass" type="button" name="doopass"><?php echo Language::$word->SUBMIT; ?></button>
         <div class="formFooter">
            <a id="backto" class="underlineHover"><?php echo Language::$word->M_SUB14; ?></a>
         </div>
      </div>
   </div>
   <footer> Copyright &copy;<?php echo date('Y') . ' ' . App::Core()->company; ?>
   </footer>
</div>
<script type="text/javascript" src="<?php echo ADMINVIEW; ?>/js/login.js"></script>
<script type="text/javascript">
   $(document).ready(function () {
      $.Login({
         url: "<?php echo FRONTVIEW;?>",
         surl: "<?php echo SITEURL;?>"
      });
      
      // Direct handling for the sub-admin registration button to bypass jQuery event handling
      <?php if(isset($this->section) && $this->section == 'sub_admin'): ?>
      document.getElementById('registerSubAdminBtn').onclick = function(e) {
         e.preventDefault();
         e.stopPropagation();
         window.location.href = '<?php echo SITEURL; ?>/sub_admin_register.php';
         return false;
      };
      <?php endif; ?>
   });
</script>
<?php Debug::displayInfo(); ?>
</body>
</html>
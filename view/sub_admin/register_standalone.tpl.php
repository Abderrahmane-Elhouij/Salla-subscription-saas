<?php
   /**
    * register_standalone.tpl.php
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2025
    * @version 1.00: register_standalone.tpl.php, v1.00 4/14/2025 10:55 PM
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title><?php echo $this->title; ?></title>
   <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
   <link href="<?php echo SITEURL; ?>/view/admin/css/base.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>/view/admin/css/transition.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>/view/admin/css/progress.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>/view/admin/css/icon.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>/view/admin/css/message.css" rel="stylesheet" type="text/css">
   <link href="<?php echo SITEURL; ?>/view/admin/css/login.css" rel="stylesheet" type="text/css">
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/jquery.js"></script>
   <script type="text/javascript" src="<?php echo SITEURL; ?>/assets/global.js"></script>
</head>
<body>
<div class="wrap">
   <div id="formContent" class="wider">
      <h2 class="center-align">Sub-Admin Registration</h2>
      <div class="fadeIn first">
         <img src="<?php echo SITEURL; ?>/uploads/avatars/default.svg" id="avatar" alt="User Icon"/>
      </div>
      
      <?php if(!empty($this->success)): ?>
         <div class="wojo success message">
            <div class="header">Success</div>
            <p><?php echo $this->message; ?></p>
         </div>
         <div class="formFooter">
            <a href="<?php echo SITEURL; ?>/sub_admin/login" class="underlineHover">Go to Login</a>
         </div>
      <?php else: ?>
      <div id="registerform">
         <?php if(count(Message::$msgs)): ?>
            <div class="wojo error message">
               <div class="header">Error</div>
               <p><?php echo Message::display(); ?></p>
            </div>
         <?php endif; ?>
         <form id="admin_form" name="admin_form" method="post">
            <div class="formRow">
               <input type="text" class="fadeIn second" name="fname" placeholder="First Name" value="<?php echo Validator::post('fname'); ?>">
            </div>
            <div class="formRow">
               <input type="text" class="fadeIn second" name="lname" placeholder="Last Name" value="<?php echo Validator::post('lname'); ?>">
            </div>
            <div class="formRow">
               <input type="text" class="fadeIn third" name="email" placeholder="Email Address" value="<?php echo Validator::post('email'); ?>">
            </div>
            <div class="formRow">
               <input type="password" class="fadeIn fourth" name="password" placeholder="Password">
            </div>
            <div class="formRow">
               <input type="password" class="fadeIn fourth" name="password2" placeholder="Confirm Password">
            </div>
            <input type="hidden" name="dosubmit" value="1">
            <button type="submit" name="submit" class="fadeIn fifth">Register</button>
         </form>
         <div class="formFooter">
            <p>Already have an account? <a href="<?php echo SITEURL; ?>/sub_admin/login" class="underlineHover">Login</a></p>
         </div>
      </div>
      <?php endif; ?>
   </div>
   <footer> Copyright &copy;<?php echo date('Y') . ' ' . $this->core->company; ?>
   </footer>
</div>
<script type="text/javascript">
   $(document).ready(function () {
      // Any additional JS functionality can go here
      $('.message .close').on('click', function() {
         $(this).closest('.message').transition('fade');
      });
   });
</script>
</body>
</html>
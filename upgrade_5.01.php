<?php
    /**
     * Upgrade
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Upgrade.php v1.00 7/17/2023 1:34 PM Gewa Exp $
     *
     */
    const _WOJO = true;
    require_once 'init.php';
    
    $version = App::Core()->wojov;
    
    if (isset($_POST['submit'])) {
        Database::Go()->rawQuery("
        INSERT INTO `gateways` (`name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES
            ('paystack', 'Paystack', 'paystack', 1, 'Secret Key', 'Currency Code', 'Public Key', 'sk_test_', 'ZAR', 'pk_test_', 0, 1)
        ")->run();
        
        Database::Go()->update(Core::sTable, array('wojov' => '5.01'))->where('id', 1, '=')->run();
        
        Url::redirect(SITEURL . '/upgrade.php?update=done');
    }
?>
<!doctype html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <title>MMP Upgrade</title>
   <style>
      body {
         font-family: Raleway, Arial, Helvetica, sans-serif;
         font-size: 14px;
         line-height: 1.3em;
         color: #FFF;
         background-color: #222;
         font-weight: 300;
         margin: 0;
         padding: 0
      }
      #wrap {
         width: 800px;
         margin-top: 150px;
         margin-right: auto;
         margin-left: auto;
         background-color: #208ed3;
         box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.1);
         border: 2px solid #111;
         border-radius: 3px
      }
      header {
         background-color: #145983;
         font-size: 26px;
         font-weight: 200;
         padding: 35px
      }
      .line {
         height: 2px;
         background: linear-gradient(to right, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 47%, rgba(255, 255, 255, 0) 100%)
      }
      .line2 {
         position: absolute;
         left: 200px;
         height: 360px;
         width: 2px;
         background: linear-gradient(to bottom, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 1) 47%, rgba(255, 255, 255, 0) 100%);
         display: block
      }
      #content {
         position: relative;
         padding: 45px 20px
      }
      #content .left {
         float: left;
         width: 200px;
         height: 400px;
         background-image: url(assets/installer.png);
         background-repeat: no-repeat;
         background-position: 10px center
      }
      #content .right {
         margin-left: 200px
      }
      h4 {
         font-size: 18px;
         font-weight: 300;
         margin: 0 0 40px;
         padding: 0
      }
      p.info {
         background-color: #383838;
         border-radius: 3px;
         box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.1);
         padding: 10px
      }
      p.info span {
         display: block;
         float: left;
         padding: 10px;
         background: rgba(255, 255, 255, 0.1);
         margin-left: -10px;
         margin-top: -10px;
         border-radius: 3px 0 0 3px;
         margin-right: 5px;
         border-right: 1px solid rgba(255, 255, 255, 0.05)
      }
      footer {
         background-color: #383838;
         padding: 20px
      }
      form {
         display: inline-block;
         float: right;
         margin: 0;
         padding: 0
      }
      .button {
         border: 2px solid #222;
         font-family: Raleway, Arial, Helvetica, sans-serif;
         font-size: 14px;
         color: #FFF;
         background-color: #208ED3;
         text-align: center;
         cursor: pointer;
         font-weight: 600;
         -webkit-transition: all .35s ease;
         -moz-transition: all .35s ease;
         -o-transition: all .35s ease;
         transition: all .35s ease;
         outline: none;
         margin: 0;
         padding: 5px 20px
      }
      .button:hover {
         background-color: #222;
         -webkit-transition: all .55s ease;
         -moz-transition: all .55s ease;
         -o-transition: all .35s ease;
         transition: all .55s ease;
         outline: none
      }
      .clear {
         font-size: 0;
         line-height: 0;
         clear: both;
         height: 0
      }
      .clearfix:after {
         content: ".";
         display: block;
         height: 0;
         clear: both;
         visibility: hidden;
      }
      a {
         text-decoration: none;
         float: right
      }
   </style>
</head>
<body>
<div id="wrap">
   <header>Welcome to MMP pro Upgrade Wizard</header>
   <div class="line"></div>
   <div id="content">
      <div class="left">
         <div class="line2"></div>
      </div>
      <div class="right">
         <h4>MMP Upgrade v5.00</h4>
          <?php if (Validator::compareNumbers($version, 5.00, '!=')): ?>
             <p class="info"><span>Warning!</span>You need at least MMP v5.00 in order to continue.</p>
          <?php else: ?>
              <?php if (isset($_GET['update']) && $_GET['update'] == 'done'): ?>
                <p class="info"><span>Success!</span>Installation Completed. Please delete upgrade.php</p>
              <?php else: ?>
                <p class="info"><span>Warning!</span>Please make sure you have performed full backup, including database!!!</p>
                <p style="margin-top:60px">When ready click Install button.</p>
                <p><span>Please be patient, and<strong> DO NOT</strong> Refresh your browser.<br>
        This process might take a while</span>.</p>
              <?php endif; ?>
          <?php endif; ?>
      </div>
   </div>
   <div class="clear"></div>
   <footer class="clearfix"><small>current <b>mmp v.<?php echo $version; ?></b></small>
       <?php if (isset($_GET['update']) && $_GET['update'] == 'done'): ?>
          <a href="admin/" class="button">Back to admin panel</a>
       <?php else: ?>
          <form method="post" name="upgrade_form">
              <?php if (Validator::compareNumbers($version, 5.00)): ?>
                 <input name="submit" type="submit" class="button" value="Upgrade MMP" id="submit"/>
              <?php endif; ?>
          </form>
       <?php endif; ?>
   </footer>
</div>
</body>
</html>
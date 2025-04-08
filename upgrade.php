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
        ALTER TABLE `banlist`
            CHANGE `item` `item` varchar(50)  COLLATE utf8mb4_unicode_ci NOT NULL after `id` ,
            CHANGE `type` `type` enum('IP','Email')  COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IP' after `item` ,
            CHANGE `comment` `comment` varchar(150)  COLLATE utf8mb4_unicode_ci NOT NULL after `type` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_unicode_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `cart`
            ADD COLUMN `user_id` int(11) unsigned  NOT NULL DEFAULT 0 first ,
            ADD COLUMN `membership_id` int(11) unsigned  NOT NULL DEFAULT 0 after `user_id` ,
            ADD COLUMN `coupon_id` int(11) unsigned  NOT NULL DEFAULT 0 after `membership_id` ,
            CHANGE `tax` `tax` decimal(13,2) unsigned  NOT NULL DEFAULT 0.00 after `coupon_id` ,
            CHANGE `cart_id` `cart_id` varchar(100) COLLATE utf8mb4_general_ci NULL after `totalprice` ,
            CHANGE `order_id` `order_id` varchar(100) COLLATE utf8mb4_general_ci NULL after `cart_id` ,
            DROP COLUMN `uid` ,
            DROP COLUMN `cid` ,
            DROP COLUMN `mid` ,
            DROP KEY `idx_membership`, ADD KEY `idx_membership`(`membership_id`) ,
            DROP KEY `idx_user`, ADD KEY `idx_user`(`user_id`) ,
            DROP KEY `PRIMARY`, ADD PRIMARY KEY(`user_id`) , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `countries`
            CHANGE `abbr` `abbr` varchar(2)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `name` `name` varchar(70)  COLLATE utf8mb4_general_ci NOT NULL after `abbr` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `coupons`
            CHANGE `title` `title` varchar(100)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `code` `code` varchar(30)  COLLATE utf8mb4_general_ci NOT NULL after `title` ,
            CHANGE `type` `type` enum('p','a')  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'p' after `discount` ,
            CHANGE `membership_id` `membership_id` varchar(50)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' after `type` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `cronjobs`
            CHANGE `stripe_customer` `stripe_customer` varchar(60)  COLLATE utf8mb4_general_ci NOT NULL after `membership_id` ,
            CHANGE `stripe_pm` `stripe_pm` varchar(80)  COLLATE utf8mb4_general_ci NOT NULL after `stripe_customer` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `custom_fields`
            CHANGE `title` `title` varchar(60) COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `tooltip` `tooltip` varchar(100) COLLATE utf8mb4_general_ci NULL after `title` ,
            CHANGE `name` `name` varchar(20) COLLATE utf8mb4_general_ci NOT NULL after `tooltip` ,
            CHANGE `section` `section` varchar(30) COLLATE utf8mb4_general_ci NULL after `required` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `downloads`
            CHANGE `alias` `alias` varchar(60)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `name` `name` varchar(80)  COLLATE utf8mb4_general_ci NOT NULL after `alias` ,
            CHANGE `extension` `extension` varchar(4)  COLLATE utf8mb4_general_ci NULL after `filesize` ,
            CHANGE `type` `type` varchar(20)  COLLATE utf8mb4_general_ci NULL after `extension` ,
            CHANGE `token` `token` varchar(32)  COLLATE utf8mb4_general_ci NOT NULL after `type` ,
            CHANGE `fileaccess` `fileaccess` varchar(24)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' COMMENT '0 = all' after `token` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `email_templates`
            CHANGE `name` `name` varchar(100)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `subject` `subject` varchar(150)  COLLATE utf8mb4_general_ci NOT NULL after `name` ,
            CHANGE `help` `help` tinytext  COLLATE utf8mb4_general_ci NULL after `subject` ,
            CHANGE `body` `body` text  COLLATE utf8mb4_general_ci NOT NULL after `help` ,
            CHANGE `type` `type` enum('news','mailer')  COLLATE utf8mb4_general_ci NULL DEFAULT 'mailer' after `body` ,
            CHANGE `typeid` `typeid` varchar(30)  COLLATE utf8mb4_general_ci NULL after `type` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `gateways`
            CHANGE `name` `name` varchar(30)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `displayname` `displayname` varchar(50)  COLLATE utf8mb4_general_ci NOT NULL after `name` ,
            CHANGE `dir` `dir` varchar(30)  COLLATE utf8mb4_general_ci NOT NULL after `displayname` ,
            CHANGE `extra_txt` `extra_txt` varchar(120)  COLLATE utf8mb4_general_ci NULL after `live` ,
            CHANGE `extra_txt2` `extra_txt2` varchar(120)  COLLATE utf8mb4_general_ci NULL after `extra_txt` ,
            CHANGE `extra_txt3` `extra_txt3` varchar(120)  COLLATE utf8mb4_general_ci NULL after `extra_txt2` ,
            CHANGE `extra` `extra` varchar(120)  COLLATE utf8mb4_general_ci NOT NULL after `extra_txt3` ,
            CHANGE `extra2` `extra2` varchar(120)  COLLATE utf8mb4_general_ci NULL after `extra` ,
            CHANGE `extra3` `extra3` varchar(120)  COLLATE utf8mb4_general_ci NULL after `extra2` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        INSERT INTO `gateways` (`name`, `displayname`, `dir`, `live`, `extra_txt`, `extra_txt2`, `extra_txt3`, `extra`, `extra2`, `extra3`, `is_recurring`, `active`) VALUES
            ('paystack', 'Paystack', 'paystack', 1, 'Secret Key', 'Currency Code', 'Public Key', 'sk_test_', 'ZAR', 'pk_test_', 0, 1)
        ")->run();

        Database::Go()->rawQuery("
        ALTER TABLE `memberships`
            CHANGE `title` `title` varchar(50)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `description` `description` varchar(200)  COLLATE utf8mb4_general_ci NULL after `title` ,
            ADD COLUMN `body` text  COLLATE utf8mb4_general_ci NULL after `description` ,
            CHANGE `price` `price` decimal(12,2) unsigned   NOT NULL DEFAULT 0.00 after `body` ,
            CHANGE `period` `period` varchar(1)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'D' after `days` ,
            CHANGE `thumb` `thumb` varchar(40)  COLLATE utf8mb4_general_ci NULL after `recurring` ,
            ADD COLUMN `sorting` smallint(1) unsigned   NOT NULL DEFAULT 0 after `created` ,
            CHANGE `active` `active` tinyint(1) unsigned   NOT NULL DEFAULT 1 after `sorting` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `news`
            CHANGE `title` `title` varchar(80)  COLLATE utf8mb4_bin NOT NULL after `id` ,
            CHANGE `body` `body` text  COLLATE utf8mb4_bin NOT NULL after `title` ,
            CHANGE `author` `author` varchar(55)  COLLATE utf8mb4_bin NOT NULL after `body` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `pages`
            CHANGE `title` `title` varchar(200)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `slug` `slug` varchar(200)  COLLATE utf8mb4_general_ci NOT NULL after `title` ,
            CHANGE `body` `body` longtext  COLLATE utf8mb4_general_ci NULL after `slug` ,
            CHANGE `page_type` `page_type` varchar(15)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'normal' after `body` ,
            CHANGE `membership_id` `membership_id` varchar(20)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' after `page_type` ,
            CHANGE `keywords` `keywords` varchar(250)  COLLATE utf8mb4_general_ci NULL after `membership_id` ,
            CHANGE `description` `description` varchar(250)  COLLATE utf8mb4_general_ci NULL after `keywords` ,
            ADD COLUMN `is_hide` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `created` ,
            ADD COLUMN `sorting` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `is_hide` ,
            CHANGE `active` `active` tinyint(1)   NOT NULL DEFAULT 0 after `sorting` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `payments`
            CHANGE `txn_id` `txn_id` varchar(50)  COLLATE utf8mb4_general_ci NULL after `id` ,
            CHANGE `currency` `currency` varchar(4)  COLLATE utf8mb4_general_ci NULL after `total` ,
            CHANGE `pp` `pp` varchar(20)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Stripe' after `currency` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `privileges`
            CHANGE `code` `code` varchar(20)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `name` `name` varchar(30)  COLLATE utf8mb4_general_ci NOT NULL after `code` ,
            CHANGE `description` `description` varchar(60)  COLLATE utf8mb4_general_ci NULL after `name` ,
            CHANGE `mode` `mode` varchar(8)  COLLATE utf8mb4_general_ci NOT NULL after `description` ,
            CHANGE `type` `type` varchar(40)  COLLATE utf8mb4_general_ci NULL after `mode` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("ALTER TABLE `role_privileges` DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `roles`
            CHANGE `code` `code` varchar(10)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `icon` `icon` varchar(20)  COLLATE utf8mb4_general_ci NULL after `code` ,
            CHANGE `name` `name` varchar(30)  COLLATE utf8mb4_general_ci NOT NULL after `icon` ,
            CHANGE `description` `description` varchar(200)  COLLATE utf8mb4_general_ci NOT NULL after `name` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `settings`
            CHANGE `company` `company` varchar(50)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `site_email` `site_email` varchar(80)  COLLATE utf8mb4_general_ci NOT NULL after `company` ,
            CHANGE `psite_email` `psite_email` varchar(80)  COLLATE utf8mb4_general_ci NULL after `site_email` ,
            CHANGE `site_dir` `site_dir` varchar(100)  COLLATE utf8mb4_general_ci NULL after `psite_email` ,
            CHANGE `backup` `backup` varchar(60)  COLLATE utf8mb4_general_ci NULL after `perpage` ,
            CHANGE `logo` `logo` varchar(40)  COLLATE utf8mb4_general_ci NULL after `backup` ,
            CHANGE `plogo` `plogo` varchar(40)  COLLATE utf8mb4_general_ci NULL after `logo` ,
            CHANGE `currency` `currency` varchar(4)  COLLATE utf8mb4_general_ci NULL after `plogo` ,
            ADD COLUMN `tax_rate` decimal(6,2) unsigned   NOT NULL DEFAULT 0.00 after `enable_tax` ,
            CHANGE `long_date` `long_date` varchar(50)  COLLATE utf8mb4_general_ci NULL after `tax_rate` ,
            CHANGE `short_date` `short_date` varchar(50)  COLLATE utf8mb4_general_ci NULL after `long_date` ,
            CHANGE `time_format` `time_format` varchar(20)  COLLATE utf8mb4_general_ci NULL after `short_date` ,
            CHANGE `calendar_date` `calendar_date` varchar(30)  COLLATE utf8mb4_general_ci NULL after `time_format` ,
            CHANGE `dtz` `dtz` varchar(80)  COLLATE utf8mb4_general_ci NULL after `calendar_date` ,
            CHANGE `locale` `locale` varchar(20)  COLLATE utf8mb4_general_ci NULL after `dtz` ,
            CHANGE `lang` `lang` varchar(20)  COLLATE utf8mb4_general_ci NULL after `locale` ,
            ADD COLUMN `eucookie` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `lang` ,
            CHANGE `one_login` `one_login` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `eucookie` ,
            CHANGE `inv_info` `inv_info` text  COLLATE utf8mb4_general_ci NULL after `weekstart` ,
            CHANGE `inv_note` `inv_note` text  COLLATE utf8mb4_general_ci NULL after `inv_info` ,
            CHANGE `offline_info` `offline_info` text  COLLATE utf8mb4_general_ci NULL after `inv_note` ,
            ADD COLUMN `page_slugs` blob   NULL after `social_media` ,
            CHANGE `enable_dmembership` `enable_dmembership` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `page_slugs` ,
            CHANGE `file_dir` `file_dir` varchar(100)  COLLATE utf8mb4_general_ci NULL after `dmembership` ,
            CHANGE `mailer` `mailer` enum('SMTP','SMAIL')  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'SMTP' after `file_dir` ,
            CHANGE `smtp_host` `smtp_host` varchar(100)  COLLATE utf8mb4_general_ci NULL after `mailer` ,
            CHANGE `smtp_user` `smtp_user` varchar(50)  COLLATE utf8mb4_general_ci NULL after `smtp_host` ,
            CHANGE `smtp_pass` `smtp_pass` varchar(50)  COLLATE utf8mb4_general_ci NULL after `smtp_user` ,
            CHANGE `smtp_port` `smtp_port` varchar(6)  COLLATE utf8mb4_general_ci NULL after `smtp_pass` ,
            CHANGE `sendmail` `sendmail` varchar(150)  COLLATE utf8mb4_general_ci NULL after `is_ssl` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `trash`
            CHANGE `parent` `parent` varchar(15)  COLLATE utf8mb4_general_ci NULL after `id` ,
            CHANGE `type` `type` varchar(15)  COLLATE utf8mb4_general_ci NULL after `parent_id` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `user_custom_fields`
            CHANGE `field_name` `field_name` varchar(40)  COLLATE utf8mb4_general_ci NULL after `field_id` ,
            CHANGE `field_value` `field_value` varchar(100)  COLLATE utf8mb4_general_ci NULL after `field_name` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `user_memberships`
            ADD COLUMN `transaction_id` int(11) unsigned   NOT NULL DEFAULT 0 after `id` ,
            ADD COLUMN `user_id` int(11) unsigned   NOT NULL DEFAULT 0 after `transaction_id` ,
            ADD COLUMN `membership_id` int(11) unsigned   NOT NULL DEFAULT 0 after `user_id` ,
            CHANGE `activated` `activated` timestamp   NULL DEFAULT CURRENT_TIMESTAMP after `membership_id` ,
            DROP COLUMN `tid` ,
            DROP COLUMN `uid` ,
            DROP COLUMN `mid` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->rawQuery("
        ALTER TABLE `users`
            CHANGE `username` `username` varchar(50)  COLLATE utf8mb4_general_ci NOT NULL after `id` ,
            CHANGE `fname` `fname` varchar(60)  COLLATE utf8mb4_general_ci NULL after `username` ,
            CHANGE `lname` `lname` varchar(60)  COLLATE utf8mb4_general_ci NULL after `fname` ,
            CHANGE `email` `email` varchar(60)  COLLATE utf8mb4_general_ci NOT NULL after `lname` ,
            CHANGE `membership_id` `membership_id` int(2) unsigned   NOT NULL DEFAULT 0 after `email` ,
            CHANGE `mem_expire` `mem_expire` varchar(20)  COLLATE utf8mb4_general_ci NULL after `membership_id` ,
            CHANGE `hash` `hash` varchar(70)  COLLATE utf8mb4_general_ci NOT NULL after `mem_expire` ,
            CHANGE `token` `token` varchar(40)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' after `hash` ,
            CHANGE `sesid` `sesid` varchar(80)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' after `userlevel` ,
            CHANGE `type` `type` varchar(10)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'member' after `sesid` ,
            CHANGE `lastlogin` `lastlogin` datetime   NULL after `trial_used` ,
            CHANGE `login_info` `login_info` varchar(150)  COLLATE utf8mb4_general_ci NULL after `lastip` ,
            CHANGE `avatar` `avatar` varchar(50)  COLLATE utf8mb4_general_ci NULL after `login_status` ,
            CHANGE `address` `address` varchar(100)  COLLATE utf8mb4_general_ci NULL after `avatar` ,
            CHANGE `city` `city` varchar(50)  COLLATE utf8mb4_general_ci NULL after `address` ,
            CHANGE `state` `state` varchar(50)  COLLATE utf8mb4_general_ci NULL after `city` ,
            CHANGE `zip` `zip` varchar(10)  COLLATE utf8mb4_general_ci NULL after `state` ,
            CHANGE `country` `country` varchar(4)  COLLATE utf8mb4_general_ci NULL after `zip` ,
            CHANGE `user_files` `user_files` varchar(150)  COLLATE utf8mb4_general_ci NOT NULL DEFAULT '0' after `country` ,
            CHANGE `notes` `notes` tinytext  COLLATE utf8mb4_general_ci NULL after `user_files` ,
            CHANGE `stripe_cus` `stripe_cus` varchar(100)  COLLATE utf8mb4_general_ci NULL after `newsletter` ,
            CHANGE `stripe_pm` `stripe_pm` varchar(80)  COLLATE utf8mb4_general_ci NULL after `stripe_cus` ,
            CHANGE `custom_fields` `custom_fields` varchar(200)  COLLATE utf8mb4_general_ci NULL after `stripe_pm` ,
            CHANGE `active` `active` enum('y','n','t','b')  COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'n' after `custom_fields` ,
            DROP COLUMN `salt` , DEFAULT CHARSET='utf8mb4', COLLATE ='utf8mb4_general_ci'
        ")->run();
        
        Database::Go()->update(Core::sTable, array('wojov' => '5.01', 'page_slugs' => '{"home":[{"page_type":"home"}],"contact":[{"page_type":"contact"}],"privacy":[{"page_type":"privacy"}]}'))->where('id', 1, '=')->run();
        
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
          <?php if (Validator::compareNumbers($version, 4.61, '!=')): ?>
             <p class="info"><span>Warning!</span>You need at least MMP v4.61 in order to continue.</p>
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
              <?php if (Validator::compareNumbers($version, 4.61)): ?>
                 <input name="submit" type="submit" class="button" value="Upgrade MMP" id="submit"/>
              <?php endif; ?>
          </form>
       <?php endif; ?>
   </footer>
</div>
</body>
</html>
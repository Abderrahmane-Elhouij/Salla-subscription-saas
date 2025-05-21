<?php
   /**
    * configuration
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: configuration.tpl.php, v1.00 7/9/2023 10:27 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }

   App::Auth()->checkOwner();
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo segment form margin-bottom">
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_SITENAME; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SITENAME; ?>" value="<?php echo $this->data->company; ?>" name="company">
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_WEBEMAIL; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_WEBEMAIL; ?>" value="<?php echo $this->data->site_email; ?>" name="site_email">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_DIR; ?>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_DIR; ?>" value="<?php echo $this->data->site_dir; ?>" name="site_dir">
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_WEBEMAIL1; ?>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_WEBEMAIL1; ?>" value="<?php echo $this->data->psite_email; ?>" name="psite_email">
         </div>
      </div>
      <div class="wojo fields align middle">
         <div class="field auto">
            <label><?php echo Language::$word->CG_LOGO; ?>
            </label>
            <input type="file" name="logo" id="logo" class="filestyle" data-input="false" data-badge="true">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGODEL; ?></label>
            <div class="wojo small inline fitted checkbox">
               <input name="dellogo" type="checkbox" value="1" id="dellogo">
               <label for="dellogo"><?php echo Language::$word->CG_LOGODEL; ?></label>
            </div>
         </div>
         <div class="field auto">
            <label><?php echo Language::$word->CG_LOGO1; ?>
            </label>
            <input type="file" name="plogo" id="plogo" class="filestyle" data-input="false" data-badge="true">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGODEL; ?>
            </label>
            <div class="wojo small inline fitted checkbox">
               <input name="dellogop" type="checkbox" value="1" id="plogodel">
               <label for="plogodel"><?php echo Language::$word->CG_LOGODEL; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_LONGDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="long_date">
               <?php echo Date::getLongDate($this->data->long_date); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_SHORTDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="short_date">
               <?php echo Date::getShortDate($this->data->short_date); ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_TIMEFORMAT; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="time_format">
               <?php echo Date::getTimeFormat($this->data->time_format); ?>
            </select>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_WEEKSTART; ?></label>
            <select name="weekstart">
               <?php echo Date::weekList(true, true, $this->data->weekstart); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_CALDATE; ?>
               <i class="icon asterisk"></i>
            </label>
            <select name="calendar_date">
               <?php echo Date::getCalendarDate($this->data->calendar_date); ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_PERPAGE; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_PERPAGE; ?>" value="<?php echo $this->data->perpage; ?>" name="perpage">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_DTZ; ?></label>
            <select name="dtz">
               <?php echo Date::getTimezones(); ?>
            </select>
         </div>
         <div class="field three wide">
            <label><?php echo Language::$word->CG_LANG; ?></label>
            <select name="lang">
               <?php foreach (Language::fetchLanguage() as $langlist): ?>
                  <option value="<?php echo substr($langlist, 0, 2); ?>" <?php echo Validator::getSelected($this->data->lang, substr($langlist, 0, 2)); ?>><?php echo strtoupper(substr($langlist, 0, 2)); ?></option>
               <?php endforeach; ?>
            </select>
         </div>
         <div class="field two wide">
            <label><?php echo Language::$word->CG_LOCALES; ?></label>
            <select name="locale">
               <?php echo Date::localeList($this->data->locale); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_REGVERIFY; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_verify" type="radio" value="1" id="reg_verify_1" <?php echo Validator::getChecked($this->data->reg_verify, 1); ?>>
               <label for="reg_verify_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_verify" type="radio" value="0" id="reg_verify_0" <?php echo Validator::getChecked($this->data->reg_verify, 0); ?>>
               <label for="reg_verify_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_AUTOVERIFY; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="auto_verify" type="radio" value="1" id="auto_verify_1" <?php echo Validator::getChecked($this->data->auto_verify, 1); ?>>
               <label for="auto_verify_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="auto_verify" type="radio" value="0" id="auto_verify_0" <?php echo Validator::getChecked($this->data->auto_verify, 0); ?>>
               <label for="auto_verify_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_REGALOWED; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_allowed" type="radio" value="1" id="reg_allowed_1" <?php echo Validator::getChecked($this->data->reg_allowed, 1); ?>>
               <label for="reg_allowed_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="reg_allowed" type="radio" value="0" id="reg_allowed_0" <?php echo Validator::getChecked($this->data->reg_allowed, 0); ?>>
               <label for="reg_allowed_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_NOTIFY_ADMIN; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="notify_admin" type="radio" value="1" id="notify_admin_1" <?php echo Validator::getChecked($this->data->notify_admin, 1); ?>>
               <label for="notify_admin_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="notify_admin" type="radio" value="0" id="notify_admin_0" <?php echo Validator::getChecked($this->data->notify_admin, 0); ?>>
               <label for="notify_admin_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_ETAX; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="enable_tax" type="radio" value="1" id="enable_tax_1" <?php echo Validator::getChecked($this->data->enable_tax, 1); ?>>
               <label for="enable_tax_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="enable_tax" type="radio" value="0" id="enable_tax_0" <?php echo Validator::getChecked($this->data->enable_tax, 0); ?>>
               <label for="enable_tax_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_ETAX_RATE; ?></label>
            <select name="tax_rate">
               <?php echo Utility::loopOptions($this->countries, 'vat', 'name', $this->data->tax_rate); ?>
            </select>
         </div>
         <div class="field auto">
            <label><?php echo Language::$word->CG_CURRENCY; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_CURRENCY; ?>" value="<?php echo $this->data->currency; ?>" name="currency">
         </div>
      </div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_FILEDIR; ?>
               <i class="icon asterisk"></i>
            </label>
            <input type="text" placeholder="<?php echo Language::$word->CG_FILEDIR; ?>" value="<?php echo $this->data->file_dir; ?>" name="file_dir">
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_LOGIN; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="one_login" type="radio" value="1" id="one_login_1" <?php echo Validator::getChecked($this->data->one_login, 1); ?>>
               <label for="one_login_1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="one_login" type="radio" value="0" id="one_login_0" <?php echo Validator::getChecked($this->data->one_login, 0); ?>>
               <label for="one_login_0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
         <div class="field">
            <label><?php echo Language::$word->CG_EUCOOKIE; ?></label>
            <div class="wojo checkbox radio fitted inline">
               <input name="eucookie" type="radio" value="1" id="eucookie1" <?php echo Validator::getChecked($this->data->eucookie, 1); ?>>
               <label for="eucookie1"><?php echo Language::$word->YES; ?></label>
            </div>
            <div class="wojo checkbox radio fitted inline">
               <input name="eucookie" type="radio" value="0" id="eucookie0" <?php echo Validator::getChecked($this->data->eucookie, 0); ?>>
               <label for="eucookie0"><?php echo Language::$word->NO; ?></label>
            </div>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_TWID; ?></label>
            <div class="wojo icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_TWID; ?>" value="<?php echo $this->data->social->twitter; ?>" name="twitter">
               <i class="icon twitter"></i>
            </div>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_FBID; ?></label>
            <div class="wojo icon input">
               <input type="text" placeholder="<?php echo Language::$word->CG_FBID; ?>" value="<?php echo $this->data->social->facebook; ?>" name="facebook">
               <i class="icon facebook"></i>
            </div>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_MEMBERSHIP; ?>
            </label>
            <div class="wojo toggle checkbox">
               <input name="enable_dmembership" type="checkbox" id="enable_dmembership" value="1" <?php echo Validator::getChecked($this->data->enable_dmembership, 1); ?>>
               <label for="enable_dmembership"><?php echo Language::$word->YES; ?></label>
            </div>
            <p class="wojo small positive text"><em><?php echo Language::$word->CG_MEMBERSHIP_T; ?></em></p>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->META_T29; ?>
            </label>
            <select name="dmembership">
               <option value="0">-/-</option>
               <?php echo Utility::loopOptions($this->memberships, 'id', 'title', $this->data->dmembership); ?>
            </select>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_INVDATA; ?>
            </label>
            <textarea class="altpost" name="inv_info"><?php echo $this->data->inv_info; ?></textarea>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_INVNOTE; ?>
            </label>
            <textarea class="altpost" name="inv_note"><?php echo $this->data->inv_note; ?></textarea>
         </div>
      </div>      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field">
            <label><?php echo Language::$word->CG_OFFLINE; ?></label>
            <textarea class="altpost" name="offline_info"><?php echo $this->data->offline_info; ?></textarea>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <!-- Salla Integration Section -->
      <h3><?php echo Language::$word->CG_SALLA_INTEGRATION; ?></h3>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_SALLA_CLIENT_ID; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SALLA_CLIENT_ID; ?>" value="<?php echo $this->data->salla_client_id; ?>" name="salla_client_id">
            <p class="wojo small text"><?php echo Language::$word->CG_SALLA_CLIENT_ID_INFO; ?></p>
         </div>
         <div class="field five wide">
            <label><?php echo Language::$word->CG_SALLA_CLIENT_SECRET; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SALLA_CLIENT_SECRET; ?>" value="<?php echo $this->data->salla_client_secret; ?>" name="salla_client_secret">
            <p class="wojo small text"><?php echo Language::$word->CG_SALLA_CLIENT_SECRET_INFO; ?></p>
         </div>
      </div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_SALLA_WEBHOOK_SECRET; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SALLA_WEBHOOK_SECRET; ?>" value="<?php echo $this->data->salla_webhook_secret; ?>" name="salla_webhook_secret">
            <p class="wojo small text"><?php echo Language::$word->CG_SALLA_WEBHOOK_SECRET_INFO; ?></p>
         </div>
      </div>
      <div class="wojo auto very wide divider"></div>
      <div class="wojo fields">
         <div class="field five wide">
            <label><?php echo Language::$word->CG_MAILER; ?></label>
            <select name="mailer" id="mailer_change">
               <option value="SMAIL" <?php echo Validator::getSelected($this->data->mailer, 'SMAIL'); ?>>Sendmail</option>
               <option value="SMTP" <?php echo Validator::getSelected($this->data->mailer, 'SMTP'); ?>>SMTP Mailer</option>
            </select>
         </div>
         <div class="field showsmail<?php echo ($this->data->mailer == 'SMAIL')? '' : ' hide-all'; ?>">
            <label><?php echo Language::$word->CG_SMAILPATH; ?></label>
            <input type="text" placeholder="<?php echo Language::$word->CG_SMAILPATH; ?>" value="<?php echo $this->data->sendmail; ?>" name="sendmail">
         </div>
      </div>
      <div class="showsmtp<?php echo ($this->data->mailer == 'SMTP')? '' : ' hide-all'; ?>">
         <div class="wojo fields">
            <div class="field five wide">
               <label><?php echo Language::$word->CG_SMTP_HOST; ?>
                  <i class="icon asterisk"></i>
               </label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_HOST; ?>" value="<?php echo $this->data->smtp_host; ?>" name="smtp_host">
            </div>
            <div class="field five wide">
               <label><?php echo Language::$word->CG_SMTP_USER; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_USER; ?>" value="<?php echo $this->data->smtp_user; ?>" name="smtp_user">
            </div>
         </div>
         <div class="wojo fields">
            <div class="field three wide">
               <label><?php echo Language::$word->CG_SMTP_PASS; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_PASS; ?>" value="<?php echo $this->data->smtp_pass; ?>" name="smtp_pass">
            </div>
            <div class="field three wide">
               <label><?php echo Language::$word->CG_SMTP_PORT; ?></label>
               <input type="text" placeholder="<?php echo Language::$word->CG_SMTP_PORT; ?>" value="<?php echo $this->data->smtp_port; ?>" name="smtp_port">
            </div>
            <div class="field four wide">
               <label><?php echo Language::$word->CG_SMTP_SSL; ?></label>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_ssl" type="radio" value="1" id="is_ssl_1" <?php echo Validator::getChecked($this->data->is_ssl, 1); ?>>
                  <label for="is_ssl_1">SSL</label>
               </div>
               <div class="wojo checkbox radio fitted inline">
                  <input name="is_ssl" type="radio" value="0" id="is_ssl_0" <?php echo Validator::getChecked($this->data->is_ssl, 0); ?>>
                  <label for="is_ssl_0">TLS</label>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <button type="button" data-action="processConfig" name="dosubmit" class="wojo primary button"><?php echo Language::$word->CG_UPDATE; ?></button>
   </div>
</form>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('#mailer_change').change(function () {
         let val = $('#mailer_change option:selected').val();
         if (val === 'SMTP') {
            $('.showsmtp').show();
            $('.showsmail').hide();
         } else {
            $('.showsmtp').hide();
            $('.showsmail').show();
         }
      });
   });
   // ]]>
</script>

<?php
   /**
    * _membership_new.tpl.php
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: _membership_new.tpl.php, v1.00 7/5/2023 7:36 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form method="post" id="wojo_form" name="wojo_form">
   <div class="wojo simple segment form margin-bottom">
      <div class="row gutters">
         <div class="columns screen-70 tablet-60 mobile-100 phone-100 padding">
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->NAME; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <input type="text" placeholder="<?php echo Language::$word->NAME; ?>" name="title">
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->DESCRIPTION; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <input type="text" placeholder="<?php echo Language::$word->DESCRIPTION; ?>" name="description">
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_PRICE; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <div class="wojo labeled input">
                     <div class="wojo simple label"><?php echo Utility::currencySymbol(); ?></div>
                     <input type="text" placeholder="<?php echo Language::$word->MEM_PRICE; ?>" name="price">
                  </div>
               </div>
            </div>
            <div class="wojo fields align-middle">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_DAYS; ?>
                     <i class="icon asterisk"></i>
                  </label>
               </div>
               <div class="field">
                  <div class="wojo action input">
                     <input type="text" placeholder="<?php echo Language::$word->MEM_DAYS; ?>" name="days">
                     <select name="period">
                        <?php echo Utility::loopOptionsSimpleAlt(Date::getMembershipPeriod()); ?>
                     </select>
                  </div>
               </div>
            </div>
            <div class="wojo fields">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_PRIVATE; ?></label>
               </div>
               <div class="field">
                  <div class="wojo checkbox radio inline">
                     <input name="private" type="radio" value="1" id="private_1">
                     <label for="private_1"><?php echo Language::$word->YES; ?></label>
                  </div>
                  <div class="wojo checkbox radio inline">
                     <input name="private" type="radio" value="0" checked="checked" id="private_0">
                     <label for="private_0"><?php echo Language::$word->NO; ?></label>
                  </div>
               </div>
            </div>
            <div class="wojo fields">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->MEM_REC; ?></label>
               </div>
               <div class="field">
                  <div class="wojo checkbox radio inline">
                     <input name="recurring" type="radio" value="1" checked="checked" id="recurring_1">
                     <label for="recurring_1"><?php echo Language::$word->YES; ?></label>
                  </div>
                  <div class="wojo checkbox radio inline">
                     <input name="recurring" type="radio" value="0" id="recurring_0">
                     <label for="recurring_0"><?php echo Language::$word->NO; ?></label>
                  </div>
               </div>
            </div>
            <div class="wojo fields">
               <div class="field four wide labeled">
                  <label><?php echo Language::$word->PUBLISHED; ?></label>
               </div>
               <div class="field">
                  <div class="wojo checkbox radio inline">
                     <input name="active" type="radio" value="1" checked="checked" id="active_1">
                     <label for="active_1"><?php echo Language::$word->YES; ?></label>
                  </div>
                  <div class="wojo checkbox radio inline">
                     <input name="active" type="radio" value="0" id="active_0">
                     <label for="active_0"><?php echo Language::$word->NO; ?></label>
                  </div>
               </div>
            </div>
         </div>
         <div class="columns screen-30 tablet-40 mobile-100 phone-100">
            <div class="wojo block fields">
               <div class="field">
                  <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
               </div>
               <div class="field">
                  <label><?php echo Language::$word->DESCRIPTION; ?></label>
                  <textarea class="altpost" placeholder="<?php echo Language::$word->DESCRIPTION; ?>" name="body"></textarea>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="center-align">
      <a href="<?php echo Url::url('/admin/memberships'); ?>" class="wojo small simple button"><?php echo Language::$word->CANCEL; ?></a>
      <button type="button" data-action="processMembership" name="dosubmit" class="wojo primary button"><?php echo Language::$word->MEM_SUB1; ?></button>
   </div>
</form>

<!-- Fix for period dropdown submission -->
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
    // Make sure the period dropdown has a value selected
    var periodSelect = document.querySelector('select[name="period"]');
    if (periodSelect && !periodSelect.value) {
        // Select the first option if none is selected
        if (periodSelect.options.length > 0) {
            periodSelect.value = periodSelect.options[0].value;
        }
    }
    
    // Add validation before form submission
    var form = document.getElementById('wojo_form');
    var submitButton = form.querySelector('button[data-action="processMembership"]');
    
    submitButton.addEventListener('click', function(e) {
        // Make sure period has a value
        if (!periodSelect.value) {
            alert('Please select a membership period');
            periodSelect.focus();
            e.preventDefault();
            return false;
        }
        
        // Create a hidden input with period value if it doesn't exist
        if (periodSelect.value) {
            var hiddenPeriod = document.querySelector('input[name="period_hidden"]');
            if (!hiddenPeriod) {
                hiddenPeriod = document.createElement('input');
                hiddenPeriod.type = 'hidden';
                hiddenPeriod.name = 'period';
                hiddenPeriod.value = periodSelect.value;
                form.appendChild(hiddenPeriod);
            } else {
                hiddenPeriod.value = periodSelect.value;
            }
        }
    });
});
</script>

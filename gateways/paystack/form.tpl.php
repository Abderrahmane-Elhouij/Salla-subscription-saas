<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.0: form.tpl.php, v1.00 11/5/2023 12:04 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<form class="center-align" method="post" id="pf_form" name="pf_form">
   <div class="form-group">
      <input type="hidden" id="email-address" required value="<?php echo App::Auth()->email; ?>"/>
   </div>
   <div class="form-group">
      <input type="hidden" id="amount" value="<?php echo round($this->cart->totalprice * 100); ?>"/>
   </div>
   <div class="form-group">
      <input type="hidden" id="first-name" value="<?php echo App::Auth()->fname; ?>"/>
   </div>
   <div class="form-group">
      <input type="hidden" id="last-name" value="<?php echo App::Auth()->lname; ?>"/>
   </div>
   <div class="form-submit">
      <button type="button" id="pstackButton" class="wojo white shadow icon button">
         <img class="wojo medium image" src="<?php echo SITEURL . '/gateways/paystack/paystack_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
      </button>
   </div>
</form>
<script type="text/javascript">
   // <![CDATA[
   $(document).ready(function () {
      $('#pf_form').on('click', 'button', function (event) {
         event.preventDefault();
         let handler = PaystackPop.setup({
            key: '<?php echo $this->gateway->extra3;?>', // Replace with your public key
            email: document.getElementById('email-address').value,
            amount: document.getElementById('amount').value,
            currency: "<?php echo $this->gateway->extra2;?>",
            ref: '' + Math.floor((Math.random() * 1000000000) + 1),
            onClose: function () {
               //alert('Window closed.');
            },
            callback: function (response) {
               $('#pstackButton').addClass('loading');
               $.ajax({
                  type: 'post',
                  dataType: 'json',
                  url: '<?php echo SITEURL;?>/gateways/paystack/ipn.php',
                  data: {
                     processPaystackPayment: 1,
                     payment_method: response.reference
                  },
                  success: function (json) {
                     $('#pstackButton').removeClass('loading');
                     if (json.type === 'success') {
                        $('main').transition('scaleOut', {
                           duration: 4000,
                           complete: function () {
                              window.location.href = '<?php echo Url::url('/dashboard', 'history');?>';
                           }
                        });
                     }
                     if (json.message) {
                        $.wNotice({
                           autoclose: 4000,
                           type: json.type,
                           title: json.title,
                           text: json.message
                        });
                     }
                  }
               });
            }
         });
         handler.openIframe();
      });
   });
   // ]]>
</script>

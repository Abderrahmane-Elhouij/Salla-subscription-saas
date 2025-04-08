<?php
   /**
    * form
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: form.tpl.php, v1.00 7/18/2023 8:28 AM Gewa Exp $
    *
    */
   
   use Razorpay\Api\Api;
   
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
   
   require 'lib/Razorpay.php';
   
   $api = new Api($this->gateway->extra, $this->gateway->extra3);
   $displayCurrency = $this->gateway->extra2;
   
   $orderData = array(
     'receipt' => md5(time()),
     'amount' => round($this->cart->totalprice * 100),
     'currency' => $this->gateway->extra2,
     'payment_capture' => 1 // auto capture
   );
   
   $razorpayOrder = $api->order->create($orderData);
   $razorpayOrderId = $razorpayOrder['id'];
   $displayAmount = $amount = $orderData['amount'];
   
   $data = array(
     'key' => $this->gateway->extra,
     'amount' => $amount,
     'name' => $this->row->title,
     'description' => '',
     'image' => UPLOADURL . '/' . App::Core()->logo,
     'prefill' => array(
       'name' => App::Auth()->name,
       'email' => App::Auth()->email,
       'contact' => '',
     ),
     'theme' => array(
       'color' => '#667eea'
     ),
     'order_id' => $razorpayOrderId,
   );
   
   $json = json_encode($data);
   
   Database::Go()->update(Membership::cTable, array('order_id' => $razorpayOrderId))->where('user_id', App::Auth()->uid, '=')->run();
?>
<form name="razorpayform" action="<?php echo Url::url('/validate');?>" method="POST">
   <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
   <input type="hidden" name="razorpay_signature"  id="razorpay_signature" >
   <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
   <input type="hidden" name="type" value="MEMB">
   <input type="hidden" name="name" value="razorpay">
</form>
<script type="text/javascript">
   var options = <?php echo $json;?>;
   options.handler = function(response) {
      document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
      document.getElementById('razorpay_signature').value = response.razorpay_signature;
      document.razorpayform.submit();
   };

   options.theme.image_padding = false;

   options.modal = {
      ondismiss: function() {
         console.log("This code runs when the popup is closed");
      },
      escape: true,
      backdropclose: false
   };

   var rzp = new Razorpay(options);

   document.getElementById('rzrpay').onclick = function(e){
      rzp.open();
      e.preventDefault();
   }
</script>
<div class="center-align">
   <a id="rzrpay" class="wojo white shadow icon button">
      <img class="wojo medium image" src="<?php echo SITEURL . '/gateways/razorpay/razorpay_logo.svg'; ?>" alt="<?php echo $this->gateway->displayname; ?>">
   </a>
</div>
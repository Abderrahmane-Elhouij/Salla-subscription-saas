<?php
/**
 * Update Order
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: update_order.tpl.php, v1.00 5/8/2025 10:41 AM
 */
if (!defined("_WOJO"))
    die('Direct access to this location is not allowed.');
?>
<style>
  .form-section {
    margin-bottom: 2rem;
  }
  .section-title {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    /*border-bottom: 1px solid var(--border-color);*/
  }
  .section-title i {
    margin-right: 0.5rem;
    color: var(--primary-color);
  }
  .section-title h4 {
    margin: 0;
    color: var(--secondary-color);
  }
</style>
<div class="row gutters align-middle">
  <div class="column">
    <h3 class="text-color-primary">Update Order</h3>
    <p class="wojo small text text-color-secondary">Update order information on Salla</p>
  </div>
  <div class="column auto">
    <a aria-readonly="true" href="<?php echo Url::url('/sub_admin/subscriptions/detail/' . $this->data->id); ?>" class="wojo small simple button">
      <i class="icon chevron left"></i> Back to Subscription
    </a>
  </div>
</div>

<form method="post" id="update_order_form" name="update_order_form">
  <div class="wojo segment form shadow">
    <!-- Order Information -->
    <div class="form-section">
      <div class="section-title">
        <i class="icon shopping bag"></i>
        <h4>Order Information</h4>
      </div>
      
      <div class="wojo fields">
        <div class="field">
          <label>Order ID</label>
          <input type="text" value="<?php echo $this->data->salla_order_id; ?>" readonly>
          <p class="wojo small text">Order ID cannot be changed</p>
        </div>
        <div class="field">
          <label>Status</label>
          <select name="status">
            <option value="active" <?php echo $this->data->status == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="pending" <?php echo $this->data->status == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="canceled" <?php echo $this->data->status == 'canceled' ? 'selected' : ''; ?>>Canceled</option>
          </select>
        </div>
      </div>
      
      <div class="wojo fields">
        <div class="field">
          <label>Start Date</label>
          <div class="wojo icon input" data-datepicker="true">
            <input name="start_date" type="text" placeholder="Start Date" value="<?php echo Date::doDate('calendar', $this->data->start_date); ?>" readonly class="datepick">
            <i class="icon calendar"></i>
          </div>
        </div>
        <div class="field">
          <label>End Date</label>
          <div class="wojo icon input" data-datepicker="true">
            <input name="end_date" type="text" placeholder="End Date" value="<?php echo Date::doDate('calendar', $this->data->end_date); ?>" readonly class="datepick">
            <i class="icon calendar"></i>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Customer Information -->
    <div class="form-section">
      <div class="section-title">
        <i class="icon user"></i>
        <h4>Customer Information</h4>
      </div>
      
      <div class="wojo fields">
        <div class="field">
          <label>Customer Name</label>
          <input readonly type="text" name="customer_name" value="<?php echo $this->data->customer_name; ?>" placeholder="Customer Name">
        </div>
        <div class="field">
          <label>Customer Email</label>
          <input readonly type="email" name="customer_email" value="<?php echo $this->data->customer_email; ?>" placeholder="Customer Email">
        </div>
      </div>
      
      <div class="wojo fields">
        <div class="field">
          <label>Customer Phone</label>
          <input readonly type="text" name="customer_phone" value="<?php echo $this->data->customer_phone; ?>" placeholder="Customer Phone">
        </div>
      </div>
    </div>
    
    <div class="wojo divider"></div>
    
    <div class="center aligned">
      <button type="button" data-action="updateOrder" class="wojo primary button"><i class="icon pencil"></i> Update Order</button>
    </div>
    
    <input type="hidden" name="id" value="<?php echo $this->data->id; ?>">
    <input type="hidden" name="salla_order_id" value="<?php echo $this->data->salla_order_id; ?>">
  </div>
</form>

<div id="response_message" class="wojo message" style="display: none;"></div>

<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    $("button[data-action='updateOrder']").on('click', function() {
        const $button = $(this);
        const $form = $("#update_order_form");
        const $message = $("#response_message");
        
        $button.addClass("loading").prop("disabled", true);
        $message.hide();
        
        $.ajax({
            type: 'post',
            url: "<?php echo SITEURL; ?>/sub_admin/subscriptions/process-update-order",
            data: $form.serialize(),
            dataType: 'json',            success: function(json) {
                $button.removeClass("loading").prop("disabled", false);
                
                // Log success for debugging
                console.log("AJAX Success Response:", json);
                
                $message
                    .show()
                    .removeClass("success error info")
                    .addClass(json.type)
                    .html('<i class="icon circle check"></i><div class="header">' + json.title + '</div><p>' + json.message + '</p>');
                
                if (json.type === "success") {
                    setTimeout(function() {
                        window.location.href = "<?php echo Url::url('/sub_admin/subscriptions/detail/' . $this->data->id); ?>";
                    }, 2000);
                }            },error: function(xhr, status, error) {
                $button.removeClass("loading").prop("disabled", false);
                
                let errorMessage = "An unexpected error occurred. ";
                
                // Try to parse response if it's JSON
                if (xhr.responseText) {
                    try {
                        const errorObj = JSON.parse(xhr.responseText);
                        // Check if this is actually a success response with truncated data
                        if (errorObj.type === "success") {
                            // Handle as success
                            $message
                                .show()
                                .removeClass("error info")
                                .addClass("success")
                                .html('<i class="icon circle check"></i><div class="header">' + errorObj.title + '</div><p>' + errorObj.message + '</p>');
                            
                            // Redirect after success
                            setTimeout(function() {
                                window.location.href = "<?php echo Url::url('/sub_admin/subscriptions/detail/' . $this->data->id); ?>";
                            }, 2000);
                            return;
                        }
                        
                        if (errorObj.message) {
                            errorMessage += errorObj.message;
                        } else {
                            errorMessage += "Please try again. Error details: " + error;
                        }
                    } catch (e) {
                        // Check if this is a truncated success response
                        if (xhr.responseText.indexOf('"type":"success"') !== -1) {
                            // Handle as success
                            $message
                                .show()
                                .removeClass("error info")
                                .addClass("success")
                                .html('<i class="icon circle check"></i><div class="header">Success</div><p>Order has been updated successfully.</p>');
                            
                            // Redirect after success
                            setTimeout(function() {
                                window.location.href = "<?php echo Url::url('/sub_admin/subscriptions/detail/' . $this->data->id); ?>";
                            }, 2000);
                            return;
                        }
                        errorMessage += "Server responded with: " + xhr.responseText.substring(0, 100);
                    }
                } else {
                    errorMessage += "Please check your connection and try again.";
                }
                
                $message
                    .show()
                    .removeClass("success")
                    .addClass("error")
                    .html('<i class="icon circle check"></i><div class="header">Error</div><p>' + errorMessage + '</p>');
                
                // Log error to console for debugging
                console.error("AJAX Error:", status, error, xhr.responseText);
            }
        });
    });
});
// ]]>
</script>
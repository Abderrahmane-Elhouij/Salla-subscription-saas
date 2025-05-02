<?php
/**
 * Subscription Detail
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: subscription_detail.tpl.php, v1.00 5/1/2025 10:41 AM
 */
if (!defined("_WOJO"))
    die('Direct access to this location is not allowed.');
?>
<div class="row gutters align-middle">
  <div class="column">
    <h3>Subscription Detail</h3>
    <p class="wojo small text">View detailed information about this subscription</p>
  </div>
  <div class="column auto">
    <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo small icon button">
      <i class="icon chevron left"></i> Back to Subscriptions
    </a>
  </div>
</div>

<div class="row grid gutters">
  <!-- Subscription Basic Info -->
  <div class="columns screen-40 tablet-40 mobile-100 phone-100">
    <div class="wojo segment shadow">
      <h4>Subscription Information</h4>
      <div class="wojo divider"></div>
      
      <div class="wojo relaxed list">
        <div class="item">
          <div class="content">
            <div class="header">Subscription ID</div>
            <div class="description"><?php echo $this->data->id; ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Salla Order ID</div>
            <div class="description"><?php echo $this->data->salla_order_id ? $this->data->salla_order_id : 'N/A'; ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Status</div>
            <div class="description">
              <div class="wojo small <?php echo $this->data->status == 'active' ? 'positive' : ($this->data->status == 'canceled' ? 'negative' : 'primary'); ?> label">
                <?php echo ucfirst($this->data->status); ?>
              </div>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Start Date</div>
            <div class="description"><?php echo Date::doDate("long_date", $this->data->start_date); ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">End Date</div>
            <div class="description"><?php echo Date::doDate("long_date", $this->data->end_date); ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Remaining</div>
            <div class="description">
              <?php 
                $days_left = round((strtotime($this->data->end_date) - time()) / (60 * 60 * 24)); 
                if($days_left > 0) {
                  echo '<div class="wojo small positive text">' . $days_left . ' days left</div>';
                } else {
                  echo '<div class="wojo small negative text">Expired ' . abs($days_left) . ' days ago</div>';
                }
              ?>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Created</div>
            <div class="description"><?php echo Date::doDate("long_date", $this->data->created_at); ?></div>
          </div>
        </div>
        <?php if($this->data->updated_at): ?>
        <div class="item">
          <div class="content">
            <div class="header">Last Updated</div>
            <div class="description"><?php echo Date::doDate("long_date", $this->data->updated_at); ?></div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  
  <!-- Membership Details -->
  <div class="columns screen-30 tablet-30 mobile-100 phone-100">
    <div class="wojo segment shadow">
      <h4>Membership Information</h4>
      <div class="wojo divider"></div>
      
      <?php //if($this->data->membership_thumb): ?>
      <!-- <div class="content-center margin-bottom">
        <img src="<?php echo $this->data->membership_thumb; ?>" alt="<?php echo $this->data->membership_title; ?>" class="wojo medium rounded image">
      </div> -->
      <?php //endif; ?>
      
      <div class="wojo relaxed list">
        <div class="item">
          <div class="content">
            <div class="header">Membership</div>
            <div class="description"><?php echo $this->data->membership_title; ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Price</div>
            <div class="description"><?php echo Utility::formatMoney($this->data->membership_price); ?></div>
          </div>
        </div>
        <?php if($this->data->membership_description): ?>
        <div class="item">
          <div class="content">
            <div class="header">Description</div>
            <div class="description"><?php echo $this->data->membership_description; ?></div>
          </div>
        </div>
        <?php endif; ?>
        <div class="item">
          <div class="content">
            <div class="header">Salla Product ID</div>
            <div class="description"><?php echo $this->data->salla_product_id ? $this->data->salla_product_id : 'N/A'; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Customer Details -->
  <div class="columns screen-30 tablet-30 mobile-100 phone-100">
    <div class="wojo segment shadow">
      <h4>Customer Information</h4>
      <div class="wojo divider"></div>
      
      <div class="wojo relaxed list">
        <div class="item">
          <div class="content">
            <div class="header">Name</div>
            <div class="description">
              <?php echo $this->data->user_fname ? $this->data->user_fname . ' ' . $this->data->user_lname : ($this->data->customer_name ? $this->data->customer_name : 'N/A'); ?>
            </div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Email</div>
            <div class="description"><?php echo $this->data->user_email ? $this->data->user_email : ($this->data->customer_email ? $this->data->customer_email : 'N/A'); ?></div>
          </div>
        </div>
        <div class="item">
          <div class="content">
            <div class="header">Phone</div>
            <div class="description"><?php echo $this->data->customer_phone ? $this->data->customer_phone : 'N/A'; ?></div>
          </div>
        </div>
        <?php if($this->data->user_id): ?>
        <div class="item">
          <div class="content">
            <div class="header">User ID</div>
            <div class="description"><?php echo $this->data->user_id; ?></div>
          </div>
        </div>
        <?php endif; ?>
        <?php if($this->data->salla_customer_id): ?>
        <div class="item">
          <div class="content">
            <div class="header">Salla Customer ID</div>
            <div class="description"><?php echo $this->data->salla_customer_id; ?></div>
          </div>
        </div>
        <?php endif; ?>
        <?php if($this->data->user_address || $this->data->user_city || $this->data->user_country): ?>
        <div class="item">
          <div class="content">
            <div class="header">Address</div>
            <div class="description">
              <?php 
                $address = [];
                if($this->data->user_address) $address[] = $this->data->user_address;
                if($this->data->user_city) $address[] = $this->data->user_city;
                if($this->data->user_country) $address[] = $this->data->user_country;
                echo implode(', ', $address);
              ?>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
      
      <?php if($this->data->user_id): ?>
      <div class="margin-top content-right">
        <a href="<?php echo Url::url('/sub_admin/users/edit/' . $this->data->user_id); ?>" class="wojo small secondary button">
          <i class="icon user"></i> View User
        </a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
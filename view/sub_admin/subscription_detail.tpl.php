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
<style>
  .data-label {
    font-weight: 500;
    color: var(--secondary-color);
    font-size: 0.95rem;
    padding-bottom: 0.25rem;
  }
  .data-value {
    color: var(--body-color);
    font-size: 1rem;
    padding-bottom: 0.75rem;
  }
  .info-card {
    background-color: #ffffff;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }
  .info-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }
  .section-header {
    display: flex;
    align-items: center;
    padding-bottom: 0.5rem;
  }
  .section-header i {
    margin-right: 0.5rem;
    color: var(--primary-color);
  }
  .status-active {
    background-color: var(--positive-color-inverted);
    color: var(--positive-color);
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    display: inline-block;
  }
  .status-canceled {
    background-color: var(--negative-color-inverted);
    color: var(--negative-color);
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    display: inline-block;
  }
  .status-pending {
    background-color: var(--alert-color-inverted);
    color: var(--alert-color);
    font-weight: 500;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    display: inline-block;
  }
  .data-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-gap: 1rem;
  }
  @media (max-width: 768px) {
    .data-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="row gutters align-middle">
  <div class="column">
    <h3 class="text-color-primary">Subscription Detail</h3>
    <p class="wojo small text text-color-secondary">View detailed information about this subscription</p>
  </div>
  <div class="column auto" style="position: absolute; top: 20px; right: 20px;">
    <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo small simple button">
      <i class="icon chevron left"></i> Back to Subscriptions
    </a>
    <?php if($this->data->salla_order_id): ?>
    <a href="<?php echo Url::url('/sub_admin/subscriptions/update-order/' . $this->data->id); ?>" class="wojo small orange button">
      <i class="icon pencil"></i> Update Order
    </a>
    <?php endif; ?>
  </div>
</div>

<div class="row gutters">
  <div class="columns mobile-100 phone-100">
    <!-- Subscription Basic Info -->
    <div class="wojo segment shadow margin-bottom info-card">
      <div class="section-header">
        <i class="icon calendar check"></i>
        <h4 class="text-color-primary">Subscription Information</h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>
          <div class="data-label">Status</div>
          <div class="data-value">
            <span class="status-<?php echo $this->data->status; ?>">
              <?php echo ucfirst($this->data->status); ?>
            </span>
          </div>
          
          <div class="data-label">Created</div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->created_at); ?></div>
        </div>
        
        <div>
          <div class="data-label">Start Date</div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->start_date); ?></div>
          
          <div class="data-label">End Date</div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->end_date); ?></div>
          
          <div class="data-label">Remaining</div>
          <div class="data-value">
            <?php 
              $days_left = round((strtotime($this->data->end_date) - time()) / (60 * 60 * 24)); 
              if($days_left > 0) {
                echo '<span class="status-active">' . $days_left . ' days left</span>';
              } else {
                echo '<span class="status-canceled">Expired ' . abs($days_left) . ' days ago</span>';
              }
            ?>
          </div>
        </div>
      </div>
      
      <?php if($this->data->salla_order_id || $this->data->updated_at): ?>
      <div class="wojo divider"></div>
      <div class="data-grid">
        <?php if($this->data->salla_order_id): ?>
        <div>
          <div class="data-label">Salla Order ID</div>
          <div class="data-value"><?php echo $this->data->salla_order_id ? $this->data->salla_order_id : 'N/A'; ?></div>
        </div>
        <?php endif; ?>
        
        <?php if($this->data->updated_at): ?>
        <div>
          <div class="data-label">Last Updated</div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->updated_at); ?></div>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    
    <!-- Membership Details -->
    <div class="wojo segment shadow margin-bottom info-card">
      <div class="section-header">
        <i class="icon star"></i>
        <h4 class="text-color-secondary">Membership Information</h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>
          <div class="data-label">Membership</div>
          <div class="data-value"><strong><?php echo $this->data->membership_title; ?></strong></div>
          
          <?php if($this->data->membership_description): ?>
          <div class="data-label">Description</div>
          <div class="data-value"><?php echo $this->data->membership_description; ?></div>
          <?php endif; ?>
        </div>
        
        <div>
          <div class="data-label">Price</div>
          <div class="data-value" style="font-size: 1.2rem; color: var(--primary-color); font-weight: 500;">
            <?php echo Utility::formatMoney($this->data->membership_price); ?>
          </div>
          
          <?php if($this->data->salla_product_id): ?>
          <div class="data-label">Salla Product ID</div>
          <div class="data-value"><?php echo $this->data->salla_product_id; ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Customer Details -->
    <div class="wojo segment shadow info-card">
      <div class="section-header">
        <i class="icon user"></i>
        <h4 class="text-color-secondary">Customer Information</h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>
          <div class="data-label">Name</div>
          <div class="data-value">
            <strong>
              <?php echo $this->data->user_fname ? $this->data->user_fname . ' ' . $this->data->user_lname : ($this->data->customer_name ? $this->data->customer_name : 'N/A'); ?>
            </strong>
          </div>
          
          <div class="data-label">Email</div>
          <div class="data-value">
            <a href="mailto:<?php echo $this->data->user_email ? $this->data->user_email : $this->data->customer_email; ?>">
              <?php echo $this->data->user_email ? $this->data->user_email : ($this->data->customer_email ? $this->data->customer_email : 'N/A'); ?>
            </a>
          </div>
        </div>
        
        <div>
          <div class="data-label">Phone</div>
          <div class="data-value">
            <?php if($this->data->customer_phone): ?>
              <a href="tel:<?php echo $this->data->customer_phone; ?>"><?php echo $this->data->customer_phone; ?></a>
            <?php else: ?>
              N/A
            <?php endif; ?>
          </div>
          
          <?php if($this->data->user_address || $this->data->user_city || $this->data->user_country): ?>
          <div class="data-label">Address</div>
          <div class="data-value">
            <?php 
              $address = [];
              if($this->data->user_address) $address[] = $this->data->user_address;
              if($this->data->user_city) $address[] = $this->data->user_city;
              if($this->data->user_country) $address[] = $this->data->user_country;
              echo implode(', ', $address);
            ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
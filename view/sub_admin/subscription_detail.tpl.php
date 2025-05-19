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
  }  @media (max-width: 768px) {
    .data-grid {
      grid-template-columns: 1fr;
    }
  }
  
  .page-header {
    background-color: #ffffff;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    position: relative;
  }
  
  .page-header-title {
    margin-top: 0;
    margin-bottom: 0.5rem;
    font-weight: 600;
  }
  
  .page-header-desc {
    margin-bottom: 0;
  }
  
  .action-buttons {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
    justify-content: flex-end;
  }
  
  .back-button {
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
  }
  
  .back-button:hover {
    transform: translateX(-3px);
  }
  
  .update-button {
    transition: all 0.2s ease;
  }
  
  .update-button:hover {
    transform: translateY(-2px);
  }
  
  @media (max-width: 768px) {
    .page-header {
      flex-direction: column;
    }
    
    .action-buttons {
      margin-top: 1rem;
      justify-content: flex-start;
    }
  }
</style>

<div class="page-header wojo segment">
  <div class="row gutters align-middle">
    <div class="column">
      <h3 class="page-header-title text-color-primary"><?php echo Language::$word->SUB_DETAIL; ?></h3>
      <p class="page-header-desc wojo small text text-color-secondary"></p>
    </div>
    <div class="column auto">
      <div class="action-buttons">
        <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo small simple button back-button">
          <i class="icon chevron left"></i> <?php echo Language::$word->SUB_BACK_TO_SUBSCRIPTIONS; ?>
        </a>
        <?php if($this->data->salla_order_id): ?>
        <a href="<?php echo Url::url('/sub_admin/subscriptions/update-order/' . $this->data->id); ?>" class="wojo small orange button update-button">
          <i class="icon pencil"></i> <?php echo Language::$word->SUB_UPDATE_ORDER; ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="row gutters">
  <div class="columns mobile-100 phone-100">    <!-- Subscription Basic Info -->
    <div class="wojo segment shadow margin-bottom info-card">
      <div class="section-header">
        <i class="icon calendar check"></i>
        <h4 class="text-color-primary"><?php echo Language::$word->SUB_INFORMATION; ?></h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>
          <div class="data-label"><?php echo Language::$word->SUB_STATUS; ?></div>
          <div class="data-value">
            <span class="status-<?php echo $this->data->status; ?>">
              <?php 
                if ($this->data->status == 'active') {
                  echo Language::$word->SUB_ACTIVE;
                } elseif ($this->data->status == 'pending') {
                  echo Language::$word->SUB_PENDING;
                } elseif ($this->data->status == 'canceled') {
                  echo Language::$word->SUB_CANCELED;
                } elseif ($this->data->status == 'expired') {
                  echo Language::$word->SUB_EXPIRED_STATUS;
                } else {
                  echo ucfirst($this->data->status);
                }
              ?>
            </span>
          </div>
          
          <div class="data-label"><?php echo Language::$word->SUB_CREATED; ?></div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->created_at); ?></div>
        </div>
        
        <div>
          <div class="data-label"><?php echo Language::$word->SUB_START_DATE; ?></div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->start_date); ?></div>
          
          <div class="data-label"><?php echo Language::$word->SUB_END_DATE; ?></div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->end_date); ?></div>
          
          <div class="data-label"><?php echo Language::$word->SUB_REMAINING; ?></div>
          <div class="data-value">
            <?php 
              $days_left = round((strtotime($this->data->end_date) - time()) / (60 * 60 * 24)); 
              if($days_left > 0) {
                echo '<span class="status-active">' . $days_left . ' ' . Language::$word->SUB_DAYS_LEFT . '</span>';
              } else {
                echo '<span class="status-canceled">' . Language::$word->SUB_EXPIRED . ' ' . abs($days_left) . ' ' . Language::$word->SUB_DAYS_AGO . '</span>';
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
          <div class="data-label"><?php echo Language::$word->SUB_SALLA_ORDER_ID; ?></div>
          <div class="data-value"><?php echo $this->data->salla_order_id ? $this->data->salla_order_id : Language::$word->SUB_NA; ?></div>
        </div>
        <?php endif; ?>
        
        <?php if($this->data->updated_at): ?>
        <div>
          <div class="data-label"><?php echo Language::$word->SUB_LAST_UPDATED; ?></div>
          <div class="data-value"><?php echo Date::doDate("long_date", $this->data->updated_at); ?></div>
        </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    
    <!-- Membership Details -->
    <div class="wojo segment shadow margin-bottom info-card">      <div class="section-header">
        <i class="icon star"></i>
        <h4 class="text-color-secondary"><?php echo Language::$word->SUB_MEMBERSHIP_INFO; ?></h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>          <div class="data-label"><?php echo Language::$word->SUB_MEMBERSHIP; ?></div>
          <div class="data-value"><strong><?php echo $this->data->membership_title; ?></strong></div>
          
          <?php if($this->data->membership_description): ?>
          <div class="data-label"><?php echo Language::$word->SUB_DESCRIPTION; ?></div>
          <div class="data-value"><?php echo $this->data->membership_description; ?></div>
          <?php endif; ?>
        </div>
        
        <div>          <div class="data-label"><?php echo Language::$word->SUB_PRICE; ?></div>
          <div class="data-value" style="font-size: 1.2rem; color: var(--primary-color); font-weight: 500;">
            <?php echo Utility::formatMoney($this->data->membership_price); ?>
          </div>
          
          <?php if($this->data->salla_product_id): ?>          <div class="data-label"><?php echo Language::$word->SUB_SALLA_PRODUCT_ID; ?></div>
          <div class="data-value"><?php echo $this->data->salla_product_id; ?></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Customer Details -->
    <div class="wojo segment shadow info-card">      <div class="section-header">
        <i class="icon user"></i>
        <h4 class="text-color-secondary"><?php echo Language::$word->SUB_CUSTOMER_INFO; ?></h4>
      </div>
      <div class="wojo divider"></div>
      
      <div class="data-grid">
        <div>
          <div class="data-label"><?php echo Language::$word->SUB_NAME; ?></div>
          <div class="data-value">
            <strong>
              <?php echo $this->data->user_fname ? $this->data->user_fname . ' ' . $this->data->user_lname : ($this->data->customer_name ? $this->data->customer_name : Language::$word->SUB_NA); ?>
            </strong>
          </div>
          
          <div class="data-label"><?php echo Language::$word->SUB_EMAIL; ?></div>
          <div class="data-value">
            <a href="mailto:<?php echo $this->data->user_email ? $this->data->user_email : $this->data->customer_email; ?>">
              <?php echo $this->data->user_email ? $this->data->user_email : ($this->data->customer_email ? $this->data->customer_email : Language::$word->SUB_NA); ?>
            </a>
          </div>
        </div>
        
        <div>
          <div class="data-label"><?php echo Language::$word->SUB_PHONE; ?></div>
          <div class="data-value">
            <?php if($this->data->customer_phone): ?>
              <a href="tel:<?php echo $this->data->customer_phone; ?>"><?php echo $this->data->customer_phone; ?></a>
            <?php else: ?>
              <?php echo Language::$word->SUB_NA; ?>
            <?php endif; ?>
          </div>
          
          <?php if($this->data->user_address || $this->data->user_city || $this->data->user_country): ?>
          <div class="data-label"><?php echo Language::$word->SUB_ADDRESS; ?></div>
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
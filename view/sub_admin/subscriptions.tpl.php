<?php
/**
 * Subscriptions
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: subscriptions.tpl.php, v1.00 5/1/2025 10:41 AM
 */
if (!defined("_WOJO"))
    die('Direct access to this location is not allowed.');
?>
<style>
  .filter-dropdown {
    position: relative;
    display: inline-block;
  }
  
  .filter-content {
    display: none;
    position: absolute;
    background-color: #fff;
    min-width: 280px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    z-index: 1;
    border-radius: 0.5rem;
    padding: 1rem;
    right: 0;
    top: 100%;
    margin-top: 0.5rem;
  }
  
  .filter-content.show {
    display: block;
  }
  
  .filter-section {
    margin-bottom: 1rem;
  }
  
  .filter-section-title {
    color: var(--secondary-color);
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 0.25rem;
  }
  
  .filter-option {
    display: flex;
    align-items: center;
    margin: 0.5rem 0;
  }
  
  .filter-toggle {
    position: relative;
    display: inline-block;
    width: 36px;
    height: 20px;
  }
  
  .filter-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
  }
  
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    transition: .4s;
    border-radius: 34px;
  }
  
  .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: 2px;
    bottom: 2px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
  }
  
  input:checked + .slider {
    background-color: var(--primary-color);
  }
  
  input:checked + .slider:before {
    transform: translateX(16px);
  }
  
  .filter-option-label {
    margin-left: 0.75rem;
    font-size: 0.9rem;
    color: var(--body-color);
  }
  
  .filter-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
    padding-top: 0.75rem;
    border-top: 1px solid var(--border-color);
  }
  
  .filter-btn {
    font-size: 0.8rem;
    padding: 0.35rem 0.75rem;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .apply-btn {
    background-color: var(--primary-color);
    color: white;
    border: none;
  }
  
  .reset-btn {
    background-color: transparent;
    color: var(--secondary-color);
    border: 1px solid var(--border-color);
    margin-right: 0.5rem;
  }
  
  .apply-btn:hover {
    background-color: var(--primary-color-hover);
  }
  
  .reset-btn:hover {
    background-color: var(--border-color);
  }
</style>

<div class="wojo segment shadow">
  <div class="row small gutters align-middle">
    <div class="column">
      <div class="wojo icon text">
        <i class="icon calendar check"></i>
        <h3 class="wojo primary text">Salla Subscriptions</h3>
      </div>
      <p class="wojo small dimmed text">View and manage customer subscriptions from your Salla store</p>
    </div>
    <div class="column auto">
      <div class="filter-dropdown">
        <button class="wojo small secondary button" id="filterButton">
          <i class="icon filter"></i> Customize View
        </button>
        <div class="filter-content" id="filterDropdown">
          <!-- Membership Information -->
          <div class="filter-section">
            <div class="filter-section-title">Membership Info</div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="membership-title" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Membership Title</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="membership-price" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Membership Price</span>
            </div>
          </div>
          
          <!-- Customer Information -->
          <div class="filter-section">
            <div class="filter-section-title">Customer Info</div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="customer-name" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Customer Name</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="customer-email" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Customer Email</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="customer-phone">
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Customer Phone</span>
            </div>
          </div>
            <!-- Period Information -->
          <div class="filter-section">
            <div class="filter-section-title">Subscription Period</div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="start-date" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Start Date</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="end-date" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">End Date</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="days-left" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Days Remaining</span>
            </div>
          </div>
          
          <!-- Status Information -->
          <div class="filter-section">
            <div class="filter-section-title">Status & Actions</div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="status" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Subscription Status</span>
            </div>
            <div class="filter-option">
              <label class="filter-toggle">
                <input type="checkbox" data-column="actions" checked>
                <span class="slider"></span>
              </label>
              <span class="filter-option-label">Action Buttons</span>
            </div>
          </div>
          
          <!-- Filter Actions -->
          <div class="filter-actions">
            <div>
              <button class="filter-btn reset-btn" id="resetFilters">Reset</button>
            </div>
            <div>
              <button class="filter-btn apply-btn" id="applyFilters">Apply Filters</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php if(!$this->data):?>
<div class="wojo segment content-center">
  <img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="wojo small thick caps text">No subscriptions found</p>
</div>
<?php else:?>

<?php //if($this->pager->display_pages()):?>
<!--<div class="row small gutters align-middle">-->
<!--  <div class="columns auto mobile-100 phone-100">-->
<!--    <div class="wojo small secondary text">--><?php //echo $this->pager->items_per_page();?><!-- --><?php //echo $this->pager->limit(Url::url(Router::$path));?><!--</div>-->
<!--  </div>-->
<!--  <div class="columns right aligned mobile-100 phone-100">--><?php //echo $this->pager->display_pages();?><!--</div>-->
<!--</div>-->
<?php //endif;?>

<div class="wojo segment shadow">
  <table class="wojo basic responsive table">
    <thead>
      <tr>
        <th class="center aligned">ID</th>
        <th class="membership-column">Membership</th>
        <th class="customer-column">Customer</th>
        <th class="period-column">Period</th>
        <th>Status</th>
        <th class="center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->data as $row):?>
      <tr id="item_<?php echo $row->id;?>">
        <td class="center aligned"><span class="wojo small primary inverted circular label"><?php echo $row->id;?></span></td>
        <td class="membership-column">
          <!-- Membership Title -->
          <div class="wojo small thick text membership-title"><?php echo $row->membership_title;?></div>
          
          <!-- Membership Price -->
          <div class="wojo small dimmed text membership-price"><?php echo Utility::formatMoney($row->membership_price);?></div>
        </td>
        <td class="customer-column">
          <!-- Customer Name -->
          <?php if($row->user_fname || $row->customer_name): ?>
          <div class="wojo small thick text customer-name">
            <?php echo $row->user_fname ? $row->user_fname . ' ' . $row->user_lname : $row->customer_name;?>
          </div>
          <?php endif; ?>
          
          <!-- Customer Email -->
          <div class="wojo small dimmed text customer-email">
            <?php echo $row->user_email ? $row->user_email : $row->customer_email;?>
          </div>
          
          <!-- Customer Phone -->
          <?php if($row->customer_phone): ?>
          <div class="wojo small icon text customer-phone">
            <i class="icon phone"></i>
            <span><?php echo $row->customer_phone;?></span>
          </div>
          <?php endif; ?>
        </td>
        <td class="period-column">
          <!-- Start Date -->
          <div class="wojo small icon text start-date">
            <i class="icon calendar outline"></i>
            <span>Start: <?php echo Date::doDate("short_date", $row->start_date);?></span>
          </div>
          
          <!-- End Date -->
          <div class="wojo small icon text end-date">
            <i class="icon calendar alt"></i>
            <span>End: <?php echo Date::doDate("short_date", $row->end_date);?></span>
          </div>
          
          <!-- Days Left -->
          <?php
            $days_left = round((strtotime($row->end_date) - time()) / (60 * 60 * 24));
            $label_class = $days_left > 30 ? 'positive' : ($days_left > 0 ? 'primary' : 'negative');
          ?>
          <div class="wojo small <?php echo $label_class; ?> text days-left">
            <?php echo $days_left > 0 ? $days_left . ' days left' : 'Expired'; ?>
          </div>
        </td>
        <td>
          <div class="wojo small <?php echo $row->status == 'active' ? 'positive' : ($row->status == 'canceled' ? 'negative' : 'primary'); ?> inverted label">
            <?php echo ucfirst($row->status);?>
          </div>
        </td>
        <td class="center aligned">
          <a href="<?php echo Url::url(Router::$path, "detail/" . $row->id);?>" class="wojo icon primary inverted circular button" data-tooltip="View Details">
            <i class="icon eye"></i>
          </a>
          <?php if($row->salla_order_id): ?>
          <a href="<?php echo Url::url(Router::$path, "update-order/" . $row->id);?>" class="wojo icon orange inverted circular button" data-tooltip="Update Order">
            <i class="icon pencil"></i>
          </a>
          <?php endif; ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>

<?php //if($this->pager->display_pages()):?>
<!--<div class="row gutters align-middle spaced">-->
<!--  <div class="columns auto mobile-100 phone-100">-->
<!--    <div class="wojo small secondary text">--><?php //echo Language::$word->TOTAL . ': ' . $this->pager->items_total();?><!-- / --><?php //echo Language::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Language::$word->OF . ' ' . $this->pager->num_pages();?><!--</div>-->
<!--  </div>-->
<!--  <div class="columns right aligned mobile-100 phone-100">--><?php //echo $this->pager->display_pages();?><!--</div>-->
<!--</div>-->
<?php //endif;?>

<?php endif;?>

<script>
// Wait for the DOM to fully load
document.addEventListener('DOMContentLoaded', function() {
  // Get the filter button and dropdown
  const filterButton = document.getElementById('filterButton');
  const filterDropdown = document.getElementById('filterDropdown');
  const applyFiltersBtn = document.getElementById('applyFilters');
  const resetFiltersBtn = document.getElementById('resetFilters');
    // Default filter settings
  const defaultFilters = {
    'membership-title': true,
    'membership-price': true,
    'customer-name': true,
    'customer-email': true,
    'customer-phone': false,
    'start-date': true,
    'end-date': true,
    'days-left': true,
    'status': true,
    'actions': true
  };
  
  // Initialize filters from localStorage or use defaults
  let currentFilters = JSON.parse(localStorage.getItem('subscriptionFilters')) || defaultFilters;
  
  // Function to toggle the dropdown
  function toggleDropdown() {
    filterDropdown.classList.toggle('show');
  }
  
  // Function to close the dropdown when clicking outside
  function closeDropdown(event) {
    if (!filterButton.contains(event.target) && !filterDropdown.contains(event.target)) {
      filterDropdown.classList.remove('show');
    }
  }
  
  // Function to apply filters to the table
  function applyFilters() {
    // Update current filters based on checkbox states
    const checkboxes = document.querySelectorAll('#filterDropdown input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      currentFilters[checkbox.dataset.column] = checkbox.checked;
    });
    
    // Save to localStorage
    localStorage.setItem('subscriptionFilters', JSON.stringify(currentFilters));
    
    // Apply to UI
    updateTableDisplay();
    
    // Close dropdown
    filterDropdown.classList.remove('show');
  }
  
  // Function to reset filters to defaults
  function resetFilters() {
    // Reset to defaults
    currentFilters = {...defaultFilters};
    
    // Update checkboxes
    const checkboxes = document.querySelectorAll('#filterDropdown input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.checked = defaultFilters[checkbox.dataset.column];
    });
    
    // Save to localStorage
    localStorage.setItem('subscriptionFilters', JSON.stringify(currentFilters));
    
    // Apply to UI
    updateTableDisplay();
  }
    // Function to update table based on current filters
  function updateTableDisplay() {
    // Membership column items
    const membershipTitles = document.querySelectorAll('.membership-title');
    const membershipPrices = document.querySelectorAll('.membership-price');
    
    // Customer column items
    const customerNames = document.querySelectorAll('.customer-name');
    const customerEmails = document.querySelectorAll('.customer-email');
    const customerPhones = document.querySelectorAll('.customer-phone');
    
    // Period column items
    const startDates = document.querySelectorAll('.start-date');
    const endDates = document.querySelectorAll('.end-date');
    const daysLeftElements = document.querySelectorAll('.days-left');
    
    // Status column items
    const statusColumns = document.querySelectorAll('td:nth-child(5)');
    
    // Actions column items
    const actionsColumns = document.querySelectorAll('td:nth-child(6)');
    
    // Apply visibility based on filter settings
    // Membership
    toggleVisibility(membershipTitles, currentFilters['membership-title']);
    toggleVisibility(membershipPrices, currentFilters['membership-price']);
    
    // Customer
    toggleVisibility(customerNames, currentFilters['customer-name']);
    toggleVisibility(customerEmails, currentFilters['customer-email']);
    toggleVisibility(customerPhones, currentFilters['customer-phone']);
    
    // Period
    toggleVisibility(startDates, currentFilters['start-date']);
    toggleVisibility(endDates, currentFilters['end-date']);
    toggleVisibility(daysLeftElements, currentFilters['days-left']);
    
    // Status
    toggleVisibility(statusColumns, currentFilters['status']);
    
    // Actions
    toggleVisibility(actionsColumns, currentFilters['actions']);
    
    // Hide entire column if all elements in it are hidden
    const membershipVisible = currentFilters['membership-title'] || currentFilters['membership-price'];
    toggleColumnVisibility('membership-column', membershipVisible);
    
    const customerVisible = currentFilters['customer-name'] || currentFilters['customer-email'] || currentFilters['customer-phone'];
    toggleColumnVisibility('customer-column', customerVisible);
    
    const periodVisible = currentFilters['start-date'] || currentFilters['end-date'] || currentFilters['days-left'];
    toggleColumnVisibility('period-column', periodVisible);
    
    // Also need to toggle the table headers for status and actions
    const statusHeader = document.querySelector('th:nth-child(5)');
    if (statusHeader) {
      statusHeader.style.display = currentFilters['status'] ? '' : 'none';
    }
    
    const actionsHeader = document.querySelector('th:nth-child(6)');
    if (actionsHeader) {
      actionsHeader.style.display = currentFilters['actions'] ? '' : 'none';
    }
  }
  
  // Helper function to toggle visibility of elements
  function toggleVisibility(elements, isVisible) {
    elements.forEach(element => {
      if (isVisible) {
        element.style.display = '';
      } else {
        element.style.display = 'none';
      }
    });
  }
  
  // Helper function to toggle visibility of columns
  function toggleColumnVisibility(columnClass, isVisible) {
    const columns = document.querySelectorAll('.' + columnClass);
    columns.forEach(column => {
      if (isVisible) {
        column.style.display = '';
      } else {
        column.style.display = 'none';
      }
    });
  }
  
  // Set initial checkbox states based on saved filters
  function initializeCheckboxes() {
    const checkboxes = document.querySelectorAll('#filterDropdown input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      const column = checkbox.dataset.column;
      checkbox.checked = currentFilters[column];
    });
  }
  
  // Add event listeners
  filterButton.addEventListener('click', toggleDropdown);
  document.addEventListener('click', closeDropdown);
  applyFiltersBtn.addEventListener('click', applyFilters);
  resetFiltersBtn.addEventListener('click', resetFilters);
  
  // Initialize checkboxes and table display
  initializeCheckboxes();
  updateTableDisplay();
});
</script>
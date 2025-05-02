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
<div class="wojo segment shadow">
  <div class="row small gutters align-middle">
    <div class="column">
      <div class="wojo icon text">
        <i class="icon calendar check"></i>
        <h3 class="wojo primary text">Salla Subscriptions</h3>
      </div>
      <p class="wojo small dimmed text">View and manage customer subscriptions from your Salla store</p>
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
        <th>Membership</th>
        <th>Customer</th>
        <th>Period</th>
        <th>Status</th>
        <th class="center aligned">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->data as $row):?>
      <tr id="item_<?php echo $row->id;?>">
        <td class="center aligned"><span class="wojo small primary inverted circular label"><?php echo $row->id;?></span></td>
        <td>
          <?php //if($row->membership_thumb): ?>
        <!-- <div class="wojo small inline image">
            <img src="<?php echo $row->membership_thumb;?>" alt="<?php echo $row->membership_title;?>">
          </div> -->
          <?php //endif; ?>
          <div class="wojo small thick text"><?php echo $row->membership_title;?></div>
          <div class="wojo small dimmed text"><?php echo Utility::formatMoney($row->membership_price);?></div>
        </td>
        <td>
          <?php if($row->user_fname || $row->customer_name): ?>
          <div class="wojo small thick text"><?php echo $row->user_fname ? $row->user_fname . ' ' . $row->user_lname : $row->customer_name;?></div>
          <?php endif; ?>
          <div class="wojo small dimmed text"><?php echo $row->user_email ? $row->user_email : $row->customer_email;?></div>
          <?php if($row->customer_phone): ?>
          <div class="wojo small icon text">
            <i class="icon phone"></i>
            <span><?php echo $row->customer_phone;?></span>
          </div>
          <?php endif; ?>
        </td>
        <td>
          <div class="wojo small icon text">
            <i class="icon calendar outline"></i>
            <span>Start: <?php echo Date::doDate("short_date", $row->start_date);?></span>
          </div>
          <div class="wojo small icon text">
            <i class="icon calendar alt"></i>
            <span>End: <?php echo Date::doDate("short_date", $row->end_date);?></span>
          </div>
          <?php
            $days_left = round((strtotime($row->end_date) - time()) / (60 * 60 * 24));
            $label_class = $days_left > 30 ? 'positive' : ($days_left > 0 ? 'primary' : 'negative');
          ?>
          <div class="wojo small <?php echo $label_class; ?> text">
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
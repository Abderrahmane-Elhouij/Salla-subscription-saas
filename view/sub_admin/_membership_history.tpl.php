<?php
   /**
    * _membership_history
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2025
    * @version 1.00: _membership_history.tpl.php, v1.00 4/13/2025 2:41 PM
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="row horizontal-gutters align-middle">
  <div class="columns auto">
    <div class="wojo icon button" onClick="history.go(-1)">
      <i class="icon long arrow left"></i>
    </div>
  </div>
  <div class="columns">
    <h4><?php echo $this->data->title;?></h4>
  </div>
  <div class="columns auto">
    <div class="wojo primary stacked button">
      <?php echo $this->data->title;?>
      <div class="advent wojo very small label"><?php echo Utility::formatMoney($this->data->price);?></div>
    </div>
  </div>
</div>
<div class="wojo segment">
   <p class="wojo small text"><?php echo Language::$word->SUB_ADMIN_RESTRICTED; ?></p>
</div>
<?php if(!$this->plist):?>
<div class="center-align"><img src="<?php echo ADMINVIEW;?>/images/notfound.svg" alt="">
  <p class="wojo small thick caps text"><?php echo Language::$word->MEM_NOMEMBER;?></p>
</div>
<?php else:?>
<div class="wojo segment">
  <table class="wojo sorting basic table">
    <thead>
      <tr>
        <th class="disabled center aligned"></th>
        <th data-sort="string"><?php echo Language::$word->NAME;?></th>
        <th data-sort="int"><?php echo Language::$word->TRX_AMOUNT;?></th>
        <th data-sort="int"><?php echo Language::$word->TRX_TAX;?></th>
        <th data-sort="int"><?php echo Language::$word->TRX_COUPON;?></th>
        <th data-sort="int"><?php echo Language::$word->TRX_TOTAMT;?></th>
        <th data-sort="int"><?php echo Language::$word->CREATED;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->plist as $row):?>
      <tr>
        <td class="auto"><span class="wojo small simple label"><?php echo $row->id;?></span></td>
        <td><a class="inverted" href="<?php echo Url::url("/admin/users/edit/" . $row->user_id);?>"><?php echo $row->name;?></a></td>
        <td><?php echo $row->currency . ' ' . $row->rate_amount;?></td>
        <td><?php echo $row->tax?></td>
        <td><?php echo $row->coupon?></td>
        <td><?php echo $row->currency . ' ' . $row->total;?></td>
        <td><?php echo Date::doDate("short_date", $row->created);?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <div class="wojo small double passive link divider">
    <div class="row align-middle">
      <div class="columns"><?php echo Language::$word->TRX_TOTAMT;?>
        : <?php echo Utility::formatMoney(Stats::doArraySum($this->plist, "total"));?></div>
      <div class="columns auto"><?php echo $this->pager->display();?></div>
    </div>
  </div>
</div>
<?php endif;?>
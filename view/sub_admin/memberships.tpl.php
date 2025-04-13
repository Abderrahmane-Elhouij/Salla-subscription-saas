<?php
  /**
   * Memberships
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2025
   * @version $Id: memberships.tpl.php, v1.00 2025-04-13 22:32:48 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<?php switch(Filter::$action): case "edit": ?>
<?php include_once("_membershipEdit.tpl.php");?>
<?php break;?>
<?php case "new": ?>
<h2><?php echo Language::$word->MEM_SUB1;?></h2>
<form method="post" id="wojo_form" name="wojo_form" enctype="multipart/form-data">
  <div class="wojo segment form">
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->NAME;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <input type="text" placeholder="<?php echo Language::$word->NAME;?>" name="title">
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->MEM_PRICE;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo labeled input">
          <div class="wojo label"><?php echo Utility::currencySymbol();?></div>
          <input type="text" placeholder="<?php echo Language::$word->MEM_PRICE;?>" name="price">
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->DESCRIPTION;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <textarea class="altpost" name="description"></textarea>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->MEM_DAYS;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo action input">
          <input type="text" placeholder="<?php echo Language::$word->MEM_DAYS;?>" name="days">
          <select class="wojo selection dropdown" name="period">
            <?php echo Utility::loopOptionsSimple(Date::getMembershipPeriod(), "D");?>
          </select>
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->MEM_PRIVATE;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="private" type="radio" value="1" id="private_1">
          <label for="private_1"><?php echo Language::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="private" type="radio" value="0" id="private_0" checked="checked">
          <label for="private_0"><?php echo Language::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->MEM_REC;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="recurring" type="radio" value="1" id="recurring_1">
          <label for="recurring_1"><?php echo Language::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="recurring" type="radio" value="0" id="recurring_0" checked="checked">
          <label for="recurring_0"><?php echo Language::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Language::$word->PUBLISHED;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="active" type="radio" value="1" id="active_1" checked="checked">
          <label for="active_1"><?php echo Language::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="active" type="radio" value="0" id="active_0">
          <label for="active_0"><?php echo Language::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="content-center">
      <button type="button" data-action="processMembership" data-url="controller.php" name="dosubmit" class="wojo primary button"><?php echo Language::$word->MEM_SUB1;?></button>
    </div>
  </div>
</form>
<?php break;?>
<?php default: ?>
<div class="row small gutters align-middle">
  <div class="column">
    <h3><?php echo Language::$word->MEM_TITLE;?></h3>
    <p class="wojo small text"><?php echo Language::$word->MEM_SUB;?></p>
  </div>
  <div class="column auto"><a href="<?php echo Url::url(Router::$path, "new/");?>" class="wojo small primary stacked button"><i class="icon plus alt"></i><?php echo Language::$word->MEM_SUB1;?></a>
  </div>
</div>
<?php if(!$this->data):?>
<div class="wojo segment content-center">
  <img src="<?php echo ADMINVIEW;?>/images/notfound.png" alt="">
  <p class="wojo small thick caps text"><?php echo Language::$word->MEM_NOMEM;?></p>
</div>
<?php else:?>
<?php if($this->pager->display_pages()):?>
<div class="row small gutters align-middle">
  <div class="columns auto mobile-100 phone-100">
    <div class="wojo small secondary text"><?php echo $this->pager->items_per_page();?> <?php echo $this->pager->limit(Url::url(Router::$path));?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages();?></div>
</div>
<?php endif;?>
<div class="wojo segment shadow">
  <table class="wojo basic responsive table" id="sortable">
    <thead>
      <tr>
        <th class="column-small"></th>
        <th data-sort="string"><?php echo Language::$word->MEM_NAME;?></th>
        <th data-sort="int"><?php echo Language::$word->MEM_PRICE;?></th>
        <th data-sort="int"><?php echo Language::$word->MEM_DAYS;?></th>
        <th class="center aligned"><?php echo Language::$word->MEM_REC1;?></th>
        <th class="center aligned"><?php echo Language::$word->ACTIONS;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($this->data as $row):?>
      <tr id="item_<?php echo $row->id;?>">
        <td><span class="wojo small text"><?php echo $row->id;?></span></td>
        <td><b><?php echo $row->title;?></b></td>
        <td><?php echo Utility::formatMoney($row->price);?></td>
        <td><?php echo $row->days;?> <?php echo Date::getPeriodReadable($row->period);?></td>
        <td class="center aligned"><?php echo Utility::isActive($row->recurring);?></td>
        <td class="center aligned">
        <a href="<?php echo Url::url(Router::$path, "edit/" . $row->id);?>" class="wojo icon primary inverted circular button"><i class="icon pencil"></i></a>
        <a data-set='{"option":[{"delete": "deleteMembership","title": "<?php echo Validator::sanitize($row->title, "chars");?>","id":<?php echo $row->id;?>}],"action":"delete","url":"controller.php","parent":"#item_<?php echo $row->id;?>"}' class="wojo icon negative inverted circular delete button"><i class="icon trash"></i></a></td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php if($this->pager->display_pages()):?>
<div class="row gutters align-middle spaced">
  <div class="columns auto mobile-100 phone-100">
    <div class="wojo small secondary text"><?php echo Lang::$word->TOTAL . ': ' . $this->pager->items_total();?> / <?php echo Lang::$word->CURPAGE . ': ' . $this->pager->current_page . ' ' . Lang::$word->OF . ' ' . $this->pager->num_pages();?></div>
  </div>
  <div class="columns right aligned mobile-100 phone-100"><?php echo $this->pager->display_pages();?></div>
</div>
<?php endif;?>
<?php endif;?>
<?php break;?>
<?php endswitch;?>
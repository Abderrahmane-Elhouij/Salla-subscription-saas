<?php
   /**
    * loadSummary
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: loadSummary.tpl.php, v1.00 7/17/2023 5:02 PM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<div class="wojo small segment">
   <table class="wojo basic small responsive table">
      <thead>
      <tr>
         <th colspan="2"><?php echo Language::$word->M_SUB26; ?></th>
      </tr>
      </thead>
      <tr>
         <td>
            <strong><?php echo Language::$word->MEM_NAME; ?></strong>
         </td>
         <td><?php echo $this->row->title; ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->MEM_PRICE; ?></strong>
         </td>
         <td><?php echo Utility::formatMoney($this->cart->total); ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->DC_SUB4; ?>
            </strong>
         </td>
         <td class="disc">0.00</td>
      </tr>
      <?php if (App::Core()->enable_tax):?>
         <tr>
            <td>
               <strong><?php echo Language::$word->TRX_TAX; ?></strong>
            </td>
            <td class="totaltax"><?php echo Utility::formatMoney($this->cart->total * $this->cart->tax); ?></td>
         </tr>
      <?php endif; ?>
      <tr>
         <td>
            <strong><?php echo Language::$word->TRX_TOTAMT; ?></strong>
         </td>
         <td class="totalamt"><?php echo Utility::formatMoney($this->cart->tax * $this->cart->total + $this->cart->total); ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->MEM_DAYS; ?>
            </strong>
         </td>
         <td><?php echo $this->row->days; ?>
            <?php echo Date::getPeriodReadable($this->row->period); ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->MEM_REC1; ?></strong>
         </td>
         <td><?php echo ($this->row->recurring)? Language::$word->YES : Language::$word->NO; ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->MEM_VALID; ?></strong>
         </td>
         <td><?php echo Membership::calculateDays($this->row->id); ?></td>
      </tr>
      <tr>
         <td>
            <strong><?php echo Language::$word->DESCRIPTION; ?></strong>
         </td>
         <td><?php echo $this->row->description; ?></td>
      </tr>
      <?php if (!$this->row->recurring): ?>
         <tr>
            <td>
               <strong><?php echo Language::$word->DC_CODE; ?></strong>
            </td>
            <td>
               <div class="wojo small action input">
                  <input type="text" placeholder="<?php echo Language::$word->DC_CODE_I; ?>" name="coupon">
                  <a data-id="<?php echo $this->row->id; ?>" id="cinput" class="wojo small icon primary button">
                     <i class="icon search"></i>
                  </a>
               </div>
            </td>
         </tr>
      <?php endif; ?>
      <tr id="gatedata">
         <td>
            <strong><?php echo Language::$word->M_SUB22; ?></strong>
         </td>
         <td>
            <div id="activateCoupon" class="hide-all">
               <a class="wojo primary small button activateCoupon"><?php echo Language::$word->ACTIVATE; ?></a>
            </div>
            <div id="gateList">
               <div class="row blocks phone-1 mobile-3 tablet-4 screen-5 small gutters justify-center">
                  <?php foreach ($this->gateways as $grows): ?>
                     <?php if ($this->row->recurring and !$grows->is_recurring): ?>
                        <?php continue; ?>
                     <?php else: ?>
                        <div class="columns">
                           <a class="wojo white shadow icon fluid button sGateway" data-id="<?php echo $grows->id; ?>">
                              <img class="wojo default image" src="<?php echo SITEURL . '/gateways/' . $grows->dir . '/' . $grows->dir . '_logo.svg'; ?>" alt="<?php echo $grows->displayname; ?>">
                           </a>
                        </div>
                     <?php endif; ?>
                  <?php endforeach; ?>
               </div>
            </div>
         </td>
      </tr>
      <tr>
         <td colspan="2" id="gdata"></td>
      </tr>
   </table>
</div>
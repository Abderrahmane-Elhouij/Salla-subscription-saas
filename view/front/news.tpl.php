<?php
   /**
    * news
    *
    * @package Wojo Framework
    * @author wojoscripts.com
    * @copyright 2023
    * @version 5.00: news.tpl.php, v1.00 7/17/2023 10:40 AM Gewa Exp $
    *
    */
   if (!defined('_WOJO')) {
      die('Direct access to this location is not allowed.');
   }
?>
<main>
   <div class="padding-big-vertical">
      <div class="wojo-grid">
         <h4><?php echo Language::$word->NW_TITLE1; ?></h4>
         <?php if ($this->data): ?>
            <?php foreach ($this->data as $row): ?>
               <div class="wojo card" id="item_<?php echo $row->id; ?>">
                  <div class="header divided">
                     <div class="row horizontal-gutters">
                        <div class="columns auto align-middle">
                           <i class="icon large newspaper"></i>
                        </div>
                        <div class="columns">
                           <p class="text-weight-500 text-size-small"><?php echo Date::doDate('short_date', $row->created); ?></p>
                           <?php echo $row->title; ?>
                           <p>
                              <small><?php echo Language::$word->BY; ?>: <?php echo $row->author; ?></small>
                           </p>
                        </div>
                     </div>
                  </div>
                  <div class="content">
                     <?php echo Url::out_url($row->body); ?>
                  </div>
               </div>
            <?php endforeach; ?>
         <?php endif; ?>
      </div>
   </div>
</main>
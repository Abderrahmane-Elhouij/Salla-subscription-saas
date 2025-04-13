<?php
  /**
   * Membership Edit
   *
   * @package Wojo Framework
   * @author wojoscripts.com
   * @copyright 2025
   * @version $Id: _membershipEdit.tpl.php, v1.00 2025-04-13 22:52:48 gewa Exp $
   */
  if (!defined("_WOJO"))
      die('Direct access to this location is not allowed.');
?>
<h2><?php echo Lang::$word->MEM_SUB2;?></h2>
<form method="post" id="wojo_form" name="wojo_form" enctype="multipart/form-data">
  <div class="wojo segment form">
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->NAME;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <input type="text" placeholder="<?php echo Lang::$word->NAME;?>" value="<?php echo $this->data->title;?>" name="title">
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->MEM_PRICE;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo labeled input">
          <div class="wojo label"><?php echo Utility::currencySymbol();?></div>
          <input type="text" placeholder="<?php echo Lang::$word->MEM_PRICE;?>" value="<?php echo $this->data->price;?>" name="price">
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->DESCRIPTION;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <textarea class="altpost" name="description"><?php echo $this->data->description;?></textarea>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->MEM_DAYS;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo action input">
          <input type="text" placeholder="<?php echo Lang::$word->MEM_DAYS;?>" value="<?php echo $this->data->days;?>" name="days">
          <select class="wojo selection dropdown" name="period">
            <?php echo Utility::loopOptionsSimple(Date::getMembershipPeriod(), $this->data->period);?>
          </select>
        </div>
      </div>
    </div>
    <?php if($this->data->thumb):?>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->THUMB;?></label>
      </div>
      <div class="field">
        <div class="wojo basic segment"><img src="<?php echo UPLOADURL;?>/memberships/<?php echo $this->data->thumb;?>" alt="" class="wojo medium image">
          <div class="margin-top">
            <label class="wojo checkbox">
              <input name="thumb_delete" type="checkbox" value="1" class="is_delete_image">
              <i></i><?php echo Lang::$word->DELTHUMB;?></label>
          </div>
        </div>
      </div>
    </div>
    <?php else:?>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->THUMB;?></label>
      </div>
      <div class="field">
        <input type="file" name="thumb" data-type="image" accept="image/png, image/jpeg">
      </div>
    </div>
    <?php endif;?>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->MEM_PRIVATE;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="private" type="radio" value="1" id="private_1" <?php Utility::getChecked($this->data->private, 1); ?>>
          <label for="private_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="private" type="radio" value="0" id="private_0" <?php Utility::getChecked($this->data->private, 0); ?>>
          <label for="private_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->MEM_REC;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="recurring" type="radio" value="1" id="recurring_1" <?php Utility::getChecked($this->data->recurring, 1); ?>>
          <label for="recurring_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="recurring" type="radio" value="0" id="recurring_0" <?php Utility::getChecked($this->data->recurring, 0); ?>>
          <label for="recurring_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="wojo fields align-middle">
      <div class="field four wide labeled">
        <label class="content-right mobile-content-left"><?php echo Lang::$word->PUBLISHED;?> <i class="icon asterisk"></i></label>
      </div>
      <div class="field">
        <div class="wojo checkbox radio fitted inline">
          <input name="active" type="radio" value="1" id="active_1" <?php Utility::getChecked($this->data->active, 1); ?>>
          <label for="active_1"><?php echo Lang::$word->YES;?></label>
        </div>
        <div class="wojo checkbox radio fitted inline">
          <input name="active" type="radio" value="0" id="active_0" <?php Utility::getChecked($this->data->active, 0); ?>>
          <label for="active_0"><?php echo Lang::$word->NO;?></label>
        </div>
      </div>
    </div>
    <div class="content-center">
      <a href="<?php echo Url::url("/sub_admin/memberships");?>" class="wojo small simple button"><?php echo Lang::$word->CANCEL;?></a>
      <button type="button" data-action="processMembership" data-url="controller.php" name="dosubmit" class="wojo primary button"><?php echo Lang::$word->MEM_UPDATE;?></button>
    </div>
  </div>
  <input type="hidden" name="id" value="<?php echo $this->data->id;?>">
</form>
<script type="text/javascript"> 
// <![CDATA[  
$(document).ready(function() {
    $("button[name=dosubmit]").click(function() {
        const $button = $(this);
        const $form = $("#wojo_form");
        if ($form.valid()) {
            const formData = new FormData($form[0]);
            $.ajax({
                type: 'post',
                url: $button.data('url'),
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $button.addClass("loading").prop("disabled", true);
                }
            }).done(function(json) {
                setTimeout(function() {
                    $button.removeClass("loading").prop("disabled", false);
                    if (json.type === "success") {
                        $form.trigger("reset");
                        switch (json.type) {
                            case "success":
                                $.wNotice(decodeURIComponent(json.title), decodeURIComponent(json.message), {
                                    status: 'success',
                                    autoclose: 6000
                                });
                                break;
                            case "error":
                                $.wNotice(decodeURIComponent(json.title), decodeURIComponent(json.message), {
                                    status: 'error',
                                    autoclose: 6000
                                });
                                break;
                            case "info":
                                $.wNotice(decodeURIComponent(json.title), decodeURIComponent(json.message), {
                                    status: 'info',
                                    autoclose: 6000
                                });
                                break;
                        }
                        if (json.redirect) {
                            setTimeout(function() {
                                window.location.href = json.redirect;
                            }, 1000);
                        }
                    }
                }, 500);
            });
        }
    });
});
// ]]>
</script>
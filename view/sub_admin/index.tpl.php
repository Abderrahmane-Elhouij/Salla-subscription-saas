<?php
/**
 * Index
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: index.tpl.php, v1.00 4/13/2025 2:41 PM
 *
 */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
?>
<div class="row gutters align-middle">
    <div class="column mobile-100 phone-100">
        <h2><?php echo Language::$word->SUB_ADMIN_DASHBOARD; ?></h2>
        <p class="wojo small text"><?php echo Language::$word->SUB_ADMIN_WELCOME; ?></p>
    </div>
    <div class="column shrink mobile-100 phone-100">
        <div class="wojo horizontal small divided list">
            <div class="item">
                <?php echo Date::doDate('long_date', date('Y-m-d')); ?>
            </div>
            <div class="item">
                <?php echo App::Auth()->fname; ?> <?php echo App::Auth()->lname; ?>
            </div>
        </div>
    </div>
</div>

<div class="row gutters">
   <div class="columns screen-50 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('/sub_admin/users'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-positive text-size-massive counter"><?php echo $this->totalUsers; ?></span>
            <p class="text-color-positive"><?php echo Language::$word->SUB_ADMIN_USERS; ?></p>
         </div>
      </a>
   </div>
   <div class="columns screen-50 tablet-50 mobile-50 phone-100">
      <a href="<?php echo Url::url('/sub_admin/memberships'); ?>" class="wojo basic attached card">
         <div class="content center-align"><span class="text-color-primary text-size-massive counter"><?php echo $this->totalMemberships; ?></span>
            <p class="text-color-primary"><?php echo Language::$word->SUB_ADMIN_MEMBERSHIPS; ?></p>
         </div>
      </a>
   </div>
</div>

<div class="row horizontal-gutters">
    <div class="columns screen-50 tablet-50 mobile-100 phone-100">
        <div class="wojo segment shadow">
            <div class="row align-middle">
                <div class="column">
                    <h4><?php echo Language::$word->SUB_ADMIN_REVENUE; ?></h4>
                </div>
            </div>
            <div class="wojo relaxed divider"></div>
            <div class="wojo big fluid vertical buttons">
                <div class="wojo secondary very relaxed button">
                    <div class="content">
                        <div class="description"><?php echo Language::$word->TRX_AMOUNT; ?></div>
                        <div class="label"><?php echo Utility::formatMoney($this->totalRevenue); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="columns screen-50 tablet-50 mobile-100 phone-100">
        <div class="wojo segment shadow">
            <h4><?php echo Language::$word->SUB_ADMIN_QUICK_MENU; ?></h4>
            <div class="wojo relaxed divider"></div>
            <div class="wojo small fluid middle aligned list">
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/users/new'); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_USER; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/memberships/new'); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_MEMBERSHIP; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/users'); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_USERS; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/memberships'); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_MEMBERSHIPS; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo small secondary button">View Salla Subscriptions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo SITEURL; ?>/assets/sparkline.min.js"></script>
<script type="text/javascript">
    // <![CDATA[
    $(document).ready(function () {
        // Initialize counters with animation
        $('.counter').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 2000,
                easing: 'swing',
                step: function (now) {
                    $(this).text(Math.ceil(now));
                }
            });
        });
    });
    // ]]>
</script>
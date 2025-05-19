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

<div class="row gutters">
    <div class="columns screen-50 tablet-50 mobile-50 phone-100">
        <a href="<?php echo Url::url('/sub_admin/users'); ?>" class="wojo basic attached card">
            <div class="content center-align"><span
                        class="text-color-positive text-size-massive counter"><?php echo $this->totalUsers; ?></span>
                <p class="text-color-positive"><?php echo Language::$word->SUB_ADMIN_USERS; ?></p>
            </div>
        </a>
    </div>
    <div class="columns screen-50 tablet-50 mobile-50 phone-100">
        <a href="<?php echo Url::url('/sub_admin/memberships'); ?>" class="wojo basic attached card">
            <div class="content center-align"><span
                        class="text-color-primary text-size-massive counter"><?php echo $this->totalMemberships; ?></span>
                <p class="text-color-primary"><?php echo Language::$word->SUB_ADMIN_MEMBERSHIPS; ?></p>
            </div>
        </a>
    </div>
</div>

<div class="row horizontal-gutters">
    <div class="columns screen-50 tablet-50 mobile-100 phone-100">
        <div class="wojo segment shadow">            <div class="row align-middle">
                <div class="column">
                    <h4><?php echo Language::$word->SUB_ADMIN_SUBSCRIPTIONS; ?></h4>
                </div>
            </div>
            <div class="wojo relaxed divider"></div>
            <div class="wojo big fluid vertical buttons">
                <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo secondary very relaxed button">
                    <div class="content">
                        <div class="description"><?php echo Language::$word->SUB_ADMIN_TOTAL_SUBSCRIPTIONS; ?></div>
                        <div class="label" style="font-size: 1.6em; font-weight: bold; margin-top: 8px;"><span
                                    class="counter"><?php echo $this->totalSubscriptions; ?></span></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="columns screen-50 tablet-50 mobile-100 phone-100">
        <div class="wojo segment shadow">
            <h4><?php echo Language::$word->SUB_ADMIN_QUICK_MENU; ?></h4>
            <div class="wojo relaxed divider"></div>
            <div class="wojo small fluid middle aligned list">
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/connect'); ?>" class="wojo small positive button">
                        <i class="icon link"></i> <?php echo Language::$word->SUB_ADMIN_CONNECT_STORE; ?>
                    </a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/users/new'); ?>"
                       class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_USER; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/memberships/new'); ?>"
                       class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_MEMBERSHIP; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/users'); ?>"
                       class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_USERS; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/memberships'); ?>"
                       class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_MEMBERSHIPS; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url('/sub_admin/subscriptions'); ?>" class="wojo small secondary button">View
                        Salla Subscriptions</a>
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
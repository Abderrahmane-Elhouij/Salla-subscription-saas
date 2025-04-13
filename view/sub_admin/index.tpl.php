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
<div class="row horizontal-gutters">
    <div class="column">
        <div class="wojo segment shadow">
            <div class="row align-middle">
                <div class="column">
                    <h4><?php echo Language::$word->SUB_ADMIN_USERS; ?></h4>
                </div>
                <div class="column shrink">
                    <div class="wojo small fluid basic positive button">
                        <a href="<?php echo Url::url('/sub_admin/users'); ?>">
                            <?php echo Language::$word->VIEW; ?></a>
                    </div>
                </div>
            </div>
            <div class="wojo relaxed divider"></div>
            <p class="wojo basic text"><?php echo $this->totalUsers; ?> <?php echo Language::$word->SUB_ADMIN_USERS; ?></p>
            <p class="wojo small text"><?php echo str_replace('[NUMBER]', $this->totalUsers, Language::$word->SUB_ADMIN_USERS_DESC); ?></p>
        </div>
    </div>
    <div class="column">
        <div class="wojo segment shadow">
            <div class="row align-middle">
                <div class="column">
                    <h4><?php echo Language::$word->SUB_ADMIN_MEMBERSHIPS; ?></h4>
                </div>
                <div class="column shrink">
                    <div class="wojo small fluid basic positive button">
                        <a href="<?php echo Url::url('/sub_admin/memberships'); ?>">
                            <?php echo Language::$word->VIEW; ?></a>
                    </div>
                </div>
            </div>
            <div class="wojo relaxed divider"></div>
            <p class="wojo basic text"><?php echo $this->totalMemberships; ?> <?php echo Language::$word->SUB_ADMIN_MEMBERSHIPS; ?></p>
            <p class="wojo small text"><?php echo str_replace('[NUMBER]', $this->totalMemberships, Language::$word->SUB_ADMIN_MEMBERSHIPS_DESC); ?></p>
        </div>
    </div>
</div>

<div class="row horizontal-gutters">
    <div class="column mobile-100">
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
    <div class="column phone-100">
        <div class="wojo segment shadow">
            <h4><?php echo Language::$word->SUB_ADMIN_QUICK_MENU; ?></h4>
            <div class="wojo relaxed divider"></div>
            <div class="wojo small fluid middle aligned list">
                <div class="item">
                    <a href="<?php echo Url::url("/sub_admin/users/new"); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_USER; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url("/sub_admin/memberships/new"); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_NEW_MEMBERSHIP; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url("/sub_admin/users"); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_USERS; ?></a>
                </div>
                <div class="item">
                    <a href="<?php echo Url::url("/sub_admin/memberships"); ?>" class="wojo small secondary button"><?php echo Language::$word->SUB_ADMIN_VIEW_MEMBERSHIPS; ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
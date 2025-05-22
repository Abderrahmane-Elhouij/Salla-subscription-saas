<?php
/**
 * index
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: index.tpl.php, v1.00 05/19/2025 1:45 PM
 *
 */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
?>
<!-- Ensure proper responsive behavior with viewport meta tag -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<main class="overflow-hidden">

    <!-- Hero Section -->
    <section class="hero-section padding-top-huge padding-bottom-big">
        <div class="wojo-grid">
            <div class="row gutters align-middle">
                <div class="columns screen-50 tablet-50 mobile-100 phone-100 hero-content">
                    <div class="wojo ribbon left primary"><?php echo Language::$word->HOME_RIBBON ?? "Salla Official Partner"; ?></div>
                    <h1 class="wojo huge text animated fadeInUp"><?php echo Language::$word->HOME_TITLE ?? "Ishtrakat - Smart Subscription Management for Salla Stores"; ?></h1>
                    <p class="wojo medium text margin-bottom animated fadeInUp delay-1"><?php echo Language::$word->HOME_SUBTITLE ?? "Transform how you handle recurring revenue with Ishtrakat's powerful dashboard that simplifies subscription visualization and management for your Salla store."; ?></p>
                    <div class="hero-buttons margin-top animated fadeInUp delay-2">
                        <a href="<?php echo Url::url('/standalone_register.php'); ?>"
                           class="wojo large primary button with-icon"><?php echo Language::$word->HOME_GET_STARTED ?? "Get Started"; ?>
                            <i class="icon right arrow"></i></a>
                        <a href="<?php echo Url::url('/sub_admin/login'); ?>"
                           class="wojo large secondary button margin-left"><?php echo Language::$word->HOME_LOGIN ?? "Login"; ?></a>
                    </div>
                </div>
                <div class="columns screen-50 tablet-50 mobile-100 phone-100 animated fadeIn">
                    <div class="hero-image-wrapper">
                        <img src="<?php echo UPLOADURL; ?>/dashboard-preview.svg"
                             alt="<?php echo Language::$word->HOME_DASHBOARD_ALT ?? "Dashboard Preview"; ?>"
                             class="wojo round big image responsive-img">
                        <div class="floating-shape shape-1"></div>
                        <div class="floating-shape shape-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="padding-vertical-big bg-light-gray">
        <div class="wojo-grid">
            <div class="center-align margin-bottom-big">
                <h2 class="wojo primary text"><?php echo Language::$word->HOME_FEATURES_TITLE ?? "Powerful Subscription Management Features"; ?></h2>
                <p class="wojo medium text"><?php echo Language::$word->HOME_FEATURES_SUBTITLE ?? "Everything you need to manage your Salla store subscriptions"; ?></p>
            </div>
            <div class="row gutters screen-3 tablet-2 mobile-2 phone-1">
                <!-- Feature 1 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo primary inverted circular icon"><i class="icon display"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE1_TITLE ?? "Clean Dashboard"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE1_DESC ?? "View your subscription information with an intuitive and easy-to-use interface designed for Salla merchants."; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo secondary inverted circular icon"><i class="icon people"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE2_TITLE ?? "Customer Management"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE2_DESC ?? "Manage subscribers with detailed profiles and keep track of your customer information in one place."; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo positive inverted circular icon"><i class="icon calendar"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE3_TITLE ?? "Subscription Tracking"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE3_DESC ?? "Monitor subscription statuses and keep track of active and expired subscriptions in your Salla store."; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gutters screen-3 tablet-2 mobile-2 phone-1 margin-top">
                <!-- Feature 4 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo info inverted circular icon"><i class="icon bell"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE4_TITLE ?? "Renewal Reminders"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE4_DESC ?? "Keep track of subscription renewal dates with timely reminders and notifications."; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo violet inverted circular icon"><i class="icon gears"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE5_TITLE ?? "Subscription Settings"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE5_DESC ?? "Customize your subscription settings to match your Salla store's specific requirements."; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="columns">
                    <div class="wojo basic attached card hover-shadow">
                        <div class="content center-align padding-big">
                            <div class="wojo orange inverted circular icon"><i class="icon arrow clockwise"></i></div>
                            <h3 class="margin-top"><?php echo Language::$word->HOME_FEATURE6_TITLE ?? "Auto Synchronization"; ?></h3>
                            <p><?php echo Language::$word->HOME_FEATURE6_DESC ?? "Automatically sync subscription data between your Salla store and the management dashboard."; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Salla Integration Section -->
    <section class="padding-vertical-big">
        <div class="wojo-grid">
            <div class="row gutters align-middle">
                <div class="columns screen-50 tablet-50 mobile-100 phone-100 align-self-middle">
                    <h2 class="wojo primary text"><?php echo Language::$word->HOME_SALLA_TITLE ?? "Seamless Salla Integration"; ?></h2>
                    <div class="wojo relaxed divided list">
                        <div class="item">
                            <i class="icon check positive"></i>
                            <div class="content">
                                <h4><?php echo Language::$word->HOME_SALLA_FEATURE1 ?? "One-Click Connection"; ?></h4>
                                <p><?php echo Language::$word->HOME_SALLA_FEATURE1_DESC ?? "Connect your Salla store with just one click and start managing subscriptions instantly."; ?></p>
                            </div>
                        </div>
                        <div class="item">
                            <i class="icon check positive"></i>
                            <div class="content">
                                <h4><?php echo Language::$word->HOME_SALLA_FEATURE2 ?? "Automatic Synchronization"; ?></h4>
                                <p><?php echo Language::$word->HOME_SALLA_FEATURE2_DESC ?? "Customer and product data automatically syncs between Salla and Ishtrakat."; ?></p>
                            </div>
                        </div>
                        <div class="item">
                            <i class="icon check positive"></i>
                            <div class="content">
                                <h4><?php echo Language::$word->HOME_SALLA_FEATURE3 ?? "Unified Experience"; ?></h4>
                                <p><?php echo Language::$word->HOME_SALLA_FEATURE3_DESC ?? "Provide a seamless shopping experience for your subscription customers."; ?></p>
                            </div>
                        </div>
                    </div>
                </div>                <div class="columns screen-50 tablet-50 mobile-100 phone-100 center-align">
                    <img src="<?php echo UPLOADURL; ?>/salla-integration.svg"
                         alt="<?php echo Language::$word->HOME_SALLA_INTEGRATION_ALT ?? "Salla Integration"; ?>"
                         class="wojo huge image responsive-img salla-integration-img">
                </div>
            </div>
        </div>
    </section>    <!-- Stats Section -->
    <section class="padding-vertical-big bg-stats-gradient">
        <div class="wojo-grid">
            <div class="center-align margin-bottom">
                <h2 class="wojo white text"><?php echo Language::$word->HOME_STATS_TITLE ?? "Our Impact in Numbers"; ?></h2>
                <p class="wojo white dimmed text"><?php echo Language::$word->HOME_STATS_SUBTITLE ?? "Trusted by merchants across Saudi Arabia"; ?></p>
            </div>
            <div class="row gutters screen-4 tablet-2 mobile-2 phone-1">
                <div class="columns">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="icon bag"></i></div>
                        <div class="stat-content">
                            <h2 class="stat-number counter-anim" data-count="100">100+</h2>
                            <p class="stat-label"><?php echo Language::$word->HOME_STAT1 ?? "Active Merchants"; ?></p>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="icon repeat"></i></div>
                        <div class="stat-content">
                            <h2 class="stat-number counter-anim" data-count="2.5">10k+</h2>
                            <p class="stat-label"><?php echo Language::$word->HOME_STAT2 ?? "Subscriptions Managed"; ?></p>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="icon server"></i></div>
                        <div class="stat-content">
                            <h2 class="stat-number counter-anim" data-count="99.9">99.9%</h2>
                            <p class="stat-label"><?php echo Language::$word->HOME_STAT3 ?? "Uptime"; ?></p>
                        </div>
                    </div>
                </div>
                <div class="columns">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="icon person"></i></div>
                        <div class="stat-content">
                            <h2 class="stat-number">24/7</h2>
                            <p class="stat-label"><?php echo Language::$word->HOME_STAT4 ?? "Customer Support"; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Content Section -->
    <section class="padding-vertical">
        <div class="wojo-grid">
            <?php if (isset($this->row) && !empty($this->row->body)): ?>
                <?php echo Url::out_url($this->row->body); ?>
            <?php endif; ?>
        </div>
    </section>
    <!-- Call to Action -->
    <section class="padding-vertical-big bg-light-gray cta-section">
        <div class="wojo-grid">
            <div class="row align-center">
                <div class="columns screen-70 tablet-80 mobile-100 phone-100">
                    <div class="wojo segment center aligned shadow-hover">
                        <h2 class="wojo text"><?php echo Language::$word->HOME_CTA_TITLE ?? "Ready to Simplify Your Subscription Management?"; ?></h2>
                        <p class="wojo text margin-bottom"><?php echo Language::$word->HOME_CTA_TEXT ?? "Start managing your Salla store subscriptions today with Ishtrakat's powerful, easy-to-use platform."; ?></p>
                        <a href="<?php echo Url::url('/standalone_register.php'); ?>"
                           class="wojo large primary button with-icon"><?php echo Language::$word->HOME_CTA_BUTTON ?? "Create Your Account"; ?>
                            <i class="icon user plus"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Additional CSS for the new homepage -->
<style>
    /* Base styles - using the app's existing color scheme */
    :root {
        /* Use the app's existing color variables directly */
        --light-gray: var(--grey-color-100, #fafafa);
        --shadow-color: var(--shadow-color, rgba(136, 152, 170, .15));
        --text-color: var(--body-color, #51596c);
        --white: var(--white-color, #ffffff);
        --border-radius: 4px;
        --transition-speed: 0.3s ease;
    }

    /* Ensure responsive viewport */
    @-ms-viewport {
        width: device-width;
    }

    /* General Styles */
    .hero-section {
        background: linear-gradient(135deg, var(--light-color) 0%, var(--grey-color-100) 100%);
        position: relative;
        overflow: hidden;
        min-height: 60vh; /* Ensure minimum height on all devices */
        display: flex;
        align-items: center;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-image-wrapper {
        position: relative;
        z-index: 1;
        margin: 1rem auto;
        max-width: 100%;
    }

    .responsive-img {
        max-width: 100%;
        height: auto;
    }

    .floating-shape {
        position: absolute;
        border-radius: 50%;
        z-index: -1;
        opacity: 0.6;
    }

    .shape-1 {
        width: 300px;
        height: 300px;
        background: linear-gradient(45deg, var(--primary-color) 0%, var(--primary-color-hover) 100%);
        top: -150px;
        right: -100px;
        filter: blur(50px);
    }

    .shape-2 {
        width: 200px;
        height: 200px;
        background: linear-gradient(45deg, var(--secondary-color) 0%, var(--secondary-color-hover) 100%);
        bottom: -100px;
        left: 10%;
        filter: blur(40px);
    }

    .hero-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .bg-light-gray {
        background-color: var(--grey-color-100);
    }

    .bg-primary {
        background-color: var(--primary-color);
    }

    /* CTA Section */
    .cta-section .wojo.segment {
        border-radius: 8px;
        box-shadow: 0 5px 20px var(--shadow-color);
        transition: box-shadow var(--transition-speed), transform var(--transition-speed);
    }

    .cta-section .shadow-hover:hover {
        box-shadow: 0 8px 25px var(--shadow-color);
        transform: translateY(-5px);
    }

    /* Stats Section Styles */
    .bg-stats-gradient {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-color-dark, #0056b3) 100%);
        position: relative;
        overflow: hidden;
    }

    .bg-stats-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.6;
    }

    .wojo.white.dimmed.text {
        opacity: 0.8;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }

    .stat-card {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 2rem 1.5rem;
        text-align: center;
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        margin-bottom: 0;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        background-color: rgba(255, 255, 255, 0.2);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .stat-icon i {
        font-size: 2rem;
        color: white;
    }

    .stat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-number {
        font-size: 3rem !important;
        font-weight: 700;
        color: white;
        margin: 0 0 0.5rem;
        position: relative;
        display: inline-block;
    }

    .stat-number::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 3px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    .stat-label {
        color: white;
        font-size: 1.1rem;
        font-weight: 500;
        margin-top: 1rem;
        margin-bottom: 0;
    }

    /* Counter Animation */
    .counter-anim {
        position: relative;
        display: inline-block;
    }

    /* Counter Animation Script */

    /* Fix for any horizontal overflow issues */
    .wojo-grid {
        overflow-x: hidden;
    }

    /* Fix for stats section spacing */
    .row.gutters.screen-4.tablet-2.mobile-2.phone-1 {
        margin-left: -1rem;
        margin-right: -1rem;
    }

    .row.gutters.screen-4.tablet-2.mobile-2.phone-1 > .columns {
        padding: 0 1rem;
    }

    /* Spacing utilities */
    .padding-top-huge {
        padding-top: 6rem;
    }

    .padding-vertical-big {
        padding-top: 4rem;
        padding-bottom: 4rem;
    }

    /* Card and hover effects */
    .hover-shadow {
        transition: transform var(--transition-speed), box-shadow var(--transition-speed);
    }

    .hover-shadow:hover {
        box-shadow: 0 5px 15px var(--shadow-color);
        transform: translateY(-3px);
    }

    /* For touch devices */
    @media (hover: none) {
        .hover-shadow:active {
            box-shadow: 0 5px 15px var(--shadow-color);
            transform: translateY(-3px);
        }
    }

    .wojo.circular.icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        transition: transform var(--transition-speed);
    }

    .wojo.circular.icon:hover {
        transform: scale(1.05);
    }

    .wojo.circular.icon i {
        font-size: 2rem;
    }

    .wojo.ribbon {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin-bottom: 1rem;
        font-weight: 600;
        border-radius: var(--border-radius);
        position: relative;
    }

    /* Buttons */
    .wojo.button.with-icon {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .hero-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    /* Animation classes */
    .animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }

    .fadeIn {
        animation-name: fadeIn;
    }

    .fadeInUp {
        animation-name: fadeInUp;
    }

    .delay-1 {
        animation-delay: 0.3s;
    }

    .delay-2 {
        animation-delay: 0.6s;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translate3d(0, 30px, 0);
        }
        to {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
    }

    /* Responsive adjustments - Tablets */
    @media only screen and (max-width: 768px) {
        .padding-top-huge {
            padding-top: 3rem;
        }

        .padding-vertical-big {
            padding-top: 2.5rem;
            padding-bottom: 2.5rem;
        }

        .floating-shape.shape-1 {
            width: 200px;
            height: 200px;
        }

        .floating-shape.shape-2 {
            width: 150px;
            height: 150px;
        }

        .hero-badges {
            margin-top: 1rem;
        }

        .wojo.circular.icon {
            width: 70px;
            height: 70px;
        }

        .wojo.circular.icon i {
            font-size: 1.7rem;
        }

        /* Ensure grid displays 2 items per row on tablets and small screens */
        .row.gutters.screen-3.tablet-2 > .columns {
            width: 50% !important;
            flex: 0 0 50% !important;
        }
    }

    /* Small Tablets and Large Mobile */
    @media only screen and (max-width: 600px) {
        .padding-top-huge {
            padding-top: 2.5rem;
        }

        .padding-vertical-big {
            padding-top: 2.2rem;
            padding-bottom: 2.2rem;
        }

        .wojo.huge.text {
            font-size: 2.2rem;
            line-height: 1.3;
        }

        .wojo.medium.text {
            font-size: 1rem;
        }

        .hero-buttons {
            justify-content: flex-start;
        }

        .wojo.button.margin-left {
            margin-left: 0.5rem;
        }

        /* Reinforce 2 items per row on mobile */
        .row.gutters.screen-3.tablet-2.mobile-2 > .columns {
            width: 50% !important;
            flex: 0 0 50% !important;
        }

        /* Improve card spacing */
        .wojo.basic.attached.card .content {
            padding: 1.5rem !important;
        }

        .wojo.circular.icon {
            width: 60px;
            height: 60px;
        }

        .wojo.circular.icon i {
            font-size: 1.5rem;
        }

        /* Improved stats section for small screens */
        .text-size-massive {
            font-size: 2.5rem !important;
        }
    }

    /* Small Mobile Phones */
    @media only screen and (max-width: 480px) {
        .padding-top-huge {
            padding-top: 2rem;
        }

        .padding-vertical-big {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .wojo.huge.text {
            font-size: 2rem;
            line-height: 1.2;
        }

        /* Better button handling for small screens */
        .hero-buttons {
            justify-content: center;
        }

        .wojo.button.margin-left {
            margin-left: 0;
        }

        .wojo.button {
            padding: 0.8rem 1.2rem !important;
        }

        .hero-badges {
            flex-direction: column;
            align-items: flex-start;
        }

        /* Adjust card spacing for tiny screens */
        .row.gutters {
            margin-left: -0.5rem !important;
            margin-right: -0.5rem !important;
        }

        .row.gutters > .columns {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        /* Ensure phone displays 1 item per row */
        .row.gutters.screen-3.tablet-2.mobile-2.phone-1 > .columns {
            width: 100% !important;
            flex: 0 0 100% !important;
        }

        /* Make stat numbers more readable on small devices */
        .text-size-massive {
            font-size: 2rem !important;
        }

        /* Optimize CTA for small screens */
        .wojo.segment.center.aligned {
            padding: 1.5rem !important;
        }

        /* Fix Salla integration section on small screens */
        .wojo.relaxed.divided.list .item {
            padding: 1rem 0;
        }

        .wojo.relaxed.divided.list .content h4 {
            margin-bottom: 0.25rem;
        }
    }

    /* RTL Support for Arabic */
    html[dir="rtl"] .wojo.ribbon.left {
        direction: rtl;
    }

    html[dir="rtl"] .wojo.button.with-icon i.right.arrow {
        transform: rotate(180deg);
    }

    html[dir="rtl"] .shape-1 {
        right: auto;
        left: -100px;
    }

    html[dir="rtl"] .shape-2 {
        left: auto;
        right: 10%;
    }

    html[dir="rtl"] .wojo.relaxed.divided.list .item i {
        float: right;
        margin-right: 0;
        margin-left: 1rem;
    }

    html[dir="rtl"] .wojo.button.margin-left {
        margin-left: 0;
        margin-right: 1rem;
    }

    html[dir="rtl"] .text-align-left {
        text-align: right;
    }

    html[dir="rtl"] .text-align-right {
        text-align: left;
    }

    /* Fix icon placement in RTL */
    html[dir="rtl"] .wojo.button.with-icon {
        flex-direction: row-reverse;
    }

    /* RTL support for stats section */
    html[dir="rtl"] .stat-card {
        direction: rtl;
    }

    /* Enhanced RTL responsive support */
    @media only screen and (max-width: 768px) {
        html[dir="rtl"] .wojo.relaxed.divided.list .item i {
            margin-left: 0.5rem;
        }
    }

    @media only screen and (max-width: 480px) {
        html[dir="rtl"] .wojo.button.margin-left {
            margin-right: 0;
            margin-top: 1rem;
        }

        html[dir="rtl"] .wojo.relaxed.divided.list .content {
            padding-right: 0.5rem;
        }

        html[dir="rtl"] .row.align-center {
            text-align: center;
        }
    }

    /* Print media query for better printing */
    @media print {
        .hero-section {
            background: none !important;
        }

        .floating-shape {
            display: none;
        }

        .animated {
            animation: none !important;
        }

        .wojo-grid {
            width: 100% !important;
        }

        .padding-top-huge, .padding-vertical-big {
            padding: 1rem !important;
        }
    }

    /* Custom responsive grid fixes for feature cards */
    /* Ensure consistent grid layout across all screen sizes */
    @media only screen and (min-width: 992px) {
        /* Wide screens - 3 cards per row */
        .row.gutters.screen-3 > .columns {
            width: 33.33% !important;
            flex: 0 0 33.33% !important;
        }
    }

    @media only screen and (max-width: 991px) and (min-width: 481px) {
        /* Tablets and medium screens - 2 cards per row */
        .row.gutters.screen-3.tablet-2 > .columns,
        .row.gutters.screen-3.tablet-2.mobile-2 > .columns {
            width: 50% !important;
            flex: 0 0 50% !important;
        }
    }

    @media only screen and (max-width: 480px) {
        /* Phones - 1 card per row */
        .row.gutters.screen-3.tablet-2.mobile-2.phone-1 > .columns,
        .row.gutters.screen-4.tablet-2.mobile-2.phone-1 > .columns {
            width: 100% !important;
            flex: 0 0 100% !important;
        }
    }    /* Salla Integration Image Styles */
    .salla-integration-img {
        max-width: 100% !important;
        transition: transform 0.3s ease;
    }
    
    .salla-integration-img:hover {
        transform: scale(1.05);
    }
    
    @media only screen and (max-width: 768px) {
        .salla-integration-img {
            max-width: 95% !important;
        }
    }
    
    /* Stats Section Responsive Styles */
    @media only screen and (max-width: 991px) {
        .stat-card {
            margin-bottom: 1rem;
        }
    }

    @media only screen and (max-width: 768px) {
        .stat-icon {
            width: 60px;
            height: 60px;
        }

        .stat-icon i {
            font-size: 1.6rem;
        }

        .stat-number {
            font-size: 2.5rem !important;
        }

        .stat-label {
            font-size: 1rem;
        }

        .stat-card {
            padding: 1.5rem 1rem;
        }

        .bg-stats-gradient .wojo.white.dimmed.text {
            font-size: 1rem;
        }
    }

    @media only screen and (max-width: 480px) {
        .stat-number {
            font-size: 2.2rem !important;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            margin-bottom: 1rem;
        }

        .stat-icon i {
            font-size: 1.4rem;
        }

        .stat-card {
            padding: 1.2rem 0.8rem;
            margin-bottom: 1rem;
        }

        .row.gutters.screen-4.tablet-2.mobile-2.phone-1 > .columns {
            margin-bottom: 1rem;
        }
    }
</style>

<!-- Stats Counter Animation Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.bottom >= 0
            );
        }

        // Handle counter animation
        function animateCounters() {
            const statsSection = document.querySelector('.bg-stats-gradient');
            if (statsSection && isInViewport(statsSection)) {
                const counters = statsSection.querySelectorAll('.counter-anim');

                // If counters are already animated, don't animate again
                if (statsSection.getAttribute('data-animated') === 'true') {
                    return;
                }

                statsSection.setAttribute('data-animated', 'true');

                counters.forEach(counter => {
                    const target = parseFloat(counter.getAttribute('data-count'));
                    const duration = 2000; // 2 seconds
                    let suffix = '';

                    // Check if the number contains a suffix
                    if (counter.textContent.includes('k+')) {
                        suffix = 'k+';
                    } else if (counter.textContent.includes('%')) {
                        suffix = '%';
                    }

                    const startTime = performance.now();

                    function updateCounter(currentTime) {
                        const elapsedTime = currentTime - startTime;
                        const progress = Math.min(elapsedTime / duration, 1);

                        // Easing function for smoother animation
                        const easedProgress = 1 - Math.pow(1 - progress, 3);

                        let currentValue = Math.floor(easedProgress * target);
                        if (suffix === '%' && target < 100) {
                            // For percentage with decimal points
                            currentValue = (easedProgress * target).toFixed(1);
                        }

                        counter.textContent = currentValue + suffix;

                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        }
                    }

                    requestAnimationFrame(updateCounter);
                });
            }
        }

        // Check on scroll and initial load
        window.addEventListener('scroll', animateCounters, {passive: true});
        window.addEventListener('resize', animateCounters, {passive: true});

        // Initial check
        animateCounters();
    });
</script>
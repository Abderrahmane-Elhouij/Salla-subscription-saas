<?php
    /**
     * Cron
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Cron.php, v1.00 7/1/2023 4:56 PM Gewa Exp $
     *
     */
    
    use Stripe\Exception\ApiErrorException;
    
    const _WOJO = true;
    require_once '../init.php';
      try {
        // Run regular system cron jobs
        Cron::run(1);
          // Check if we need to run subscription expiry notifications (once a day)
        if (date('H') == '00') { // Run at midnight
            // Include the Salla subscription expiry notification script
            include_once 'salla_subscription_expiry_notification.php';
        }
    } catch (\PHPMailer\PHPMailer\Exception|ApiErrorException|NotFoundException $e) {
        error_log($e->getMessage(), 3, 'cron.log');
    }
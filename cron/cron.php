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
        Cron::run(1);
    } catch (\PHPMailer\PHPMailer\Exception|ApiErrorException|NotFoundException $e) {
        error_log($e->getMessage(), 3, 'cron.log');
    }
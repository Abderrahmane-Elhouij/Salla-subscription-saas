<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: ipn.php, v1.00 7/18/2023 6:01 PM Gewa Exp $
     *
     */
    const _WOJO = true;
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['payment_status'])) {
        require_once '../../init.php';
        require_once 'PayPal.php';
        
        $pp = Database::Go()->select(Core::gTable)->where('name', 'paypal', '=')->first()->run();
        
        $listener = new PayPal();
        $listener->use_live = $pp->live;
        
        try {
            $listener->requirePostMethod();
            $pp_ver = $listener->processIpn();
        } catch (Exception $e) {
            error_log('Process IPN failed: ' . $e->getMessage() . ' [' . $_SERVER['REMOTE_ADDR'] . "] \n" . $listener->getResponse(), 3, 'pp_errorlog.log');
            exit;
        }
        
        $payment_status = $_POST['payment_status'];
        $receiver_email = $_POST['receiver_email'];
        $mc_currency = Validator::sanitize($_POST['mc_currency']);
        list($membership_id, $user_id) = explode('_', $_POST['item_number']);
        $mc_gross = $_POST['mc_gross'];
        $txn_id = isset($_POST['txn_id']) ? Validator::sanitize($_POST['txn_id']) : time();
        
        $row = Database::Go()->select(Membership::mTable)->where('id', intval($membership_id), '=')->first()->run();
        $usr = Database::Go()->select(User::mTable)->where('id', intval($user_id), '=')->first()->run();
        $cart = Membership::getCart($usr->id);
        
        if ($cart) {
            $v1 = Validator::compareNumbers($mc_gross, $cart->totalprice);
        } else {
            $cart = new stdClass;
            $tax = Membership::calculateTax();
            $v1 = Validator::compareNumbers($mc_gross, $row->price, 'gte');
            
            $cart->originalprice = $row->price;
            $cart->total = $row->price;
            $cart->coupon = 0;
            $cart->totaltax = Validator::sanitize($row->price * $tax, 'float');
            $cart->totalprice = Validator::sanitize($tax * $row->price + $row->price, 'float');
        }
        
        if ($pp_ver) {
            if ($_POST['payment_status'] == 'Completed') {
                if ($row and $v1 and $receiver_email == $pp->extra) {
                    $data = array(
                        'txn_id' => $txn_id,
                        'membership_id' => $row->id,
                        'user_id' => $usr->id,
                        'rate_amount' => $cart->total,
                        'coupon' => $cart->coupon,
                        'total' => $cart->totalprice,
                        'tax' => $cart->totaltax,
                        'currency' => strtoupper($mc_currency),
                        'ip' => Url::getIP(),
                        'pp' => 'PayPal',
                        'status' => 1,
                    );
                    
                    $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                    
                    //insert user membership
                    $u_data = array(
                        'transaction_id' => $last_id,
                        'user_id' => $usr->id,
                        'membership_id' => $row->id,
                        'expire' => Membership::calculateDays($row->id),
                        'recurring' => $row->recurring,
                        'active' => 1,
                    );
                    
                    //update user record
                    $x_data = array(
                        'membership_id' => $row->id,
                        'mem_expire' => $u_data['expire'],
                    );
                    
                    Database::Go()->insert(Membership::umTable, $u_data)->run();
                    Database::Go()->update(User::mTable, $x_data)->where('id', $usr->id, '=')->run();
                    Database::Go()->delete(Membership::cTable)->where('user_id', $usr->id, '=')->run();
                    
                    /* == Notify Administrator == */
                    $mailer = Mailer::sendMail();
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'payComplete', '=')->first()->run();
                    $core = App::Core();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[DATE]',
                        '[SITEURL]',
                        '[NAME]',
                        '[ITEMNAME]',
                        '[PRICE]',
                        '[STATUS]',
                        '[PP]',
                        '[IP]',
                        '[CEMAIL]',
                        '[FB]',
                        '[TW]'
                    ), array(
                        $core->plogo,
                        $core->company,
                        $core->company,
                        date('Y'),
                        SITEURL,
                        $usr->fname . ' ' . $usr->lname,
                        $row->title,
                        $data['total'],
                        'Completed',
                        'PayPal',
                        Url::getIP(),
                        $core->site_email,
                        $core->social->facebook,
                        $core->social->twitter
                    ), $tpl->body);
                    
                    $mailer->Subject = $tpl->subject;
                    $mailer->Body = $body;
                    try {
                        $mailer->setFrom($core->site_email, $core->company);
                        $mailer->addAddress($core->site_email, $core->company);
                        $mailer->isHTML();
                        $mailer->send();
                    } catch (\PHPMailer\PHPMailer\Exception) {
                    }
                    
                    /* == Notify User == */
					$umailer = Mailer::sendMail();
                    $tpl2 = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'payCompleteUser', '=')->first()->run();
                    
                    $ubody = str_replace(array(
                        '[LOGO]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[DATE]',
                        '[SITEURL]',
                        '[NAME]',
                        '[ITEMNAME]',
                        '[PRICE]',
                        '[COUPON]',
                        '[TAX]',
                        '[TYPE]',
                        '[PP]',
                        '[CEMAIL]',
                        '[FB]',
                        '[TW]'
                    ), array(
                        $core->plogo,
                        $core->company,
                        $core->company,
                        date('Y'),
                        SITEURL,
                        $usr->fname . ' ' . $usr->lname,
                        $row->title,
                        $data['total'],
                        $data['coupon'],
                        $data['tax'],
                        Language::$word->MEMBERSHIP,
                        'PayPal',
                        Url::getIP(),
                        $core->site_email,
                        $core->social->facebook,
                        $core->social->twitter
                    ), $tpl->body);
                    
                    $umailer->Subject = $tpl->subject;
                    $umailer->Body = $ubody;
                    try {
                        $umailer->setFrom($core->site_email, $core->company);
                        $umailer->addAddress($usr->email, $usr->fname . ' ' . $usr->lname);
                        $umailer->isHTML();
                        $umailer->send();
                    } catch (\PHPMailer\PHPMailer\Exception) {
                    }
                }
            } else {
                error_log('Process IPN failed: payment or paypal email mismatch', 3, 'pp_errorlog.log');
            }
        }
    }
<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00 ipn.php, v1.00 7/18/2023 6:25 PM Gewa Exp $
     *
     */
    
    use Mollie\Api\Exceptions\ApiException;
    use Mollie\Api\MollieApiClient;
    
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!App::Auth()->is_User()) {
        exit;
    }
    
    if (Validator::get('order_id')) {
        require_once 'vendor/autoload.php';
        $apikey = Database::Go()->select(Core::gTable, array('extra'))->where('name', 'ideal', '=')->first()->run();
        
        $mollie = new MollieApiClient;
        
        try {
            $mollie->setApiKey($apikey->extra);
            
            $o = Validator::sanitize($_GET['order_id'], 'string');
            $cart = Database::Go()->select(Membership::cTable)->where('order_id', $o, '=')->first()->run();
            
            if ($cart) {
                $payment = $mollie->payments->get($cart->cart_id);
                if ($payment->isPaid() and Validator::compareNumbers($payment->amount->value, $cart->totalprice)) {
                    $row = Database::Go()->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                    $data = array(
                        'txn_id' => $payment->metadata->order_id,
                        'membership_id' => $row->id,
                        'user_id' => App::Auth()->uid,
                        'rate_amount' => $cart->total,
                        'coupon' => $cart->coupon,
                        'total' => $cart->totalprice,
                        'tax' => $cart->totaltax,
                        'currency' => 'EUR',
                        'ip' => Url::getIP(),
                        'pp' => 'iDeal',
                        'status' => 1,
                    );
                    
                    $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                    
                    //insert user membership
                    $u_data = array(
                        'transaction_id' => $last_id,
                        'user_id' => App::Auth()->uid,
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
                    Database::Go()->update(User::mTable, $x_data)->where('id', App::Auth()->uid, '=')->run();
                    Database::Go()->delete(Membership::cTable)->where('user_m_id', App::Auth()->uid, '=')->run();
                    
                    //update membership status
                    Auth::$udata->membership_id = Session::set('membership_id', $row->id);
                    Auth::$udata->mem_expire = Session::set('mem_expire', $x_data['mem_expire']);
                    
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->STR_POK;
                    
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
                        App::Auth()->fname . ' ' . App::Auth()->lname,
                        $row->{'title' . Language::$lang},
                        $data['total'],
                        'Completed',
                        'iDeal',
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
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->STR_ERR1;
                }
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->STR_ERR1;
            }
            print json_encode($json);
        } catch (ApiException $e) {
            $json['type'] = 'error';
            $json['title'] = Language::$word->ERROR;
            $json['message'] = 'API call failed: ' . htmlspecialchars($e->getMessage());
            print json_encode($json);
        }
    } else {
        $json['type'] = 'error';
        $json['title'] = Language::$word->ERROR;
        $json['message'] = Language::$word->STR_ERR1;
        print json_encode($json);
    }
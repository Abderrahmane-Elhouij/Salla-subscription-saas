<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00 ipn.php, v1.00 7/18/2023 6:17 PM Gewa Exp $
     *
     */
    
    use Razorpay\Api\Api;
    use Razorpay\Api\Errors\SignatureVerificationError;
    
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!App::Auth()->is_User()) {
        exit;
    }
    
    require 'lib/Razorpay.php';
    
    if (isset($_POST['razorpay_payment_id'])) {
        $validate = Validator::run($_POST);
        $validate
            ->set('razorpay_signature', 'Invalid Signature')->required()->string()
            ->set('razorpay_payment_id', 'Invalid Payment ID')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Membership::getCart(App::Auth()->uid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        if (count(Message::$msgs) === 0) {
            $apikey = Database::Go()->select(Core::gTable)->where('name', 'razorpay', '=')->first()->run();
            $api = new Api($apikey->extra, $apikey->extra3);
            
            try {
                $attributes = array(
                    'razorpay_order_id' => $cart->order_id,
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );
                
                $api->utility->verifyPaymentSignature($attributes);
                
                // insert payment record
                $row = Database::Go()->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => App::Auth()->uid,
                    'rate_amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => $apikey->extra2,
                    'ip' => Url::getIP(),
                    'pp' => 'RazorPay',
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
                
                $jn['type'] = 'success';
                $jn['title'] = Language::$word->SUCCESS;
                $jn['message'] = Language::$word->STR_POK;
                print json_encode($jn);
                
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
                    'RazorPay',
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
                
            } catch (SignatureVerificationError $e) {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = $e->getMessage();
                print json_encode($json);
            }
        } else {
            Message::msgSingleStatus();
        }
    }
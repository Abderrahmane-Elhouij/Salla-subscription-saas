<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00 ipn.php, v1.00 11/05/2023 8:52 AM Gewa Exp $
     *
     */
    
    const _WOJO = true;
    require_once '../../init.php';
    
    if (!App::Auth()->is_User()) {
        exit;
    }
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['processPaystackPayment'])) {
        $validate = Validator::run($_POST);
        $validate->set('payment_method', 'Invalid Payment Methods')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Membership::getCart(App::Auth()->uid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        
        if (count(Message::$msgs) === 0) {
            $key = Database::Go()->select(Core::gTable)->where('name', 'paystack', '=')->first()->run();
            $core = App::Core();
            $auth = App::Auth();
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.paystack.co/transaction/verify/' . $safe->payment_method,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $key->extra,
                    'Cache-Control: no-cache',
                ),
            ));
            
            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);
            
            if ($error) {
                $json['type'] = 'error';
                $json['title'] = 'ERROR';
                $json['message'] = 'cURL Error #:' . $error;
                print json_encode($json);
                exit;
            } else {
                $result = json_decode($response);
                if ($result->data->status == 'success') {
                    $row = Database::Go()->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                    
                    // insert payment record
                    $data = array(
                        'txn_id' => time(),
                        'membership_id' => $row->id,
                        'user_id' => $auth->uid,
                        'rate_amount' => $cart->total,
                        'coupon' => $cart->coupon,
                        'total' => $cart->totalprice,
                        'tax' => $cart->totaltax,
                        'currency' => $key->extra2,
                        'ip' => Url::getIP(),
                        'pp' => 'Paystack',
                        'status' => 1,
                    );
                    
                    $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                    
                    //insert user membership
                    $u_data = array(
                        'transaction_id' => $safe->payment_method,
                        'user_id' => $auth->uid,
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
                    Database::Go()->update(User::mTable, $x_data)->where('id', $auth->uid, '=')->run();
                    
                    Database::Go()->delete(Membership::cTable)->where('user_id', $auth->uid, '=')->run();
                    
                    //update membership status
                    Auth::$udata->membership_id = Session::set('membership_id', $row->id);
                    Auth::$udata->mem_expire = Session::set('mem_expire', $x_data['mem_expire']);
                    
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->STR_POK;
                    
                    /* == Notify Administrator == */
                    $mailer = Mailer::sendMail();
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'payComplete', '=')->first()->run();
                    
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
                        $auth->fname . ' ' . $auth->lname,
                        $row->title,
                        $data['total'],
                        'Completed',
                        'Paystack',
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
                    $json['title'] = 'ERROR';
                    $json['message'] = $result->data->gateway_response;
                }
                print json_encode($json);
            }
        } else {
            Message::msgSingleStatus();
        }
    }
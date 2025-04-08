<?php
    /**
     * ipn
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00 ipn.php, v1.00 7/18/2023 8:52 AM Gewa Exp $
     *
     */
    
    use Stripe\Customer;
    use Stripe\Exception\CardException;
    use Stripe\Stripe;
    
    const _WOJO = true;
    require_once '../../init.php';
    
    if (!App::Auth()->is_User()) {
        exit;
    }
    
    ini_set('log_errors', true);
    ini_set('error_log', dirname(__file__) . '/ipn_errors.log');
    
    if (isset($_POST['processStripePayment'])) {
        $validate = Validator::run($_POST);
        $validate->set('payment_method', 'Invalid Payment Methods')->required()->string();
        $safe = $validate->safe();
        
        if (!$cart = Membership::getCart(App::Auth()->uid)) {
            Message::$msgs['cart'] = Language::$word->STR_ERR;
        }
        
        if (count(Message::$msgs) === 0) {
            require_once BASEPATH . 'gateways/stripe/vendor/autoload.php';
            $key = Database::Go()->select(Core::gTable)->where('name', 'stripe', '=')->first()->run();
            $core = App::Core();
            $auth = App::Auth();
            
            Stripe::setApiKey($key->extra);
            
            try {
                //Create a client
                $client = Customer::create(array(
                    'description' => $auth->name,
                    'payment_method' => $safe->payment_method,
                ));
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
                    'pp' => 'Stripe',
                    'status' => 1,
                );
                
                $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                
                //insert user membership
                $u_data = array(
                    'transaction_id' => $last_id,
                    'user_id' => $auth->uid,
                    'membership_id' => $row->id,
                    'expire' => Membership::calculateDays($row->id),
                    'recurring' => $row->recurring,
                    'active' => 1,
                );
                
                //update user record
                $x_data = array(
                    'stripe_cus' => $client['id'],
                    'membership_id' => $row->id,
                    'mem_expire' => $u_data['expire'],
                );
                
                Database::Go()->insert(Membership::umTable, $u_data)->run();
                Database::Go()->update(User::mTable, $x_data)->where('id', $auth->uid, '=')->run();
                
                //insert cron record
                if ($row->recurring) {
                    $cdata = array(
                        'user_id' => $auth->uid,
                        'membership_id' => $row->id,
                        'amount' => $cart->totalprice,
                        'stripe_customer' => $client['id'],
                        'stripe_pm' => $safe->payment_method,
                        'renewal' => $u_data['expire'],
                    );
                    Database::Go()->insert(Core::cjTable, $cdata)->run();
                }
                
                Database::Go()->delete(Membership::cTable)->where('user_id', $auth->uid, '=')->run();
                
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
                    'Stripe',
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
                
            } catch (CardException $e) {
                $json['type'] = 'error';
                $json['title'] = 'ERROR';
                Message::$msgs['msg'] = 'Message is: ' . $e->getError()->message() . "\n";
                Message::msgSingleStatus();
            }
        } else {
            Message::msgSingleStatus();
        }
    }
<?php
    /**
     * Front Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Front.php, v1.00 7/12/2023 10:32 AM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Front
    {
        
        /**
         * index
         *
         * @return void
         */
        public function index(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            $tpl->row = Database::Go()->select(Content::pTable)->where('page_type', 'home', '=')->first()->run();
            $tpl->memberships = Database::Go()->select(Membership::mTable)->where('private', 1, '<')->orderBy('sorting', 'ASC')->run();
            if ($tpl->row) {
                $tpl->keywords = $tpl->row->keywords;
                $tpl->description = $tpl->row->description;
            }
            
            $tpl->template = 'front/index';
        }
        
        /**
         * page
         *
         * @param string $slug
         * @return void
         */
        public function page(string $slug): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = Language::$word->META_T38;
            
            if (!$row = Database::Go()->select(Content::pTable)->where('slug', $slug, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($slug) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $tpl->template = 'front/error';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                    $tpl->title = Language::$word->META_ERROR;
                    $tpl->template = 'front/404';
                }
            } else {
                $tpl->row = $row;
                $tpl->title = $tpl->row->title . ' - ' . App::Core()->company;
                $tpl->keywords = $row->keywords;
                $tpl->description = $row->description;
                $tpl->crumbs = [array(0 => Language::$word->HOME, 1 => ''), $row->title];
                
                if ($row->page_type == 'membership') {
                    $tpl->packages = Database::Go()->select(Membership::mTable)->where('active', 1, '=')->where('private', 1, '<')->run();
                }
                
                $tpl->template = 'front/page';
            }
        }
        
        /**
         * login
         *
         * @return void
         */
        public function login(): void
        {
            if (App::Auth()->is_User()) {
                Url::redirect(Url::url('/dashboard'));
                exit;
            }
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/full/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $tpl->template = 'front/login';
        }
        
        /**
         * register
         *
         * @return void
         */
        public function register(): void
        {
            if (App::Auth()->is_User()) {
                Url::redirect(Url::url('/dashboard'));
                exit;
            }
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/full/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $tpl->custom_fields = Content::renderCustomFieldsFront(0);
            $tpl->countries = App::Core()->enable_tax ? Database::Go()->select(Content::cTable)->orderBy('sorting', 'DESC')->run() : null;
            $tpl->template = 'front/register';
        }
        
        /**
         * dashboard
         *
         * @return void
         */
        public function dashboard(): void
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(SITEURL);
                exit;
            }
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $tpl->data = Database::Go()->select(Membership::mTable)->where('private', 1, '<')->where('active', 1, '=')->orderBy('sorting', 'ASC')->run();
            $tpl->user = Database::Go()->select(User::mTable, array('membership_id'))->where('id', App::Auth()->uid, '=')->first()->run();
            
            $tpl->template = 'front/dashboard';
        }
        
        /**
         * history
         *
         * @return void
         */
        public function history(): void
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(SITEURL);
                exit;
            }
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $tpl->data = Stats::userHistory(App::Auth()->uid, 'expire');
            $tpl->totals = Stats::userTotals();
            
            $tpl->template = 'front/history';
        }
        
        /**
         * profile
         *
         * @return void
         */
        public function profile(): void
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(SITEURL);
                exit;
            }
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $sql = 'SELECT *,u.id as id, m.title as mtitle FROM `' . User::mTable . '` as u LEFT JOIN ' . Membership::mTable . ' as m on m.id = u.membership_id WHERE u.id = ?';
            
            $tpl->data = Database::Go()->rawQuery($sql, array(App::Auth()->uid))->first()->run();
            $tpl->countries = App::Core()->enable_tax ? Database::Go()->select(Content::cTable)->orderBy('sorting', 'DESC')->run() : null;
            $tpl->custom_fields = Content::renderCustomFields(App::Auth()->uid);
            
            $tpl->template = 'front/profile';
        }
        
        /**
         * downloads
         *
         * @return void
         */
        public function downloads(): void
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(SITEURL);
                exit;
            }
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $user = Database::Go()->select(User::mTable, array('membership_id', 'user_files'))->where('id', App::Auth()->uid, '=')->first()->run();
            $tpl->data = Database::Go()->rawQuery('SELECT * FROM  `' . Content::fTable . '` WHERE FIND_IN_SET(' . $user->membership_id . ', fileaccess) ORDER BY created DESC')->run();
            $tpl->userfiles = Database::Go()->rawQuery('SELECT * FROM  `' . Content::fTable . '` WHERE id IN(' . $user->user_files . ') ORDER BY created DESC')->run();
            
            $tpl->template = 'front/download';
        }
        
        /**
         * registration
         *
         * @return void
         * @throws NotFoundException
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function registration(): void
        {
            $validate = Validator::run($_POST);
            $core = App::Core();
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('password', Language::$word->M_PASSWORD)->required()->string()->min_len(8)->max_len(16)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('agree', Language::$word->PRIVACY)->required()->numeric()
                ->set('captcha', Language::$word->CAPTCHA)->required()->numeric()->equals(Session::get('wcaptcha'))->exact_len(5);
            
            if ($core->enable_tax) {
                $validate
                    ->set('address', Language::$word->M_ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set('city', Language::$word->M_CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set('zip', Language::$word->M_ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set('state', Language::$word->M_STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set('country', Language::$word->M_COUNTRY)->required()->string()->exact_len(2);
            }
            
            $safe = $validate->safe();
            
            if (strlen($safe->email)) {
                if (Auth::emailExists($safe->email)) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
                }
            }
            Content::verifyCustomFields();
            if (count(Message::$msgs) === 0) {
                $hash = Auth::doHash($safe->password);
                $username = Utility::randomString();
                
                if ($core->reg_verify == 1) {
                    $active = 't';
                } elseif ($core->auto_verify == 0) {
                    $active = 'n';
                } else {
                    $active = 'y';
                }
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'hash' => $hash,
                    'type' => 'member',
                    'token' => Utility::randNumbers(),
                    'active' => $active,
                    'userlevel' => 1,
                );
                
                if ($core->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['state'] = $safe->state;
                    $data['zip'] = $safe->zip;
                    $data['country'] = $safe->country;
                }
                
                $last_id = Database::Go()->insert(User::mTable, $data)->run();
                
                //Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                $dataArray = array();
                if ($fl_array) {
                    $fields = Database::Go()->select(Content::cfTable)->run();
                    foreach ($fields as $row) {
                        $dataArray[] = array(
                            'user_id' => $last_id,
                            'field_id' => $row->id,
                            'field_name' => $row->name,
                        );
                    }
                    Database::Go()->batch(User::cfTable, $dataArray)->run();
                    
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        Database::Go()->update(User::cfTable, $cfdata)->where('user_id', $last_id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                //Default membership
                if ($core->enable_dmembership) {
                    $row = Database::Go()->select(Membership::mTable)->where('id', $core->dmembership, '=')->first()->run();
                    $datam = array(
                        'txn_id' => 'MAN_' . Utility::randomString(12),
                        'membership_id' => $row->id,
                        'user_id' => $last_id,
                        'rate_amount' => $row->price,
                        'coupon' => 0,
                        'total' => $row->price,
                        'tax' => 0,
                        'currency' => $core->currency,
                        'ip' => Url::getIP(),
                        'pp' => 'MANUAL',
                        'status' => 1,
                    );
                    
                    $trans_id = Database::Go()->insert(Membership::pTable, $datam)->run();
                    
                    //insert user membership
                    $udata = array(
                        'transaction_id' => $trans_id,
                        'user_id' => $last_id,
                        'membership_id' => $row->id,
                        'expire' => Membership::calculateDays($row->id),
                        'recurring' => 0,
                        'active' => 1,
                    );
                    
                    //update user record
                    $xdata = array(
                        'membership_id' => $row->id,
                        'mem_expire' => $udata['expire'],
                    );
                    
                    Database::Go()->insert(Membership::umTable, $udata)->run();
                    Database::Go()->update(User::mTable, $xdata)->where('id', $last_id, '=')->run();
                }
                
                $mailer = Mailer::sendMail();
                
                if ($core->reg_verify == 1) {
                    $message = Language::$word->M_INFO7;
                    $json['redirect'] = SITEURL;
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'regMail', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[NAME]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[LINK]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        date('Y'),
                        $core->company,
                        $core->company,
                        $safe->fname . ' ' . $safe->lname,
                        $safe->email,
                        $safe->password,
                        Url::url('/activation', '?token=' . $data['token'] . '&email=' . $data['email']),
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL
                    ), $tpl->body);
                } elseif ($core->auto_verify == 0) {
                    $message = Language::$word->M_INFO7;
                    $json['redirect'] = SITEURL;
                    
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'regMailPending', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[NAME]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        date('Y'),
                        $core->company,
                        $core->company,
                        $safe->fname . ' ' . $safe->lname,
                        $safe->email,
                        $safe->password,
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL
                    ), $tpl->body);
                } else {
                    //login user
                    App::Auth()->login($safe->email, $safe->password, true);
                    $message = Language::$word->M_INFO8;
                    $json['redirect'] = Url::url('/dashboard');
                    
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'welcomeEmail', '=')->first()->run();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[NAME]',
                        '[USERNAME]',
                        '[PASSWORD]',
                        '[LINK]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        date('Y'),
                        $core->company,
                        $core->company,
                        $safe->fname . ' ' . $safe->lname,
                        $safe->email,
                        $safe->password,
                        Url::url(''),
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL
                    ), $tpl->body);
                }
                $mailer->Subject = $tpl->subject;
                $mailer->Body = $body;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                $mailer->isHTML();
                $mailer->send();
                
                if ($core->notify_admin) {
                    $mailer2 = Mailer::sendMail();
                    $tpl2 = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'notifyAdmin', '=')->first()->run();
                    
                    $body2 = str_replace(array(
                        '[LOGO]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[EMAIL]',
                        '[NAME]',
                        '[IP]',
                        '[FB]',
                        '[TW]',
                        '[CEMAIL]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        date('Y'),
                        $core->company,
                        $core->company,
                        $safe->email,
                        $data['fname'] . ' ' . $data['lname'],
                        Url::getIP(),
                        $core->social->facebook,
                        $core->social->twitter,
                        $core->site_email,
                        SITEURL
                    ), $tpl2->body);
                    
                    $mailer2->Subject = $tpl2->subject;
                    $mailer2->Body = $body2;
                    $mailer2->setFrom($core->site_email, $core->company);
                    $mailer2->addAddress($core->site_email, $core->company);
                    $mailer2->isHTML();
                    $mailer2->send();
                }
                if (Database::Go()->affected()) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = $message;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->M_INFO11;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * news
         *
         * @return void
         */
        public function news(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = App::Core()->company . ' - ' . Language::$word->NW_TITLE1;
            $tpl->keywords = null;
            $tpl->description = null;
            
            $tpl->data = Database::Go()->select(Content::nTable)->where('active', 1, '=')->orderBy('created', 'DESC')->run();
            
            $tpl->template = 'front/news';
        }
        
        /**
         * processContact
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processContact(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('name', Language::$word->CNT_NAME)->required()->string()->min_len(2)->max_len(60)
                ->set('notes', Language::$word->MESSAGE)->required()->string(true, true)->min_len(10)->max_len(200)
                ->set('subject', Language::$word->ET_SUBJECT)->string()
                ->set('phone', Language::$word->ET_SUBJECT)->string()
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('agree', Language::$word->PRIVACY)->required()->numeric()
                ->set('captcha', Language::$word->CAPTCHA)->required()->numeric()->exact_len(5);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $row = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'contact', '=')->first()->run();
                $mailer = Mailer::sendMail();
                $core = App::Core();
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[NAME]',
                    '[EMAIL]',
                    '[PHONE]',
                    '[MAILSUBJECT]',
                    '[MESSAGE]',
                    '[IP]',
                    '[DATE]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'
                ), array(
                    $core->plogo,
                    $safe->name,
                    $safe->email,
                    $safe->phone,
                    $safe->subject,
                    $safe->notes,
                    Url::getIP(),
                    date('Y'),
                    $core->company,
                    $core->company,
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL
                ), $row->body);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($core->site_email, $core->company);
                $mailer->addReplyTo($safe->email, $safe->name);
                
                $mailer->isHTML();
                $mailer->Subject = $row->subject;
                $mailer->Body = $body;
                
                if ($mailer->send()) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    //$json['redirect'] = Url::url('/page', $core->page_slugs->home[0]->page_type);
                    $json['redirect'] = Url::url('');
                    $json['message'] = Language::$word->CNT_OK;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->M_INFO11;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * password
         *
         * @param string $token
         * @return void
         */
        public function password(string $token): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/full/';
            $tpl->title = Language::$word->META_T31;
            $tpl->keywords = null;
            $tpl->description = null;
            
            if (!$row = Database::Go()->select(User::mTable)->where('token', $token, '=')->where('active', 'y', '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid token ' . ($token) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                    $tpl->template = 'front/error';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                    $tpl->title = Language::$word->META_ERROR;
                    $tpl->template = 'front/404';
                }
            } else {
                $tpl->row = $row;
                $tpl->crumbs = [array(0 => Language::$word->HOME, 1 => ''), Language::$word->M_PASSWORD_RES];
                
                $tpl->template = 'front/password';
            }
        }
        
        /**
         * passwordChange
         *
         * @return void
         */
        public function passwordChange(): void
        {
            $validate = Validator::run($_POST);
            
            $validate
                ->set('token', 'Token')->required()->string()
                ->set('password', Language::$word->NEWPASS)->required()->string()->min_len(8)->max_len(12);
            
            $safe = $validate->safe();
            
            if (!$row = Database::Go()->select(User::mTable, array('id', 'type'))->where('token', $safe->token, '=')->first()->run()) {
                Message::$msgs['token'] = 'Invalid Token.';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = 'Invalid Token.';
                $json['type'] = 'error';
            }
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'hash' => Auth::doHash($safe->password),
                    'token' => 0,
                );
                
                Database::Go()->update(User::mTable, $data)->where('id', $row->id, '=')->run();
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['redirect'] = ($row->type == 'member') ? Url::url('/login') : Url::url('/admin');
                $json['message'] = Language::$word->M_PASSUPD_OK2;
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * passReset
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function passReset(): void
        {
            $validate = Validator::run($_POST);
            
            $validate->set('email', Language::$word->M_EMAIL)->required()->email();
            $safe = $validate->safe();
            
            $json['type'] = 'error';
            $json['title'] = Language::$word->ERROR;
            $json['message'] = Language::$word->M_EMAIL_R4;
            
            if (strlen($safe->email)) {
                $row = Database::Go()->select(User::mTable, array('email', 'fname', 'lname', 'id'))
                    ->where('email', $safe->email, '=')
                    ->where('active', 'y', '=')
                    ->first()->run();
                if (!$row) {
                    Message::$msgs['fname'] = Language::$word->M_EMAIL_R4;
                }
            }
            
            if (count(Message::$msgs) === 0) {
                $row = Database::Go()->select(User::mTable, array('email', 'fname', 'lname', 'id', 'type'))
                    ->where('email', $safe->email, '=')
                    ->where('active', 'y', '=')
                    ->first()->run();
                
                $token = substr(md5(uniqid(rand(), true)), 0, 10);
                $template = ($row->type == 'member') ? 'userPassReset' : 'adminPassReset';
                $core = App::Core();
                $mailer = Mailer::sendMail();
                $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', $template, '=')->first()->run();
                
                $body = str_replace(array(
                    '[LOGO]',
                    '[NAME]',
                    '[DATE]',
                    '[COMPANY]',
                    '[SITE_NAME]',
                    '[LINK]',
                    '[IP]',
                    '[FB]',
                    '[TW]',
                    '[CEMAIL]',
                    '[SITEURL]'
                ), array(
                    $core->plogo,
                    $row->fname . ' ' . $row->lname,
                    date('Y'),
                    $core->company,
                    $core->company,
                    Url::url('/password', $token),
                    Url::getIP(),
                    $core->social->facebook,
                    $core->social->twitter,
                    $core->site_email,
                    SITEURL
                ), $tpl->body);
                
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->addAddress($row->email, $row->fname . ' ' . $row->lname);
                
                $mailer->isHTML();
                $mailer->Subject = $tpl->subject;
                $mailer->Body = $body;
                
                Database::Go()->update(User::mTable, array('token' => $token))->where('id', $row->id, '=')->run();
                
                if ($mailer->send()) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Language::$word->M_PASSWORD_RES_D;
                    print json_encode($json);
                }
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->M_EMAIL_R5;
                print json_encode($json);
            }
        }
        
        /**
         * activation
         *
         * @return void
         */
        public function activation(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/full/';
            $tpl->title = Language::$word->META_T31;
            $tpl->keywords = null;
            $tpl->description = null;
            
            if (Validator::get('token') and Validator::get('email')) {
                $validate = Validator::run($_GET);
                $validate
                    ->set('email', Language::$word->M_EMAIL)->required()->email()
                    ->set('token', Language::$word->M_INFO10)->required()->string();
                
                $safe = $validate->safe();
                if (count(Message::$msgs) === 0) {
                    if ($row = Database::Go()->select(User::mTable, array('id'))->where('email', $safe->email, '=')->where('token', $safe->token, '=')->first()->run()) {
                        Database::Go()->update(User::mTable, array('active' => 'y', 'token' => 0))->where('id', $row->id, '=')->run();
                        Url::redirect(Url::url('/activation', '?done=true'));
                    }
                }
            }
            
            $tpl->template = 'front/activation';
        }
        
        /**
         * updateProfile
         *
         * @return void
         */
        public function updateProfile(): void
        {
            $core = App::Core();
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('newsletter', 'Instagram')->numeric();
            
            if ($core->enable_tax) {
                $validate
                    ->set('address', Language::$word->M_ADDRESS)->required()->string()->min_len(3)->max_len(80)
                    ->set('city', Language::$word->M_CITY)->required()->string()->min_len(2)->max_len(60)
                    ->set('zip', Language::$word->M_ZIP)->required()->string()->min_len(3)->max_len(30)
                    ->set('state', Language::$word->M_STATE)->required()->string()->min_len(2)->max_len(60)
                    ->set('country', Language::$word->M_COUNTRY)->required()->string()->exact_len(2);
            }
            
            $thumb = File::upload('avatar', 512000, 'png,jpg,jpeg');
            
            Content::verifyCustomFields();
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'newsletter' => (strlen($safe->newsletter) ? 1 : 0)
                );
                
                if ($core->enable_tax) {
                    $data['address'] = $safe->address;
                    $data['city'] = $safe->city;
                    $data['zip'] = $safe->zip;
                    $data['state'] = $safe->state;
                    $data['country'] = $safe->country;
                }
                
                if (strlen($_POST['password'])) {
                    $data['hash'] = Auth::doHash($_POST['password']);
                }
                
                if (array_key_exists('avatar', $_FILES)) {
                    $thumbPath = UPLOADS . '/avatars/';
                    if (Auth::$udata->avatar != '') {
                        File::deleteFile(UPLOADS . '/avatars/' . Auth::$udata->avatar);
                    }
                    $result = File::process($thumb, $thumbPath, 'AVT_');
                    App::Auth()->avatar = Session::set('avatar', $result['fname']);
                    $data['avatar'] = $result['fname'];
                }
                
                Database::Go()->update(User::mTable, $data)->where('id', App::Auth()->uid, '=')->run();
                
                //Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        Database::Go()->update(User::cfTable, $cfdata)->where('user_id', App::Auth()->uid, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                Message::msgReply(true, 'success', str_replace('[NAME]', '', Language::$word->M_UPDATED));
                if (Database::Go()->affected()) {
                    Auth::$udata->email = Session::set('email', $data['email']);
                    Auth::$udata->fname = Session::set('fname', $data['fname']);
                    Auth::$udata->lname = Session::set('lname', $data['lname']);
                    Auth::$udata->name = Session::set('name', $data['fname'] . ' ' . $data['lname']);
                    if ($core->enable_tax) {
                        Auth::$udata->country = Session::set('country', $data['country']);
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * buyMembership
         *
         * @return void
         * @throws NotFoundException
         */
        public function buyMembership(): void
        {
            if ($row = Database::Go()->select(Membership::mTable)->where('id', Filter::$id, '=')->where('private', 1, '<')->first()->run()) {
                $gaterows = Database::Go()->select(Core::gTable)->where('active', 1, '=')->run();
                
                if ($row->price == 0) {
                    $data = array(
                        'membership_id' => $row->id,
                        'mem_expire' => Membership::calculateDays($row->id),
                    );
                    
                    Database::Go()->update(User::mTable, $data)->where('id', App::Auth()->uid, '=')->run();
                    Auth::$udata->membership_id = Session::set('membership_id', $row->id);
                    Auth::$udata->mem_expire = Session::set('mem_expire', $data['mem_expire']);
                    
                    $json['message'] = Message::msgSingleOk(str_replace('[NAME]', $row->title, Language::$word->M_INFO12));
                } else {
                    //$recurring = ($row->recurring) ? Language::$word->YES : Language::$word->NO;
                    Database::Go()->delete(Membership::cTable)->where('user_id', App::Auth()->uid, '=')->run();
                    $tax = Membership::calculateTax();
                    
                    $data = array(
                        'user_id' => App::Auth()->uid,
                        'membership_id' => $row->id,
                        'originalprice' => $row->price,
                        'tax' => Validator::sanitize($tax, 'float'),
                        'totaltax' => Validator::sanitize($row->price * $tax, 'float'),
                        'total' => $row->price,
                        'totalprice' => Validator::sanitize($tax * $row->price + $row->price, 'float'),
                    );
                    Database::Go()->insert(Membership::cTable, $data)->run();
                    $cart = Membership::getCart(App::Auth()->uid);
                    
                    $tpl = App::View(BASEPATH . 'view/front/snippets/');
                    $tpl->row = $row;
                    $tpl->gateways = $gaterows;
                    $tpl->cart = $cart;
                    $tpl->template = 'loadSummary';
                    $json['message'] = $tpl->render();
                }
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * getCoupon
         *
         * @return void
         */
        public function getCoupon(): void
        {
            $sql = 'SELECT * FROM `' . Content::dcTable . '` WHERE FIND_IN_SET(' . Filter::$id . ', membership_id) AND code = ? AND active = ?';
            if ($row = Database::Go()->rawQuery($sql, array(Validator::sanitize($_POST['code'], 'alphanumeric'), 1))->first()->run()) {
                $row2 = Database::Go()->select(Membership::mTable)->where('id', Filter::$id, '=')->first()->run();
                
                Database::Go()->delete(Membership::cTable)->where('user_id', App::Auth()->uid, '=')->run();
                $tax = Membership::calculateTax();
                
                if ($row->type == 'p') {
                    $disc = Validator::sanitize($row2->price / 100 * $row->discount, 'float');
                } else {
                    $disc = Validator::sanitize($row->discount, 'float');
                }
                $grand_total = Validator::sanitize($row2->price - $disc, 'float');
                
                $data = array(
                    'user_id' => App::Auth()->uid,
                    'membership_id' => $row2->id,
                    'coupon_id' => $row->id,
                    'tax' => Validator::sanitize($tax, 'float'),
                    'totaltax' => Validator::sanitize($grand_total * $tax, 'float'),
                    'coupon' => $disc,
                    'total' => $grand_total,
                    'originalprice' => Validator::sanitize($row2->price, 'float'),
                    'totalprice' => Validator::sanitize($tax * $grand_total + $grand_total, 'float'),
                );
                Database::Go()->insert(Membership::cTable, $data)->run();
                
                $json['type'] = 'success';
                $json['is_full'] = $row->discount;
                $json['disc'] = '- ' . Utility::formatMoney($disc);
                $json['tax'] = Utility::formatMoney($data['totaltax']);
                $json['grand_total'] = Utility::formatMoney($data['totalprice']);
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * activateCoupon
         *
         * @return void
         * @throws NotFoundException
         */
        public function activateCoupon(): void
        {
            $cart = Membership::getCart(App::Auth()->uid);
            if ($row = Database::Go()->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run()) {
                // insert payment record
                $data = array(
                    'txn_id' => time(),
                    'membership_id' => $row->id,
                    'user_id' => App::Auth()->uid,
                    'rate_amount' => $cart->total,
                    'coupon' => $cart->coupon,
                    'total' => $cart->totalprice,
                    'tax' => $cart->totaltax,
                    'currency' => App::Core()->currency,
                    'ip' => Url::getIP(),
                    'pp' => 'Coupon',
                    'status' => 1,
                );
                $last_id = Database::Go()->insert(Membership::pTable, $data)->run();
                
                //insert user membership
                $u_data = array(
                    'transaction_id' => $last_id,
                    'user_id' => App::Auth()->uid,
                    'membership_id' => $row->id,
                    'expire' => Membership::calculateDays($row->id),
                    'recurring' => 0,
                    'active' => 1,
                );
                
                //update user record
                $x_data = array(
                    'membership_id' => $row->id,
                    'mem_expire' => $u_data['expire'],
                );
                
                Database::Go()->insert(Membership::umTable, $u_data)->run();
                Database::Go()->update(User::mTable, $x_data)->where('id', App::Auth()->uid, '=')->run();
                Database::Go()->delete(Membership::cTable)->where('user_id', App::Auth()->uid, '=')->run();
                
                $json['type'] = 'success';
            } else {
                $json['type'] = 'error';
            }
            print json_encode($json);
        }
        
        /**
         * selectGateway
         *
         * @return void
         */
        public function selectGateway(): void
        {
            if ($cart = Membership::getCart(App::Auth()->uid)) {
                $gateway = Database::Go()->select(Core::gTable)->where('id', Filter::$id, '=')->where('active', 1, '=')->first()->run();
                $row = Database::Go()->select(Membership::mTable)->where('id', $cart->membership_id, '=')->first()->run();
                $tpl = App::View(BASEPATH . 'gateways/' . $gateway->dir . '/');
                $tpl->cart = $cart;
                $tpl->gateway = $gateway;
                $tpl->row = $row;
                $tpl->template = 'form';
                $json['message'] = $tpl->render();
            } else {
                $json['message'] = Message::msgSingleError(Language::$word->SYSERROR);
            }
            print json_encode($json);
        }
        
        /**
         * Validate
         *
         * @return void
         */
        public function validate(): void
        {
            if (!App::Auth()->is_User()) {
                Url::redirect(SITEURL);
                exit;
            }
            
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'front/';
            $tpl->title = str_replace('[COMPANY]', App::Core()->company, Language::$word->META_T28);
            
            $tpl->template = 'front/validate';
        }
    }
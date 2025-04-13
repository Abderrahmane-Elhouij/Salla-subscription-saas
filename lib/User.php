<?php
    /**
     * User Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: User.php, v1.00 7/5/2023 2:41 PM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class User
    {
        const mTable = 'users';
        const rTable = 'roles';
        const rpTable = 'role_privileges';
        const pTable = 'privileges';
        const blTable = 'banlist';
        const aTable = 'activity';
        const cfTable = 'user_custom_fields';
        
        /**
         * index
         *
         * @return void
         */
        public function index(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T2;
            $tpl->caption = Language::$word->META_T2;
            $tpl->subtitle = null;
            
            $where = match (App::Auth()->usertype) {
                'owner' => 'WHERE (type = \'staff\' || type = \'editor\' || type = \'member\' || type = \'sub_admin\')',
                'staff' => 'WHERE (type = \'editor\' || type = \'member\' || type = \'sub_admin\')',
                'sub_admin' => 'WHERE (type = \'member\')', 
                'editor' => 'WHERE (type = \'member\')',
                default => null,
            };
            
            $find = isset($_POST['find']) ? Validator::sanitize($_POST['find'], 'string', 20) : null;
            $counter = 0;
            $and = null;
            
            if (isset($_GET['letter']) and $find) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $counter = Database::Go()->count(User::mTable, "$where AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%' AND `fname` REGEXP '^" . $letter . "'")->run();
                $and = "AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%' AND `fname` REGEXP '^" . $letter . "'";
                
            } elseif (isset($_POST['find'])) {
                $counter = Database::Go()->count(User::mTable, "$where AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%'")->run();
                $and = "AND `fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%'";
                
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'string', 2);
                $and = "AND `fname` REGEXP '^" . $letter . "'";
                $counter = Database::Go()->count(User::mTable, "$where AND `fname` REGEXP '^" . $letter . "' LIMIT 1")->run();
            } else {
                if (isset($_GET['type'])) {
                    switch ($_GET['type']) {
                        case 'registered':
                            $counter = Database::Go()->count(User::mTable, "$where AND `type` = 'member'")->run();
                            $and = "AND u.type = 'member'";
                            $tpl->subtitle = Language::$word->AD_RUSER;
                            break;
                        case 'active':
                            $counter = Database::Go()->count(User::mTable, "$where AND `type` = 'member' AND active = 'y'")->run();
                            $and = "AND u.type = 'member' AND u.active = 'y'";
                            $tpl->subtitle = Language::$word->AD_AUSER;
                            break;
                        case 'pending':
                            $counter = Database::Go()->count(User::mTable, "$where AND `type` = 'member' AND active = 't'")->run();
                            $and = "AND u.type = 'member' AND u.active = 't'";
                            $tpl->subtitle = Language::$word->AD_PUSER;
                            break;
                        
                        case 'membership':
                            $counter = Database::Go()->count(User::mTable, "$where AND `type` = 'member' AND membership_id <> 0")->run();
                            $and = "AND u.type = 'member' AND u.membership_id <> 0";
                            $tpl->subtitle = Language::$word->AD_AMEM;
                            break;
                        
                        case 'expire':
                            $q = " AND `type` = 'member' AND MONTH(mem_expire) = MONTH(NOW()) AND YEAR(mem_expire) = YEAR(NOW()) AND membership_id > 0";
                            $counter = Database::Go()->count(User::mTable, "$where $q")->run();
                            $and = "AND u.type = 'member' AND MONTH(u.mem_expire) = MONTH(NOW()) AND YEAR(u.mem_expire) = YEAR(NOW()) AND u.membership_id > 0";
                            $tpl->subtitle = Language::$word->AD_AMEM;
                            break;
                    }
                } else {
                    $counter = Database::Go()->count(User::mTable)->run();
                }
            }
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'default', 13);
                $order = Validator::sanitize($order, 'default', 5);
                if (in_array($sort, array('fname', 'email', 'membership_id'))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = ' created DESC';
                }
            } else {
                $sorting = ' created DESC';
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT u.*,u.id as id,  u.active as active, CONCAT(fname,' ',lname) as fullname, m.title as mtitle, m.thumb
            FROM   `" . self::mTable . '` as u
            LEFT JOIN ' . Membership::mTable . " as m on m.id = u.membership_id
            $where
            $and
            ORDER BY $sorting" . $pager->limit;
            
            $tpl->data = Database::Go()->rawQuery($sql)->run();
            $tpl->pager = $pager;
            $tpl->template = 'admin/user';
        }
        
        /**
         * edit
         *
         * @param int $id
         * @return void
         */
        public function edit(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T3;
            $tpl->caption = Language::$word->META_T3;
            $tpl->crumbs = ['admin', 'users', 'edit'];
            
            if (!$row = Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $tpl->data = $row;
                $tpl->memberships = App::Membership()->getMembershipList();
                $tpl->countries = App::Content()->getCountryList();
                $tpl->custom_fields = Content::renderCustomFields($id);
                $tpl->userfiles = Database::Go()->select(Content::fTable)->run();
                
                $tpl->template = 'admin/user';
            }
        }
        
        public function history(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T5;
            $tpl->caption = Language::$word->META_T5;
            $tpl->crumbs = ['admin', 'users', 'history'];
            
            if (!$row = Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $tpl->data = $row;
                $tpl->mlist = Stats::userHistory($id);
                $tpl->plist = Stats::userPayments($id);
                
                $tpl->template = 'admin/user';
            }
        }
        
        /**
         * save
         *
         * @return void
         */
        public function save(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T4;
            $tpl->caption = Language::$word->META_T4;
            
            $tpl->memberships = App::Membership()->getMembershipList();
            $tpl->countries = App::Content()->getCountryList();
            $tpl->custom_fields = Content::renderCustomFields(0);
            $tpl->userfiles = Database::Go()->select(Content::fTable)->run();
            $tpl->template = 'admin/user';
        }
        
        /**
         * processUser
         *
         * @return void
         * @throws NotFoundException
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processUser(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('type', Language::$word->M_SUB9)->required()->string()
                ->set('active', Language::$word->STATUS)->required()->string()->exact_len(1)->lowercase()
                ->set('newsletter', Language::$word->M_SUB10)->required()->numeric()
                ->set('membership_id', Language::$word->M_SUB8)->numeric()
                ->set('notes', Language::$word->M_SUB11)->string()
                ->set('address', Language::$word->M_ADDRESS)->string()
                ->set('city', Language::$word->M_CITY)->string()
                ->set('state', Language::$word->M_STATE)->string()
                ->set('zip', Language::$word->M_ZIP)->string()
                ->set('country', Language::$word->M_COUNTRY)->string();
            
            if (Validator::post('extend_membership')) {
                $validate->set('mem_expire_submit', Language::$word->M_SUB15)->date();
            }
            
            if (Validator::post('add_trans')) {
                if ($_POST['membership_id'] < 1) {
                    Message::$msgs['membership_id'] = Language::$word->M_SUB24;
                }
                if (!array_key_exists('update_membership', $_POST)) {
                    Message::$msgs['update_membership'] = Language::$word->M_SUB25;
                }
            }
            
            (Filter::$id) ? $this->_updateUser($validate) : $this->_addUser($validate);
        }
        
        /**
         * _updateUser
         *
         * @param Validator $validate
         * @return void
         * @throws NotFoundException
         * @throws \PHPMailer\PHPMailer\Exception
         */
        private function _updateUser(Validator $validate): void
        {
            $safe = $validate->safe();
            
            Content::verifyCustomFields();
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'type' => $safe->type,
                    'active' => $safe->active,
                    'user_files' => array_key_exists('user_files', $_POST) ? Utility::implodeFields($_POST['user_files']) : 0,
                    'newsletter' => $safe->newsletter,
                    'notes' => $safe->notes,
                    'userlevel' => ($safe->type == 'staff' ? 8 : ($safe->type == 'editor' ? 7 : ($safe->type == 'sub_admin' ? 6 : 1))),
                );
                
                if (strlen($_POST['password']) !== 0) {
                    $data['hash'] = App::Auth()->doHash($_POST['password']);
                }
                $valid_until = null;
                $mname = null;
                
                if (array_key_exists('update_membership', $_POST)) {
                    if ($_POST['membership_id'] > 0) {
                        $valid_until = $data['mem_expire'] = Membership::calculateDays($safe->membership_id);
                        $mname = Database::Go()->select(Membership::mTable, array('title'))->where('id', $safe->membership_id, '=')->one()->run();
                        $data['membership_id'] = $safe->membership_id;
                        Database::Go()->delete(Core::cjTable)->where('user_id', Filter::$id, '=')->run();
                    } else {
                        $data['membership_id'] = 0;
                    }
                }
                
                if (array_key_exists('extend_membership', $_POST)) {
                    $valid_until = $data['mem_expire'] = Database::toDate($safe->mem_expire_submit);
                }
                
                Database::Go()->update(self::mTable, $data)->where('id', Filter::$id, '=')->run();
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        Database::Go()->update(self::cfTable, $cfdata)->where('user_id', Filter::$id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Language::$word->M_UPDATED);
                Message::msgReply(true, 'success', $message);
                
                if (array_key_exists('notify_user', $_POST) && array_key_exists('update_membership', $_POST)) {
                    $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'memUpdated', '=')->first()->run();
                    $mailer = Mailer::sendMail();
                    $core = App::Core();
                    
                    $body = str_replace(array(
                        '[LOGO]',
                        '[EMAIL]',
                        '[NAME]',
                        '[DATE]',
                        '[COMPANY]',
                        '[SITE_NAME]',
                        '[MEMBERSHIP]',
                        '[EXPIRE]',
                        '[LINK]',
                        '[CEMAIL]',
                        '[FB]',
                        '[TW]',
                        '[SITEURL]'
                    ), array(
                        $core->plogo,
                        $data['email'],
                        $data['fname'] . ' ' . $data['lname'],
                        date('Y'),
                        $core->company,
                        $core->company,
                        $mname,
                        Date::doDate('short_date', $valid_until),
                        Url::url('/login'),
                        $core->site_email,
                        $core->social->facebook,
                        $core->social->twitter,
                        SITEURL
                    ), $tpl->body);
                    
                    $mailer->Subject = $tpl->subject;
                    $mailer->Body = $body;
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                    $mailer->isHTML();
                    $mailer->send();
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * _addUser
         *
         * @param Validator $validate
         * @return void
         * @throws NotFoundException
         * @throws \PHPMailer\PHPMailer\Exception
         */
        private function _addUser(Validator $validate): void
        {
            $validate->set('password', Language::$word->M_PASSWORD)->required()->string()->min_len(6)->max_len(20);
            
            $safe = $validate->safe();
            
            if (strlen($safe->email) !== 0) {
                if (App::Auth()->emailExists($safe->email)) {
                    Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
                }
            }
            
            Content::verifyCustomFields();
            
            if (count(Message::$msgs) === 0) {
                $hash = App::Auth()->doHash($safe->password);
                $username = Utility::randomString();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'address' => $safe->address,
                    'city' => $safe->city,
                    'state' => $safe->state,
                    'zip' => $safe->zip,
                    'country' => $safe->country,
                    'hash' => $hash,
                    'type' => $safe->type,
                    'active' => $safe->active,
                    'user_files' => array_key_exists('user_files', $_POST) ? Utility::implodeFields($_POST['user_files']) : 0,
                    'newsletter' => $safe->newsletter,
                    'notes' => $safe->notes,
                    'userlevel' => ($safe->type == 'staff' ? 8 : ($safe->type == 'editor' ? 7 : ($safe->type == 'sub_admin' ? 6 : 1))),
                    'created_by' => App::Auth()->usertype == 'sub_admin' ? App::Auth()->uid : null,
                );
                
                if ($_POST['membership_id'] > 0) {
                    $data['mem_expire'] = Membership::calculateDays($safe->membership_id);
                    $data['membership_id'] = $safe->membership_id;
                }
                
                if (Validator::post('extend_membership')) {
                    $data['mem_expire'] = Database::toDate($safe->mem_expire_submit);
                }
                
                $last_id = Database::Go()->insert(User::mTable, $data)->run();
                
                //manual transaction
                if (Validator::post('add_trans')) {
                    $mem = Database::Go()->select(Membership::mTable)->where('id', $safe->membership_id, '=')->first()->run();
                    $tax = Membership::calculateTax();
                    $datax = array(
                        'txn_id' => 'MAN_' . time(),
                        'membership_id' => $safe->membership_id,
                        'user_id' => $last_id,
                        'rate_amount' => $mem->price,
                        'total' => Validator::sanitize($mem->price + ($mem->price * $tax), 'float'),
                        'tax' => Validator::sanitize(($mem->price * $tax), 'float'),
                        'currency' => App::Core()->currency,
                        'ip' => Url::getIP(),
                        'pp' => 'MANUAL',
                        'status' => 1,
                    );
                    
                    $last_idx = Database::Go()->insert(Membership::pTable, $datax)->run();
                    
                    //insert user membership
                    $udata = array(
                        'transaction_id' => $last_idx,
                        'user_id' => $last_id,
                        'membership_id' => $safe->membership_id,
                        'expire' => Membership::calculateDays($safe->membership_id),
                        'recurring' => 0,
                        'active' => 1,
                    );
                    Database::Go()->insert(Membership::umTable, $udata)->run();
                }
                
                // Start Custom Fields
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
                    Database::Go()->batch(self::cfTable, $dataArray)->run();
                    
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        Database::Go()->update(self::cfTable, $cfdata)->where('user_id', $last_id, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                if ($last_id) {
                    $message = Message::formatSuccessMessage($data['fname'] . ' ' . $data['lname'], Language::$word->M_ADDED);
                    Message::msgReply(true, 'success', $message);
                    
                    if (Validator::post('notify') && intval($_POST['notify']) == 1) {
                        $tpl = Database::Go()->select(Content::eTable, array('body', 'subject'))->where('typeid', 'regMailAdmin', '=')->first()->run();
                        $pass = Validator::cleanOut($_POST['password']);
                        $mailer = Mailer::sendMail();
                        $core = App::Core();
                        
                        $body = str_replace(array(
                            '[LOGO]',
                            '[EMAIL]',
                            '[NAME]',
                            '[DATE]',
                            '[COMPANY]',
                            '[SITE_NAME]',
                            '[USERNAME]',
                            '[PASSWORD]',
                            '[LINK]',
                            '[CEMAIL]',
                            '[FB]',
                            '[TW]',
                            '[SITEURL]'
                        ), array(
                            $core->plogo,
                            $data['email'],
                            $data['fname'] . ' ' . $data['lname'],
                            date('Y'),
                            $core->company,
                            $core->company,
                            $username,
                            $pass,
                            Url::url('/login'),
                            $core->site_email,
                            $core->social->facebook,
                            $core->social->twitter,
                            SITEURL
                        ), $tpl->body);
                        
                        $mailer->Subject = $tpl->subject;
                        $mailer->Body = $body;
                        $mailer->setFrom($core->site_email, $core->company);
                        $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                        $mailer->isHTML();
                        $mailer->send();
                    }
                }
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * getPrivileges
         *
         * @param int $id
         * @return mixed
         */
        public function getPrivileges(int $id): mixed
        {
            $sql = '
            SELECT rp.id, rp.active, p.id as prid, p.name, p.type, p.description, p.mode
              FROM `' . self::rpTable . '` as rp
              INNER JOIN `' . self::rTable . '` as r ON rp.rid = r.id
              INNER JOIN `' . self::pTable . '` as p ON rp.pid = p.id
              WHERE rp.rid = ?
              ORDER BY p.type
            ';
            
            return Database::Go()->rawQuery($sql, array($id))->run();
        }
        
        /**
         * updateRoleDescription
         *
         * @return void
         */
        public function updateRoleDescription(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('name', Language::$word->NAME)->required()->string()->string()->min_len(4)->max_len(20)
                ->set('description', Language::$word->DESCRIPTION)->required()->string()->min_len(10)->max_len(150);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'name' => $safe->name,
                    'description' => $safe->description
                );
                
                Database::Go()->update(User::rTable, $data)->where('id', Filter::$id, '=')->run();
                Message::msgModalReply(Database::Go()->affected(), 'success', Language::$word->M_INFO2, Validator::truncate($data['description'], 100));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * getInvoice
         *
         * @param int $id
         * @return mixed
         */
        public static function getInvoice(int $id): mixed
        {
            $sql = "
            SELECT p.*, m.title, m.description, DATE_FORMAT(p.created, '%Y%m%d - %H%m') as invid
            FROM `" . Membership::pTable . '` as p
            LEFT JOIN ' . Membership::mTable . ' as m ON m.id = p.membership_id
            WHERE p.id = ?
            AND p.user_id = ?
            AND p.status = ?';
            
            return Database::Go()->rawQuery($sql, array($id, App::Auth()->uid, 1))->first()->run();
        }
    }
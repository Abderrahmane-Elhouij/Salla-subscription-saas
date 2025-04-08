<?php
    /**
     * Admin Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Admin.php, v1.00 7/1/2023 10:23 PM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Admin
    {
        
        /**
         * index
         *
         * @return void
         */
        public function index(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            
            $sql = '
                SELECT COUNT(*) AS total,
                  COUNT(CASE WHEN type = ? THEN 1  END) AS users,
                  COUNT(CASE WHEN type = ? AND active = ? THEN 1  END) AS active,
                  COUNT(CASE WHEN type = ? AND active = ? THEN 1  END) AS pending,
                  COUNT(CASE WHEN type = ? AND membership_id >= 1 THEN 1  END) AS memberships
                  FROM `' . User::mTable . '`
                ';
            
            $tpl->data = Database::Go()->rawQuery($sql, array('member', 'member', 'y', 'member', 't', 'member'))->first()->run();
            $tpl->memberships = Stats::MembershipsExpireMonth();
            
            $tpl->template = 'admin/index';
            $tpl->title = Language::$word->META_T1;
        }
        
        /**
         * account
         *
         * @return void
         */
        public function account(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->M_TITLE;
            $tpl->caption = Language::$word->M_TITLE;
            $tpl->crumbs = ['admin', Language::$word->M_TITLE];
            
            $tpl->data = Database::Go()->select(User::mTable)->where('id', App::Auth()->uid, '=')->first()->run();
            $tpl->custom_fields = Content::renderCustomFields(App::Auth()->uid);
            
            $tpl->template = 'admin/account';
        }
        
        /**
         * UpdateAccount
         *
         * @return void
         */
        public function updateAccount(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(2)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email();
            
            $safe = $validate->safe();
            
            $thumb = File::upload('avatar', 512000, 'png,jpg,jpeg');
            
            Content::verifyCustomFields();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname
                );
                
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
                if (Database::Go()->affected()) {
                    App::Auth()->fname = Session::set('fname', $data['fname']);
                    App::Auth()->lname = Session::set('lname', $data['lname']);
                    App::Auth()->email = Session::set('email', $data['email']);
                }
                
                // Start Custom Fields
                $fl_array = Utility::array_key_exists_wildcard($_POST, 'custom_*', 'key-value');
                if ($fl_array) {
                    foreach ($fl_array as $key => $val) {
                        $cfdata['field_value'] = Validator::sanitize($val);
                        Database::Go()->update(User::cfTable, $cfdata)->where('id', App::Auth()->uid, '=')->where('field_name', str_replace('custom_', '', $key), '=')->run();
                    }
                }
                
                $message = str_replace('[NAME]', '', Language::$word->M_UPDATED);
                Message::msgReply(true, 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * password
         *
         * @return void
         */
        public function password(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->M_SUB2;
            $tpl->caption = Language::$word->M_SUB2;
            $tpl->crumbs = ['admin', Language::$word->M_TITLE];
            
            $tpl->template = 'admin/password';
        }
        
        /**
         * updateAdminPassword
         *
         * @return void
         */
        public function updateAdminPassword(): void
        {
            
            $validate = Validator::run($_POST);
            $validate->set('password', Language::$word->NEWPASS)->required()->string()->min_len(6)->max_len(20);
            $validate->set('password2', Language::$word->CONPASS)->required()->string()->equals($_POST['password'])->min_len(6)->max_len(20);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data['hash'] = Auth::doHash($safe->password);
                
                Database::Go()->update(User::mTable, $data)->where('id', App::Auth()->uid, '=')->run();
                Message::msgReply(Database::Go()->affected(), 'success', Language::$word->M_PASSUPD_OK);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * gatewayIndex
         *
         * @return void
         */
        public function gatewayIndex(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T22;
            $tpl->caption = Language::$word->META_T22;
            $tpl->subtitle = Language::$word->GW_SUB;
            
            $tpl->data = Database::Go()->select(Core::gTable)->run();
            
            $tpl->template = 'admin/gateway';
        }
        
        /**
         * gatewayEdit
         *
         * @param int $id
         * @return void
         */
        public function gatewayEdit(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->GW_TITLE1;
            $tpl->caption = Language::$word->GW_TITLE1;
            $tpl->crumbs = ['admin', 'gateways', 'edit'];
            
            if (!$row = Database::Go()->select(Core::gTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $tpl->data = $row;
                $tpl->template = 'admin/gateway';
            }
        }
        
        public function processGateway(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('displayname', Language::$word->GW_NAME)->required()->string()->min_len(3)->max_len(60)
                ->set('extra', Language::$word->GW_NAME)->required()->string()
                ->set('extra2', Language::$word->GW_NAME)->string()
                ->set('extra3', Language::$word->GW_NAME)->string()
                ->set('live', Language::$word->GW_LIVE)->numeric()
                ->set('active', Language::$word->ACTIVE)->numeric();
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'displayname' => $safe->displayname,
                    'extra' => $safe->extra,
                    'extra2' => $safe->extra2,
                    'extra3' => $safe->extra3,
                    'live' => $safe->live,
                    'active' => $safe->active,
                );
                
                Database::Go()->update(Core::gTable, $data)->where('id', Filter::$id, '=')->run();
                Message::msgReply(Database::Go()->affected(), 'success', Message::formatSuccessMessage($data['displayname'], Language::$word->GW_UPDATED));
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * role
         *
         * @return void
         */
        public function role(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->M_TITLE1;
            $tpl->caption = Language::$word->M_TITLE1;
            $tpl->subtitle = Language::$word->M_SUB3;
            
            $tpl->data = Database::Go()->select(User::rTable)->run();
            
            $tpl->template = 'admin/role';
        }
        
        /**
         * roleEdit
         *
         * @param int $id
         * @return void
         */
        public function roleEdit(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->M_TITLE2;
            $tpl->caption = Language::$word->M_TITLE2;
            $tpl->subtitle = null;
            $tpl->crumbs = ['admin', 'roles', Language::$word->M_TITLE1];
            
            if (!$row = Database::Go()->select(User::rTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $tpl->role = $row;
                $tpl->result = Utility::groupToLoop(App::User()->getPrivileges($id), 'type');
                
                $tpl->subtitle = str_replace('[ROLE]', '<span class="text-weight-500">' . $row->name . '</span>', Language::$word->M_SUB4);
                $tpl->subtitle .= ($row->code != 'owner') ? '<span class="text-weight-500"><i>' . Language::$word->M_INFO . '</i></span>' : null;
                
                $tpl->template = 'admin/role';
            }
        }
        
        /**
         * fileIndex
         *
         * @return void
         */
        public function fileIndex(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T35;
            $tpl->caption = Language::$word->META_T35;
            $tpl->subtitle = str_replace('[LIMIT]', '<span class="wojo bold positive text">' . ini_get('upload_max_filesize') . '</span>', Language::$word->FM_INFO);
            
            if (isset($_GET['letter']) and isset($_GET['type'])) {
                $letter = Validator::sanitize($_GET['letter'], 'default', 2);
                $type = Validator::sanitize($_GET['type'], 'alpha', 10);
                
                if (in_array($type, array('audio', 'video', 'archive', 'document', 'image',))) {
                    $where = "WHERE `type` = '$type' AND `alias` REGEXP '^" . $letter . "'";
                } else {
                    $where = "WHERE `alias` REGEXP '^" . $letter . "'";
                }
                $counter = Database::Go()->count(Content::fTable, $where)->run();
            } elseif (isset($_GET['type'])) {
                $type = Validator::sanitize($_GET['type'], 'alpha', 10);
                if (in_array($type, array('audio', 'video', 'archive', 'document', 'image',))) {
                    $where = "WHERE `type` = '$type'";
                    $counter = Database::Go()->count(Content::fTable, "WHERE `type` = '$type'")->run();
                } else {
                    $where = null;
                    $counter = Database::Go()->count(Content::fTable)->run();
                }
            } elseif (isset($_GET['letter'])) {
                $letter = Validator::sanitize($_GET['letter'], 'default', 2);
                $where = "WHERE `alias` REGEXP '^" . $letter . "'";
                $counter = Database::Go()->count(Content::fTable, $where)->run();
            } else {
                $where = null;
                $counter = Database::Go()->count(Content::fTable)->run();
            }
            
            if (isset($_GET['order']) and count(explode('|', $_GET['order'])) == 2) {
                list($sort, $order) = explode('|', $_GET['order']);
                $sort = Validator::sanitize($sort, 'string', 16);
                $order = Validator::sanitize($order, 'string', 4);
                if (in_array($sort, array('name', 'alias', 'filesize'))) {
                    $ord = ($order == 'DESC') ? ' DESC' : ' ASC';
                    $sorting = $sort . $ord;
                } else {
                    $sorting = 'created DESC';
                }
            } else {
                $sorting = 'created DESC';
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = 'SELECT * FROM `' . Content::fTable . "` $where ORDER BY $sorting" . $pager->limit;
            $tpl->data = Database::Go()->rawQuery($sql)->run();
            $tpl->pager = $pager;
            
            $tpl->template = 'admin/file';
        }
        
        /**
         * renameFile
         *
         * @return void
         */
        public function renameFile(): void
        {
            $validate = Validator::run($_POST);
            $validate->set('alias', Language::$word->FM_ALIAS)->required()->string()->min_len(3)->max_len(60);
            
            $safe = $validate->safe();
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'alias' => $safe->alias,
                    'fileaccess' => array_key_exists('fileaccess', $_POST) ? Utility::implodeFields($_POST['fileaccess']) : 0,
                );
                
                Database::Go()->update(Content::fTable, $data)->where('id', Filter::$id, '=')->run();
                $row = Database::Go()->select(Content::fTable)->where('id', Filter::$id, '=')->first()->run();
                
                $tpl = App::View(BASEPATH . 'view/admin/snippets/');
                $tpl->template = 'loadFile';
                $tpl->row = $row;
                
                if (Database::Go()->affected()) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = Message::formatSuccessMessage($data['alias'], Language::$word->FM_REN_OK);
                    $json['html'] = $tpl->render();
                } else {
                    $json['type'] = 'alert';
                    $json['title'] = Language::$word->ALERT;
                    $json['message'] = Language::$word->NOPROCCESS;
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * system
         *
         * @return void
         */
        public function system(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->SYS_TITLE;
            $tpl->caption = Language::$word->SYS_TITLE;
            $tpl->subtitle = str_replace('[VER]', App::Core()->wojov, Language::$word->SYS_INFO);
            
            $_oSTH = Database::Go()->prepare('SHOW TABLES FROM ' . DB_DATABASE);
            $_oSTH->execute();
            $tpl->data = $_oSTH->fetchAll(PDO::FETCH_COLUMN);
            
            $tpl->template = 'admin/system';
        }
        
        /**
         * backup
         *
         * @return void
         */
        public function backup(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->DBM_TITLE;
            $tpl->caption = Language::$word->DBM_TITLE;
            $tpl->subtitle = Language::$word->DBM_INFO;
            
            $tpl->dbdir = UPLOADS . '/backups/';
            $tpl->data = File::findFiles($tpl->dbdir, array('fileTypes' => array('sql'), 'returnType' => 'fileOnly'));
            
            $tpl->template = 'admin/backup';
        }
        
        /**
         * utilities
         *
         * @return void
         */
        public function utilities(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T23;
            $tpl->caption = Language::$word->META_T23;
            $tpl->subtitle = Language::$word->MT_INFO;
            
            $tpl->banned = Database::Go()->count(User::mTable)->where('active', 'b', '=')->where('type', 'member', '=')->limit(1)->run();
            
            $tpl->template = 'admin/utility';
        }
        
        /**
         * mailer
         *
         * @return void
         */
        public function mailer(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T24;
            $tpl->caption = Language::$word->META_T24;
            $tpl->subtitle = Language::$word->NL_INFO1;
            $type = Validator::get('email') ? 'singleMail' : 'newsletter';
            $tpl->data = Database::Go()->select(Content::eTable)->where('typeid', $type, '=')->first()->run();
            
            $tpl->template = 'admin/mailer';
        }
        
        /**
         * processMailer
         *
         * @return void
         * @throws \PHPMailer\PHPMailer\Exception
         */
        public function processMailer(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('subject', Language::$word->NL_SUBJECT)->required()->string()->min_len(3)->max_len(100)
                ->set('recipient', Language::$word->NL_RCPT)->required()->string()
                ->set('body', Language::$word->ET_DESC)->text('advanced');
            
            $safe = $validate->safe();
            
            $userrow = false;
            $row = false;
            
            $upl = Upload::instance(20971520, 'zip,jpg,pdf,doc,docx');
            
            if (count(Message::$msgs) === 0) {
                $to = $safe->recipient;
                $subject = $safe->subject;
                $body = Validator::cleanOut($safe->body);
                $numSent = 0;
                $failedRecipients = array();
                $core = App::Core();
                
                $mailer = Mailer::sendMail();
                $mailer->Subject = $subject;
                $mailer->setFrom($core->site_email, $core->company);
                $mailer->isHTML();
                
                if (array_key_exists('attachment', $_FILES)) {
                    $upl->process('attachment', UPLOADS . '/attachments/', 'ATT_');
                    $attachment = '<a href="' . UPLOADURL . '/attachments/' . $upl->fileInfo['fname'] . '">' . Language::$word->NL_ATTACH . '</a>';
                } else {
                    $attachment = '';
                }
                
                switch ($to) {
                    case 'all':
                        $userrow = Database::Go()->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('active', 'y', '=')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'free':
                        $userrow = Database::Go()->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('membership_id', 1, '<')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'paid':
                        $userrow = Database::Go()->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('membership_id', 0, '>')->where('type', 'member', '=')->run();
                        break;
                    
                    case 'newsletter':
                        $userrow = Database::Go()->select(User::mTable, array('email', 'CONCAT(fname," ",lname) as name'))->where('newsletter', 1, '=')->where('type', 'member', '=')->run();
                        break;
                    
                    default:
                        $row = Database::Go()->select(User::mTable, array("email, CONCAT(fname,' ',lname) as name"))->where('email', "%$to%", 'LIKE')->first()->run();
                        break;
                }
                switch ($to) {
                    case 'all':
                    case 'free':
                    case 'paid':
                    case 'newsletter':
                        if ($userrow) {
                            foreach ($userrow as $row) {
                                $mailer->Body = str_replace(array(
                                    '[LOGO]',
                                    '[NAME]',
                                    '[DATE]',
                                    '[COMPANY]',
                                    '[SITE_NAME]',
                                    '[ATTACHMENT]',
                                    '[FB]',
                                    '[TW]',
                                    '[CEMAIL]',
                                    '[SITEURL]'
                                ), array(
                                    $core->plogo,
                                    $row->name,
                                    date('Y'),
                                    $core->company,
                                    $core->company,
                                    $attachment,
                                    $core->social->facebook,
                                    $core->social->twitter,
                                    $core->site_email,
                                    SITEURL
                                ), $body);
                                
                                //$mailer->Body = $html;
                                $mailer->addAddress($row->email, $row->name);
                                
                                try {
                                    $mailer->send();
                                    $numSent++;
                                } catch (Exception) {
                                    $failedRecipients[] = htmlspecialchars($row->email);
                                    $mailer->getSMTPInstance()->reset();
                                }
                                $mailer->clearAddresses();
                                $mailer->clearAttachments();
                            }
                            unset($row);
                        }
                        break;
                    
                    default:
                        if ($row) {
                            $newbody = str_replace(array(
                                '[LOGO]',
                                '[COMPANY]',
                                '[SITE_NAME]',
                                '[NAME]',
                                '[SITEURL]',
                                '[ATTACHMENT]',
                                '[FB]',
                                '[TW]',
                                '[CEMAIL]',
                                '[DATE]'
                            ), array(
                                $core->plogo,
                                $core->company,
                                $core->site_name,
                                $row->name,
                                SITEURL,
                                $attachment,
                                $core->social->facebook,
                                $core->social->twitter,
                                $core->site_email,
                                date('Y')
                            ), $body);
                            
                            $mailer->addAddress($to, $row->name);
                            $mailer->Body = $newbody;
                            
                            $numSent++;
                            $mailer->send();
                        }
                        break;
                }
                if ($numSent) {
                    $json['type'] = 'success';
                    $json['title'] = Language::$word->SUCCESS;
                    $json['message'] = $numSent . ' ' . Language::$word->NL_SENT;
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $res = '<ul>';
                    foreach ($failedRecipients as $failed) {
                        $res .= '<li>' . $failed . '</li>';
                    }
                    $res .= '</ul>';
                    $json['message'] = Language::$word->NL_ALERT . $res;
                    
                    unset($failed);
                }
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * transactions
         *
         * @return void
         */
        public function transactions(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->TRX_PAY;
            $tpl->caption = Language::$word->TRX_PAY;
            $tpl->crumbs = ['admin', Language::$word->M_TITLE];
            
            $enddate = (Validator::get('enddate_submit') && $_GET['enddate_submit'] <> '') ? Validator::sanitize(Database::toDate($_GET['enddate_submit'], false)) : date('Y-m-d');
            $fromdate = Validator::get('fromdate_submit') ? Validator::sanitize(Database::toDate($_GET['fromdate_submit'], false)) : null;
            
            if (Validator::get('fromdate_submit') && $_GET['fromdate_submit'] <> '') {
                $counter = Database::Go()->count(Membership::pTable, "WHERE `created` BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND status = 1")->run();
                $where = "WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59' AND p.status = 1";
            } else {
                $counter = Database::Go()->count(Membership::pTable)->run();
                $where = null;
            }
            
            $pager = Paginator::instance();
            $pager->items_total = $counter;
            $pager->default_ipp = App::Core()->perpage;
            $pager->path = Url::url(Router::$path, '?');
            $pager->paginate();
            
            $sql = "
            SELECT p.*, m.title, CONCAT(u.fname,' ',u.lname) AS name
              FROM `" . Membership::pTable . '` AS p
              LEFT JOIN ' . User::mTable . ' AS u ON p.user_id = u.id
              LEFT JOIN ' . Membership::mTable . " AS m ON p.membership_id = m.id
              $where
              ORDER BY created
              DESC " . $pager->limit;
            
            $row = Database::Go()->rawQuery($sql)->run();
            $tpl->data = $row ?? null;
            $tpl->pager = $pager;
            
            $tpl->template = 'admin/transaction';
        }
        
        /**
         * help
         *
         * @return void
         */
        public function help(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->HP_TITLE;
            $tpl->caption = Language::$word->HP_TITLE;
            $tpl->subtitle = Language::$word->HP_INFO;
            
            $tpl->template = 'admin/help';
        }
        
        /**
         * trash
         *
         * @return void
         */
        public function trash(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T26;
            $tpl->caption = Language::$word->META_T26;
            $tpl->subtitle = Language::$word->TRS_INFO;
            $data = Database::Go()->select(Core::txTable)->run();
            $tpl->data = Utility::groupToLoop($data, 'type');
            
            $tpl->template = 'admin/trash';
        }
    }

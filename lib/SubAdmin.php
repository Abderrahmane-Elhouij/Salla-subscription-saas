<?php
/**
 * SubAdmin Class
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: SubAdmin.php, v1.00 4/13/2025 2:41 PM
 *
 */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}

class SubAdmin extends Admin
{
    /**
     * SubAdmin constructor
     */
    public function __construct()
    {
        // The parent Admin class doesn't have a constructor, so we don't call parent::__construct()
        // We can initialize things needed for SubAdmin here
    }

    /**
     * index
     * Main dashboard for sub-admin users
     * @return void
     */
    public function index(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T1;
        $tpl->crumbs = ['sub_admin'];
        
        $tpl->stats = new Stats();
        $tpl->core = App::Core();
        
        $tpl->totalMemberships = $this->getSubAdminMembershipCount();
        $tpl->totalUsers = $this->getSubAdminUserCount();
        $tpl->totalRevenue = $this->getSubAdminRevenue();
        
        $tpl->template = 'sub_admin/index';
    }
    
    /**
     * getSubAdminMembershipCount
     * Get count of memberships created by this sub-admin
     * @return int
     */
    private function getSubAdminMembershipCount(): int
    {
        return Database::Go()->count(Membership::mTable)
            ->where('created_by', App::Auth()->uid, '=')
            ->run();
    }
    
    /**
     * getSubAdminUserCount
     * Get count of users created by this sub-admin
     * @return int
     */
    private function getSubAdminUserCount(): int
    {
        return Database::Go()->count(User::mTable)
            ->where('created_by', App::Auth()->uid, '=')
            ->run();
    }
    
    /**
     * getSubAdminRevenue
     * Get sum of revenue from users created by this sub-admin
     * @return float
     */
    private function getSubAdminRevenue(): float
    {
        $sql = "SELECT SUM(p.total) as total_revenue
                FROM " . Membership::pTable . " as p
                JOIN " . User::mTable . " as u ON p.user_id = u.id
                WHERE u.created_by = ?";
                
        $result = Database::Go()->rawQuery($sql, array(App::Auth()->uid))->first()->run();
        return ($result && !empty($result->total_revenue)) ? $result->total_revenue : 0;
    }
    
    /**
     * role
     * Override parent role method to restrict access
     * @return void
     */
    public function role(): void
    {
        Message::msgError(Language::$word->NOACCESS);
        return;
    }
    
    /**
     * userIndex
     * Show only users created by this sub-admin
     * @return void
     */
    public function userIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T2;
        $tpl->caption = Language::$word->META_T2;
        $tpl->subtitle = null;
        
        $where = 'WHERE type = \'member\' AND created_by = ' . App::Auth()->uid;
        
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
            $counter = Database::Go()->count(User::mTable, $where)->run();
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
        FROM   `" . User::mTable . '` as u
        LEFT JOIN ' . Membership::mTable . " as m on m.id = u.membership_id
        $where
        $and
        ORDER BY $sorting" . $pager->limit;
        
        $tpl->data = Database::Go()->rawQuery($sql)->run();
        $tpl->pager = $pager;
        $tpl->template = 'sub_admin/user';
    }
    
    /**
     * membershipIndex
     * Show only memberships created by this sub-admin
     * @return void
     */
    public function membershipIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T6;
        $tpl->caption = Language::$word->META_T6;
        $tpl->subtitle = null;
        
        $tpl->data = Database::Go()->select(Membership::mTable)
            ->where('created_by', App::Auth()->uid, '=')
            ->orderBy('title', 'ASC')
            ->run();
            
        $tpl->template = 'sub_admin/membership';
    }
    
    /**
     * userEdit
     * Edit a user - only if created by this sub-admin
     * @param int $id
     * @return void
     */
    public function userEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T3;
        $tpl->caption = Language::$word->META_T3;
        $tpl->crumbs = ['sub_admin', 'users', 'edit'];
        
        if (!$row = Database::Go()->select(User::mTable)
            ->where('id', $id, '=')
            ->where('created_by', App::Auth()->uid, '=')
            ->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'sub_admin/error';
        } else {
            $tpl->data = $row;
            $tpl->memberships = $this->getSubAdminMemberships();
            $tpl->countries = App::Content()->getCountryList();
            $tpl->custom_fields = Content::renderCustomFields($id);
            $tpl->userfiles = Database::Go()->select(Content::fTable)->run();
            
            $tpl->template = 'sub_admin/user';
        }
    }
    
    /**
     * userSave
     * Create new user - marking it as created by this sub-admin
     * @return void
     */
    public function userSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T4;
        $tpl->caption = Language::$word->META_T4;
        
        $tpl->memberships = $this->getSubAdminMemberships();
        $tpl->countries = App::Content()->getCountryList();
        $tpl->custom_fields = Content::renderCustomFields(0);
        $tpl->userfiles = Database::Go()->select(Content::fTable)->run();
        $tpl->template = 'sub_admin/user';
    }
    
    /**
     * getSubAdminMemberships
     * Get memberships created by this sub-admin
     * @return mixed
     */
    private function getSubAdminMemberships(): mixed
    {
        return Database::Go()->select(Membership::mTable)
            ->where('created_by', App::Auth()->uid, '=')
            ->orderBy('title', 'ASC')
            ->run();
    }
    
    /**
     * membershipEdit
     * Edit membership - only if created by this sub-admin
     * @param int $id
     * @return void
     */
    public function membershipEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T7;
        $tpl->caption = Language::$word->META_T7;
        $tpl->crumbs = ['sub_admin', 'membership', 'edit'];
        
        if (!$row = Database::Go()->select(Membership::mTable)
            ->where('id', $id, '=')
            ->where('created_by', App::Auth()->uid, '=')
            ->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'sub_admin/error';
        } else {
            $tpl->data = $row;
            // Using the sub-admin specific template
            $tpl->template = 'sub_admin/_membership_edit';
        }
    }
    
    /**
     * membershipSave
     * Create new membership - marking it as created by this sub-admin
     * @return void
     */
    public function membershipSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->META_T8;
        $tpl->caption = Language::$word->META_T8;
        // Using the sub-admin specific template for new memberships
        $tpl->template = 'sub_admin/_membership_new';
    }
    
    /**
     * register
     * Registration page for sub-admin users
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function register(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = "Sub-Admin Registration";
        $tpl->core = App::Core();
        
        if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
            $validate = Validator::run($_POST);
            $validate
                ->set('fname', Language::$word->M_FNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('lname', Language::$word->M_LNAME)->required()->string()->min_len(3)->max_len(60)
                ->set('email', Language::$word->M_EMAIL)->required()->email()
                ->set('password', Language::$word->M_PASSWORD)->required()->string()->min_len(6)->max_len(20)
                ->set('password2', Language::$word->M_PASSWORD2)->required()->string()->equals($_POST['password']);
                
            $safe = $validate->safe();
            
            // Check if email exists
            if (strlen($safe->email) !== 0 && App::Auth()->emailExists($safe->email)) {
                Message::$msgs['email'] = Language::$word->M_EMAIL_R2;
            }
            
            if (count(Message::$msgs) === 0) {
                // User data
                $hash = App::Auth()->doHash($safe->password);
                $username = Utility::randomString();
                
                $data = array(
                    'username' => $username,
                    'email' => $safe->email,
                    'lname' => $safe->lname,
                    'fname' => $safe->fname,
                    'hash' => $hash,
                    'type' => 'sub_admin',
                    'active' => 't', // Set to pending so admin can approve
                    'userlevel' => 6, // Sub admin level
                    'newsletter' => 0
                );
                
                $last_id = Database::Go()->insert(User::mTable, $data)->run();
                
                if ($last_id) {
                    // Send notification email to admin
                    $mailer = Mailer::sendMail();
                    $core = App::Core();
                    
                    // Get admin email
                    $admin = Database::Go()->select(User::mTable)
                        ->where('type', 'owner', '=')
                        ->first()->run();
                    
                    // Send to admin
                    $subject = 'New Sub-Admin Registration';
                    $body = "Hello,\n\nA new sub-admin has registered and is awaiting approval:\n\n";
                    $body .= "Name: " . $data['fname'] . ' ' . $data['lname'] . "\n";
                    $body .= "Email: " . $data['email'] . "\n\n";
                    $body .= "Please login to approve or reject this registration.\n\n";
                    $body .= "Regards,\n" . $core->company;
                    
                    $mailer->Subject = $subject;
                    $mailer->Body = $body;
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($admin->email, $admin->fname . ' ' . $admin->lname);
                    $mailer->send();
                    
                    // Send confirmation to user
                    $subject = 'Sub-Admin Registration Confirmation';
                    $body = "Hello " . $data['fname'] . ",\n\n";
                    $body .= "Thank you for registering as a sub-administrator. Your account is pending approval from the system administrator.\n\n";
                    $body .= "You will be notified via email when your account is approved.\n\n";
                    $body .= "Your login details (save these for future use):\n";
                    $body .= "Email: " . $data['email'] . "\n";
                    $body .= "Username: " . $username . "\n\n";
                    $body .= "Regards,\n" . $core->company;
                    
                    $mailer->Subject = $subject;
                    $mailer->Body = $body;
                    $mailer->setFrom($core->site_email, $core->company);
                    $mailer->addAddress($data['email'], $data['fname'] . ' ' . $data['lname']);
                    $mailer->send();
                    
                    // Success message
                    $tpl->success = true;
                    $tpl->message = "Your registration has been submitted successfully. You will receive an email when your account is approved.";
                } else {
                    Message::msgSingleStatus();
                }
            }
        }
        
        $tpl->template = 'sub_admin/register';
    }
    
    /**
     * checkMembershipAccess
     * Validate that the membership belongs to the current sub-admin
     * @param int $id
     * @return bool
     */
    public function checkMembershipAccess(int $id): bool
    {
        $membership = Database::Go()->select(Membership::mTable)
            ->where('id', $id, '=')
            ->where('created_by', App::Auth()->uid, '=')
            ->first()->run();
            
        return $membership ? true : false;
    }
    
    /**
     * Continue to iterate?
     * Override the Admin::continueToIterate method to ensure sub-admins can only iterate their own memberships
     * @param int $id
     * @return void
     */
    public function continueToIterate(int $id = 0): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        
        // If an ID is provided, check if the membership belongs to this sub-admin
        if ($id > 0) {
            if (!$this->checkMembershipAccess($id)) {
                $tpl->error = Language::$word->META_ERROR;
                $tpl->template = 'sub_admin/error';
                return;
            }
        }
        
        $tpl->title = "Continue to iterate?";
        $tpl->membershipId = $id;
        
        // Get membership data if ID is provided
        if ($id > 0) {
            $tpl->data = Database::Go()->select(Membership::mTable)
                ->where('id', $id, '=')
                ->where('created_by', App::Auth()->uid, '=')
                ->first()->run();
        }
        
        $tpl->template = 'sub_admin/continue_iterate';
    }
}
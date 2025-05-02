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
     * Log message to a dedicated subadmin log file
     *
     * @param string $message Message to log
     * @return void
     */
    public static function log(string $message): void
    {
        $logFile = BASEPATH . 'subadmin_debug.log';
        $timestamp = date('[Y-m-d H:i:s]');
        $logMessage = $timestamp . ' ' . $message . PHP_EOL;

        // Append to log file
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    /**
     * index
     * Main dashboard for sub-admin users
     * @return void
     */
    public function index(): void
    {
        // Handle OAuth callback if Salla redirected here instead of /salla/callback
        if (isset($_GET['code']) && isset($_GET['state'])) {
            $this->handleSallaCallback();
            return;
        }

        // Enforce Salla connection
        $uid = App::Auth()->uid;
//        $store = Database::Go()->select('salla_merchants')->where('user_id', $uid, '=')->first()->run();
//        if (!$store) {
//            Url::redirect(SITEURL . '/sub_admin/connect');
//        }

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

        // Start debugging
        SubAdmin::log("Starting userIndex method for sub-admin ID: " . App::Auth()->uid);

        $sub_admin_id = App::Auth()->uid;

        // Use the select method instead of rawQuery
        $find = isset($_POST['find']) ? Validator::sanitize($_POST['find'], 'string', 20) : null;
        $counter = 0;

        if (isset($_GET['letter']) and $find) {
            $letter = Validator::sanitize($_GET['letter'], 'string', 2);
            $counter = Database::Go()->count(User::mTable, "WHERE type = 'member' AND created_by = " . $sub_admin_id . " AND (`fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%') AND `fname` REGEXP '^" . $letter . "'")->run();

        } elseif (isset($_POST['find'])) {
            $counter = Database::Go()->count(User::mTable, "WHERE type = 'member' AND created_by = " . $sub_admin_id . " AND (`fname` LIKE '%" . trim($find) . "%' OR `lname` LIKE '%" . trim($find) . "%' OR `email` LIKE '%" . trim($find) . "%')")->run();

        } elseif (isset($_GET['letter'])) {
            $letter = Validator::sanitize($_GET['letter'], 'string', 2);
            $counter = Database::Go()->count(User::mTable, "WHERE type = 'member' AND created_by = " . $sub_admin_id . " AND `fname` REGEXP '^" . $letter . "' LIMIT 1")->run();
        } else {
            $counter = Database::Go()->count(User::mTable, "WHERE type = 'member' AND created_by = " . $sub_admin_id)->run();
        }

        // Log the counter value
        SubAdmin::log("Counter value (total users found): " . $counter);

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

        // Build query using the select method instead of rawQuery
        $query = Database::Go()->select(User::mTable . " as u",
            ["u.*", "u.id as id", "u.active as active", "CONCAT(fname,' ',lname) as fullname", "m.title as mtitle", "m.thumb"])
            ->where('u.type', 'member', '=')
            ->where('u.created_by', $sub_admin_id, '=');

        // Add search conditions
        if (isset($_POST['find'])) {
            $query->orWhere("u.fname LIKE '%" . trim($find) . "%' OR u.lname LIKE '%" . trim($find) . "%' OR u.email LIKE '%" . trim($find) . "%'");
        }

        if (isset($_GET['letter']) && !empty($_GET['letter'])) {
            $letter = Validator::sanitize($_GET['letter'], 'string', 2);
            $query->where("u.fname", "^" . $letter, "REGEXP");
        }

        // Join with membership table
        $sql = "SELECT u.*, u.id as id, u.active as active, CONCAT(fname,' ',lname) as fullname, m.title as mtitle, m.thumb 
                FROM `" . User::mTable . "` as u 
                LEFT JOIN `" . Membership::mTable . "` as m ON m.id = u.membership_id 
                WHERE u.type = 'member' AND u.created_by = " . $sub_admin_id;

        if (isset($_POST['find'])) {
            $sql .= " AND (u.fname LIKE '%" . trim($find) . "%' OR u.lname LIKE '%" . trim($find) . "%' OR u.email LIKE '%" . trim($find) . "%')";
        }

        if (isset($_GET['letter']) && !empty($_GET['letter'])) {
            $letter = Validator::sanitize($_GET['letter'], 'string', 2);
            $sql .= " AND u.fname REGEXP '^" . $letter . "'";
        }

        $sql .= " ORDER BY " . $sorting . $pager->limit;

        SubAdmin::log("SQL Query: " . $sql);

        // Execute the direct query
        $tpl->data = Database::Go()->rawQuery($sql)->run();

        // Log how many results were returned
        if (is_array($tpl->data)) {
            SubAdmin::log("Number of users returned: " . count($tpl->data));
            if (!empty($tpl->data)) {
                $firstUser = $tpl->data[0];
                SubAdmin::log("First user: ID=" . $firstUser->id . ", Name=" . $firstUser->fname . " " . $firstUser->lname);
            }
        } else {
            SubAdmin::log("No users found or invalid result format - Data type: " . gettype($tpl->data));

            // Try a different approach - fetch all users created by this sub-admin
            SubAdmin::log("Trying alternative approach...");
            $simple_sql = "SELECT * FROM `" . User::mTable . "` WHERE type = 'member' AND created_by = " . $sub_admin_id;
            $result = Database::Go()->rawQuery($simple_sql)->run();

            if (is_array($result)) {
                SubAdmin::log("Alternative approach found " . count($result) . " users");
                $tpl->data = $result;
            } else {
                SubAdmin::log("Alternative approach failed too. Data type: " . gettype($result));
            }
        }

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

    /**
     * account
     * Display the sub-admin's account information
     * @return void
     */
    public function account(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->M_TITLE;
        $tpl->caption = Language::$word->M_TITLE;
        $tpl->crumbs = ['sub_admin', 'account'];

        $uid = App::Auth()->uid;

        // Get the user data
        $tpl->data = Database::Go()->select(User::mTable)->where('id', $uid, '=')->first()->run();

        // Load Salla store info if connected
        $tpl->store = Database::Go()->select('salla_merchants')->where('user_id', $uid, '=')->first()->run();

        // Load Salla token status
        $token = Database::Go()->select('salla_tokens')->where('user_id', $uid, '=')->first()->run();
        $tpl->token_status = $token ? ($token->expires_at > time() ? 'valid' : 'expired') : 'none';

        // Use a specific account template for sub-admin
        $tpl->template = 'sub_admin/account';
    }

    /**
     * password
     * Display form to change sub-admin's password
     * @return void
     */
    public function password(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = Language::$word->M_SUB2;
        $tpl->caption = Language::$word->M_SUB2;
        $tpl->crumbs = ['sub_admin', 'password'];

        // Get the user data
        $tpl->data = Database::Go()->select(User::mTable)->where('id', App::Auth()->uid, '=')->first()->run();

        // Use a specific password template for sub-admin
        $tpl->template = 'sub_admin/password';
    }

    /**
     * Initiate Salla OAuth authorization
     * @return void
     */
    public function connectStore(): void
    {
        $core = App::Core();
        // Generate and store CSRF state
        $state = bin2hex(random_bytes(16));
        Session::set('salla_state', $state);

        $params = [
            'client_id' => $core->salla_client_id,
            'client_secret' => $core->salla_client_secret,
            'response_type' => 'code',
            'scope' => 'offline_access',
            'redirect_uri' => SITEURL . '/sub_admin/salla/callback',
            'state' => $state
        ];
        $authUrl = 'https://accounts.salla.sa/oauth2/auth?' . http_build_query($params);
        Url::redirect($authUrl);
    }

    /**
     * Handle Salla OAuth callback
     * @return void
     */
    public function handleSallaCallback(): void
    {
        // Log the starting of callback process
        self::log("Starting Salla callback handling process");

        // Increase PHP execution time limit for this process
        ini_set('max_execution_time', 300); // Set to 5 minutes
        ini_set('max_input_time', 120); // Set to 2 minutes
        ini_set('memory_limit', '256M'); // Increase memory limit too

        // Log the starting of callback process
        self::log("Starting Salla callback handling process");

        // Validate CSRF state
        $stored = Session::get('salla_state');
        if (empty($_GET['state']) || $_GET['state'] !== $stored) {
            Message::msgError('Invalid OAuth state. Please try again.');
            Url::redirect(SITEURL . '/sub_admin');
        }
        Session::remove('salla_state');

        if (!isset($_GET['code'])) {
            Message::msgError('Missing authorization code.');
            Url::redirect(SITEURL . '/sub_admin');
        }

        $code = $_GET['code'];
        $state = $_GET['state'];
        $core = App::Core();
        $user_id = App::Auth()->uid;

        // Exchange code for tokens
        $tokenUrl = 'https://accounts.salla.sa/oauth2/token';
        $post = [
            'grant_type' => 'authorization_code',
            'client_id' => $core->salla_client_id,
            'client_secret' => $core->salla_client_secret,
            'code' => $code,
            'scope' => 'offline_access',
            'redirect_uri' => SITEURL . '/sub_admin/salla/callback',
            'state' => $state
        ];

        // Token exchange with file_get_contents
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($post)
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($tokenUrl, false, $context);
        $data = json_decode($response);

        if (empty($data->access_token)) {
            self::log("Failed to authenticate with Salla - No access token received");
            Message::msgError('Failed to authenticate with Salla.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        // Calculate token expiration time
        $expires_at = time() + ($data->expires_in ?? 3600);

        // Save tokens to database
        $tokenData = [
            'user_id' => $user_id,
            'access_token' => $data->access_token,
            'refresh_token' => $data->refresh_token,
            'expires_at' => $expires_at,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Check if tokens already exist for this user and if the user exists
        $user = Database::Go()->select(User::mTable)
            ->where('id', $user_id, '=')
            ->first()->run();

        if (!$user) {
            self::log("Error: Attempted to save Salla token for non-existent user ID: {$user_id}");
            Message::msgError('User account not found. Please contact support.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        $existingToken = Database::Go()->select('salla_tokens')
            ->where('user_id', $user_id, '=')
            ->first()->run();

        if ($existingToken) {
            // Update existing token
            Database::Go()->update('salla_tokens', $tokenData)
                ->where('user_id', $user_id, '=')
                ->run();
            self::log("Updated existing Salla tokens for user ID: {$user_id}");
        } else {
            // Insert new token
            Database::Go()->insert('salla_tokens', $tokenData)->run();
            self::log("Inserted new Salla tokens for user ID: {$user_id}");
        }

        // Fetch products from Salla
        $apiUrl = 'https://api.salla.dev/admin/v2/products';
        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . $data->access_token . "\r\n",
                'method' => 'GET'
            ]
        ];
        $context = stream_context_create($options);
        $resp = file_get_contents($apiUrl, false, $context);
        $productsData = json_decode($resp);

        if (empty($productsData) || !isset($productsData->data)) {
            self::log("No products found or invalid products response from Salla API");
            Message::msgError('Failed to retrieve products from Salla.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        // Get products from Salla
        $products = $productsData->data;
        self::log("Retrieved " . count($products) . " products from Salla");

        // Save products as memberships
        foreach ($products as $product) {
            // Check if this product already exists as a membership by matching Salla product ID
            $existingMembership = Database::Go()->select(Membership::mTable)
                ->where('salla_product_id', $product->id, '=')
                ->first()->run();

            // If no match by product ID, try by title and creator as fallback (for legacy data)
//            if (!$existingMembership) {
//                $existingMembership = Database::Go()->select(Membership::mTable)
//                    ->where('title', $product->name, '=')
//                    ->where('created_by', $user_id, '=')
//                    ->first()->run();
//
//                if ($existingMembership) {
//                    self::log("Found membership by title match. Will update with Salla product ID: {$product->id}");
//                }
//            }

            $membershipData = [
                'title' => $product->name,
                'salla_product_id' => $product->id,
                'description' => $product->description ?? substr($product->description ?? '', 0, 200),
                'body' => $product->description ?? '',
                'price' => $product->price->amount,
                'days' => 30, // Default subscription period - 30 days
                'period' => 'D', // D for days
                'recurring' => 1, // Set as recurring
                'private' => 0, // Not private
                'created_by' => $user_id,
                'active' => 1
            ];

            // Set main image or thumbnail if available
            if (!empty($product->main_image)) {
                $membershipData['thumb'] = $product->main_image;
            } elseif (!empty($product->thumbnail)) {
                $membershipData['thumb'] = $product->thumbnail;
            }

            // If there's a promotion, include it in the description
            if (isset($product->promotion) && !empty($product->promotion->title)) {
                $promotionText = "\n\nPromotion: " . $product->promotion->title;
                if (!empty($product->promotion->sub_title)) {
                    $promotionText .= " - " . $product->promotion->sub_title;
                }

                // Append promotion to description
                if (isset($membershipData['description'])) {
                    $membershipData['description'] .= $promotionText;
                }

                // Append promotion to body as well
                if (isset($membershipData['body'])) {
                    $membershipData['body'] .= $promotionText;
                }
            }

            // If there are product options, include them in the membership description
            if (!empty($product->options)) {
                $optionsText = "\n\nOptions:";
                foreach ($product->options as $option) {
                    if (!empty($option->name)) {
                        $optionsText .= "\n- " . $option->name;

                        // Add option values if available
                        if (!empty($option->values)) {
                            $optionsText .= ": ";
                            $valueNames = array_map(function ($value) {
                                return $value->name ?? '';
                            }, $option->values);
                            $optionsText .= implode(', ', array_filter($valueNames));
                        }
                    }
                }

                // Append options to description
                if (isset($membershipData['body'])) {
                    $membershipData['body'] .= $optionsText;
                }
            }

            if ($existingMembership) {
                // Update existing membership
                Database::Go()->update(Membership::mTable, $membershipData)
                    ->where('salla_product_id', $existingMembership->salla_product_id, '=')
                    ->run();
                //$membership_id = $existingMembership->id;
                //self::log("Updated existing membership (ID: {$membership_id}) for Salla product ID: {$product->id}, Name: {$product->name}");
            } else {
                // Insert new membership
                $membership_id = Database::Go()->insert(Membership::mTable, $membershipData)->run();
                self::log("Created new membership (ID: {$membership_id}) for Salla product ID: {$product->id}, Name: {$product->name}");
            }

            // First fetch all orders, then filter those containing this product
            $ordersUrl = "https://api.salla.dev/admin/v2/orders";
            $options = [
                'http' => [
                    'header' => "Authorization: Bearer " . $data->access_token . "\r\n",
                    'method' => 'GET'
                ]
            ];
            $context = stream_context_create($options);
            $ordersResp = @file_get_contents($ordersUrl, false, $context);

            if (!$ordersResp) {
                self::log("Failed to fetch orders");
                continue;
            }

            $ordersData = json_decode($ordersResp);
//            var_dump($ordersData);
            if (empty($ordersData) || !isset($ordersData->data)) {
                self::log("No orders found");
                continue;
            }

            // Find orders containing this product
            $productOrders = [];
            foreach ($ordersData->data as $order) {
                // Check if order items exist in the list response first (more efficient)
                if (!empty($order->items)) {
                    // Process items directly from the list response
                    foreach ($order->items as $item) {
                        if (isset($item->name) && $item->name === $product->name) {
                            $productOrders[] = $order;
                            self::log("Found product '{$product->name}' in order {$order->id} (using list response)");
                            break;
                        }
                    }
                } else {
                    // If no items in list response, get order details
                    $orderDetailUrl = "https://api.salla.dev/admin/v2/orders/{$order->id}";
                    $detailOptions = [
                        'http' => [
                            'header' => "Authorization: Bearer " . $data->access_token . "\r\n",
                            'method' => 'GET'
                        ]
                    ];
                    $detailContext = stream_context_create($detailOptions);
                    $orderDetailResp = @file_get_contents($orderDetailUrl, false, $detailContext);

                    if (!$orderDetailResp) {
                        self::log("Failed to fetch details for order {$order->id}");
                        continue;
                    }

                    $orderDetail = json_decode($orderDetailResp);

                    // Verify the response structure
                    if (empty($orderDetail) || !isset($orderDetail->data)) {
                        self::log("Invalid response format for order {$order->id}");
                        continue;
                    }

                    // Check if order contains our product - try both possible item locations
                    $orderData = $orderDetail->data;
                    $itemFound = false;

                    // Check items at detail level (detail response structure might differ)
                    if (!empty($orderData->items)) {
                        foreach ($orderData->items as $item) {
                            if (isset($item->name) && $item->name === $product->name) {
                                $productOrders[] = $orderData;
                                $itemFound = true;
                                self::log("Found product '{$product->name}' in order {$order->id} (using items in detail)");
                                break;
                            }
                        }
                    }

                    // If no items found at first level, check if they might be in another location
                    if (!$itemFound && !empty($orderData->line_items)) {
                        foreach ($orderData->line_items as $item) {
                            if (isset($item->name) && $item->name === $product->name) {
                                $productOrders[] = $orderData;
                                $itemFound = true;
                                self::log("Found product '{$product->name}' in order {$order->id} (using line_items)");
                                break;
                            }
                        }
                    }

                    // If still no items found, log it
                    if (!$itemFound) {
                        self::log("No items found matching '{$product->name}' in order {$order->id}");
                    }
                }
            }

            $orders = $productOrders;
            self::log("Retrieved " . count($orders) . " orders containing product: {$product->name}");

            // Record subscriptions from orders
            foreach ($orders as $order) {
                // Check if this subscription already exists
                $existingSubscription = Database::Go()->select('salla_subscriptions')
                    ->where('salla_order_id', $order->id, '=')
                    ->first()->run();

                // Set subscription dates
                $startDate = date('Y-m-d H:i:s', strtotime($order->date->date));
                $endDate = date('Y-m-d H:i:s', strtotime($order->date->date . ' + 30 days')); // Default to 30 days

                // Find product quantity in order items
                $quantity = 1; // Default quantity
                if (!empty($order->items)) {
                    foreach ($order->items as $item) {
                        if ($item->name === $product->name) {
                            $quantity = $item->quantity;
                            break;
                        }
                    }
                }

                $subscriptionData = [
                    'membership_id' => $membership_id,
                    'salla_product_id' => $product->id,
                    'salla_order_id' => $order->id,
                    'salla_customer_id' => isset($order->customer) ? ($order->customer->id ?? null) : null,
                    'customer_name' => isset($order->receiver) ? $order->receiver->name : (isset($order->customer) ? ($order->customer->first_name . ' ' . $order->customer->last_name) : null),
                    'customer_email' => isset($order->receiver) ? $order->receiver->email : (isset($order->customer) ? $order->customer->email : null),
                    'customer_phone' => isset($order->receiver) ? $order->receiver->phone : (isset($order->customer) ? ($order->customer->mobile_code . $order->customer->mobile) : null),
//                    'quantity' => $quantity,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Add detailed log about the subscription
                self::log("Subscription details - Membership ID: {$membership_id}, Salla Product ID: {$product->id}, " .
                    "Order ID: {$order->id}, Quantity: {$quantity}, Start: {$startDate}, End: {$endDate}");

                try {
                    if ($existingSubscription) {
                        // Update existing subscription
                        $result = Database::Go()->update('salla_subscriptions', $subscriptionData)
                            ->where('id', $existingSubscription->id, '=')
                            ->run();

                        if ($result) {
                            self::log("Successfully updated existing subscription for order ID: {$order->id}");
                        } else {
                            self::log("Failed to update subscription for order ID: {$order->id}");
                        }
                    } else {
                        // Insert new subscription
                        $result = Database::Go()->insert('salla_subscriptions', $subscriptionData)->run();

                        if ($result) {
                            self::log("Successfully created new subscription for order ID: {$order->id}");
                        } else {
                            self::log("Failed to create subscription for order ID: {$order->id}");
                        }
                    }
                } catch (Exception $e) {
                    self::log("Error processing subscription for order ID {$order->id}: " . $e->getMessage());
                }
            }
        }

        // ADDED: Import customers from Salla after handling products
        self::log("Starting import of Salla customers after product import");

        // Fetch customers from Salla API (using the same access token we just obtained)
        $customersUrl = 'https://api.salla.dev/admin/v2/customers';
        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . $data->access_token . "\r\n",
                'method' => 'GET'
            ]
        ];

        $context = stream_context_create($options);
        $customersResponse = @file_get_contents($customersUrl, false, $context);

        if (!$customersResponse) {
            self::log("Failed to fetch customers from Salla API during store connection");
            // Continue with success message even if customer import fails - we don't want to block store connection
        } else {
            $customersData = json_decode($customersResponse);
//            var_dump($customersData);
            if (!empty($customersData) && isset($customersData->data)) {
                $customers = $customersData->data;
                self::log("Retrieved " . count($customers) . " customers from Salla API during store connection");

                $importedCount = 0;
                $updatedCount = 0;

                // Process each customer
                foreach ($customers as $customer) {
                    // Skip if essential data is missing
//                    if (empty($customer->email)) {
//                        self::log("Skipping customer with missing email");
//                        continue;
//                    }

                    // Check if customer already exists by email
                    $existingUser = Database::Go()->select(User::mTable)
                        ->where('salla_customer_id', $customer->id, '=')
                        ->first()->run();

                    // Generate a username based on email
                    $username = explode('@', $customer->email)[0] . '_' . Utility::randomString(5);

                    // Generate a random password
                    $password = Utility::randomString(10);
                    $hash = App::Auth()->doHash($password);

                    // Set location field from various address components
                    $location = !empty($customer->location) && $customer->location !== 'null'
                        ? $customer->location
                        : (!empty($customer->city) ? $customer->city . ', ' . $customer->country : $customer->country);

                    // Format the mobile number
                    $mobile = (!empty($customer->mobile_code) ? $customer->mobile_code : '') .
                        (!empty($customer->mobile) ? $customer->mobile : '');

                    // Prepare user data
                    $userData = [
                        'username' => $username,
                        'fname' => $customer->first_name,
                        'lname' => $customer->last_name,
                        'email' => $customer->email ?? '',
                        'hash' => $hash,
                        'type' => 'member',
                        'active' => 'y', // Set as active by default
                        'userlevel' => 1, // Regular member level
                        'city' => $customer->city ?? '',
                        'country' => substr($customer->country ?? '', 0, 4), // Limit to 4 chars as per schema
                        'address' => $location ?? '',
                        'login_info' => $mobile,
                        'avatar' => $customer->avatar ?? null,
                        'created_by' => $user_id,
                        'newsletter' => 1,
                        'salla_customer_id' => $customer->id,
                        'custom_fields' => json_encode([
                            'gender' => $customer->gender ?? '',
                            'imported_from_salla' => true,
                            'salla_import_date' => date('Y-m-d H:i:s')
                        ])
                    ];

                    if ($existingUser) {
                        // Update existing user
                        $updateResult = Database::Go()->update(User::mTable, $userData)
                            ->where('salla_customer_id', $existingUser->salla_customer_id , '=')
                            ->run();

                        if ($updateResult) {
                            $updatedCount++;
                            self::log("Updated existing user (ID: {$existingUser->id}) from Salla customer ID: {$customer->id}");
                        } else {
                            self::log("Failed to update user from Salla customer ID: {$customer->id}");
                        }
                    } else {
                        // Insert new user
                        $userId = Database::Go()->insert(User::mTable, $userData)->run();

                        if ($userId) {
                            $importedCount++;
                            self::log("Created new user (ID: {$userId}) from Salla customer ID: {$customer->id}");
                        } else {
                            self::log("Failed to create user from Salla customer ID: {$customer->id}");
                        }
                    }
                }

                // Handle pagination if available
                if (isset($customersData->pagination) && $customersData->pagination->totalPages > 1) {
                    $currentPage = $customersData->pagination->currentPage;
                    $totalPages = $customersData->pagination->totalPages;

                    self::log("Processing additional customer pages. Current: {$currentPage}, Total: {$totalPages}");

                    // Process remaining pages
                    for ($page = $currentPage + 1; $page <= $totalPages; $page++) {
                        self::log("Fetching customers page {$page} of {$totalPages}");

                        $pageUrl = $customersUrl . '?page=' . $page;
                        $pageResponse = @file_get_contents($pageUrl, false, $context);

                        if (!$pageResponse) {
                            self::log("Failed to fetch customers page {$page}");
                            continue;
                        }

                        $pageData = json_decode($pageResponse);

                        if (empty($pageData) || !isset($pageData->data)) {
                            self::log("Invalid response for customers page {$page}");
                            continue;
                        }

                        $pageCustomers = $pageData->data;
                        self::log("Retrieved " . count($pageCustomers) . " customers from page {$page}");

                        // Process each customer in this page (similar logic as above)
                        foreach ($pageCustomers as $customer) {
                            // Skip if essential data is missing
                            if (empty($customer->email)) {
                                self::log("Skipping customer with missing email on page {$page}");
                                continue;
                            }

                            // Check if customer already exists by email
                            $existingUser = Database::Go()->select(User::mTable)
                                ->where('email', $customer->email, '=')
                                ->first()->run();

                            // Generate a username based on email
                            $username = explode('@', $customer->email)[0] . '_' . Utility::randomString(5);

                            // Generate a random password
                            $password = Utility::randomString(10);
                            $hash = App::Auth()->doHash($password);

                            // Set location field from various address components
                            $location = !empty($customer->location) && $customer->location !== 'null'
                                ? $customer->location
                                : (!empty($customer->city) ? $customer->city . ', ' . $customer->country : $customer->country);

                            // Format the mobile number
                            $mobile = (!empty($customer->mobile_code) ? $customer->mobile_code : '') .
                                (!empty($customer->mobile) ? $customer->mobile : '');

                            // Prepare user data
                            // Inside your import methods, modify the $userData array to use the new field:
                            $userData = [
                                'username' => $username,
                                'fname' => $customer->first_name,
                                'lname' => $customer->last_name,
                                'email' => $customer->email ?? '',
                                'hash' => $hash,
                                'type' => 'member',
                                'active' => 'y',
                                'userlevel' => 1,
                                'city' => $customer->city ?? '',
                                'country' => $customer->country ?? '', // Now can store the full country name
                                'address' => $location ?? '',
                                'login_info' => $mobile,
                                'avatar' => $customer->avatar ?? null,
                                'created_by' => $user_id,
                                'newsletter' => 1,
                                'salla_customer_id' => $customer->id, // Directly store here instead of in custom_fields
                                'custom_fields' => json_encode([
                                    'gender' => $customer->gender ?? '',
                                    'imported_from_salla' => true,
                                    'salla_import_date' => date('Y-m-d H:i:s')
                                ])
                            ];

                            if ($existingUser) {
                                // Update existing user
                                $updateResult = Database::Go()->update(User::mTable, $userData)
                                    ->where('id', $existingUser->id, '=')
                                    ->run();

                                if ($updateResult) {
                                    $updatedCount++;
                                    self::log("Updated existing user (ID: {$existingUser->id}) from Salla customer ID: {$customer->id}");
                                } else {
                                    self::log("Failed to update user from Salla customer ID: {$customer->id}");
                                }
                            } else {
                                // Insert new user
                                $userId = Database::Go()->insert(User::mTable, $userData)->run();

                                if ($userId) {
                                    $importedCount++;
                                    self::log("Created new user (ID: {$userId}) from Salla customer ID: {$customer->id}");
                                } else {
                                    self::log("Failed to create user from Salla customer ID: {$customer->id}");
                                }
                            }
                        }
                    }
                }

                self::log("Customer import completed during store connection. Imported: {$importedCount}, Updated: {$updatedCount}");
            } else {
                self::log("No customers found or invalid response from Salla API during store connection");
            }
        }

        // Set success message and redirect
        Message::msgReply(true, 'success', 'Salla store connected successfully. Products, subscriptions, and customers have been imported.');
        Url::redirect(SITEURL . '/sub_admin');
    }

    /**
     * Handle incoming Salla webhooks
     * @return void
     */
    public function handleSallaWebhook(): void
    {
        // Start by logging the webhook request
        self::log("Received Salla webhook request");

        // Get the webhook payload
        $payload = file_get_contents('php://input');
        $headers = getallheaders();

        // Verify webhook signature
        $signature = $headers['X-Salla-Signature'] ?? '';
        $event = $headers['X-Salla-Event'] ?? '';
        $timestamp = $headers['X-Salla-Timestamp'] ?? '';

        // Get webhook secret from settings
        $core = App::Core();
        $webhookSecret = $core->salla_webhook_secret;

        // Log event type
        self::log("Webhook Event: " . $event);

        // Verify webhook signature to ensure it's from Salla
        $calculatedSignature = hash_hmac('sha256', $timestamp . '.' . $payload, $webhookSecret);

        if (!hash_equals($calculatedSignature, $signature)) {
            self::log("Webhook signature verification failed");
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Invalid signature']);
            exit;
        }

        // Parse the JSON payload
        $data = json_decode($payload);

        if (!$data) {
            self::log("Invalid webhook payload format");
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid payload format']);
            exit;
        }

        // Process different events
        switch ($event) {
            case 'product.created':
                $this->handleProductCreated($data);
                break;

            case 'product.updated':
                $this->handleProductUpdated($data);
                break;

            case 'product.deleted':
                $this->handleProductDeleted($data);
                break;

            case 'order.created':
                $this->handleOrderCreated($data);
                break;

            case 'order.updated':
                $this->handleOrderUpdated($data);
                break;

            default:
                self::log("Unhandled webhook event: $event");
                // Respond with success to unhandled events
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Event received but not processed']);
                exit;
        }

        // Respond with success
        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Webhook processed successfully']);
    }

    /**
     * Handle product.created webhook event
     * @param object $data Webhook payload
     * @return void
     */
    private function handleProductCreated($data): void
    {
        self::log("Processing product.created event for product ID: " . $data->data->id);

        // Extract merchant ID (store owner)
        $merchantId = $data->merchant;

        // Find the user associated with this merchant
        $user = $this->findUserByMerchantId($merchantId);

        if (!$user) {
            self::log("No user found for merchant ID: $merchantId");
            return;
        }

        $product = $data->data;

        // Prepare membership data
        $membershipData = [
            'title' => $product->name,
            'description' => $product->description ?? substr($product->description ?? '', 0, 200),
            'body' => $product->description ?? '',
            'price' => $product->price->amount ?? 0,
            'days' => 30, // Default subscription period
            'period' => 'D', // D for days
            'recurring' => 1, // Set as recurring
            'private' => 0,
            'created_by' => $user->id,
            'active' => 1,
            'salla_product_id' => $product->id
        ];

        // Set main image if available
        if (!empty($product->main_image)) {
            $membershipData['thumb'] = $product->main_image;
        }

        // Insert new membership
        $membership_id = Database::Go()->insert(Membership::mTable, $membershipData)->run();

        if ($membership_id) {
            self::log("Created new membership (ID: $membership_id) from webhook for product ID: {$product->id}");
        } else {
            self::log("Failed to create membership from webhook for product ID: {$product->id}");
        }
    }

    /**
     * Handle product.updated webhook event
     * @param object $data Webhook payload
     * @return void
     */
    private function handleProductUpdated($data): void
    {
        self::log("Processing product.updated event for product ID: " . $data->data->id);

        $product = $data->data;

        // Find existing membership by Salla product ID
        $existingMembership = Database::Go()->select(Membership::mTable)
            ->where('salla_product_id', $product->id, '=')
            ->first()->run();

        if (!$existingMembership) {
            self::log("No matching membership found for Salla product ID: {$product->id}");
            return;
        }

        // Prepare updated membership data
        $membershipData = [
            'title' => $product->name,
            'description' => $product->description ?? substr($product->description ?? '', 0, 200),
            'body' => $product->description ?? '',
            'price' => $product->price->amount ?? $existingMembership->price,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update main image if available
        if (!empty($product->main_image)) {
            $membershipData['thumb'] = $product->main_image;
        }

        // Update existing membership
        $result = Database::Go()->update(Membership::mTable, $membershipData)
            ->where('id', $existingMembership->id, '=')
            ->run();

        if ($result) {
            self::log("Updated membership (ID: {$existingMembership->id}) from webhook for product ID: {$product->id}");
        } else {
            self::log("Failed to update membership from webhook for product ID: {$product->id}");
        }
    }

    /**
     * Handle product.deleted webhook event
     * @param object $data Webhook payload
     * @return void
     */
    private function handleProductDeleted($data): void
    {
        self::log("Processing product.deleted event for product ID: " . $data->data->id);

        $productId = $data->data->id;

        // Find existing membership by Salla product ID
        $existingMembership = Database::Go()->select(Membership::mTable)
            ->where('salla_product_id', $productId, '=')
            ->first()->run();

        if (!$existingMembership) {
            self::log("No matching membership found for deleted Salla product ID: {$productId}");
            return;
        }

        // Option 1: Mark as inactive instead of deleting
        $result = Database::Go()->update(Membership::mTable, ['active' => 0])
            ->where('id', $existingMembership->id, '=')
            ->run();

        if ($result) {
            self::log("Marked membership (ID: {$existingMembership->id}) as inactive due to product deletion");
        } else {
            self::log("Failed to update membership status for deleted product ID: {$productId}");
        }

        // Option 2 (alternative): Actually delete the membership
        // Uncomment if you prefer deletion
        /*
        $result = Database::Go()->delete(Membership::mTable)
            ->where('id', $existingMembership->id, '=')
            ->run();
        
        if ($result) {
            self::log("Deleted membership (ID: {$existingMembership->id}) due to product deletion");
        } else {
            self::log("Failed to delete membership for product ID: {$productId}");
        }
        */
    }

    /**
     * Handle order.created webhook event
     * @param object $data Webhook payload
     * @return void
     */
    private function handleOrderCreated($data): void
    {
        self::log("Processing order.created event for order ID: " . $data->data->id);

        $order = $data->data;
        $merchantId = $data->merchant;

        // Find the user associated with this merchant
        $user = $this->findUserByMerchantId($merchantId);

        if (!$user) {
            self::log("No user found for merchant ID: $merchantId");
            return;
        }

        // Process each item in the order
        if (empty($order->items)) {
            self::log("No items found in order ID: {$order->id}");
            return;
        }

        foreach ($order->items as $item) {
            // Find the membership for this product
            $membership = Database::Go()->select(Membership::mTable)
                ->where('salla_product_id', $item->product_id, '=')
                ->first()->run();

            if (!$membership) {
                self::log("No matching membership found for product ID: {$item->product_id} in order");
                continue;
            }

            // Set subscription dates
            $startDate = date('Y-m-d H:i:s');
            $endDate = date('Y-m-d H:i:s', strtotime("+ {$membership->days} days"));

            // Check if this subscription already exists
            $existingSubscription = Database::Go()->select('salla_subscriptions')
                ->where('salla_order_id', $order->id, '=')
                ->where('salla_product_id', $item->product_id, '=')
                ->first()->run();

            if ($existingSubscription) {
                self::log("Subscription already exists for order ID: {$order->id} and product ID: {$item->product_id}");
                continue;
            }

            // Create new subscription with customer information
            $subscriptionData = [
                'membership_id' => $membership->id,
                'salla_product_id' => $item->product_id,
                'salla_order_id' => $order->id,
                'salla_customer_id' => isset($order->customer) ? ($order->customer->id ?? null) : null,
                'customer_name' => isset($order->receiver) ? $order->receiver->name : (isset($order->customer) ? ($order->customer->first_name . ' ' . $order->customer->last_name) : null),
                'customer_email' => isset($order->receiver) ? $order->receiver->email : (isset($order->customer) ? $order->customer->email : null),
                'customer_phone' => isset($order->receiver) ? $order->receiver->phone : (isset($order->customer) ? ($order->customer->mobile_code . $order->customer->mobile) : null),
                'quantity' => $item->quantity ?? 1,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = Database::Go()->insert('salla_subscriptions', $subscriptionData)->run();

            if ($result) {
                self::log("Created new subscription from webhook for order ID: {$order->id}, product ID: {$item->product_id}");
                self::log("Customer info saved - Name: {$subscriptionData['customer_name']}, Email: {$subscriptionData['customer_email']}");
            } else {
                self::log("Failed to create subscription from webhook for order ID: {$order->id}");
            }
        }
    }

    /**
     * Handle order.updated webhook event
     * @param object $data Webhook payload
     * @return void
     */
    private function handleOrderUpdated($data): void
    {
        self::log("Processing order.updated event for order ID: " . $data->data->id);

        $order = $data->data;

        // Find existing subscriptions for this order
        $existingSubscriptions = Database::Go()->select('salla_subscriptions')
            ->where('salla_order_id', $order->id, '=')
            ->run();

        if (!$existingSubscriptions) {
            self::log("No matching subscriptions found for order ID: {$order->id}");
            return;
        }

        // Update subscription status based on order status
        $newStatus = 'active';

        // Map Salla order status to our subscription status
        if (isset($order->status)) {
            switch ($order->status->id) {
                case 'canceled':
                case 'canceled_by_customer':
                    $newStatus = 'canceled';
                    break;
                case 'completed':
                    $newStatus = 'active';
                    break;
                case 'payment_pending':
                    $newStatus = 'pending';
                    break;
                default:
                    // Keep as active for other statuses
                    $newStatus = 'active';
            }
        }

        // Prepare update data with customer information
        $updateData = [
            'status' => $newStatus,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Update customer information if available in the webhook
        if (isset($order->customer) || isset($order->receiver)) {
            if (isset($order->customer)) {
                $updateData['salla_customer_id'] = $order->customer->id ?? null;
                $updateData['customer_name'] = ($order->customer->first_name ?? '') . ' ' . ($order->customer->last_name ?? '');
                $updateData['customer_email'] = $order->customer->email ?? null;
                $updateData['customer_phone'] = ($order->customer->mobile_code ?? '') . ($order->customer->mobile ?? '');
            }

            // Receiver info takes precedence if available
            if (isset($order->receiver)) {
                $updateData['customer_name'] = $order->receiver->name ?? $updateData['customer_name'] ?? null;
                $updateData['customer_email'] = $order->receiver->email ?? $updateData['customer_email'] ?? null;
                $updateData['customer_phone'] = $order->receiver->phone ?? $updateData['customer_phone'] ?? null;
            }

            // self::log("Updating customer info - Name: {$updateData['customer_name'] ?? 'N/A'}, Email: {$updateData['customer_email'] ?? 'N/A'}");
        }

        // Update all subscriptions for this order
        $result = Database::Go()->update('salla_subscriptions', $updateData)
            ->where('salla_order_id', $order->id, '=')
            ->run();

        if ($result) {
            self::log("Updated subscription status to '$newStatus' and customer information for order ID: {$order->id}");
        } else {
            self::log("Failed to update subscription for order ID: {$order->id}");
        }
    }

    /**
     * Find user by Salla merchant ID
     * @param string $merchantId Salla merchant ID
     * @return object|null User object or null if not found
     */
    private function findUserByMerchantId(string $merchantId)
    {
        // First try to find in salla_merchants table
        $merchant = Database::Go()->select('salla_merchants')
            ->where('store_id', $merchantId, '=')
            ->first()->run();

        if ($merchant && $merchant->user_id) {
            return Database::Go()->select(User::mTable)
                ->where('id', $merchant->user_id, '=')
                ->first()->run();
        }

        // If not found, check if we have tokens for any user
        $tokens = Database::Go()->select('salla_tokens')->run();

        if (!$tokens) {
            return null;
        }

        // For each token, try to get merchant info
        foreach ($tokens as $token) {
            // If token is expired, skip
            if ($token->expires_at < time()) {
                continue;
            }

            // Use the token to fetch merchant info from Salla
            $options = [
                'http' => [
                    'header' => "Authorization: Bearer " . $token->access_token . "\r\n",
                    'method' => 'GET'
                ]
            ];

            $context = stream_context_create($options);
            $response = @file_get_contents('https://api.salla.dev/admin/v2/merchant/details', false, $context);

            if (!$response) {
                continue;
            }

            $merchantData = json_decode($response);

            if (isset($merchantData->data->id) && $merchantData->data->id === $merchantId) {
                // Found matching merchant, get user
                return Database::Go()->select(User::mTable)
                    ->where('id', $token->user_id, '=')
                    ->first()->run();
            }
        }

        return null;
    }

    /**
     * Import Salla customers
     * This method fetches customers from Salla API and registers them as members
     * @return void
     */
    public function importSallaCustomers(): void
    {
        self::log("Starting import of Salla customers");

        $user_id = App::Auth()->uid;

        // Get Salla access token
        $token = Database::Go()->select('salla_tokens')
            ->where('user_id', $user_id, '=')
            ->first()->run();

        if (!$token || $token->expires_at < time()) {
            self::log("No valid Salla token found for user ID: {$user_id}");
            Message::msgError('No valid Salla token found. Please reconnect your Salla store.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        // Fetch customers from Salla API
        $apiUrl = 'https://api.salla.dev/admin/v2/customers';
        $options = [
            'http' => [
                'header' => "Authorization: Bearer " . $token->access_token . "\r\n",
                'method' => 'GET'
            ]
        ];

        $context = stream_context_create($options);
        $response = @file_get_contents($apiUrl, false, $context);

        if (!$response) {
            self::log("Failed to fetch customers from Salla API");
            Message::msgError('Failed to fetch customers from Salla API.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        $customersData = json_decode($response);

        if (empty($customersData) || !isset($customersData->data)) {
            self::log("No customers found or invalid response from Salla API");
            Message::msgError('No customers found or invalid response from Salla API.');
            Url::redirect(SITEURL . '/sub_admin');
            return;
        }

        $customers = $customersData->data;
        self::log("Retrieved " . count($customers) . " customers from Salla API");

        $importedCount = 0;
        $updatedCount = 0;

        // Process each customer
        foreach ($customers as $customer) {
            // Check if customer already exists by email
            $existingUser = Database::Go()->select(User::mTable)
                ->where('email', $customer->email, '=')
                ->first()->run();

            // Generate a username based on email
            $username = explode('@', $customer->email)[0] . '_' . Utility::randomString(5);

            // Generate a random password
            $password = Utility::randomString(10);
            $hash = App::Auth()->doHash($password);

            // Set location field from various address components
            $location = !empty($customer->location) && $customer->location !== 'null'
                ? $customer->location
                : (!empty($customer->city) ? $customer->city . ', ' . $customer->country : $customer->country);

            // Format the mobile number
            $mobile = (!empty($customer->mobile_code) ? $customer->mobile_code : '') .
                (!empty($customer->mobile) ? $customer->mobile : '');

            // Prepare user data
            $userData = [
                'username' => $username,
                'fname' => $customer->first_name,
                'lname' => $customer->last_name,
                'email' => $customer->email,
                'hash' => $hash,
                'type' => 'member',
                'active' => 'y', // Set as active by default
                'userlevel' => 1, // Regular member level
                'city' => $customer->city ?? '',
                'country' => substr($customer->country ?? '', 0, 4), // Limit to 4 chars as per schema
                'address' => $location ?? '',
                'login_info' => $mobile,
                'avatar' => $customer->avatar ?? null,
                'created_by' => $user_id,
                'newsletter' => 1,
                'custom_fields' => json_encode([
                    'salla_customer_id' => $customer->id,
                    'gender' => $customer->gender ?? '',
                    'imported_from_salla' => true,
                    'salla_import_date' => date('Y-m-d H:i:s')
                ])
            ];

            if ($existingUser) {
                // Update existing user
                $updateResult = Database::Go()->update(User::mTable, $userData)
                    ->where('id', $existingUser->id, '=')
                    ->run();

                if ($updateResult) {
                    $updatedCount++;
                    self::log("Updated existing user (ID: {$existingUser->id}) from Salla customer ID: {$customer->id}");
                } else {
                    self::log("Failed to update user from Salla customer ID: {$customer->id}");
                }
            } else {
                // Insert new user
                $userId = Database::Go()->insert(User::mTable, $userData)->run();

                if ($userId) {
                    $importedCount++;
                    self::log("Created new user (ID: {$userId}) from Salla customer ID: {$customer->id}");
                } else {
                    self::log("Failed to create user from Salla customer ID: {$customer->id}");
                }
            }
        }

        // Check for pagination and process additional pages if available
        if (isset($customersData->pagination) && $customersData->pagination->totalPages > 1) {
            $currentPage = $customersData->pagination->currentPage;
            $totalPages = $customersData->pagination->totalPages;

            self::log("Processing additional pages. Current: {$currentPage}, Total: {$totalPages}");

            // Process remaining pages
            for ($page = $currentPage + 1; $page <= $totalPages; $page++) {
                self::log("Fetching customers page {$page} of {$totalPages}");

                $pageUrl = $apiUrl . '?page=' . $page;
                $pageResponse = @file_get_contents($pageUrl, false, $context);

                if (!$pageResponse) {
                    self::log("Failed to fetch customers page {$page}");
                    continue;
                }

                $pageData = json_decode($pageResponse);

                if (empty($pageData) || !isset($pageData->data)) {
                    self::log("Invalid response for customers page {$page}");
                    continue;
                }

                $pageCustomers = $pageData->data;
                self::log("Retrieved " . count($pageCustomers) . " customers from page {$page}");

                // Process each customer in this page (same logic as above)
                foreach ($pageCustomers as $customer) {
                    // Check if customer already exists by email
                    $existingUser = Database::Go()->select(User::mTable)
                        ->where('email', $customer->email, '=')
                        ->first()->run();

                    // Generate a username based on email
                    $username = explode('@', $customer->email)[0] . '_' . Utility::randomString(5);

                    // Generate a random password
                    $password = Utility::randomString(10);
                    $hash = App::Auth()->doHash($password);

                    // Set location field from various address components
                    $location = !empty($customer->location) && $customer->location !== 'null'
                        ? $customer->location
                        : (!empty($customer->city) ? $customer->city . ', ' . $customer->country : $customer->country);

                    // Format the mobile number
                    $mobile = (!empty($customer->mobile_code) ? $customer->mobile_code : '') .
                        (!empty($customer->mobile) ? $customer->mobile : '');

                    // Prepare user data
                    $userData = [
                        'username' => $username,
                        'fname' => $customer->first_name,
                        'lname' => $customer->last_name,
                        'email' => $customer->email,
                        'hash' => $hash,
                        'type' => 'member',
                        'active' => 'y', // Set as active by default
                        'userlevel' => 1, // Regular member level
                        'city' => $customer->city ?? '',
                        'country' => substr($customer->country ?? '', 0, 4), // Limit to 4 chars as per schema
                        'address' => $location ?? '',
                        'login_info' => $mobile,
                        'avatar' => $customer->avatar ?? null,
                        'created_by' => $user_id,
                        'newsletter' => 1,
                        'custom_fields' => json_encode([
                            'salla_customer_id' => $customer->id,
                            'gender' => $customer->gender ?? '',
                            'imported_from_salla' => true,
                            'salla_import_date' => date('Y-m-d H:i:s')
                        ])
                    ];

                    if ($existingUser) {
                        // Update existing user
                        $updateResult = Database::Go()->update(User::mTable, $userData)
                            ->where('id', $existingUser->id, '=')
                            ->run();

                        if ($updateResult) {
                            $updatedCount++;
                            self::log("Updated existing user (ID: {$existingUser->id}) from Salla customer ID: {$customer->id}");
                        } else {
                            self::log("Failed to update user from Salla customer ID: {$customer->id}");
                        }
                    } else {
                        // Insert new user
                        $userId = Database::Go()->insert(User::mTable, $userData)->run();

                        if ($userId) {
                            $importedCount++;
                            self::log("Created new user (ID: {$userId}) from Salla customer ID: {$customer->id}");
                        } else {
                            self::log("Failed to create user from Salla customer ID: {$customer->id}");
                        }
                    }
                }
            }
        }

        // Set success message and redirect
        $message = "Import completed. " .
            "Imported {$importedCount} new customers and updated {$updatedCount} existing customers.";
        Message::msgReply(true, 'success', $message);
        Url::redirect(SITEURL . '/sub_admin');
    }

    /**
     * subscriptions
     * Show Salla subscriptions for this sub-admin
     * @return void
     */
    public function subscriptions(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = "Salla Subscriptions";
        $tpl->caption = "Salla Subscriptions";
        $tpl->subtitle = "View and manage your Salla subscriptions";

        // Start debugging
        self::log("Starting subscriptions method for sub-admin ID: " . App::Auth()->uid);

        $sub_admin_id = App::Auth()->uid;
        
        // First, get all salla_product_ids created by this sub-admin
        $products_sql = "SELECT salla_product_id FROM `" . Membership::mTable . "` 
                         WHERE created_by = ? AND salla_product_id IS NOT NULL";
        $sub_admin_products = Database::Go()->rawQuery($products_sql, array($sub_admin_id))->run();
        
        // If no products found for this sub-admin, return empty results
        if (!is_array($sub_admin_products) || count($sub_admin_products) == 0) {
            self::log("No products found for this sub-admin");
            $tpl->data = [];
            $tpl->pager = Paginator::instance();
            $tpl->template = 'sub_admin/subscriptions';
            return;
        }
        
        // Create an array of product IDs for use in the IN clause
        $product_ids = [];
        foreach ($sub_admin_products as $product) {
            if (!empty($product->salla_product_id)) {
                $product_ids[] = $product->salla_product_id;
            }
        }
        
        // If no valid product IDs found, return empty results
        if (empty($product_ids)) {
            self::log("No valid product IDs found for this sub-admin");
            $tpl->data = [];
            $tpl->pager = Paginator::instance();
            $tpl->template = 'sub_admin/subscriptions';
            return;
        }
        
        // Log the product IDs we're searching for
        self::log("Searching for subscriptions with product IDs: " . implode(", ", $product_ids));
        
        // Prepare the SQL IN condition with placeholders
        $in_placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        
        // Count query using IN clause
        $count_sql = "SELECT COUNT(*) AS total 
                      FROM `salla_subscriptions` 
                      WHERE salla_product_id IN ($in_placeholders)";
        
        $count_result = Database::Go()->rawQuery($count_sql, $product_ids)->first()->run();
        $total_items = $count_result && isset($count_result->total) ? (int)$count_result->total : 0;
        
        self::log("Count query result with IN clause: " . $total_items);
        
        // Setup pagination with the count result
        $pager = Paginator::instance();
        $pager->items_total = $total_items;
        $pager->default_ipp = App::Core()->perpage;
        $pager->path = Url::url(Router::$path, '?');
        $pager->paginate();
        
        if ($total_items > 0) {
            // Get the basic subscription data first
            $basic_sql = "SELECT * FROM `salla_subscriptions` 
                        WHERE salla_product_id IN ($in_placeholders) 
                        ORDER BY created_at DESC" . $pager->limit;
            
            $subscriptions = Database::Go()->rawQuery($basic_sql, $product_ids)->run();
            
            if (is_array($subscriptions) && !empty($subscriptions)) {
                self::log("Found " . count($subscriptions) . " subscriptions");
                
                // Load the related data separately to avoid JOIN issues
                $enriched_subscriptions = [];
                
                foreach ($subscriptions as $subscription) {
                    // Add the basic subscription data
                    $enriched = clone $subscription;
                    
                    // Get membership data
                    $membership_sql = "SELECT title, price, thumb FROM `" . Membership::mTable . "` 
                                      WHERE salla_product_id = ? LIMIT 1";
                    $membership = Database::Go()->rawQuery($membership_sql, array($subscription->salla_product_id))->first()->run();
                    
                    if ($membership) {
                        $enriched->membership_title = $membership->title;
                        $enriched->membership_price = $membership->price;
                        $enriched->membership_thumb = $membership->thumb;
                    }
                    
                    // Get user data if salla_customer_id exists
                    if (!empty($subscription->salla_customer_id)) {
                        $user_sql = "SELECT fname, lname, email FROM `" . User::mTable . "` 
                                    WHERE salla_customer_id = ? LIMIT 1";
                        $user = Database::Go()->rawQuery($user_sql, array($subscription->salla_customer_id))->first()->run();
                        
                        if ($user) {
                            $enriched->user_fname = $user->fname;
                            $enriched->user_lname = $user->lname;
                            $enriched->user_email = $user->email;
                        }
                    }
                    
                    $enriched_subscriptions[] = $enriched;
                }
                
                $tpl->data = $enriched_subscriptions;
                self::log("Successfully enriched " . count($enriched_subscriptions) . " subscriptions with related data");
            } else {
                self::log("Failed to retrieve subscriptions with the basic query");
                $tpl->data = [];
            }
        } else {
            $tpl->data = [];
        }
        
        $tpl->pager = $pager;
        
        // Log how many results were returned in final data
        if (is_array($tpl->data)) {
            self::log("Final number of subscriptions returned: " . count($tpl->data));
        } else {
            self::log("Final data is not an array. Setting empty array.");
            $tpl->data = []; // Ensure data is always an array even if empty
        }
        
        $tpl->template = 'sub_admin/subscriptions';
    }

    /**
     * subscriptionDetail
     * Show detailed information for a specific subscription
     * @param int $id
     * @return void
     */
    public function subscriptionDetail(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'sub_admin/';
        $tpl->title = "Subscription Detail";
        $tpl->caption = "Subscription Detail";
        $tpl->subtitle = null;

        self::log("Starting subscriptionDetail method for ID: " . $id);

        $sub_admin_id = App::Auth()->uid;

        // First, get the subscription by ID directly
        $subscription_sql = "SELECT * FROM `salla_subscriptions` WHERE id = ? LIMIT 1";
        $subscription = Database::Go()->rawQuery($subscription_sql, array($id))->first()->run();

        if (!$subscription) {
            self::log("Subscription not found. ID: " . $id);
            if (DEBUG) {
                $tpl->error = 'Invalid subscription ID ' . $id . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'sub_admin/error';
            return;
        }

        // Now check if this subscription's membership was created by this sub-admin
        $membership_check_sql = "SELECT id FROM `" . Membership::mTable . "` 
                           WHERE salla_product_id = ? AND created_by = ? LIMIT 1";
        $membership_check = Database::Go()->rawQuery($membership_check_sql,
            array($subscription->salla_product_id, $sub_admin_id))
            ->first()->run();

        if (!$membership_check) {
            self::log("Subscription doesn't belong to this sub-admin. ID: " . $id);
            if (DEBUG) {
                $tpl->error = 'Access denied for subscription ID ' . $id . ' [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'sub_admin/error';
            return;
        }

        // Enrich the subscription with related data
        $enriched = clone $subscription;

        // Get membership data separately
        $membership_sql = "SELECT title, price, thumb, description FROM `" . Membership::mTable . "` 
                      WHERE salla_product_id = ? LIMIT 1";
        $membership = Database::Go()->rawQuery($membership_sql, array($subscription->salla_product_id))->first()->run();

        if ($membership) {
            $enriched->membership_title = $membership->title;
            $enriched->membership_price = $membership->price;
            $enriched->membership_thumb = $membership->thumb;
            $enriched->membership_description = $membership->description;
        }

        // Get user data separately if salla_customer_id exists
        if (!empty($subscription->salla_customer_id)) {
            $user_sql = "SELECT id, fname, lname, email, city, country, address FROM `" . User::mTable . "` 
                    WHERE salla_customer_id = ? LIMIT 1";
            $user = Database::Go()->rawQuery($user_sql, array($subscription->salla_customer_id))->first()->run();

            if ($user) {
                $enriched->user_id = $user->id;
                $enriched->user_fname = $user->fname;
                $enriched->user_lname = $user->lname;
                $enriched->user_email = $user->email;
                $enriched->user_city = $user->city;
                $enriched->user_country = $user->country;
                $enriched->user_address = $user->address;
            }
        }

        $tpl->data = $enriched;
        self::log("Successfully loaded subscription detail for ID: " . $id);
        $tpl->template = 'sub_admin/subscription_detail';
    }
}
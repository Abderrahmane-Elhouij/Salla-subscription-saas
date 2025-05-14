<?php
/**
 * Standalone Sub-Admin Registration
 * This file bypasses all routing and authentication mechanisms
 */

// Start with a clean slate
session_start();

// Define the constant needed by the framework
define("_WOJO", true);

// Define BASEPATH constant which is needed by the Mailer class
$BASEPATH = str_replace('standalone_register.php', '', realpath(__FILE__));
define('BASEPATH', $BASEPATH);

// Include minimal required files
require_once("lib/config.ini.php");
require_once("bootstrap.php");
Bootstrap::init();
require_once("lib/Session.php");
require_once("lib/Database.php");
require_once("lib/Validator.php");
require_once("lib/User.php");
require_once("lib/Auth.php");
require_once("lib/App.php");
require_once("lib/Message.php");
require_once("lib/Utility.php");
require_once("lib/View.php");
require_once("lib/Mailer.php");

// Define necessary constants
define("UPLOADURL", "uploads");
define("ADMINVIEW", "view/admin");

// Create a registration form that matches the app's style
$registration_html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub-Admin Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link href="<?php echo $siteurl; ?>/view/admin/css/base.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $siteurl; ?>/view/admin/css/transition.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $siteurl; ?>/view/admin/css/progress.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $siteurl; ?>/view/admin/css/icon.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $siteurl; ?>/view/admin/css/message.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $siteurl; ?>/view/admin/css/login.css" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo $siteurl; ?>/assets/jquery.js"></script>
    <script type="text/javascript" src="<?php echo $siteurl; ?>/assets/global.js"></script>
    <style>
        /* Additional styles for the standalone registration page */
        #formContent {
            max-width: 500px;
        }
        .formRow {
            margin: 0.5rem 0;
        }
        .wojo.message {
            margin: 1rem;
            padding: 1rem;
            border-radius: 0.25rem;
        }
        .wojo.error.message {
            background-color: #FFF6F6;
            color: #9F3A38;
            border-left: 4px solid #9F3A38;
        }
        .wojo.success.message {
            background-color: #FCFFF5;
            color: #2C662D;
            border-left: 4px solid #2C662D;
        }
        .wojo.message .header {
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .wojo.message ul {
            margin: 0.5em 0;
            padding-left: 1em;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div id="formContent">
        <h2 class="center-align">Sub-Admin Registration</h2>
        <div class="fadeIn first">
            <img src="<?php echo $siteurl; ?>/uploads/avatars/default.svg" id="avatar" alt="User Icon"/>
        </div>
        
        <!-- Display success message if registration successful -->
        <?php if(isset($success) && $success): ?>
        <div class="wojo success message">
            <div class="header">Success</div>
            <p><?php echo $message; ?></p>
        </div>
        <div class="formFooter">
            <a href="<?php echo $siteurl; ?>/sub_admin/login" class="underlineHover">Go to Login</a>
        </div>
        <?php else: ?>
        
        <div id="registerform">            <!-- Display errors if any -->
            <?php if(count(Message::$msgs) > 0): ?>
            <div class="wojo error message">
                <div class="header">Error</div>
                <ul>
                    <?php foreach (Message::$msgs as $msg): ?>
                    <li><?php echo $msg; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <!-- Registration Form -->
            <form id="admin_form" name="admin_form" method="post">
                <div class="formRow">
                    <input type="text" class="fadeIn second" name="fname" placeholder="First Name" value="<?php echo Validator::post(\'fname\'); ?>">
                </div>
                
                <div class="formRow">
                    <input type="text" class="fadeIn second" name="lname" placeholder="Last Name" value="<?php echo Validator::post(\'lname\'); ?>">
                </div>
                
                <div class="formRow">
                    <input type="text" class="fadeIn third" name="email" placeholder="Email Address" value="<?php echo Validator::post(\'email\'); ?>">
                </div>
                
                <div class="formRow">
                    <input type="password" class="fadeIn fourth" name="password" placeholder="Password">
                </div>
                
                <div class="formRow">
                    <input type="password" class="fadeIn fourth" name="password2" placeholder="Confirm Password">
                </div>
                
                <input type="hidden" name="dosubmit" value="1">
                <button type="submit" name="submit" class="fadeIn fifth">Register</button>
            </form>
            
            <div class="formFooter">
                <p>Already have an account? <a href="<?php echo $siteurl; ?>/sub_admin/login" class="underlineHover">Login</a></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <footer> Copyright &copy;<?php echo date(\'Y\') . \' \' . App::Core()->company; ?></footer>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        // Handle message close functionality
        $(\'.message .close\').on(\'click\', function() {
            $(this).closest(\'.message\').transition(\'fade\');
        });
    });
</script>
</body>
</html>';

// Get site URL
$dir = (App::Core()->site_dir) ? '/' . App::Core()->site_dir : '';
$url = preg_replace('#/+#', '/', $_SERVER['HTTP_HOST'] . $dir);
$siteurl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . '://' . $url;

// Process form submission
$success = false;
$message = "";

if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
    $validate = Validator::run($_POST);
    $validate
        ->set('fname', 'First Name')->required()->string()->min_len(3)->max_len(60)
        ->set('lname', 'Last Name')->required()->string()->min_len(3)->max_len(60)
        ->set('email', 'Email')->required()->email()
        ->set('password', 'Password')->required()->string()->min_len(6)->max_len(20)
        ->set('password2', 'Confirm Password')->required()->string()->equals($_POST['password']);
        
    $safe = $validate->safe();
    
    // Check if email exists
    if (strlen($safe->email) !== 0 && App::Auth()->emailExists($safe->email)) {
        Message::$msgs['email'] = "Email already exists";
    }
    
    if (count(Message::$msgs) === 0) {
        // Generate user data
        $hash = App::Auth()->doHash($safe->password);
        $username = Utility::randomString();
        
        $data = array(
            'username' => $username,
            'email' => $safe->email,
            'lname' => $safe->lname,
            'fname' => $safe->fname,
            'hash' => $hash,
            'type' => 'sub_admin',
            'active' => 't', // Pending admin approval
            'userlevel' => 6, // Sub admin level
            'newsletter' => 0
        );
        
        // Insert user into database
        $last_id = Database::Go()->insert(User::mTable, $data)->run();
        
        if ($last_id) {
            $success = true;
            $message = "Your registration has been submitted successfully. You will receive an email when your account is approved.";
            
            // Add email sending functionality
            try {
                // Send notification email to admin
                $mailer = Mailer::sendMail();
                $core = App::Core();
                
                // Get admin email
                $admin = Database::Go()->select(User::mTable)
                    ->where('type', 'owner', '=')
                    ->first()->run();
                
                if ($admin) {
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
                    $mailer = Mailer::sendMail(); // Create new instance for second email
                    
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
                } else {
                    $message .= " However, no admin user was found to notify.";
                }
            } catch (Exception $e) {
                // Log the error but don't show it to the user
                error_log("Error sending registration emails: " . $e->getMessage());
            }
        } else {
            Message::$msgs['general'] = "An error occurred while processing your registration.";
        }
    }
}

// Render the template with PHP
ob_start();
eval('?>' . $registration_html);
$output = ob_get_clean();

// Output the final HTML
echo $output;
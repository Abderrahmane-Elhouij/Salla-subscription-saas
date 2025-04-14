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

// Create a basic registration form
$registration_html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sub-Admin Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f9fc; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { text-align: center; color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], input[type="email"], input[type="password"] { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
            font-size: 16px; box-sizing: border-box;
        }
        button { 
            background: #4a6ee0; color: white; border: none; padding: 12px 20px; 
            border-radius: 4px; width: 100%; font-size: 16px; cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { background: #3a5cc0; }
        .error { color: #e74c3c; margin-top: 20px; }
        .success { color: #2ecc71; margin-top: 20px; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #4a6ee0; text-decoration: none; }
        .login-link a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sub-Admin Registration</h1>
        
        <!-- Display errors if any -->
        <?php if(count(Message::$msgs) > 0): ?>
        <div class="error">
            <?php echo Message::display(); ?>
        </div>
        <?php endif; ?>
        
        <!-- Display success message if registration successful -->
        <?php if(isset($success) && $success): ?>
        <div class="success">
            <p><?php echo $message; ?></p>
            <div class="login-link">
                <a href="<?php echo $siteurl; ?>/sub_admin/login">Go to Login</a>
            </div>
        </div>
        <?php else: ?>
        
        <!-- Registration Form -->
        <form method="post" action="">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?php echo Validator::post(\'fname\'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="<?php echo Validator::post(\'lname\'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo Validator::post(\'email\'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label for="password2">Confirm Password</label>
                <input type="password" id="password2" name="password2" required>
            </div>
            
            <input type="hidden" name="dosubmit" value="1">
            <button type="submit">Register</button>
            
            <div class="login-link">
                Already have an account? <a href="<?php echo $siteurl; ?>/sub_admin/login">Login</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
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
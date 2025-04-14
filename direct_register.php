<?php
/**
 * Direct Sub-Admin Registration File
 * This file completely bypasses the routing and authentication checks
 */

// Start with a clean output buffer
ob_start();

// Define constants needed by the framework
define("_WOJO", true);

// Include the full initialization file to ensure all constants are available
require_once("init.php");

// Create a simplified standalone template
$tpl = new View(BASEPATH . 'view/');
$tpl->dir = 'sub_admin/';
$tpl->title = "Sub-Admin Registration";
$tpl->template = 'sub_admin/register_standalone';
$tpl->core = App::Core();

// Handle form submission
if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
    // Process form using direct class instantiation
    $validate = Validator::run($_POST);
    $validate
        ->set('fname', 'First Name')->required()->string()->min_len(3)->max_len(60)
        ->set('lname', 'Last Name')->required()->string()->min_len(3)->max_len(60)
        ->set('email', 'Email')->required()->email()
        ->set('password', 'Password')->required()->string()->min_len(6)->max_len(20)
        ->set('password2', 'Confirm Password')->required()->string()->equals($_POST['password']);
        
    $safe = $validate->safe();
    
    // Check if email exists
    $auth = new Auth();
    if (strlen($safe->email) !== 0 && $auth->emailExists($safe->email)) {
        Message::$msgs['email'] = "Email already exists";
    }
    
    if (count(Message::$msgs) === 0) {
        // Generate data
        $hash = $auth->doHash($safe->password);
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
        
        $db = Database::Go();
        $last_id = $db->insert(User::mTable, $data)->run();
        
        if ($last_id) {
            // Set success message
            $tpl->success = true;
            $tpl->message = "Your registration has been submitted successfully. You will receive an email when your account is approved.";
            
            // Attempt to send email notifications
            try {
                // Send notification to admin
                $mailer = Mailer::sendMail();
                $core = App::Core();
                
                // Get admin email
                $admin = $db->select(User::mTable)
                    ->where('type', 'owner', '=')
                    ->first()->run();
                
                // Only send emails if admin is found
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
                }
            } catch (Exception $e) {
                // Just log the error but don't stop the registration
                error_log("Email error: " . $e->getMessage());
            }
        } else {
            Message::msgSingleStatus();
        }
    }
}

// Render the template
echo $tpl->render();
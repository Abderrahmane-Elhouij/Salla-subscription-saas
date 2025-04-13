<?php
/**
 * Sub Admin Registration - Standalone file
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: sub_admin_register.php, v1.00 4/13/2025 2:41 PM
 *
 */
define("_WOJO", true);
require_once("init.php");

// Process form submission
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
            $success = true;
            $message = "Your registration has been submitted successfully. You will receive an email when your account is approved.";
        } else {
            Message::msgSingleStatus();
        }
    }
}

$tpl = App::View(BASEPATH . 'view/');
$tpl->dir = 'sub_admin/';
$tpl->title = "Sub-Admin Registration";
$tpl->template = 'sub_admin/register';
$tpl->core = App::Core();

if (isset($success)) {
    $tpl->success = $success;
    $tpl->message = $message;
}

echo $tpl->render();
?>
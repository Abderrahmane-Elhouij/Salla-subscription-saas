<?php
// Email test script
// This will help diagnose email sending issues

// Define the necessary constants
define("_WOJO", true);
$BASEPATH = str_replace('test_email.php', '', realpath(__FILE__));
define('BASEPATH', $BASEPATH);

// Include required files
require_once("lib/config.ini.php");
require_once("bootstrap.php");
Bootstrap::init();
require_once("lib/App.php");
require_once("lib/Mailer.php");

// Get the core settings
$core = App::Core();

// Display email settings for debugging
echo "<h2>Email Configuration</h2>";
echo "<p>Mailer Type: " . $core->mailer . "</p>";
echo "<p>SMTP Host: " . $core->smtp_host . "</p>";
echo "<p>SMTP User: " . $core->smtp_user . "</p>";
echo "<p>SMTP Port: " . $core->smtp_port . "</p>";
echo "<p>Is SSL: " . ($core->is_ssl ? 'Yes' : 'No') . "</p>";
echo "<p>Site Email: " . $core->site_email . "</p>";

// Try to send a test email
echo "<h2>Sending Test Email</h2>";

try {
    $mailer = Mailer::sendMail();
    
    // Enable debug mode
    $mailer->SMTPDebug = 2; // Detailed output
    
    // Set up test email
    $mailer->Subject = 'Test Email from Subscription System';
    $mailer->Body = "This is a test email to verify the email system is working properly.\n\nIf you received this, the SMTP configuration is correct.";
    $mailer->setFrom($core->site_email, $core->company);
    
    // Ask for test email address
    if(isset($_POST['test_email']) && filter_var($_POST['test_email'], FILTER_VALIDATE_EMAIL)) {
        $testEmail = $_POST['test_email'];
        
        $mailer->addAddress($testEmail);
        
        // Attempt to send
        ob_start(); // Capture debug output
        $result = $mailer->send();
        $debug = ob_get_clean();
        
        if($result) {
            echo "<p style='color:green;'>Email sent successfully to " . htmlspecialchars($testEmail) . "!</p>";
        } else {
            echo "<p style='color:red;'>Failed to send email. Error: " . $mailer->ErrorInfo . "</p>";
        }
        
        echo "<h3>Debug Output:</h3>";
        echo "<pre>" . htmlspecialchars($debug) . "</pre>";
    } else {
        // Show form to enter test email
        echo "<form method='post'>";
        echo "<p>Enter an email address to test:</p>";
        echo "<input type='email' name='test_email' required>";
        echo "<button type='submit'>Send Test Email</button>";
        echo "</form>";
    }
} catch (Exception $e) {
    echo "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
}
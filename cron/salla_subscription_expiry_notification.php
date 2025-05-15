<?php
/**
 * Subscription Expiry Notification Cron Job
 *
 * This script checks for Salla subscriptions that are about to expire in a week
 * and sends notification emails to the respective users.
 * Modified to ignore users table mem_expire field and focus only on salla_subscriptions.
 *
 * @package Subscription SaaS
 * @author Your Name
 * @version 1.2
 */

// Define the necessary constants
define("_WOJO", true);
$BASEPATH = dirname(dirname(__FILE__)) . '/';
define('BASEPATH', $BASEPATH);

// Include required files
require_once(BASEPATH . "/lib/config.ini.php");
require_once(BASEPATH . "/bootstrap.php");
Bootstrap::init();
require_once(BASEPATH . "/lib/App.php");
require_once(BASEPATH . "/lib/Mailer.php");
require_once(BASEPATH . "/lib/User.php");
require_once(BASEPATH . "/lib/Date.php");

// Get the core settings
$core = App::Core();

// Set execution time limit
@ini_set('max_execution_time', 360);
@set_time_limit(360);

// Create a log function
function logMessage($message)
{
    $logFile = BASEPATH . '/cron/salla_expiry_notification.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

// Log script start
//logMessage("Salla subscription expiry notification script started");

try {
    // Get current date
    $currentDate = date('Y-m-d');

    // Date one week from now
    $oneWeekFromNow = date('Y-m-d', strtotime('+7 days'));

    //logMessage("Checking for Salla subscriptions expiring on: " . $oneWeekFromNow);

    // Check the salla_subscriptions table for expiring subscriptions - without joins
    $subscriptionQuery = "SELECT * FROM `salla_subscriptions` 
                          WHERE status = 'active' 
                          AND DATE(end_date) = DATE('$oneWeekFromNow')";

    $subscriptions = Database::Go()->rawQuery($subscriptionQuery)->run();
    $subscriptionCount = count($subscriptions);
    //logMessage("Found $subscriptionCount Salla subscriptions expiring in a week");

    // Process Salla subscriptions
    foreach ($subscriptions as $subscription) {
        //logMessage("Processing subscription ID: {$subscription->id}, Email: {$subscription->customer_email}");

        // Get membership title directly without join
        $membershipTitle = "";
        if ($subscription->salla_product_id > 0) {
            $memQuery = "SELECT title FROM `" . Membership::mTable . "` WHERE salla_product_id = ?";
            $membership = Database::Go()->rawQuery($memQuery, array($subscription->salla_product_id))->first()->run();
            if ($membership) {
                $membershipTitle = $membership->title;
            } else {
                $membershipTitle = "Subscription #" . $subscription->membership_id;
            }
        } else {
            $membershipTitle = "Subscription";
        }

        // Get user if associated by salla_customer_id - without join
        $user = null;
        if (!empty($subscription->salla_customer_id)) {
            $userQuery = "SELECT * FROM `" . User::mTable . "` WHERE salla_customer_id = ?";
            $user = Database::Go()->rawQuery($userQuery, array($subscription->salla_customer_id))->first()->run();
        }

        // Set email and name based on available data
        $email = $user ? $user->email : $subscription->customer_email;
        $name = $user ? ($user->fname . ' ' . $user->lname) : $subscription->customer_name;

        // Format expiry date
        $expiryDate = Date::doDate('long_date', $subscription->end_date);

        // Calculate remaining days
        $remaining = floor((strtotime($subscription->end_date) - time()) / 86400);
        //logMessage("Subscription expires in $remaining days");

        // Use the helper method to send notification
        try {
            // Send email notification
            $subject = "Your " . $core->company . " Subscription is Expiring Soon";

            // Email body
            $body = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>";
            $body .= "<h2 style='color: #333;'>Subscription Expiry Notification</h2>";
            $body .= "<p>Hello <strong>" . $name . "</strong>,</p>";
            $body .= "<p>This is a friendly reminder that your <strong>" . $membershipTitle . "</strong> subscription is set to expire on <strong>" . $expiryDate . "</strong>.</p>";
            $body .= "<p>To continue enjoying our services without interruption, please renew your subscription before the expiration date.</p>";
            $body .= "<p>If you have any questions or need assistance, please don't hesitate to contact us.</p>";
            $body .= "<p>Thank you for being a valued subscriber!</p>";
            $body .= "<p>Best regards,<br>The " . $core->company . " Team</p>";
            $body .= "</div>";

            $mailer = Mailer::sendMail();
            $mailer->Subject = $subject;
            $mailer->Body = $body;
            $mailer->setFrom($core->site_email, $core->company);
            $mailer->addAddress($email, $name);
            $mailer->isHTML(true);

            if ($mailer->send()) {
                //logMessage("Successfully sent expiry notification email to subscriber: {$email}");
                return;
            } else {
                //logMessage("Failed to send email to subscriber: {$email}. Error: " . $mailer->ErrorInfo);
                return;
            }
        } catch (Exception $e) {
            logMessage("Exception while sending email to subscriber: {$email}. Error: " . $e->getMessage());
        }
    }

    // Log completion
    //logMessage("Salla subscription expiry notification script completed. Processed $subscriptionCount potential notifications.");

} catch (Exception $e) {
    logMessage("Critical error: " . $e->getMessage());
}

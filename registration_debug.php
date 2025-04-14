<?php
/**
 * Redirection debug script
 */
// Enable full error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Define the constant needed by the framework
define("_WOJO", true);

// Add a custom header to prevent caching 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Required includes
require_once("init.php");

// Log current information
echo "<h1>Registration Debug Information</h1>";
echo "<pre>";
echo "Current script: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "Current URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Server Name: " . $_SERVER['SERVER_NAME'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";

// Check if this route should bypass authentication
$auth = App::Auth();
$reflection = new ReflectionObject($auth);
$method = $reflection->getMethod('isPublicRoute');
$method->setAccessible(true);
echo "\nRoute check:\n";
echo "isPublicRoute() result: " . ($method->invoke($auth) ? 'true (this route should bypass auth)' : 'false (this route requires auth)') . "\n";
echo "User is logged in: " . ($auth->logged_in ? 'true' : 'false') . "\n";

// Test registration links
echo "</pre>";
echo "<h2>Test registration links:</h2>";
echo "<ul>";
echo "<li><a href='" . SITEURL . "/sub_admin_register.php'>Direct file: /sub_admin_register.php</a></li>";
echo "<li><a href='" . SITEURL . "/subadmin-register'>Route: /subadmin-register</a></li>";
echo "<li><a href='" . SITEURL . "/sub_admin/register'>Route: /sub_admin/register</a></li>";
echo "<li><a href='" . SITEURL . "/direct_register.php'>Direct alternate file: /direct_register.php</a></li>";
echo "</ul>";

// Provide a fixed link to the registration page
echo "<h2>Emergency Registration Access:</h2>";
echo "<p>If all else fails, use this direct form that bypasses all routing:</p>";
echo "<form method='post' action='" . SITEURL . "/sub_admin_register.php'>";
echo "<input type='text' name='fname' placeholder='First Name' required><br>";
echo "<input type='text' name='lname' placeholder='Last Name' required><br>";
echo "<input type='email' name='email' placeholder='Email' required><br>";
echo "<input type='password' name='password' placeholder='Password' required><br>";
echo "<input type='password' name='password2' placeholder='Confirm Password' required><br>";
echo "<input type='hidden' name='dosubmit' value='1'>";
echo "<button type='submit'>Register</button>";
echo "</form>";

// Print sessions info
echo "<h2>Session Information:</h2>";
echo "<pre>";
echo "Session ID: " . session_id() . "\n";
echo "Session variables: \n";
print_r($_SESSION);
echo "</pre>";

// Show a button to clear session
echo "<p><a href='?clear_session=1' style='color: red;'>Clear Session & Cookies</a></p>";

// Clear session if requested
if (isset($_GET['clear_session'])) {
    session_destroy();
    
    // Clear cookies
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
    
    echo "<p>Session and cookies cleared! <a href=''>Refresh</a></p>";
}
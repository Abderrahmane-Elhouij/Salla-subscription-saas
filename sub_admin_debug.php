<?php
/**
 * Debug script for sub-admin registration
 * This file will check the path handling and authentication bypass for sub-admin registration
 */

// Enable PHP error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define constants to match your application
define("_WOJO", true);
require_once("init.php");

// Output system information
echo "<h1>Sub-Admin Registration Debug Info</h1>";
echo "<h2>URL Information</h2>";
echo "<pre>";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "PATH_INFO: " . (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'Not set') . "\n";
echo "</pre>";

// Test the isPublicRoute method directly
echo "<h2>Route Authentication Check</h2>";
echo "<pre>";
$auth = App::Auth();
$reflection = new ReflectionObject($auth);
$method = $reflection->getMethod('isPublicRoute');
$method->setAccessible(true);
echo "isPublicRoute() result: " . ($method->invoke($auth) ? 'true' : 'false') . "\n";
echo "Current path should bypass auth: " . ($auth->logged_in ? 'No (logged in)' : 'Yes (not logged in)') . "\n";
echo "</pre>";

// Show available routes
echo "<h2>Registration Routes</h2>";
echo "<ul>";
echo "<li><a href='" . SITEURL . "/sub_admin_register.php'>Direct File: /sub_admin_register.php</a> (Should always work)</li>";
echo "<li><a href='" . SITEURL . "/subadmin-register'>Route: /subadmin-register</a></li>";
echo "<li><a href='" . SITEURL . "/sub_admin/register'>Route: /sub_admin/register</a></li>";
echo "</ul>";

echo "<p>Click each link to test if it works correctly</p>";
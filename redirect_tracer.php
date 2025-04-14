<?php
/**
 * Redirect Tracer Script
 * This script tracks the entire HTTP redirect chain to debug redirection issues
 */

// Set this script to send plain text
header('Content-Type: text/plain');

// Start tracking session
session_start();

// Record script entry
echo "== Redirect Tracer ==\n";
echo "Current Script: " . $_SERVER['PHP_SELF'] . "\n";
echo "Current URL: " . $_SERVER['REQUEST_URI'] . "\n";
echo "HTTP Referer: " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'None') . "\n";

// Check if we've previously recorded redirects
if (!isset($_SESSION['redirect_trace'])) {
    $_SESSION['redirect_trace'] = [];
}

// Add this URL to the trace
$_SESSION['redirect_trace'][] = $_SERVER['REQUEST_URI'];

// Show full trace
echo "\nRedirect Chain:\n";
foreach ($_SESSION['redirect_trace'] as $i => $url) {
    echo ($i+1) . ": " . $url . "\n";
}

// Clear session if requested
if (isset($_GET['clear'])) {
    $_SESSION['redirect_trace'] = [];
    echo "\nRedirect trace cleared!\n";
}

// Show links to test routes
echo "\nTest Links:\n";
echo "- Direct access file: http://localhost:8000/sub_admin_register.php\n";
echo "- Route /subadmin-register: http://localhost:8000/subadmin-register\n";
echo "- Route /sub_admin/register: http://localhost:8000/sub_admin/register\n";
echo "- Sub-Admin Login: http://localhost:8000/sub_admin/login\n";
echo "- Clear trace: http://localhost:8000/redirect_tracer.php?clear=1\n";

// Exit
exit;
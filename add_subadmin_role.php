<?php
/**
 * Add SubAdmin Role
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: add_subadmin_role.php, v1.00 4/13/2025 2:41 PM
 *
 */
define("_WOJO", true);
require_once("init.php");

if (!App::Auth()->is_Admin() || !App::Auth()->checkAcl('owner')) {
    die('Direct access to this location is not allowed.');
}

// Read the SQL file
$sql_file = file_get_contents('add_subadmin_role.sql');

// Split SQL into individual queries
$queries = preg_split('/;\s*$/m', $sql_file);

// Execute each query
$count = 0;
foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        try {
            Database::Go()->rawQuery($query)->run();
            $count++;
        } catch (Exception $e) {
            echo '<div style="color:red">Error executing query: ' . htmlspecialchars($query) . '<br>Error message: ' . $e->getMessage() . '</div>';
        }
    }
}

echo '<div style="color:green">Successfully executed ' . $count . ' queries. The sub-admin role has been added to your system.</div>';
echo '<div><a href="' . SITEURL . '/admin">Click here to go back to admin</a></div>';
?>
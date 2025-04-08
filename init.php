<?php
    /**
     * init
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.0: init.php, v1.00 7/1/2023 4:41 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
    $BASEPATH = str_replace('init.php', '', realpath(__FILE__));
    define('BASEPATH', $BASEPATH);
    
    $configFile = BASEPATH . 'lib/config.ini.php';
if (file_exists($configFile)) {
    require_once($configFile);
} else {
    header('Location: setup/');
    exit;
}
    
    require_once(BASEPATH . 'bootstrap.php');
    Bootstrap::init();
    new Session;
    Debug::run();
    wError::run();
    Filter::run();
    Language::run();
    
    define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    
    const ADMIN = BASEPATH . 'admin/';
    const FRONT = BASEPATH . 'front/';
    
    $dir = (App::Core()->site_dir) ? '/' . App::Core()->site_dir : '';
    $url = preg_replace('#/+#', '/', $_SERVER['HTTP_HOST'] . $dir);
    $site_url = Url::protocol() . '://' . $url;
    
    define('SITEURL', $site_url);
    const UPLOADURL = SITEURL . '/uploads';
    const UPLOADS = BASEPATH . 'uploads';
    
    const ADMINURL = SITEURL . '/admin';
    const ADMINVIEW = SITEURL . '/view/admin';
    const ADMINBASE = BASEPATH . 'view/admin';
    
    const FRONTVIEW = SITEURL . '/view/front';
    const FRONTBASE = BASEPATH . 'view/front';

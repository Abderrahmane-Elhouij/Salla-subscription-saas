<?php
    /**
     * index
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.0: index.php, v1.00 7/1/2023 4:41 PM Gewa Exp $
     *
     */
    
    const _WOJO = true;
    
    include('init.php');
    $router = new Router();
    $tpl = App::View(BASEPATH . 'view/');
    $core = App::Core();
    
    // PUBLIC ROUTE FOR SUB-ADMIN REGISTRATION - Place this BEFORE all other routes
    $router->match('GET|POST', '/subadmin-register', function () use ($tpl) {
        $tpl->dir = 'sub_admin/';
        $tpl->title = "Sub-Admin Registration";
        $tpl->template = 'sub_admin/register';
        $tpl->core = App::Core();
        
        if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
            App::SubAdmin()->register();
        }
    });
    
    // Direct access route for sub-admin registration 
    $router->match('GET|POST', '/sub_admin/register', function () use ($tpl) {
        // Explicitly disable any redirects for this route
        Session::set('no_redirect', true);
        
        $tpl->dir = 'sub_admin/';
        $tpl->title = "Sub-Admin Registration";
        $tpl->template = 'sub_admin/register';
        $tpl->core = App::Core();
        
        // Process registration if form submitted
        if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
            App::SubAdmin()->register();
        }
    });
    
    //admin routes
    $router->mount('/admin', function () use ($router, $tpl) {
        //admin login
        $router->match('GET|POST', '/login', function () use ($tpl) {
            if (App::Auth()->is_Admin()) {
                if (App::Auth()->is_SubAdmin()) {
                    Url::redirect(SITEURL . '/sub_admin/');
                } else {
                    Url::redirect(SITEURL . '/admin/');
                }
                exit;
            }
            
            $tpl->template = 'admin/login';
            $tpl->title = Language::$word->LOGIN;
        });
        
        //admin index
        $router->get('/', 'Admin@index');
        
        //admin users
        $router->mount('/users', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'User@index');
            $router->match('GET|POST', '/grid', 'User@index');
            $router->get('/history/(\d+)', 'User@history');
            $router->get('/edit/(\d+)', 'User@edit');
            $router->get('/new', 'User@save');
        });
        
        //admin memberships
        $router->mount('/memberships', function () use ($router, $tpl) {
            $router->match('GET', '/', 'Membership@index');
            $router->get('/history/(\d+)', 'Membership@history');
            $router->get('/edit/(\d+)', 'Membership@edit');
            $router->get('/new', 'Membership@save');
        });
        
        //admin email templates
        $router->mount('/templates', function () use ($router, $tpl) {
            $router->get('/', 'Content@templateIndex');
            $router->get('/edit/(\d+)', 'Content@templateEdit');
        });
        
        //admin countries
        $router->mount('/countries', function () use ($router, $tpl) {
            $router->get('/', 'Content@countryIndex');
            $router->get('/edit/(\d+)', 'Content@countryEdit');
        });
        
        //admin coupons
        $router->mount('/coupons', function () use ($router, $tpl) {
            $router->get('/', 'Content@couponIndex');
            $router->get('/edit/(\d+)', 'Content@couponEdit');
            $router->get('/new', 'Content@couponSave');
        });
        
        //admin pages
        $router->mount('/pages', function () use ($router, $tpl) {
            $router->get('/', 'Content@pageIndex');
            $router->get('/edit/(\d+)', 'Content@pageEdit');
            $router->get('/new', 'Content@pageSave');
        });
        
        //admin custom fields
        $router->mount('/fields', function () use ($router, $tpl) {
            $router->get('/', 'Content@fieldIndex');
            $router->get('/edit/(\d+)', 'Content@fieldEdit');
            $router->get('/new', 'Content@fieldSave');
        });
        
        //admin news
        $router->mount('/news', function () use ($router, $tpl) {
            $router->get('/', 'Content@newsIndex');
            $router->get('/edit/(\d+)', 'Content@newsEdit');
            $router->get('/new', 'Content@newsSave');
        });
        
        //admin account
        $router->mount('/account', function () use ($router, $tpl) {
            $router->get('/', 'Admin@account');
            $router->get('/password', 'Admin@password');
        });
        
        //admin gateways
        $router->mount('/gateways', function () use ($router, $tpl) {
            $router->get('/', 'Admin@gatewayIndex');
            $router->get('/edit/(\d+)', 'Admin@gatewayEdit');
        });
        
        //admin permissions
        $router->mount('/roles', function () use ($router, $tpl) {
            $router->get('/', 'Admin@role');
            $router->get('/edit/(\d+)', 'Admin@roleEdit');
        });
        
        //admin utilities manager
        $router->get('/utilities', 'Admin@utilities');
        
        //admin backup
        $router->get('/backup', 'Admin@backup');
        
        //admin files
        $router->get('/files', 'Admin@fileIndex');
        
        //admin newsletter
        $router->get('/mailer', 'Admin@mailer');
        
        //admin system
        $router->get('/system', 'Admin@system');
        
        //admin transactions
        $router->match('GET|POST', '/transactions', 'Admin@transactions');
        
        //admin configuration
        $router->get('/configuration', 'Core@index');
        
        //admin help
        //$router->get('/help', 'Admin@Help');
        
        //admin trash
        $router->get('/trash', 'Admin@trash');
        
        //admin language manager
        $router->get('/language', 'Language@index');
        
        //logout
        $router->get('/logout', function () {
            App::Auth()->logout();
            Url::redirect(SITEURL . '/admin/');
        });
    });
    
    //sub-admin routes
    $router->mount('/sub_admin', function () use ($router, $tpl) {
        // sub-admin registration page - move this BEFORE the login route for proper processing order
        $router->match('GET|POST', '/register', function () use ($tpl) {
            $tpl->dir = 'sub_admin/';
            $tpl->title = "Sub-Admin Registration";
            $tpl->template = 'sub_admin/register';
            $tpl->core = App::Core();
            
            if (isset($_POST['dosubmit']) && $_POST['dosubmit'] == '1') {
                App::SubAdmin()->register();
            }
        });
        
        //sub-admin login - uses same login page as admin
        $router->match('GET|POST', '/login', function () use ($tpl) {
            if (App::Auth()->is_Admin()) {
                Url::redirect(SITEURL . '/sub_admin/');
                exit;
            }
            
            $tpl->template = 'admin/login';
            $tpl->title = Language::$word->LOGIN;
            $tpl->section = 'sub_admin'; // Add section identifier
        });
        
        // Add Salla store connection routes
        $router->get('/connect', 'SubAdmin@connectStore');
        $router->get('/salla/callback', 'SubAdmin@handleSallaCallback');
        
        // Add Salla webhook endpoint
        $router->post('/salla/webhook', 'SubAdmin@handleSallaWebhook');
        
        //sub-admin index
        $router->get('/', 'SubAdmin@index');
        
        //sub-admin users - only shows users created by this sub-admin
        $router->mount('/users', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'SubAdmin@userIndex');
            $router->match('GET|POST', '/grid', 'SubAdmin@userIndex');
            $router->get('/edit/(\d+)', 'SubAdmin@userEdit');
            $router->get('/new', 'SubAdmin@userSave');
        });
        
        //sub-admin memberships - only shows memberships created by this sub-admin
        $router->mount('/memberships', function () use ($router, $tpl) {
            $router->match('GET', '/', 'SubAdmin@membershipIndex');
            $router->get('/edit/(\d+)', 'SubAdmin@membershipEdit');
            $router->get('/new', 'SubAdmin@membershipSave');
            // New route for editing Salla products
            $router->get('/edit-salla-product/(\d+)', 'SubAdmin@editSallaProduct');
            // API endpoint for updating Salla products
            $router->post('/update-salla-product', 'SubAdmin@updateSallaProduct');
        });
        
        //sub-admin account
        $router->mount('/account', function () use ($router, $tpl) {
            $router->get('/', 'SubAdmin@account');
            $router->get('/password', 'SubAdmin@password');
        });
        
        //sub-admin files - limited access
        $router->get('/files', 'SubAdmin@fileIndex');
        
        //sub-admin subscriptions
        $router->mount('/subscriptions', function () use ($router, $tpl) {
            $router->match('GET|POST', '/', 'SubAdmin@subscriptions');
            $router->get('/detail/(\d+)', 'SubAdmin@subscriptionDetail');
            $router->get('/update-order/(\d+)', 'SubAdmin@updateOrder');
            $router->post('/process-update-order', 'SubAdmin@processUpdateOrder');
        });
        
        //logout
        $router->get('/logout', function () {
            App::Auth()->logout();
            Url::redirect(SITEURL . '/sub_admin/');
        });
    });
    
    //front end routes
    $router->match('GET|POST', '/', 'Front@index');
    $router->match('GET|POST', '/login', 'Front@login');
    
    if ($core->reg_allowed) {
        $router->match('GET|POST', '/register', 'Front@register');
    }
    
    $router->get('/contact', 'Front@Contact');
    $router->get('/activation', 'Front@activation');
    $router->get('/news', 'Front@news');
    $router->get('/validate', 'Front@validate');
    $router->match('GET|POST', '/password/([a-z0-9_-]+)', 'Front@password');
    
    $router->match('GET|POST', '/page/([a-z0-9_-]+)', 'Front@page');
    
    $router->mount('/dashboard', function () use ($router, $tpl) {
        $router->match('GET|POST', '/', 'Front@dashboard');
        $router->get('/history', 'Front@history');
        $router->get('/profile', 'Front@profile');
        $router->get('/downloads', 'Front@downloads');
    });
    
    //Custom Routes add here
    $router->get('/logout', function () {
        App::Auth()->logout();
        Url::redirect(SITEURL . '/');
    });
    
    //404
    $router->set404(function () use ($core, $router) {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = $router->segments[0] == 'admin' ? 'admin/' : 'front/';
        $tpl->core = $core;
        $tpl->auth = App::Auth();
        $tpl->db = App::Database();
        $tpl->segments = $router->segments;
        $tpl->template = $router->segments[0] == 'admin' ? 'admin/404' : 'front/404';
        $tpl->title = Language::$word->META_ERROR;
        $tpl->keywords = null;
        $tpl->description = null;
        $tpl->pages = Database::Go()->select(Content::pTable, array('title', 'slug', 'is_hide', 'membership_id', 'page_type'))->where('active', 1, '=')->orderBy('sorting', 'ASC')->run();
        echo $tpl->render();
    });
    
    // Run router
    $router->run(function () use ($tpl, $core, $router) {
        $tpl->segments = $router->segments;
        $tpl->core = $core;
        $tpl->auth = App::Auth();
        if(!str_starts_with($router->segments[0], 'admin')) {
            $tpl->pages = Database::Go()->select(Content::pTable, array('title', 'slug', 'is_hide', 'membership_id', 'page_type'))->where('active', 1, '=')->orderBy('sorting', 'ASC')->run();
        }
        echo $tpl->render();
    });
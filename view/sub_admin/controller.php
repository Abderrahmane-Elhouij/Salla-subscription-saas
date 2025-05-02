<?php
/**
 * Controller
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: controller.php, v1.00 4/13/2025 2:41 PM
 *
 */
if (!defined("_WOJO")) {
    die('Direct access to this location is not allowed.');
}

if (!Auth::is_SubAdmin()) {
    Url::redirect(SITEURL . '/index.php');
    exit;
}

// Get page actions
$pAction = Url::segment($this->segments, 1);
$gAction = get('action');
$dir = "sub_admin/";

// Set core actions
switch ($pAction) {
    // Dashboard
    case 'index': 
        $core->setTitle(Language::$word->SUB_ADMIN_DASHBOARD);
        break;
        
    // Users section
    case 'users':
        switch (Url::segment($this->segments, 2)) {
            // Edit user
            case 'edit':
                $core->setTitle(Language::$word->META_T3);
                break;
                
            // New user
            case 'new':
                $core->setTitle(Language::$word->META_T4);
                break;
                
            // User list
            default:
                $core->setTitle(Language::$word->META_T2);
                break;
        }
        break;
        
    // Membership section
    case 'memberships':
        switch (Url::segment($this->segments, 2)) {
            // Edit membership
            case 'edit':
                $id = Url::segment($this->segments, 3);
                if ($id) {
                    // Check if membership belongs to this sub-admin
                    $membership = Database::Go()->select(Membership::mTable)
                        ->where('id', $id, '=')
                        ->where('created_by', App::Auth()->uid, '=')
                        ->first()->run();
                        
                    if ($membership) {
                        $tpl = App::View(BASEPATH . 'view/');
                        $tpl->dir = 'sub_admin/';
                        $tpl->data = $membership;
                        $tpl->title = Language::$word->META_T7;
                    } else {
                        Url::redirect(SITEURL . '/sub_admin/memberships');
                    }
                }
                $core->setTitle(Language::$word->META_T7);
                break;
                
            // New membership
            case 'new':
                $core->setTitle(Language::$word->META_T8);
                break;
                
            // Membership list
            default:
                $tpl = App::View(BASEPATH . 'view/');
                $tpl->dir = 'sub_admin/';
                
                $pager = Paginator::instance();
                $pager->items_total = Database::Go()->count(Membership::mTable)
                    ->where('created_by', App::Auth()->uid, '=')
                    ->run();
                $pager->default_ipp = App::Core()->perpage;
                $pager->path = Url::url(Router::$path, "?");
                $pager->paginate();
                
                $sql = "SELECT * FROM " . Membership::mTable . " 
                        WHERE created_by = ? 
                        ORDER BY id DESC " . $pager->limit;
                
                $tpl->data = Database::Go()->rawQuery($sql, array(App::Auth()->uid))->run();
                $tpl->pager = $pager;
                $tpl->title = Language::$word->META_T6;
                break;
        }
        break;
        
    // Account section
    case 'account':
        $core->setTitle(Language::$word->M_TITLE);
        break;
        
    // Password section
    case 'password':
        $core->setTitle(Language::$word->M_SUB2);
        break;
        
    // Files section
    case 'files':
        $core->setTitle(Language::$word->META_T35);
        break;
        
    // Subscriptions section
    case 'subscriptions':
        switch (Url::segment($this->segments, 2)) {
            // Subscription detail view
            case 'detail':
                $core->setTitle("Subscription Detail");
                break;
                
            // Subscriptions list
            default:
                $core->setTitle("Salla Subscriptions");
                break;
        }
        break;
        
    // Default
    default:
        $core->setTitle(Language::$word->SUB_ADMIN_DASHBOARD);
        break;
}

if (isset($_GET['pgtitle'])) {
    $core->setTitle(Validator::sanitize($_GET['pgtitle']));
}

// Debug mode for created_by field
if (isset($_POST['action']) && $_POST['action'] == 'processUser') {
    SubAdmin::log("Processing user from subadmin. Auth UID: " . App::Auth()->uid);
    SubAdmin::log("POST data: " . print_r($_POST, true));
}

// Process actions
switch (Filter::$action):
    // Process User
    case "processUser":
        // Ensure created_by is set explicitly for sub-admin created users
        if (App::Auth()->type == 'sub_admin') {
            SubAdmin::log("Setting created_by explicitly to: " . App::Auth()->uid);
            $_POST['created_by'] = App::Auth()->uid;
        }
        App::User()->processUser();
        break;

    // Process Membership
    case "processMembership":
        // Ensure created_by is set explicitly for sub-admin created memberships
        if (App::Auth()->type == 'sub_admin' && !isset($_POST['created_by'])) {
            $_POST['created_by'] = App::Auth()->uid;
        }
        App::Membership()->processMembership();
        break;
endswitch;

// Process Ajax requests
if (isset($_POST['action'])):
    switch ($_POST['action']):
        /* == Process Membership == */
        case "processMembership":
            processMembership();
            break;
            
        /* == Delete Membership == */
        case "deleteMembership":
            if(Filter::$id) {
                // Check if this membership belongs to the sub-admin
                $membership = Database::Go()->select(Membership::mTable)
                    ->where('id', Filter::$id, '=')
                    ->where('created_by', App::Auth()->uid, '=')
                    ->first()->run();
                    
                if($membership) {
                    $res = Database::Go()->delete(Membership::mTable)->where("id", Filter::$id, "=")->run();
                    if ($res) {
                        $json['type'] = 'success';
                        $json['title'] = Language::$word->SUCCESS;
                        $json['message'] = str_replace("[NAME]", $membership->title, Lang::$word->MEM_DEL_OK);
                    } else {
                        $json['type'] = 'error';
                        $json['title'] = Language::$word->ERROR;
                        $json['message'] = Lang::$word->NOPROCCESS;
                    }
                    print json_encode($json);
                } else {
                    $json['type'] = 'error';
                    $json['title'] = Language::$word->ERROR;
                    $json['message'] = Language::$word->NOACCESS;
                    print json_encode($json);
                }
            }
            break;
    endswitch;
endif;

/**
 * processMembership
 * 
 * Processes membership data for creation and updates
 * Ensures that sub-admins can only modify their own memberships
 *
 * @return void
 */
function processMembership(): void
{
    $validate = Validator::run($_POST);
    $validate
        ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(60)
        ->set('description', Language::$word->DESCRIPTION)->required()->string()
        ->set('price', Language::$word->MEM_PRICE)->required()->float()
        ->set('days', Language::$word->MEM_DAYS)->required()->numeric()
        ->set('recurring', Language::$word->MEM_REC)->required()->numeric()
        ->set('private', Language::$word->MEM_PRIVATE)->required()->numeric()
        ->set('active', Language::$word->PUBLISHED)->required()->numeric();
    
    $safe = $validate->safe();
    
    // Only try to upload a thumbnail if a file was actually uploaded
    $thumb = null;
    if (isset($_FILES['thumb']) && !empty($_FILES['thumb']['name'])) {
        $thumb = File::upload('thumb', 3145728, 'png,jpg,jpeg');
    }
    
    if (count(Message::$msgs) === 0) {
        $data = array(
            'title' => $safe->title,
            'description' => $safe->description,
            'price' => $safe->price,
            'days' => $safe->days,
            'period' => isset($safe->period) ? $safe->period : 'D',
            'recurring' => $safe->recurring,
            'private' => $safe->private,
            'active' => $safe->active,
            'created_by' => App::Auth()->uid, // Always set created_by to the current sub-admin
        );
        
        // For editing, verify the membership belongs to this sub-admin
        if (Filter::$id) {
            $membership = Database::Go()->select(Membership::mTable)
                ->where('id', Filter::$id, '=')
                ->where('created_by', App::Auth()->uid, '=')
                ->first()->run();
            
            if (!$membership) {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->NOACCESS;
                print json_encode($json);
                return;
            }
        }
        
        // Only process the thumbnail if it was uploaded
        if ($thumb && array_key_exists('thumb', $_FILES) && !empty($_FILES['thumb']['name'])) {
            $thumbPath = UPLOADS . '/memberships/';
            $result = File::process($thumb, $thumbPath, 'MEM_');
            $data['thumb'] = $result['fname'];
        }
        
        // Check if we need to delete thumbnail
        if (isset($_POST['thumb_delete']) && $_POST['thumb_delete'] == 1 && Filter::$id) {
            $data['thumb'] = '';
        }
        
        if (Filter::$id) {
            $result = Database::Go()->update(Membership::mTable, $data)->where('id', Filter::$id, '=')->run();
            $message = Message::formatSuccessMessage($data['title'], Language::$word->MEM_UPDATE_OK);
            $type = 'update';
        } else {
            $result = Database::Go()->insert(Membership::mTable, $data)->run();
            Filter::$id = Database::Go()->insertid();
            $message = Message::formatSuccessMessage($data['title'], Language::$word->MEM_ADDED_OK);
            $type = 'add';
        }
        
        $json['type'] = 'success';
        $json['title'] = Language::$word->SUCCESS;
        $json['message'] = $message;
        $json['redirect'] = Url::url('/sub_admin/memberships');
        $json['type'] = $type;
        print json_encode($json);
    } else {
        $json['type'] = 'error';
        $json['title'] = Language::$word->ERROR;
        $json['message'] = Message::$msgs['name'];
        print json_encode($json);
    }
}
?>
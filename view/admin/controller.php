<?php
    /**
     * controller
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: controller.php, v1.00 7/3/2023 8:27 AM Gewa Exp $
     *
     */
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!App::Auth()->is_Admin()) {
        exit;
    }
    
    $delete = Validator::post('delete');
    $trash = Validator::post('trash');
    $action = Validator::post('action');
    $restore = Validator::post('restore');
    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
    
    /* == Actions == */
    switch ($action):
        //Process Page
        case 'processPage':
            App::Content()->processPage();
            break;
        
        //Process News
        case 'processNews':
            App::Content()->processNews();
            break;
        
        //Process Coupon
        case 'processCoupon':
            App::Content()->processCoupon();
            break;
        
        //Process Field
        case 'processField':
            App::Content()->processField();
            break;
        
        //Process Template
        case 'processTemplate':
            App::Content()->processTemplate();
            break;
        
        //Process Country
        case 'processCountry':
            App::Content()->processCountry();
            break;
        
        //Process Membership
        case 'processMembership':
            App::Membership()->processMembership();
            break;
        
        //Process User
        case 'processUser':
            App::User()->processUser();
            break;
        
        //Update Account
        case 'updateAccount':
            App::Admin()->updateAccount();
            break;
        
        //Update Password
        case 'updatePassword':
            App::Admin()->updateAdminPassword();
            break;
        
        //Process Gateway
        case 'processGateway':
            App::Admin()->processGateway();
            break;
        
        //Process Mailer
        case 'processMailer':
            App::Admin()->processMailer();
            break;
        
        //Delete Inactive users
        case 'processInactive':
            Stats::deleteInactive(intval($_POST['days']));
            break;
        
        //Delete Banned Users
        case 'processBanned':
            Stats::deleteBanned();
            break;
        
        //Delete Cart
        case 'processCart':
            Stats::emptyCart();
            break;
        
        //Process Configuration
        case 'processConfig':
            App::Core()->processConfig();
            break;
    endswitch;
    
    /* == Trash == */
    switch ($action):
        //Trash Page
        case 'trashPage':
            if ($row = Database::Go()->select(Content::pTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'page',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                Database::Go()->insert(Core::txTable, $data)->run();
                Database::Go()->delete(Content::pTable)->where('id', $row->id, '=')->run();
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->PG_TRASH_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
            break;
        
        //Trash News
        case 'trashNews':
            if ($row = Database::Go()->select(Content::nTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'news',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                Database::Go()->insert(Core::txTable, $data)->run();
                Database::Go()->delete(Content::nTable)->where('id', $row->id, '=')->run();
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->NW_TRASH_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
            break;
        
        //Trash Coupon
        case 'trashCoupon':
            if ($row = Database::Go()->select(Content::dcTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'coupon',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                Database::Go()->insert(Core::txTable, $data)->run();
                Database::Go()->delete(Content::dcTable)->where('id', $row->id, '=')->run();
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->DC_TRASH_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
            break;
        
        //Trash Membership
        case 'trashMembership':
            if ($row = Database::Go()->select(Membership::mTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'membership',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                Database::Go()->insert(Core::txTable, $data)->run();
                Database::Go()->delete(Membership::mTable)->where('id', $row->id, '=')->run();
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->MEM_TRASH_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
            break;
        
        //Trash User
        case 'trashUser':
            if ($row = Database::Go()->select(User::mTable)->where('id', Filter::$id, '=')->first()->run()) {
                $data = array(
                    'type' => 'user',
                    'parent_id' => Filter::$id,
                    'dataset' => json_encode($row)
                );
                Database::Go()->insert(Core::txTable, $data)->run();
                Database::Go()->delete(User::mTable)->where('id', $row->id, '=')->run();
            }
            
            $message = str_replace('[NAME]', $title, Language::$word->M_TRASH_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
            break;
    endswitch;
    
    /* == Delete Actions == */
    switch ($delete):
        //Delete Custom Field
        case 'deleteField':
            if ($row = Database::Go()->delete(Content::cfTable)->where('id', Filter::$id, '=')->run()) {
                Database::Go()->delete(User::cfTable)->where('field_id', Filter::$id, '=')->run();
                $json['type'] = 'success';
            }
            
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->CF_DEL_OK);
            print json_encode($json);
            break;
        
        //Delete File
        case 'deleteFile':
            if ($row = Database::Go()->select(Content::fTable)->where('id', Filter::$id, '=')->first()->run()) {
                File::deleteFile(App::Core()->file_dir . $row->alias);
                Database::Go()->delete(Content::fTable)->where('id', $row->id, '=')->run();
                $json['type'] = 'success';
            }
            
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->FM_DEL_OK);
            print json_encode($json);
            break;
        
        //Delete Database Backup
        case 'deleteBackup':
            File::deleteFile(UPLOADS . '/backups/' . $title);
            
            $json['type'] = 'success';
            $json['title'] = Language::$word->SUCCESS;
            $json['message'] = str_replace('[NAME]', $title, Language::$word->DBM_DEL_OK);
            print json_encode($json);
            break;
        
        //Delete Trash
        case 'trashAll':
            Database::Go()->truncate(Core::txTable)->run();
            Message::msgReply(true, 'success', Language::$word->TRASH_DEL_OK);
            break;
        
        //Delete User
        case 'deleteUser':
            $res = Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
            Message::msgReply($res, 'success', str_replace('[NAME]', $title, Language::$word->M_DELETE_OK));
            break;
        
        //Delete Coupon
        case 'deleteCoupon':
            $res = Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
            Message::msgReply($res, 'success', str_replace('[NAME]', $title, Language::$word->DC_DELETE_OK));
            break;
        
        //Delete News
        case 'deleteNews':
            $res = Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
            Message::msgReply($res, 'success', str_replace('[NAME]', $title, Language::$word->NW_DELETE_OK));
            break;
        
        //Delete Page
        case 'deletePage':
            $res = Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
            Message::msgReply($res, 'success', str_replace('[NAME]', $title, Language::$word->PG_DELETE_OK));
            break;
        
        //Delete Membership
        case 'deleteMembership':
            $res = Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
            Message::msgReply($res, 'success', str_replace('[NAME]', $title, Language::$word->MEM_DELETE_OK));
            break;
    endswitch;
    
    /* == Restore Actions == */
    switch ($restore):
        //Restore Database
        case 'restoreBackup':
            DatabaseTools::doRestore($title);
            break;
        
        //Restore User
        case 'restoreUser':
            if ($result = Database::Go()->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, User::mTable);
                Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                
                Message::msgReply(true, 'success', str_replace('[NAME]', $title, Language::$word->M_RESTORE_OK));
            }
            break;
        
        //Restore Coupon
        case 'restoreCoupon':
            if ($result = Database::Go()->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::dcTable);
                Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                
                Message::msgReply(true, 'success', str_replace('[NAME]', $title, Language::$word->DC_RESTORE_OK));
            }
            break;
        
        //Restore Page
        case 'restorePage':
            if ($result = Database::Go()->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::pTable);
                Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                
                Message::msgReply(true, 'success', str_replace('[NAME]', $title, Language::$word->PG_RESTORE_OK));
            }
            break;
        
        //Restore News
        case 'restoreNews':
            if ($result = Database::Go()->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Content::nTable);
                Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                
                Message::msgReply(true, 'success', str_replace('[NAME]', $title, Language::$word->NW_RESTORE_OK));
            }
            break;
        
        //Restore Membership
        case 'restoreMembership':
            if ($result = Database::Go()->select(Core::txTable, array('dataset'))->where('id', Filter::$id, '=')->first()->run()) {
                $array = Utility::jSonToArray($result->dataset);
                Core::restoreFromTrash($array, Membership::mTable);
                Database::Go()->delete(Core::txTable)->where('id', Filter::$id, '=')->run();
                
                Message::msgReply(true, 'success', str_replace('[NAME]', $title, Language::$word->MEM_RESTORE_OK));
            }
            break;
    endswitch;
<?php
/**
 * Helper
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2025
 * @version 1.00: helper.php, v1.00 4/13/2025 2:41 PM
 *
 */
define("_WOJO", true);
require_once("../../init.php");

if (!App::Auth()->is_Admin()) {
    Url::redirect(SITEURL . '/index.php');
    exit;
}

// Check if user is sub-admin
if (!App::Auth()->is_SubAdmin()) {
    Message::msgError(Language::$word->NOACCESS);
    exit;
}

if (Filter::$do && Filter::$action) {
    /* == Post Actions== */
    switch (Filter::$action):
        /* == Process User== */
        case "processUser":
            App::User()->processUser();
            break;

        /* == Process Membership== */
        case "processMembership":
            if (App::Auth()->usertype == 'sub_admin') {
                App::Membership()->processMembership();
            } else {
                Message::msgError(Language::$word->NOACCESS);
            }
            break;

        /* == Delete File== */
        case "deleteFile":
            if (Auth::hasPrivileges('delete_files')) {
                $result = File::deleteFile(Filter::$id);
                Message::msgReply($result, 'success', str_replace("[NAME]", $result, Language::$word->FM_DEL_OK));
            } else {
                Message::msgError(Language::$word->NOACCESS);
            }
            break;

        /* == Change User Status== */
        case "userStatus":
            $data = array('active' => Validator::sanitize($_POST['active'], 'string'));
            Database::Go()->update(User::mTable, $data)->where('id', Filter::$id, '=')->where('created_by', App::Auth()->uid, '=')->run();
            break;

        /* == Change Membership Status== */
        case "membershipStatus":
            $data = array('active' => intval($_POST['active']));
            Database::Go()->update(Membership::mTable, $data)->where('id', Filter::$id, '=')->where('created_by', App::Auth()->uid, '=')->run();
            break;
            
        /* == Process Password== */
        case "processPassword":
            if ($data = Filter::$post->validate($rules = ['password' => ['required|string|min_len,6|max_len,20']], $to_trim = true)) {
                $salt = ''; //Random::generateString(16);
                $data['hash'] = Auth::doHash($data['password']);
                Database::Go()->update(User::mTable, $data)->where('id', App::Auth()->uid, '=')->run();
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = Language::$word->M_PASSUPD_OK;
                print json_encode($json);
            } else {
                Message::msgSingleStatus();
            }
            break;

        /* == Update Account== */
        case "updateAccount":
            if ($data = Filter::$post->validate($rules = ['fname' => ['required|string|min_len,3|max_len,60'], 'lname' => ['required|string|min_len,3|max_len,60'], 'email' => ['required|email']], $to_trim = true)) {
                Database::Go()->update(User::mTable, $data)->where('id', App::Auth()->uid, '=')->run();
                if ($row = Database::Go()->select(User::mTable, array('email', 'lname', 'fname'))->where('id', App::Auth()->uid, '=')->first()->run()) {
                    App::Auth()->fname = $row->fname;
                    App::Auth()->lname = $row->lname;
                    App::Auth()->fullname = $row->fname . ' ' . $row->lname;
                    App::Auth()->email = $row->email;
                    App::Auth()->name = $row->fname;
                    session::set('fname', $row->fname);
                    session::set('lname', $row->lname);
                    session::set('fullname', $row->fname . ' ' . $row->lname);
                    session::set('email', $row->email);
                }
                $json['title'] = Language::$word->SUCCESS;
                $json['type'] = 'success';
                $json['message'] = Language::$word->M_UPDATED;
                print json_encode($json);
            } else {
                $json['message'] = '';
                foreach (Filter::$msgs as $field => $msg) {
                    $json['message'] .= $msg;
                }
                print json_encode($json);
            }
            break;
    endswitch;
    
    /* == Get Actions== */
    switch (Filter::$do):
        /* == Load Modal== */
        case "loadModal":
            switch (Filter::$action):
                /* == Edit File== */
                case "editFile":
                    $tpl = App::View(BASEPATH . 'view/sub_admin/snippets/');
                    $tpl->template = 'editFile';
                    $tpl->data = Database::Go()->select(Content::fTable)->where('id', Filter::$id, '=')->first()->run();
                    $tpl->memberships = App::Membership()->getMembershipList();
                    echo $tpl->render();
                    break;
            endswitch;
            break;
    endswitch;
}
?>
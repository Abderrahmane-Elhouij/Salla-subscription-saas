<?php
    /**
     * helper
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: helper.php, v1.00 7/2/2023 3:08 PM Gewa Exp $
     *
     */
    const _WOJO = true;
    require_once('../../init.php');
    
    if (!App::Auth()->is_Admin()) {
        exit;
    }
    
    $gAction = Validator::get('action');
    $pAction = Validator::post('action');
    $iAction = Validator::post('iaction');
    $title = Validator::post('title') ? Validator::sanitize($_POST['title']) : null;
    
    /* == Post Actions== */
    switch ($pAction) :
        
        //Copy Page
        case 'copyPage':
            App::Content()->copyPage();
            break;
        
        //Edit language phrase
        case 'editPhrase':
            $payload = BASEPATH . Language::langdir . $_POST['path'];
            if (File::exists($payload)) {
                $data = json_decode(File::loadFile($payload), true);
                $update = array();
                $what = Validator::sanitize($_POST['key']);
                
                foreach ($data as $key => $value) {
                    foreach ($value as $name => $row) {
                        if ($name == $what) {
                            $value[$name] = $title;
                        }
                        $update[$key] = $value;
                    }
                }
                
                $jsonData = json_encode($update, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
                File::writeToFile($payload, $jsonData);
                
                $json['title'] = $title;
                print json_encode($json);
            }
            break;
        
        //Update Country Tax
        case 'editTax':
            if (empty($_POST['title'])) {
                print '0.000';
                exit;
            }
            $data['vat'] = Validator::sanitize($_POST['title'], 'float');
            Database::Go()->update(Content::cTable, $data)->where('id', Filter::$id, '=')->run();
            
            $json['title'] = $title;
            print json_encode($json);
            break;
        
        //Change Coupon Status
        case 'couponStatus':
            Database::Go()->update(Content::dcTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
            break;
        
        //Change Gateway Status
        case 'gatewayStatus':
            if (Auth::checkAcl('owner')):
                Database::Go()->update(Core::gTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
            endif;
            break;
        
        //Update Role Description
        case 'editRole':
            App::User()->updateRoleDescription();
            break;
        
        //Chnage Role
        case 'changeRole':
            if (Auth::checkAcl('owner')) {
                Database::Go()->update(User::rpTable, array('active' => intval($_POST['active'])))->where('id', Filter::$id, '=')->run();
            }
            break;
        
        //Rename File
        case 'renameFile':
            App::Admin()->renameFile();
            break;
        
        //Optimize Database
        case 'optimizeDatabase':
            $json['html'] = DatabaseTools::optimize();
            $json['type'] = 'success';
            print json_encode($json);
            break;
    endswitch;
    
    /* == Get Actions== */
    switch ($gAction) :
        //Copy Page
        case 'copyPage':
            $tpl = App::View(BASEPATH . 'view/admin/snippets/');
            $tpl->core = App::Core();
            $tpl->data = Database::Go()->select(Content::pTable)->where('id', Filter::$id, '=')->first()->run();
            $tpl->template = 'copyPage';
            echo $tpl->render();
            break;
        
        //Language Section == */
        case 'languageSection':
            $payload = BASEPATH . Language::langdir . $_GET['abbr'] . '.lang.json';
            if (File::exists($payload)) {
                $tpl = App::View(BASEPATH . 'view/admin/snippets/');
                
                $data = json_decode(File::loadFile($payload), true);
                $section = $data[Validator::sanitize($_GET['section'])];
                
                $tpl->section = $section;
                $tpl->type = $_GET['type'];
                $tpl->abbr = $_GET['abbr'];
                $tpl->template = 'languageSection';
                $json['html'] = $tpl->render();
            } else {
                $json['type'] = 'error';
                $json['title'] = Language::$word->ERROR;
                $json['message'] = Language::$word->FU_ERROR15;
            }
            print json_encode($json);
            break;
        
        //Membership Payments Chart
        case 'getMembershipPaymentsChart':
            $data = Stats::getMembershipPaymentsChart(Filter::$id);
            print json_encode($data);
            break;
        
        //Export Membership Payments
        case 'exportMembershipPayments':
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=MembershipPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
            
            $result = Stats::exportMembershipPayments(Filter::$id);
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
            }
            break;
        
        //Export Users
        case 'exportUsers':
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=UserList.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('Name', 'Membership', 'Expire', 'Email', 'Newsletter', 'Created'));
            
            $result = Stats::exportUsers();
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
            }
            break;
        
        //Export User Payments
        case 'exportUserPayments':
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=UserPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'Name', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
            
            $result = Stats::exportUserPayments(Filter::$id);
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
            }
            break;
        
        //User Payments Chart
        case 'getUserPaymentsChart':
            $data = Stats::getUserPaymentsChart(Filter::$id);
            print json_encode($data);
            break;
        
        //Edit Role
        case 'editRole':
            $tpl = App::View(BASEPATH . 'view/admin/snippets/');
            $tpl->data = Database::Go()->select(User::rTable)->where('id', Filter::$id, '=')->first()->run();
            $tpl->template = 'editRole';
            echo $tpl->render();
            break;
        
        //Rename File
        case 'renameFile':
            $tpl = App::View(BASEPATH . 'view/admin/snippets/');
            $tpl->template = 'renameFile';
            $tpl->row = Database::Go()->select(Content::fTable)->where('id', Filter::$id, '=')->first()->run();
            $tpl->memberships = App::Membership()->getMembershipList();
            echo $tpl->render();
            break;
        
        //All Sales Chart
        case 'getSalesChart':
            $data = Stats::getAllSalesStats();
            print json_encode($data);
            break;
        
        /* == All Sales Export == */
        case 'exportAllTransactions':
            header('Pragma: no-cache');
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=AllPayments.csv');
            
            $data = fopen('php://output', 'w');
            fputcsv($data, array('TXN ID', 'Item', 'User', 'Amount', 'TAX/VAT', 'Coupon', 'Total Amount', 'Currency', 'Processor', 'Created'));
            
            $result = Stats::exportAllTransactions();
            if ($result) {
                foreach ($result as $row) {
                    fputcsv($data, $row);
                }
            }
            break;
        
        //Index Payments Chart
        case 'getIndexStats':
            $data = Stats::indexSalesStats();
            print json_encode($data);
            break;
        
        //Main Stats
        case 'getMainStats':
            $data = Stats::getMainStats();
            print json_encode($data);
            break;
    endswitch;
    
    
    /* == Instant Actions== */
    switch ($iAction) :
        //Sort Pages
        case 'sortPages':
            $i = 0;
            $query = 'UPDATE `' . Content::pTable . '` SET `sorting` = CASE ';
            $idlist = '';
            foreach ($_POST['sorting'] as $item) {
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $idlist .= $item . ',';
            }
            $idlist = substr($idlist, 0, -1);
            $query .= '
				  END
				  WHERE id IN (' . $idlist . ')';
            Database::Go()->rawQuery($query)->run();
            break;
            
        //Sort Memberships
        case 'sortMemberships':
            $i = 0;
            $query = 'UPDATE `' . Membership::mTable . '` SET `sorting` = CASE ';
            $idlist = '';
            foreach ($_POST['sorting'] as $item) {
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $idlist .= $item . ',';
            }
            $idlist = substr($idlist, 0, -1);
            $query .= '
				  END
				  WHERE id IN (' . $idlist . ')';
            Database::Go()->rawQuery($query)->run();
            break;
            
        //Sort Custom Fields
        case 'sortFields':
            $i = 0;
            $query = 'UPDATE `' . Content::cfTable . '` SET `sorting` = CASE ';
            $idlist = '';
            foreach ($_POST['sorting'] as $item) {
                $i++;
                $query .= ' WHEN id = ' . $item . ' THEN ' . $i . ' ';
                $idlist .= $item . ',';
            }
            $idlist = substr($idlist, 0, -1);
            $query .= '
				  END
				  WHERE id IN (' . $idlist . ')';
            Database::Go()->rawQuery($query)->run();
            break;
        
        //File Upload
        case 'fileUpload':
            if (!empty($_FILES['file']['name'])) {
                $tpl = App::View(BASEPATH . 'view/admin/snippets/');
                $tpl->template = 'loadFile';
                
                $upl = Upload::instance(Content::FS, Content::FE);
                $upl->process('file', App::Core()->file_dir);
                if (count(Message::$msgs) === 0) {
                    $data = array(
                        'alias' => $upl->fileInfo['name'],
                        'name' => $upl->fileInfo['fname'],
                        'filesize' => $upl->fileInfo['size'],
                        'extension' => $upl->fileInfo['ext'],
                        'type' => $upl->fileInfo['type_short'],
                        'token' => Utility::randomString(16),
                        'fileaccess' => 0,
                    );
                    
                    $last_id = Database::Go()->insert(Content::fTable, $data)->run();
                    $row = Database::Go()->select(Content::fTable)->where('id', $last_id, '=')->first()->run();
                    $tpl->row = $row;
                    
                    $json['status'] = 'success';
                    $json['filename'] = $data['name'];
                    $json['type'] = File::getFileType($data['name']);
                    $json['id'] = $last_id;
                    $json['html'] = $tpl->render();
                } else {
                    $json['type'] = 'error';
                    $json['message'] = Message::$msgs['name'];
                }
                print json_encode($json);
            }
            break;
        
        //Database Backup
        case 'databaseBackup':
            if ($sql = DatabaseTools::fetch()) {
                $fname = UPLOADS . '/backups/';
                $fname .= date(DatabaseTools::suffix);
                $fname .= '.sql';
                
                DatabaseTools::save($fname, $sql, false);
                
                $data['backup'] = basename($fname);
                Database::Go()->update(Core::sTable, $data)->where('id', 1, '=')->run();
                
                $tpl = App::View(BASEPATH . 'view/admin/snippets/');
                $tpl->template = 'loadDatabaseBackup';
                $tpl->backup = $data['backup'];
                $tpl->dbdir = UPLOADS . '/backups/';
                
                Message::msgModalReply(Database::Go()->affected(), 'success', Language::$word->DBM_BKP_OK, $tpl->render());
            }
            break;
        
        //Clear Session Temp Queries
        case 'session':
            Session::remove('debug-queries');
            Session::remove('debug-warnings');
            Session::remove('debug-errors');
            print 'ok';
            break;
    endswitch;
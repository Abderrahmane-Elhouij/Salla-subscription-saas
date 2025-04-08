<?php
    /**
     * Content Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Content.php, v1.00 7/2/2023 3:48 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
class Content
{
    const cTable = 'countries';
    const dcTable = 'coupons';
    const eTable = 'email_templates';
    const pTable = 'pages';
    const cfTable = 'custom_fields';
    const nTable = 'news';
    const fTable = 'downloads';
        
    const FS = 104857600;
    const FE = 'png,jpg,jpeg,bmp,zip,pdf,doc,docx,txt,xls,xlsx,rar,mp4,mp3';
        
    /**
     * pageIndex
     *
     * @return void
     */
    public function pageIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T37;
        $tpl->caption = Language::$word->PG_TITLE;
        $tpl->subtitle = Language::$word->PG_INFO;
            
        $tpl->data = Database::Go()->select(self::pTable)->orderBy('sorting', 'ASC')->run();
        $tpl->template = 'admin/page';
    }
        
    /**
     * pageEdit
     *
     * @param int $id
     * @return void
     */
    public function pageEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T38;
        $tpl->caption = Language::$word->META_T38;
        $tpl->crumbs = ['admin', 'pages', 'edit'];
            
        if (!$row = Database::Go()->select(self::pTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->memberships = App::Membership()->getMembershipList();
            $tpl->template = 'admin/page';
        }
    }
        
    /**
     * pageSave
     *
     * @return void
     */
    public function pageSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T39;
        $tpl->caption = Language::$word->META_T39;
        $tpl->crumbs = ['admin', 'pages', 'new'];
            
        $tpl->memberships = App::Membership()->getMembershipList();
        $tpl->template = 'admin/page';
    }
        
    /**
     * processPage
     *
     * @return void
     */
    public function processPage(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(100)
            ->set('active', Language::$word->PUBLISHED)->required()->numeric()
            ->set('is_hide', Language::$word->PUBLISHED)->required()->numeric()
            ->set('slug', Language::$word->PG_SLUG)->text()
            ->set('body', Language::$word->DESCRIPTION)->text('advanced')
            ->set('keywords', Language::$word->METAKEYS)->string(true, true)
            ->set('description', Language::$word->METADESC)->string(true, true);
            
        $safe = $validate->safe();
            
        if ($safe->is_hide and !array_key_exists('membership_id', $_POST)) {
            Message::$msgs['membership_id'] = Language::$word->PG_MEM_E;
        }
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'title' => $safe->title,
                'slug' => strlen($safe->slug) === 0 ? Url::doSeo($safe->title) : Url::doSeo($safe->slug),
                'body' => Url::in_url($safe->body),
                'membership_id' => array_key_exists('membership_id', $_POST) ? Utility::implodeFields($_POST['membership_id']) : 0,
                'keywords' => $safe->keywords,
                'description' => $safe->description,
                'is_hide' => $safe->is_hide,
                'active' => $safe->active,
            );
                
            if (array_key_exists('membership_id', $_POST)) {
                $data['page_type'] = 'membership';
            }
                
            (Filter::$id) ? Database::Go()->update(self::pTable, $data)->where('id', Filter::$id, '=')->run() : Database::Go()->insert(self::pTable, $data)->run();
                
            if (Filter::$id) {
                $pages = Database::Go()->select('pages', array('page_type'))
                    ->where('page_type', 'contact', '=')
                    ->orWhere('page_type', 'privacy', '=')
                    ->orWhere('page_type', 'home', '=')
                    ->run();
                    
                $result = Utility::groupToLoop($pages, 'page_type');
                Database::Go()->update(Core::sTable, array('page_slugs' => json_encode($result)))->where('id', 1, '=')->run();
            }
                
            $message = Filter::$id ?
                Message::formatSuccessMessage($data['title'], Language::$word->PG_UPDATE_OK) :
                Message::formatSuccessMessage($data['title'], Language::$word->PG_ADDED_OK);
            Message::msgReply(Database::Go()->affected(), 'success', $message);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * copyPage
     *
     * @return void
     */
    public function copyPage(): void
    {
        $validate = Validator::run($_POST);
        $validate->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(100);
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array();
            $data_m = array(
                'title' => $safe->title,
                'slug' => Url::doSeo($safe->title),
                'created' => Database::toDate(),
            );
                
            $rows = Database::Go()->select(self::pTable)->where('id', Filter::$id, '=')->first()->run();
                
            foreach (new LimitIterator(new ArrayIterator($rows), 1) as $key => $row) {
                $data[$key] = (isset($row)) ? $row : '';
            }
                
            $last_id = Database::Go()->insert(self::pTable, $data)->run();
            Database::Go()->update(self::pTable, $data_m)->where('id', $last_id, '=')->run();
                
            if ($last_id) {
                $message = Message::formatSuccessMessage($data_m['title'], Language::$word->PG_ADDED_OK);
                $json['type'] = 'success';
                $json['title'] = Language::$word->SUCCESS;
                $json['message'] = $message;
                $json['redirect'] = Url::url('/admin/pages/edit/', $last_id);
            } else {
                $json['type'] = 'alert';
                $json['title'] = Language::$word->ALERT;
                $json['message'] = Language::$word->NOPROCCESS;
            }
            print json_encode($json);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * newsIndex
     *
     * @return void
     */
    public function newsIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T18;
        $tpl->caption = Language::$word->META_T18;
        $tpl->subtitle = Language::$word->NW_INFO;
            
        $tpl->data = Database::Go()->select(self::nTable)->orderBy('created', 'DESC')->run();
        $tpl->template = 'admin/news';
    }
        
    /**
     * newsEdit
     *
     * @param int $id
     * @return void
     */
    public function newsEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T19;
        $tpl->caption = Language::$word->META_T19;
        $tpl->crumbs = ['admin', 'news', 'edit'];
            
        if (!$row = Database::Go()->select(self::nTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->template = 'admin/news';
        }
    }
        
    /**
     * newsSave
     *
     * @return void
     */
    public function newsSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T20;
        $tpl->caption = Language::$word->META_T20;
            
        $tpl->template = 'admin/news';
    }
        
    /**
     * processNews
     *
     * @return void
     */
    public function processNews(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(50)
            ->set('body', Language::$word->DESCRIPTION)->text('advanced')
            ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'title' => $safe->title,
                'body' => $safe->body,
                'author' => App::Auth()->name,
                'active' => $safe->active,
            );
                
            (Filter::$id) ? Database::Go()->update(self::nTable, $data)->where('id', Filter::$id, '=')->run() : Database::Go()->insert(self::nTable, $data)->run();
                
            $message = Filter::$id ?
                Message::formatSuccessMessage($data['title'], Language::$word->NW_UPDATE_OK) :
                Message::formatSuccessMessage($data['title'], Language::$word->NW_ADDED_OK);
                
            Message::msgReply(Database::Go()->affected(), 'success', $message);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * couponIndex
     *
     * @return void
     */
    public function couponIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T12;
        $tpl->caption = Language::$word->META_T12;
        $tpl->subtitle = Language::$word->DC_SUB;
            
        $tpl->data = Database::Go()->select(self::dcTable)->run();
        $tpl->template = 'admin/coupon';
    }
        
    /**
     * couponEdit
     *
     * @param int $id
     * @return void
     */
    public function couponEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T13;
        $tpl->caption = Language::$word->META_T13;
        $tpl->subtitle = Language::$word->DC_SUB;
        $tpl->crumbs = ['admin', 'coupons', 'edit'];
            
        if (!$row = Database::Go()->select(self::dcTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->memberships = App::Membership()->getMembershipList();
            $tpl->template = 'admin/coupon';
        }
    }
        
    public function couponSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T14;
        $tpl->caption = Language::$word->META_T14;
            
        $tpl->memberships = App::Membership()->getMembershipList();
        $tpl->template = 'admin/coupon';
    }
        
    /**
     * processCoupon
     *
     * @return void
     */
    public function processCoupon(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(50)
            ->set('discount', Language::$word->DC_DISC)->required()->numeric()->min_numeric(1)->max_numeric(100)
            ->set('code', Language::$word->DC_CODE)->required()->string()
            ->set('type', Language::$word->DC_TYPE)->required()->string()
            ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'title' => $safe->title,
                'code' => $safe->code,
                'discount' => $safe->discount,
                'type' => $safe->type,
                'membership_id' => Validator::post('membership_id') ? Utility::implodeFields($_POST['membership_id']) : 0,
                'active' => $safe->active,
            );
                
            (Filter::$id) ? Database::Go()->update(self::dcTable, $data)->where('id', Filter::$id, '=')->run() : Database::Go()->insert(self::dcTable, $data)->run();
                
            $message = Filter::$id ?
                Message::formatSuccessMessage($data['title'], Language::$word->DC_UPDATE_OK) :
                Message::formatSuccessMessage($data['title'], Language::$word->DC_ADDED_OK);
                
            Message::msgReply(Database::Go()->affected(), 'success', $message);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    public function fieldIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->CF_TITLE;
        $tpl->caption = Language::$word->CF_TITLE;
        $tpl->subtitle = Language::$word->CF_INFO;
        $tpl->crumbs = ['admin', Language::$word->META_T15];
            
        $tpl->data = Database::Go()->select(self::cfTable)->orderBy('sorting', 'ASC')->run();
        $tpl->template = 'admin/field';
    }
        
    /**
     * fieldEdit
     *
     * @param int $id
     * @return void
     */
    public function fieldEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T16;
        $tpl->caption = Language::$word->META_T16;
        $tpl->crumbs = ['admin', 'fields', 'edit'];
            
        if (!$row = Database::Go()->select(self::cfTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->template = 'admin/field';
        }
    }
        
    public function fieldSave(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T17;
        $tpl->caption = Language::$word->META_T17;
            
        $tpl->template = 'admin/field';
    }
        
    /**
     * processField
     *
     * @return void
     */
    public function processField(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(50)
            ->set('required', Language::$word->CF_REQUIRED)->required()->numeric()
            ->set('tooltip', Language::$word->CF_TIP)->string()
            ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'title' => $safe->title,
                'tooltip' => $safe->tooltip,
                'required' => $safe->required,
                'section' => 'profile',
                'active' => $safe->active,
            );
                
            if (!Filter::$id) {
                $data['name'] = Utility::randomString(6);
            }
            $last_id = 0;
            (Filter::$id) ? Database::Go()->update(self::cfTable, $data)->where('id', Filter::$id, '=')->run() : $last_id = Database::Go()->insert(self::cfTable, $data)->run();
                
            if (!Filter::$id) {
                $users = Database::Go()->select(User::mTable)->run();
                $dataArray = array();
                foreach ($users as $row) {
                    $dataArray[] = array(
                        'user_id' => $row->id,
                        'field_id' => $last_id,
                        'field_name' => $data['name'],
                    );
                }
                Database::Go()->batch(User::cfTable, $dataArray)->run();
            }
                
            $message = Filter::$id ?
                Message::formatSuccessMessage($data['title'], Language::$word->CF_UPDATE_OK) :
                Message::formatSuccessMessage($data['title'], Language::$word->CF_ADDED_OK);
                
            Message::msgReply(Database::Go()->affected(), 'success', $message);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * templateIndex
     *
     * @return void
     */
    public function templateIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T10;
        $tpl->caption = Language::$word->META_T10;
        $tpl->subtitle = Language::$word->ET_SUB;
        $tpl->crumbs = ['admin', 'email templates'];
            
        $tpl->data = Database::Go()->select(self::eTable)->orderBy('name', 'ASC')->run();
        $tpl->template = 'admin/template';
    }
        
    /**
     * templateEdit
     *
     * @param int $id
     * @return void
     */
    public function templateEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T11;
        $tpl->caption = Language::$word->META_T11;
        $tpl->crumbs = ['admin', 'templates', 'edit'];
            
        if (!$row = Database::Go()->select(self::eTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->template = 'admin/template';
        }
    }
        
    /**
     * processTemplate
     *
     * @return void
     */
    public function processTemplate(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('name', Language::$word->ET_NAME)->required()->string()->min_len(3)->max_len(60)
            ->set('subject', Language::$word->ET_SUBJECT)->required()->string()->min_len(3)->max_len(120)
            ->set('body', Language::$word->DESCRIPTION)->text('advanced')
            ->set('help', Language::$word->ET_DESC)->string(true, true);
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'name' => $safe->name,
                'subject' => $safe->subject,
                'help' => $safe->help,
                'body' => str_replace(SITEURL, '[SITEURL]', $safe->body),
            );
                
            Database::Go()->update(self::eTable, $data)->where('id', Filter::$id, '=')->run();
            Message::msgReply(Database::Go()->affected(), 'success', Message::formatSuccessMessage($data['name'], Language::$word->ET_UPDATED));
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * countryIndex
     *
     * @return void
     */
    public function countryIndex(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->CNT_TITLE;
        $tpl->caption = Language::$word->CNT_TITLE;
        $tpl->subtitle = Language::$word->CNT_INFO;
            
        $tpl->data = Database::Go()->select(self::cTable)->orderBy('sorting', 'DESC')->run();
        $tpl->template = 'admin/country';
    }
        
    /**
     * countryEdit
     *
     * @param int $id
     * @return void
     */
    public function countryEdit(int $id): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->CNT_EDIT;
        $tpl->caption = Language::$word->CNT_EDIT;
        $tpl->crumbs = ['admin', 'countries', 'edit'];
            
        if (!$row = Database::Go()->select(self::cTable)->where('id', $id, '=')->first()->run()) {
            if (DEBUG) {
                $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
            } else {
                $tpl->error = Language::$word->META_ERROR;
            }
            $tpl->template = 'admin/error';
        } else {
            $tpl->data = $row;
            $tpl->template = 'admin/country';
        }
    }
        
    /**
     * processCountry
     *
     * @return void
     */
    public function processCountry(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('name', Language::$word->NAME)->required()->string()->min_len(2)->max_len(60)
            ->set('abbr', Language::$word->CNT_ABBR)->required()->string()->uppercase()->exact_len(2)
            ->set('active', Language::$word->STATUS)->required()->numeric()
            ->set('home', Language::$word->DEFAULT)->required()->numeric()
            ->set('sorting', Language::$word->SORTING)->numeric()
            ->set('vat', Language::$word->TRX_TAX)->required()->float()
            ->set('id', 'ID')->required()->numeric();
            
        $safe = $validate->safe();
            
        if (count(Message::$msgs) === 0) {
            $data = array(
                'name' => $safe->name,
                'abbr' => $safe->abbr,
                'sorting' => $safe->sorting,
                'home' => $safe->home,
                'active' => $safe->active,
                'vat' => $safe->vat,
            );
                
            if ($data['home'] == 1) {
                Database::Go()->rawQuery('UPDATE `' . Content::cTable . '` SET `home`= ?;', array(0))->run();
            }
                
            Database::Go()->update(Content::cTable, $data)->where('id', Filter::$id, '=')->run();
            Message::msgReply(Database::Go()->affected(), 'success', Message::formatSuccessMessage($data['name'], Language::$word->CNT_UPDATED));
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * renderCustomFields
     *
     * @param int $id
     * @return string
     */
    public static function renderCustomFields(int $id): string
    {
        if ($id) {
            $sql = '
			    SELECT cf.*,cd.field_value
			      FROM `' . self::cfTable . '` AS cf
			      LEFT JOIN `' . User::cfTable . '` AS cd ON cd.field_id = cf.id
			      WHERE cd.user_id = ?
			      AND cf.active = ?
			      ORDER BY cf.sorting;
			    ';
            $data = Database::Go()->rawQuery($sql, array($id, 1))->run();
        } else {
            $data = Database::Go()->select(self::cfTable)->orderBy('sorting', 'ASC')->run();
        }
            
        $html = '';
        if ($data) {
            foreach ($data as $row) {
                $tooltip = $row->tooltip ? ' <span data-tooltip="' . $row->tooltip . '"><i class="icon question circle"></i></span>' : '';
                $required = $row->required ? ' <i class="icon asterisk"></i>' : '';
                $html .= '<div class="wojo fields align-middle">';
                $html .= '<div class="field four wide labeled">';
                $html .= '<label>' . $row->title . $required . $tooltip . '</label>';
                $html .= '</div>';
                $html .= '<div class="six wide field">';
                $html .= '<input name="custom_' . $row->name . '" type="text" placeholder="' . $row->title . '" value="' . ($id ? $row->field_value : '') . '">';
                $html .= '</div>';
                $html .= '</div>';
            }
        }
        return $html;
    }
        
    /**
     * renderCustomFieldsFront
     *
     * @param int $id
     * @return string
     */
    public static function renderCustomFieldsFront(int $id): string
    {
        if ($id) {
            $sql = '
			    SELECT cf.*,cd.field_value
			      FROM `' . self::cfTable . '` AS cf
			      LEFT JOIN `' . User::cfTable . '` AS cd ON cd.field_id = cf.id
			      WHERE cd.user_id = ?
			      AND cf.active = ?
			      ORDER BY cf.sorting;
			    ';
            $data = Database::Go()->rawQuery($sql, array($id, 1))->run();
        } else {
            $data = Database::Go()->select(self::cfTable)->orderBy('sorting', 'ASC')->run();
        }
            
        $html = '';
        if ($data) {
            $html .= '<div class="wojo block fields">';
            foreach ($data as $row) {
                $tooltip = $row->tooltip ? ' <span data-tooltip="' . $row->tooltip . '"><i class="icon question circle"></i></span>' : '';
                $required = $row->required ? ' <i class="icon asterisk"></i>' : '';
                $html .= '<div class="field">';
                $html .= '<label>' . $row->title . $required . $tooltip . '</label>';
                $html .= '<input name="custom_' . $row->name . '" type="text" placeholder="' . $row->title . '" value="' . ($id ? $row->field_value : '') . '">';
                $html .= '</div>';
            }
            $html .= '</div>';
        }
        return $html;
    }
        
    /**
     * verifyCustomFields
     *
     * @return void
     */
    public static function verifyCustomFields(): void
    {
        if ($data = Database::Go()->select(self::cfTable)->where('active', 1, '=')->where('required', 1, '=')->run()) {
            foreach ($data as $row) {
                Validator::checkPost('custom_' . $row->name, Language::$word->FIELD_R0 . ' "' . $row->title . '" ' . Language::$word->FIELD_R100);
            }
        }
    }
        
    /**
     * getCountryList
     *
     * @return mixed
     */
    public function getCountryList(): mixed
    {
        return Database::Go()->select(self::cTable)->orderBy('sorting', 'DESC')->run();
    }
        
    /**
     * pageType
     *
     * @param string $type
     * @return string
     */
    public static function pageType(string $type): string
    {
        return match ($type) {
            'home' => '<i class="icon house disabled"></i>',
            'contact' => '<i class="icon envelope disabled"></i>',
            'membership' => '<i class="icon lock disabled"></i>',
            default => '<i class="icon file disabled"></i>',
        };
    }
}

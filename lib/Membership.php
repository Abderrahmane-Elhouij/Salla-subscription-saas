<?php
    /**
     * Membership Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Membership.php, v1.00 7/3/2023 8:16 AM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Membership
    {
        
        const mTable = 'memberships';
        const umTable = 'user_memberships';
        const pTable = 'payments';
        const cTable = 'cart';
        
        /**
         * index
         *
         * @return void
         */
        public function index(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T6;
            $tpl->caption = Language::$word->META_T6;
            $tpl->subtitle = Language::$word->MEM_SUB;
            
            $sql = 'SELECT m.*, (SELECT COUNT(p.membership_id) FROM payments as p WHERE p.membership_id = m.id) as total FROM memberships as m ORDER BY m.sorting';
            $tpl->data = Database::Go()->rawQuery($sql)->run();
            
            $tpl->template = 'admin/membership';
        }
        
        /**
         * edit
         *
         * @param int $id
         * @return void
         */
        public function edit(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T7;
            $tpl->caption = Language::$word->META_T7;
            $tpl->crumbs = ['admin', 'memberships', 'edit'];
            
            if (!$row = Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $tpl->data = $row;
                $tpl->template = 'admin/membership';
            }
        }
        
        /**
         * save
         *
         * @return void
         */
        public function save(): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T8;
            $tpl->caption = Language::$word->META_T8;
            
            $tpl->template = 'admin/membership';
        }
        
        public function history(int $id): void
        {
            $tpl = App::View(BASEPATH . 'view/');
            $tpl->dir = 'admin/';
            $tpl->title = Language::$word->META_T9;
            $tpl->caption = Language::$word->META_T9;
            $tpl->crumbs = ['admin', 'memberships', 'history'];
            
            if (!$row = Database::Go()->select(self::mTable)->where('id', $id, '=')->first()->run()) {
                if (DEBUG) {
                    $tpl->error = 'Invalid ID ' . ($id) . ' detected [' . __CLASS__ . ', ln.:' . __line__ . ']';
                } else {
                    $tpl->error = Language::$word->META_ERROR;
                }
                $tpl->template = 'admin/error';
            } else {
                $pager = Paginator::instance();
                $pager->items_total = Database::Go()->count(Membership::pTable)->where('membership_id', $row->id, '=')->where('status', 1, '=')->run();
                $pager->default_ipp = App::Core()->perpage;
                $pager->path = Url::url(Router::$path, '?');
                $pager->paginate();
                
                $sql = "
                SELECT p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.created, p.user_id, CONCAT(u.fname,' ',u.lname) as name
                  FROM `" . self::pTable . '` AS p
                  LEFT JOIN ' . User::mTable . ' AS u ON u.id = p.user_id
                  WHERE p.membership_id = ?
                  AND p.status = ?
                  ORDER BY p.created
                  DESC' . $pager->limit;
                
                $tpl->data = $row;
                $tpl->plist = Database::Go()->rawQuery($sql, array($row->id, 1))->run();
                $tpl->pager = $pager;
                $tpl->template = 'admin/membership';
            }
        }
        
        /**
         * processMembership
         *
         * @return void
         */
        public function processMembership(): void
        {
            $validate = Validator::run($_POST);
            $validate
                ->set('title', Language::$word->NAME)->required()->string()->min_len(3)->max_len(60)
                ->set('description', Language::$word->DESCRIPTION)->required()->string()
                ->set('body', Language::$word->DESCRIPTION)->text('advanced')
                ->set('price', Language::$word->MEM_PRICE)->required()->float()
                ->set('days', Language::$word->MEM_DAYS)->required()->numeric()
                ->set('period', Language::$word->MEM_DAYS)->required()->string()->exact_len(1)
                ->set('recurring', Language::$word->MEM_REC)->required()->numeric()
                ->set('private', Language::$word->MEM_PRIVATE)->required()->numeric()
                ->set('active', Language::$word->PUBLISHED)->required()->numeric();
            
            $safe = $validate->safe();
            $thumb = File::upload('thumb', 3145728, 'png,jpg,jpeg');
            
            if (count(Message::$msgs) === 0) {
                $data = array(
                    'title' => $safe->title,
                    'description' => $safe->description,
                    'body' => $safe->body,
                    'price' => $safe->price,
                    'days' => $safe->days,
                    'period' => $safe->period,
                    'recurring' => $safe->recurring,
                    'private' => $safe->private,
                    'active' => $safe->active,
                );
                
                if (array_key_exists('thumb', $_FILES)) {
                    $thumbPath = UPLOADS . '/memberships/';
                    $result = File::process($thumb, $thumbPath, 'MEM_');
                    $data['thumb'] = $result['fname'];
                }
                
                (Filter::$id) ? Database::Go()->update(self::mTable, $data)->where('id', Filter::$id, '=')->run() : Database::Go()->insert(self::mTable, $data)->run();
                
                $message = Filter::$id ?
                    Message::formatSuccessMessage($data['title'], Language::$word->MEM_UPDATE_OK) :
                    Message::formatSuccessMessage($data['title'], Language::$word->MEM_ADDED_OK);
                
                Message::msgReply(Database::Go()->affected(), 'success', $message);
            } else {
                Message::msgSingleStatus();
            }
        }
        
        /**
         * calculateDays
         *
         * @param int $membership_id
         * @return string
         * @throws NotFoundException
         */
        public static function calculateDays(int $membership_id): string
        {
            $row = Database::Go()->select(self::mTable, array('days', 'period'))->where('id', $membership_id, '=')->first()->run();
            if ($row) {
                $diff = match ($row->period) {
                    'D' => ' day',
                    'W' => ' week',
                    'M' => ' month',
                    'Y' => ' year',
                    default => throw new NotFoundException(sprintf('The requested action "%s" don\'t match any type.', $membership_id))
                };
                $expire = Date::numberOfDays('+' . $row->days . $diff);
            } else {
                $expire = '';
            }
            return $expire;
        }
        
        /**
         * getCart
         *
         * @param int $id
         * @return mixed
         */
        public static function getCart(int $id): mixed
        {
            return Database::Go()->select(self::cTable)->where('user_id', $id, '=')->first()->run();
        }
        
        /**
         * calculateTax
         *
         * @return float|int
         */
        public static function calculateTax(): float|int
        {
            $core = App::Core();
            
            return ($core->enable_tax and $core->tax_rate > 0) ? $core->tax_rate / 100 : 0;
        }
        
        /**
         * is_valid
         *
         * @param array $memberships
         * @return bool
         */
        public static function is_valid(array $memberships): bool
        {
            return in_array(App::Auth()->membership_id, $memberships);
        }
        
        /**
         * getMembershipList
         *
         * @return mixed
         */
        public function getMembershipList(): mixed
        {
            return Database::Go()->select(self::mTable, array('id', 'title'))->orderBy('title', 'ASC')->run();
        }
    }
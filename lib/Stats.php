<?php
    /**
     * Stats Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Stats.php, v1.00 7/1/2023 10:25 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
class Stats
{
    /**
     * MembershipsExpireMonth
     *
     * @return array|false|int|mixed
     */
    public static function MembershipsExpireMonth(): mixed
    {
        $sql = "
            SELECT COUNT(id) as total, DATE_FORMAT(mem_expire,'%Y-%m-%d') as expires
              FROM `" . User::mTable . '`
              WHERE MONTH(mem_expire) = MONTH(NOW())
              AND YEAR(mem_expire) = YEAR(NOW())
              AND membership_id > 0
              GROUP BY expires
            ';
            
        return Database::Go()->rawQuery($sql)->run();
    }
        
    /**
     * getMembershipPaymentsChart
     *
     * @param int $id
     * @return array
     */
    public static function getMembershipPaymentsChart(int $id): array
    {
            
        $data = array();
        $data['label'] = array();
        $data['color'] = array();
        $data['legend'] = array();
        $data['preUnits'] = Utility::currencySymbol();
            
        $color = array(
            '#03a9f4',
            '#33BFC1',
            '#ff9800',
            '#e91e63',
        );
            
        $labels = array(
            Language::$word->TRX_SALES,
            Language::$word->TRX_AMOUNT,
            Language::$word->TRX_TAX,
            Language::$word->TRX_COUPON,
        );
            
        for ($i = 1; $i <= 12; $i++) {
            $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
            $reg_data[$i] = array(
                'month' => date('M', mktime(0, 0, 0, $i)),
                'sales' => 0,
                'amount' => 0,
                'tax' => 0,
                'coupon' => 0
            );
        }
            
        $sql = '
            SELECT COUNT(id) as sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
              FROM `' . Membership::pTable . '`
              WHERE membership_id = ?
              AND status = ?
              GROUP BY MONTH(created)
            ';
        $query = Database::Go()->rawQuery($sql, array($id, 1));
            
        foreach ($query->run() as $result) {
            $reg_data[$result->created] = array(
                'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                'sales' => $result->sales,
                'amount' => $result->amount,
                'tax' => $result->tax,
                'coupon' => $result->coupon
            );
        }
            
        foreach ($reg_data as $key => $value) {
            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
            $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
            $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
        }
            
        foreach ($labels as $k => $label) {
            $data['label'][] = $label;
            $data['color'][] = $color[$k];
            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
        }
        $data['data'] = array_values($data['data']);
        return $data;
    }
        
    /**
     * exportMembershipPayments
     *
     * @param int $id
     * @return int|mixed
     */
    public static function exportMembershipPayments(int $id): mixed
    {
        $sql = "
            SELECT p.txn_id, CONCAT(u.fname,' ',u.lname) as name, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
              FROM `" . Membership::pTable . '` as p
              LEFT JOIN `' . User::mTable . '` as u ON u.id = p.user_id
              WHERE p.membership_id = ?
              AND p.status = ?
              ORDER BY p.created DESC;
            ';
            
        $rows = Database::Go()->rawQuery($sql, array($id, 1))->run();
        $array = json_decode(json_encode($rows), true);
            
        return $array ? : 0;
    }
        
    /**
     * exportUsers
     *
     * @return array
     */
    public static function exportUsers(): array
    {
        $sql = "
            SELECT CONCAT(fname, ' ', lname) as name, u.membership_id, u.mem_expire, u.email, u.newsletter, u.created, m.title
              FROM `" . User::mTable . '` as u
              LEFT JOIN ' . Membership::mTable . " as m ON m.id = u.membership_id
              WHERE (TYPE = 'staff' || TYPE = 'editor' || TYPE = 'member')
              ORDER BY u.fname
            ";
            
        $rows = Database::Go()->rawQuery($sql)->run();
            
        $result = array();
        if (is_array($rows)) {
            foreach ($rows as $i => $val) {
                $result[$i]['name'] = $val->name;
                $result[$i]['membership'] = $val->membership_id ? $val->title : '-/-';
                $result[$i]['mem_expire'] = $val->membership_id ? Date::doDate('long_date', $val->mem_expire) : '-/-';
                $result[$i]['email'] = $val->email;
                $result[$i]['newsletter'] = $val->newsletter ? Language::$word->YES : Language::$word->NO;
                $result[$i]['created'] = $val->created;
            }
        }
            
        return $result;
    }
        
    /**
     * userHistory
     *
     * @param int $id
     * @param string $order
     * @return array|false|int|mixed
     */
    public static function userHistory(int $id, string $order = 'activated'): mixed
    {
        $sql = '
			SELECT um.activated, um.membership_id, um.transaction_id, um.expire, um.recurring, m.price, m.title
			  FROM `' . Membership::umTable . '` AS um
			  LEFT JOIN ' . Membership::mTable . " AS m ON m.id = um.membership_id
			  WHERE um.user_id = ?
			  ORDER BY um.$order DESC;
			";
            
        return Database::Go()->rawQuery($sql, array($id))->run() ? : 0;
    }
        
    /**
     * userPayments
     *
     * @param int $id
     * @return array|false|int|mixed
     */
    public static function userPayments(int $id): mixed
    {
        $sql = '
			SELECT p.txn_id, m.title, p.rate_amount, p.tax, p.coupon, p.total, p.created, p.status, p.membership_id
			  FROM `' . Membership::pTable . '` AS p
			  LEFT JOIN ' . Membership::mTable . ' AS m ON m.id = p.membership_id
			  WHERE p.user_id =?
			  ORDER BY p.created DESC;
			';
            
        return Database::Go()->rawQuery($sql, array($id))->run() ? : 0;
    }
        
    /**
     * exportUserPayments
     *
     * @param int $id
     * @return int|mixed
     */
    public static function exportUserPayments(int $id): mixed
    {
        $sql = '
            SELECT p.txn_id, m.title, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
              FROM `' . Membership::pTable . '` AS p
              LEFT JOIN ' . Membership::mTable . ' AS m ON m.id = p.membership_id
              WHERE p.user_id =?
              ORDER BY p.created DESC;
            ';
            
        return Database::Go()->rawQuery($sql, array($id))->run('array') ? : 0;
    }
        
    /**
     * getUserPaymentsChart
     *
     * @param int $id
     * @return array
     */
    public static function getUserPaymentsChart(int $id): array
    {
            
        $data = array();
        $data['label'] = array();
        $data['color'] = array();
        $data['legend'] = array();
        $data['preUnits'] = Utility::currencySymbol();
            
        $color = array(
            '#03a9f4',
            '#33BFC1',
            '#ff9800',
            '#e91e63',
        );
            
        $labels = array(
            Language::$word->TRX_SALES,
            Language::$word->TRX_AMOUNT,
            Language::$word->TRX_TAX,
            Language::$word->TRX_COUPON,
        );
            
        for ($i = 1; $i <= 12; $i++) {
            $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
            $reg_data[$i] = array(
                'month' => date('M', mktime(0, 0, 0, $i)),
                'sales' => 0,
                'amount' => 0,
                'tax' => 0,
                'coupon' => 0
            );
        }
            
        $sql = '
            SELECT COUNT(id) as sales, SUM(rate_amount) as amount, SUM(tax) as tax, SUM(coupon) as coupon, MONTH(created) as created
              FROM `' . Membership::pTable . '`
              WHERE user_id = ?
              GROUP BY MONTH(created)
            ';
            
        $query = Database::Go()->rawQuery($sql, array($id));
            
        foreach ($query->run() as $result) {
            $reg_data[$result->created] = array(
                'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                'sales' => $result->sales,
                'amount' => $result->amount,
                'tax' => $result->tax,
                'coupon' => $result->coupon
            );
        }
            
        foreach ($reg_data as $key => $value) {
            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
            $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
            $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
        }
            
        foreach ($labels as $k => $label) {
            $data['label'][] = $label;
            $data['color'][] = $color[$k];
            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
        }
            
        $data['data'] = array_values($data['data']);
        return $data;
    }
        
    /**
     * deleteInactive
     *
     * @param int $days
     * @return void
     */
    public static function deleteInactive(int $days): void
    {
        $sql = 'DELETE FROM `' . User::mTable . "` WHERE DATE(lastlogin) < DATE_SUB(CURDATE(), INTERVAL $days DAY) AND TYPE = ? AND active = ?";
        Database::Go()->rawQuery($sql, array('member', 'y'))->run();
        $total = Database::Go()->affected();
            
        Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->MT_DELINCT_OK));
    }
        
    /**
     * deleteBanned
     *
     * @return void
     */
    public static function deleteBanned(): void
    {
        Database::Go()->delete(User::mTable)->where('active', 'b', '=')->run();
        $total = Database::Go()->affected();
            
        Message::msgReply($total, 'success', Message::formatSuccessMessage($total, Language::$word->MT_DELBND_OK));
    }
        
    /**
     * emptyCart
     *
     * @return void
     */
    public static function emptyCart(): void
    {
        $sql = 'DELETE FROM `' . Membership::cTable . '` WHERE DATE(created) < DATE_SUB(CURDATE(), INTERVAL 1 DAY)';
        Database::Go()->rawQuery($sql)->run();
        $total = Database::Go()->affected();
            
        Message::msgReply(Database::Go()->affected(), 'success', Message::formatSuccessMessage($total, Language::$word->MT_DELCRT_OK));
    }
        
    /**
     * getMainStats
     *
     * @return array
     * @throws Exception
     */
    public static function getMainStats(): array
    {
        $data = array();
        $data['label'] = array();
        $data['color'] = array();
        $data['legend'] = array();
        $data['preUnits'] = Utility::currencySymbol();
            
        $color = array(
            '#f44336',
            '#2196f3',
            '#e91e63',
            '#4caf50',
            '#ff9800',
            '#ff5722',
            '#795548',
            '#607d8b',
            '#00bcd4',
            '#9c27b0'
        );
            
        $begin = new DateTime(date('Y') . '-01');
        $ends = new DateTime(date('Y') . '-12');
        $end = $ends->modify('+1 month');
            
        $interval = new DateInterval('P1M');
        $date_range = new DatePeriod($begin, $interval, $end);
            
        $sql = "
              SELECT DATE_FORMAT(p.created, '%Y-%m') as cdate, m.title, p.membership_id, p.rate_amount
              FROM `" . Membership::pTable . '` AS p
              LEFT JOIN `' . Membership::mTable . '` AS m ON m.id = p.membership_id
              WHERE status = ?;
            ';
        $query = Database::Go()->rawQuery($sql, array(1))->run();
        $memberships = Utility::groupToLoop($query, 'title');
            
        foreach ($date_range as $k => $date) {
            $data['data'][$k]['m'] = Date::doDate('MMM', $date->format('Y-m'));
            if ($memberships) {
                foreach ($memberships as $title => $rows) {
                    $sum = 0;
                    foreach ($rows as $row) {
                        $data['data'][$k][$row->title] = $sum;
                        if ($row->cdate == $date->format('Y-m')) {
                            $sum += $row->rate_amount;
                            $data['data'][$k][$title] = $sum;
                        }
                    }
                }
            } else {
                $data['data'][$k]['-/-'] = 0;
            }
        }
            
        if ($memberships) {
            $k = 0;
            foreach ($memberships as $label => $vals) {
                $k++;
                $data['label'][] = $label;
                $data['color'][] = $color[$k];
                $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
            }
        } else {
            $data['label'][] = '-/-';
            $data['color'][] = $color[0];
            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[0] . '"> </span> -/-</div>';
        }
            
        return $data;
    }
        
    /**
     * indexSalesStats
     *
     * @return array
     */
    public static function indexSalesStats(): array
    {
        $data = array();
        $data['label'] = array();
        $data['color'] = array();
        $data['legend'] = array();
            
        $color = array(
            '#03a9f4',
            '#33BFC1'
        );
            
        $labels = array(
            Language::$word->TRX_SALES,
            Language::$word->TRX_AMOUNT
        );
            
        for ($i = 1; $i <= 12; $i++) {
            $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
            $reg_data[$i] = array(
                'month' => date('M', mktime(0, 0, 0, $i)),
                'sales' => 0,
                'amount' => 0,
            );
        }
            
        $sql = '
            SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, MONTH(created) as created
              FROM `' . Membership::pTable . '`
              WHERE status = ?
              GROUP BY MONTH(created);
            ';
            
        $query = Database::Go()->rawQuery($sql, array(1));
        foreach ($query->run() as $result) {
            $reg_data[$result->created] = array(
                'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                'sales' => $result->sales,
                'amount' => $result->amount
            );
        }
            
        $total_sum = 0;
        $total_sales = 0;
            
        foreach ($reg_data as $key => $value) {
            $data['sales'][] = array($key, $value['sales']);
            $data['amount'][] = array($key, $value['amount']);
            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
            $total_sum += $value['amount'];
            $total_sales += $value['sales'];
        }
            
        $data['totalsum'] = Utility::formatMoney($total_sum);
        $data['totalsales'] = $total_sales;
        $data['sales_str'] = implode(',', array_column($data['sales'], 1));
        $data['amount_str'] = implode(',', array_column($data['amount'], 1));
            
        foreach ($labels as $k => $label) {
            $data['label'][] = $label;
            $data['color'][] = $color[$k];
            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
        }
        $data['data'] = array_values($data['data']);
            
        return $data;
    }
        
    /**
     * getAllSalesStats
     *
     * @return array
     */
    public static function getAllSalesStats(): array
    {
        $range = (isset($_GET['timerange'])) ? Validator::sanitize($_GET['timerange'], 'string', 6) : 'all';
            
        $data = array();
        $reg_data = array();
        $data['label'] = array();
        $data['color'] = array();
        $data['legend'] = array();
        $data['preUnits'] = Utility::currencySymbol();
            
        $color = array(
            '#03a9f4',
            '#33BFC1',
            '#ff9800',
            '#e91e63',
        );
            
        $labels = array(
            Language::$word->TRX_SALES,
            Language::$word->TRX_AMOUNT,
            Language::$word->TRX_TAX,
            Language::$word->TRX_COUPON,
        );
            
        switch ($range) {
            case 'day':
                for ($i = 0; $i < 24; $i++) {
                    $data['data'][$i]['m'] = $i;
                    $reg_data[$i] = array(
                        'hour' => $i,
                        'sales' => 0,
                        'amount' => 0,
                        'tax' => 0,
                        'coupon' => 0,
                    );
                }
                    
                $sql = '
                    SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, HOUR(created) as hour
                      FROM `' . Membership::pTable . '`
                      WHERE DATE(created) = DATE(NOW())
                      AND status = ?
                      GROUP BY HOUR(created)
                      ORDER BY hour;
                    ';
                $query = Database::Go()->rawQuery($sql, array(1));
                    
                foreach ($query->run() as $result) {
                    $reg_data[$result->hour] = array(
                        'hour' => $result->hour,
                        'sales' => $result->sales,
                        'amount' => $result->amount,
                        'tax' => $result->tax,
                        'coupon' => $result->coupon
                    );
                }
                break;
                
            case 'week':
                $date[] = array();
                $date_start = strtotime('-' . date('w') . ' days');
                for ($i = 0; $i < 7; $i++) {
                    $date = date('Y-m-d', $date_start + ($i * 86400));
                    $data['data'][$i]['m'] = Date::doDate('EE', date('D', strtotime($date)));
                    $reg_data[date('w', strtotime($date))] = array(
                        'day' => date('D', strtotime($date)),
                        'sales' => 0,
                        'amount' => 0,
                        'tax' => 0,
                        'coupon' => 0,
                    );
                }
                    
                $sql = '
                    SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, DAYNAME(created) as created
                      FROM `' . Membership::pTable . "`
                      WHERE DATE(created) >= DATE('" . Validator::sanitize(date('Y-m-d', $date_start), 'string', 10) . "')
                      AND YEAR(created) = YEAR(CURDATE())
                      AND status = ?
                      GROUP BY DAYNAME(created);
                    ";
                $query = Database::Go()->rawQuery($sql, array(1));
                    
                foreach ($query->run() as $result) {
                    $reg_data[date('w', strtotime($date))] = array(
                        'day' => Date::doDate('EE', date('D', strtotime($result->created))),
                        'sales' => $result->sales,
                        'amount' => $result->amount,
                        'tax' => $result->tax,
                        'coupon' => $result->coupon
                    );
                }
                break;
                
            case 'month':
                for ($i = 1; $i <= date('t'); $i++) {
                    $date = date('Y') . '-' . date('m') . '-' . $i;
                    $data['data'][$i]['m'] = date('d', strtotime($date));
                    $reg_data[date('j', strtotime($date))] = array(
                        'day' => date('d', strtotime($date)),
                        'sales' => 0,
                        'amount' => 0,
                        'tax' => 0,
                        'coupon' => 0,
                    );
                }
                    
                $sql = '
                    SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, DAY(created) as created
                      FROM `' . Membership::pTable . '`
                      WHERE MONTH(created) = MONTH(CURDATE())
                      AND YEAR(created) = YEAR(CURDATE())
                      AND status = ?
                      GROUP BY DAY(created);
                    ';
                $query = Database::Go()->rawQuery($sql, array(1));
                    
                foreach ($query->run() as $result) {
                    $reg_data[$result->created] = array(
                        'month' => $result->created,
                        'sales' => $result->sales,
                        'amount' => $result->amount,
                        'tax' => $result->tax,
                        'coupon' => $result->coupon
                    );
                }
                break;
                
            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                    $reg_data[$i] = array(
                        'month' => date('M', mktime(0, 0, 0, $i)),
                        'sales' => 0,
                        'amount' => 0,
                        'tax' => 0,
                        'coupon' => 0,
                    );
                }
                    
                $sql = '
                    SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
                      FROM `' . Membership::pTable . '`
                      WHERE YEAR(created) = YEAR(NOW())
                      AND status = ?
                      GROUP BY MONTH(created);
                    ';
                $query = Database::Go()->rawQuery($sql, array(1));
                    
                foreach ($query->run() as $result) {
                    $reg_data[$result->created] = array(
                        'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                        'sales' => $result->sales,
                        'amount' => $result->amount,
                        'tax' => $result->tax,
                        'coupon' => $result->coupon
                    );
                }
                break;
                
            case 'all':
                for ($i = 1; $i <= 12; $i++) {
                    $data['data'][$i]['m'] = Date::doDate('MMM', date('F', mktime(0, 0, 0, $i, 10)));
                    $reg_data[$i] = array(
                        'month' => date('M', mktime(0, 0, 0, $i)),
                        'sales' => 0,
                        'amount' => 0,
                        'tax' => 0,
                        'coupon' => 0,
                    );
                }
                    
                $sql = '
                    SELECT COUNT(id) AS sales, SUM(rate_amount) AS amount, SUM(tax) AS tax, SUM(coupon) AS coupon, MONTH(created) as created
                      FROM `' . Membership::pTable . '`
                      WHERE status = ?
                      GROUP BY MONTH(created);
                    ';
                $query = Database::Go()->rawQuery($sql, array(1));
                    
                foreach ($query->run() as $result) {
                    $reg_data[$result->created] = array(
                        'month' => Date::doDate('MMM', date('F', mktime(0, 0, 0, $result->created, 10))),
                        'sales' => $result->sales,
                        'amount' => $result->amount,
                        'tax' => $result->tax,
                        'coupon' => $result->coupon
                    );
                }
                break;
        }
            
        foreach ($reg_data as $key => $value) {
            $data['data'][$key][Language::$word->TRX_SALES] = $value['sales'];
            $data['data'][$key][Language::$word->TRX_AMOUNT] = $value['amount'];
            $data['data'][$key][Language::$word->TRX_TAX] = $value['tax'];
            $data['data'][$key][Language::$word->TRX_COUPON] = $value['coupon'];
        }
            
        foreach ($labels as $k => $label) {
            $data['label'][] = $label;
            $data['color'][] = $color[$k];
            $data['legend'][] = '<div class="item"><span class="wojo right ring label spaced" style="background:' . $color[$k] . '"> </span> ' . $label . '</div>';
        }
        $data['data'] = array_values($data['data']);
        return $data;
    }
        
    /**
     * exportAllTransactions
     *
     * @return mixed
     */
    public static function exportAllTransactions(): mixed
    {
        $from = isset($_GET['fromdate_submit']) ? Validator::sanitize($_GET['fromdate_submit'], 'string', 10) : null;
        $end = isset($_GET['enddate_submit']) ? Validator::sanitize($_GET['enddate_submit'], 'string', 10) : null;
            
        $enddate = (isset($end) && $end) <> '' ? Validator::sanitize(Database::toDate($end, false)) : date('Y-m-d');
        $fromdate = isset($from) ? Validator::sanitize(Database::toDate($from, false)) : null;
            
        if (isset($fromdate) && $enddate <> '') {
            $where = "WHERE p.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
        } else {
            $where = null;
        }
            
        $sql = "
            SELECT p.txn_id, m.title, CONCAT(u.fname,' ',u.lname) as name, p.rate_amount, p.tax, p.coupon, p.total, p.currency, p.pp, p.created
            FROM `" . Membership::pTable . '` AS p
            LEFT JOIN `' . User::mTable . '` AS u ON u.id = p.user_id
            LEFT JOIN `' . Membership::mTable . "` AS m ON m.id = p.membership_id
            $where
            ORDER BY p.created DESC;
            ";
            
        return Database::Go()->rawQuery($sql)->run('array');
    }
        
    /**
     * userTotals
     *
     * @return mixed
     */
    public static function userTotals(): mixed
    {
        $sql = 'SELECT SUM(total) as total FROM `' . Membership::pTable . '` WHERE user_id = ? GROUP BY user_id';
            
        $row = Database::Go()->rawQuery($sql, array(App::Auth()->uid))->first()->run();
        return $row ? $row->total : 0;
    }
        
    /**
     * doArraySum
     *
     * @param array|int $array $array
     * @param string $key
     * @return int|string
     */
    public static function doArraySum(array|int $array, string $key): int|string
    {
        if (is_array($array)) {
            return (number_format(array_sum(array_map(function ($item) use ($key) {
                return $item->$key;
            }, $array)), 2, '.', ''));
        }
        return 0;
    }
}

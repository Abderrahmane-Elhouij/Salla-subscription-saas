<?php
    /**
     * Core Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Core.php, v1.00 7/1/2023 9:15 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
class Core
{
    const sTable = 'settings';
    const txTable = 'trash';
    const cjTable = 'cronjobs';
    const gTable = 'gateways';
    public static string $language;
        
    public string $company;
    public string $site_dir;
    public string $site_email;
    public string $psite_email;
    public string $logo;
    public string $plogo;
    public string $short_date;
    public string $long_date;
    public string $calendar_date;
    public string $time_format;
    public string $dtz;
    public string $locale;
    public string $lang;
    public int $weekstart;
    public int $perpage;
    public int $eucookie;
    public string $currency;
    public int $enable_tax;
    public float $tax_rate;
    public string $inv_info;
    public string $inv_note;
    public string $offline_info;
    public int $reg_allowed;
    public int $reg_verify;
    public int $notify_admin;
    public int $auto_verify;
    public stdClass $social;
    public stdClass $page_slugs;
    public string $mailer;
    public string $file_dir;
    public int $one_login;
    public int $enable_dmembership;
    public int $dmembership;
    public string $smtp_host;
    public string $smtp_user;
    public string $smtp_pass;
    public int $smtp_port;
    public string $sendmail;
    public int $is_ssl;

    // Add Salla credentials
    public ?string $salla_client_id = null;
    public ?string $salla_client_secret = null;
    public ?string $salla_webhook_secret = null;

    public string $backup;
    public string $wojov;
    public string $wojon;
    public string $_url;
    public array $_urlParts;
        
    /**
     *
     */
    public function __construct()
    {
        $this->settings();
        ($this->dtz) ? ini_set('date.timezone', $this->dtz) : date_default_timezone_set('UTC');
        Locale::setDefault($this->locale);
    }
        
    /**
     * index
     *
     * @return void
     */
    public function index(): void
    {
        $tpl = App::View(BASEPATH . 'view/');
        $tpl->dir = 'admin/';
        $tpl->title = Language::$word->META_T25;
        $tpl->caption = Language::$word->META_T25;
        $tpl->subtitle = Language::$word->CG_INFO;
            
        $tpl->data = $this;
        $tpl->countries = Database::Go()->select(Content::cTable)->orderBy('sorting', 'DESC')->run();
        $tpl->memberships = App::Membership()->getMembershipList();
            
        $tpl->template = 'admin/configuration';
    }
        
    /**
     * processConfig
     *
     * @return void
     */
    public function processConfig(): void
    {
        $validate = Validator::run($_POST);
        $validate
            ->set('company', Language::$word->CG_SITENAME)->required()->string()->min_len(2)->max_len(80)
            ->set('site_email', Language::$word->CG_WEBEMAIL)->required()->email()
            ->set('psite_email', Language::$word->CG_WEBEMAIL1)->email()
            ->set('perpage', Language::$word->CG_PERPAGE)->required()->numeric();
            
        $validate
            ->set('long_date', Language::$word->CG_LONGDATE)->required()->string()
            ->set('short_date', Language::$word->CG_SHORTDATE)->required()->string()
            ->set('calendar_date', Language::$word->CG_CALDATE)->required()->string()
            ->set('time_format', Language::$word->CG_TIMEFORMAT)->required()->string()
            ->set('dtz', Language::$word->CG_DTZ)->required()->string()
            ->set('locale', Language::$word->CG_LOCALES)->required()->string()
            ->set('weekstart', Language::$word->CG_WEEKSTART)->required()->numeric()
            ->set('lang', Language::$word->CG_LANG)->required()->string()->exact_len(2);
            
        $validate
            ->set('eucookie', Language::$word->CG_EUCOOKIE)->required()->numeric()
            ->set('currency', Language::$word->CG_CURRENCY)->required()->string()->min_len(3)->max_len(6)
            ->set('tax_rate', Language::$word->CG_ETAX_RATE)->required()->float()
            ->set('enable_tax', Language::$word->CG_ETAX)->required()->numeric()
            ->set('reg_verify', Language::$word->CG_REGVERIFY)->required()->numeric()
            ->set('auto_verify', Language::$word->CG_AUTOVERIFY)->required()->numeric()
            ->set('notify_admin', Language::$word->CG_NOTIFY_ADMIN)->required()->numeric()
            ->set('reg_allowed', Language::$word->CG_REGALOWED)->required()->numeric();
            
        $validate
            ->set('file_dir', Language::$word->CG_FILEDIR)->required()->path()
            ->set('one_login', Language::$word->CG_LOGIN)->required()->numeric()
            ->set('mailer', Language::$word->CG_MAILER)->required()->string()->min_len(3)->max_len(5)
            ->set('is_ssl', Language::$word->CG_SMTP_SSL)->required()->numeric()
            ->set('site_dir', Language::$word->CG_DIR)->string()
            ->set('twitter', Language::$word->CG_TWID)->string()
            ->set('facebook', Language::$word->CG_FBID)->string();
            
        $validate
            ->set('dmembership', Language::$word->CG_AUTOVERIFY)->numeric()
            ->set('inv_info', Language::$word->CG_INVDATA)->text('basic')
            ->set('inv_note', Language::$word->CG_INVNOTE)->text('basic')
            ->set('offline_info', Language::$word->CG_OFFLINE)->text('basic')
            ->set('sendmail', Language::$word->CG_SMAILPATH)->path();
            
        $validate
            ->set('smtp_host', Language::$word->CG_SMTP_HOST)->string()
            ->set('smtp_user', Language::$word->CG_SMTP_USER)->string()
            ->set('smtp_pass', Language::$word->CG_SMTP_PASS)->string()
            ->set('smtp_port', Language::$word->CG_SMTP_PORT)->numeric()
            // Validate Salla credentials
            ->set('salla_client_id', 'Salla Client ID')->string()
            ->set('salla_client_secret', 'Salla Client Secret')->string()
            ->set('salla_webhook_secret', 'Salla Webhook Secret')->string();
            
        switch ($_POST['mailer']) {
            case 'SMTP':
                $validate
                    ->set('smtp_host', Language::$word->CG_SMTP_HOST)->required()->string()
                    ->set('smtp_user', Language::$word->CG_SMTP_USER)->required()->string()
                    ->set('smtp_pass', Language::$word->CG_SMTP_PASS)->required()->string()
                    ->set('smtp_port', Language::$word->CG_SMTP_PORT)->required()->numeric();
                break;
                
            case 'SMAIL':
                $validate->set('sendmail', Language::$word->CG_SMAILPATH)->required()->string();
                break;
        }
        $safe = $validate->safe();
            
        $logo = File::upload('logo', 3145728, 'png,jpg,svg');
        $plogo = File::upload('plogo', 3145728, 'png,jpg,svg');
            
        if (count(Message::$msgs) === 0) {
            $smedia['facebook'] = $safe->facebook;
            $smedia['twitter'] = $safe->twitter;
                
            $data = array(
                'company' => $safe->company,
                'site_email' => $safe->site_email,
                'psite_email' => $safe->psite_email,
                'long_date' => $safe->long_date,
                'short_date' => $safe->short_date,
                'time_format' => $safe->time_format,
                'calendar_date' => $safe->calendar_date,
                'weekstart' => $safe->weekstart,
                'lang' => $safe->lang,
                'perpage' => $safe->perpage,
                'dtz' => $safe->dtz,
                'locale' => $safe->locale,
                'reg_verify' => $safe->reg_verify,
                'auto_verify' => $safe->auto_verify,
                'reg_allowed' => $safe->reg_allowed,
                'notify_admin' => $safe->notify_admin,
                'currency' => $safe->currency,
                'enable_tax' => $safe->enable_tax,
                'tax_rate' => $safe->tax_rate,
                'one_login' => $safe->one_login,
                'social_media' => json_encode($smedia),
                'mailer' => $safe->mailer,
                'sendmail' => $safe->sendmail,
                'smtp_host' => $safe->smtp_host,
                'smtp_user' => $safe->smtp_user,
                'smtp_pass' => $safe->smtp_pass,
                'smtp_port' => $safe->smtp_port,
                'is_ssl' => $safe->is_ssl,
                'file_dir' => $safe->file_dir,
                'enable_dmembership' => array_key_exists('enable_dmembership', $_POST) ? 1 : 0,
                'dmembership' => $safe->dmembership,
                'inv_info' => $safe->inv_info,
                'inv_note' => $safe->inv_note,
                'offline_info' => $safe->offline_info,
                // Add Salla credentials
                'salla_client_id' => $safe->salla_client_id,
                'salla_client_secret' => $safe->salla_client_secret,
                'salla_webhook_secret' => $safe->salla_webhook_secret,
            );
                
            if (array_key_exists('logo', $_FILES)) {
                File::deleteFile(UPLOADS . $this->logo);
                $result = File::process($logo, UPLOADS . '/', false, 'logo', false);
                $data['logo'] = $result['fname'];
            }
                
            if (array_key_exists('plogo', $_FILES)) {
                File::deleteFile(UPLOADS . $this->logo);
                $result = File::process($plogo, UPLOADS . '/', false, 'print_logo', false);
                $data['plogo'] = $result['fname'];
            }
                
            if (Validator::post('dellogo')) {
                $data['logo'] = 'NULL';
            }
            if (Validator::post('dellogop')) {
                $data['plogo'] = 'NULL';
            }
                
            Database::Go()->update(Core::sTable, $data)->where('id', 1, '=')->run();
            Message::msgReply(Database::Go()->affected(), 'success', Language::$word->CG_UPDATED);
        } else {
            Message::msgSingleStatus();
        }
    }
        
    /**
     * settings
     *
     * @return void
     */
    protected function settings(): void
    {
        $row = Database::Go()->select(self::sTable)->where('id', 1, '=')->first()->run();
            
        $this->company = $row->company;
        $this->site_dir = $row->site_dir;
        $this->site_email = $row->site_email;
        $this->psite_email = $row->psite_email;
        $this->logo = $row->logo;
        $this->plogo = $row->plogo;
        $this->short_date = $row->short_date;
        $this->long_date = $row->long_date;
        $this->calendar_date = $row->calendar_date;
        $this->time_format = $row->time_format;
        $this->dtz = $row->dtz;
        $this->locale = $row->locale;
        $this->lang = $row->lang;
        $this->weekstart = $row->weekstart;
        $this->perpage = $row->perpage;
        $this->eucookie = $row->eucookie;
        $this->currency = $row->currency;
        $this->enable_tax = $row->enable_tax;
        $this->tax_rate = $row->tax_rate;
        $this->inv_info = $row->inv_info;
        $this->inv_note = $row->inv_note;
        $this->offline_info = $row->offline_info;
        $this->reg_allowed = $row->reg_allowed;
        $this->reg_verify = $row->reg_verify;
        $this->notify_admin = $row->notify_admin;
        $this->auto_verify = $row->auto_verify;
        $this->social = json_decode($row->social_media);
        $this->mailer = $row->mailer;
        $this->file_dir = $row->file_dir;
        $this->one_login = $row->one_login;
            
        $this->enable_dmembership = $row->enable_dmembership;
        $this->dmembership = $row->dmembership;
            
        $this->page_slugs = json_decode($row->page_slugs);
            
        $this->smtp_host = $row->smtp_host;
        $this->smtp_user = $row->smtp_user;
        $this->smtp_pass = $row->smtp_pass;
        $this->smtp_port = $row->smtp_port;
        $this->sendmail = $row->sendmail;
        $this->is_ssl = $row->is_ssl;

        // Load Salla integration credentials
        $this->salla_client_id = $row->salla_client_id;
        $this->salla_client_secret = $row->salla_client_secret;
        $this->salla_webhook_secret = $row->salla_webhook_secret;

        $this->backup = $row->backup;
        $this->wojov = $row->wojov;
        $this->wojon = $row->wojon;
    }
}

<?php
    /**
     * Language Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Language.php, v1.00 7/1/2023 6:33 PM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class Language
    {
        const langdir = 'lang/';
        public static string $language;
        public static stdClass $word;
        public static string $lang;
        public static array $main = [];
        public static array $section = [];
        
        
        /**
         * run
         *
         * @return void
         */
        public static function run(): void
        {
            self::get();
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
            $tpl->title = Language::$word->META_T21;
            $tpl->caption = Language::$word->META_T21;
            $tpl->subtitle = Language::$word->LG_SUB2;
            
            $tpl->data = Language::$main;
            
            $sections = Language::$section;
            $tpl->sections = new ArrayObject($sections);
            $tpl->sections->asort();
            
            $tpl->template = 'admin/language';
        }
        
        /**
         * get
         *
         * @return void
         */
        private static function get(): void
        {
            $core = App::Core();
            if (isset($_COOKIE['LANG_MMP'])) {
                $sel_lang = sanitize($_COOKIE['LANG_MMP'], 2);
                $vlang = self::fetchLanguage();
                if (in_array($sel_lang, $vlang)) {
                    Core::$language = $sel_lang;
                } else {
                    Core::$language = $core->lang;
                }
                if (file_exists(BASEPATH . self::langdir . Core::$language . '.lang.json')) {
                    self::$word = self::set(BASEPATH . self::langdir . Core::$language . '.lang.json');
                } else {
                    self::$word = self::set(BASEPATH . self::langdir . $core->lang . '.lang.json');
                }
            } else {
                Core::$language = $core->lang;
                self::$word = self::set(BASEPATH . self::langdir . $core->lang . '.lang.json');
                
            }
            self::$lang = '_' . Core::$language;
        }
        
        /**
         * set
         *
         * @param string $lang
         * @return object
         */
        private static function set(string $lang): object
        {
            $data = json_decode(File::loadFile($lang), true);
            self::$section = array_keys($data);
            self::$main = array_reduce($data, 'array_merge', array());
            return (object) array_reduce($data, 'array_merge', array());
        }
        
        /**
         * fetchLanguage
         *
         * @return array
         */
        public static function fetchLanguage(): array
        {
            $directory = BASEPATH . self::langdir;
            return File::findFiles($directory, array('fileTypes' => array('json'), 'returnType' => 'fileOnly'));
        }
    }
<?php
    /**
     * bootstrap
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: bootstrap.php, v1.00 7/1/2023 4:36 PM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    
    define('BASE', realpath(dirname(__file__)) . '/lib') . '/';
    const DS = DIRECTORY_SEPARATOR;
    
    class Bootstrap
    {
        protected static ?Bootstrap $instance = null;
        
        
        /**
         *
         */
        private function __construct()
        {
            spl_autoload_register(array($this, 'autoLoad'));
        }
        
        /**
         * init
         *
         * @return Bootstrap|null
         */
        public static function init(): ?Bootstrap
        {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            
            return self::$instance;
        }
        
        /**
         * Autoloader
         *
         * @param $class_paths
         * @return bool|array
         */
        public static function autoloader($class_paths = null): bool|array
        {
            static $is_init = false;
            static $count = 0;
            
            static $conf = array(
                'basepath' => '',
                //'extensions' => array('.class.php'), // multiple extensions ex: ('.php', '.class.php')
                'extensions' => array('.php'), // multiple extensions ex: ('.php', '.class.php')
                'namespace' => ''
            );
            
            static $paths = [];
            
            if (is_null($class_paths)) {
                return $paths;
            }
            
            if (is_array($class_paths) && isset($class_paths[0]) && is_array($class_paths[0])) {
                foreach ($class_paths[0] as $k => $v) {
                    if (isset($conf[$k]) || array_key_exists($k, $conf)) {
                        $conf[$k] = $v;
                    }
                }
                unset($class_paths[0]);
            }
            
            if (!$is_init) {
                spl_autoload_extensions(implode(',', $conf['extensions']));
                $is_init = true;
            }
            
            $paths['conf'] = $conf;
            
            if (!is_array($class_paths)) {
                $class_path = str_replace('', DIRECTORY_SEPARATOR, $class_paths);
                foreach ($paths as $path) {
                    if (!is_array($path)) {
                        foreach ($conf['extensions'] as &$ext) {
                            $ext = trim($ext);
                            
                            if (file_exists($path . $class_path . $ext)) {
                                if (!isset($paths['loaded'])) {
                                    $paths['loaded'] = [];
                                }
                                
                                $paths['loaded'][] = $path . $class_path . $ext;
                                
                                require $path . $class_path . $ext;
                                Debug::addMessage('params', __METHOD__ . '[' . ++$count . ']', 'autoloaded class "' . $path . $class_path . $ext);
                                
                                return true;
                            }
                        }
                    }
                }
                
                return false; // failed to autoload class
            } else {
                $is_unregistered = true;
                
                if (count($class_paths) > 0) {
                    foreach ($class_paths as $path) {
                        $tmp_path = File::_fixPath($path);
                        
                        if (!in_array($tmp_path, $paths)) {
                            $paths[] = $tmp_path;
                            Debug::addMessage('params', __METHOD__ . '[' . ++$count . ']', 'registered path "' . 'autoloaded class "' . $tmp_path, 'session');
                        }
                    }
                    
                    if (spl_autoload_register('Bootstrap::autoloader')) {
                        $is_unregistered = false; // flag unable to register
                    } else {
                        Debug::addMessage('params', __METHOD__ . '[' . ++$count . ']', 'autoload register failed', 'session');
                    }
                }
                
                return !DEBUG ? !$is_unregistered : $paths;
            }
        }
        
        /**
         * autoLoad
         *
         * @param string $class
         * @return true|void
         */
        public function autoLoad(string $class)
        {
            $exts = array('.php', '.class.php');
            $exts = array('.php');
            
            spl_autoload_extensions("'" . implode(',', $exts) . "'");
            set_include_path(get_include_path() . PATH_SEPARATOR . BASE);
            
            foreach ($exts as $ext) {
                if (is_readable($path = BASE . strtolower($class . $ext))) {
                    require_once $path;
                    return true;
                }
            }
            self::recursiveAutoLoad();
        }
        
        /**
         * recursiveAutoLoad
         *
         * @return void
         */
        private static function recursiveAutoLoad(): void
        {
            if (is_dir(BASE)) {
                if (($handle = opendir(BASE)) !== false) {
                    while (($resource = readdir($handle)) !== false) {
                        if (($resource == '..') or ($resource == '.')) {
                            continue;
                        }
                        
                        if (is_dir($dir = BASE . DS . $resource)) {
                            continue;
                        } else {
                            if (is_readable($file = BASE . DS . $resource)) {
                                require_once $file;
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }
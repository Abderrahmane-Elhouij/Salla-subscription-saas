<?php
    /**
     * View Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: View.php, v1.00 7/1/2023 6:44 PM Gewa Exp $
     *
     */
    if (!defined('_WOJO')) {
        die('Direct access to this location is not allowed.');
    }
    
    class View
    {
        public array $properties;
        public string $path;
        public string $template = '';
        public string $dir = '';
        public array $crumbs;
        public string $ext = '.tpl.php';
		
        /**
         * @param string $path
         */
        public function __construct(string $path)
        {
            $this->properties = array();
            $this->path = $path;
        }
        
        /**
         * render
         *
         * @return false|string
         */
        public function render(): false|string
        {
            $this->template = $this->path . $this->template . $this->ext;
            try {
                if (!file_exists($this->template)) {
                    Debug::addMessage('errors', '<i>Exception</i>', 'filename ' . $this->path . $this->template . ' not found', 'session');
                    throw new Exception($this->template . ' template was not found');
                }
                Debug::addMessage('params', 'template', $this->template, 'session');
                ob_start();
                if ($this->dir) {
                    include_once $this->path . $this->dir . 'header' . $this->ext;
                }
                include_once($this->template);
                if ($this->dir) {
                    include_once $this->path . $this->dir . 'footer' . $this->ext;
                }
            } catch (Exception $e) {
                echo 'Caught exception: ', Message::msgSingleError($e->getMessage());
            }
            
            return ob_get_clean();
        }
        
        /**
         * __set
         *
         * @param $name
         * @param $value
         * @return void
         */
        public function __set($name, $value)
        {
            $this->properties[$name] = $value;
        }
        
        /**
         * __get
         *
         * @param $name
         * @return mixed|void
         */
        public function __get($name)
        {
            if (array_key_exists($name, $this->properties)) {
                return $this->properties[$name];
            }
            
        }
    }
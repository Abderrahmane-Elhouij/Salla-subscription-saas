<?php
    /**
     * App Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: App.php, v1.00 7/1/2023 6:20 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
final class App
{
    private static array $instances = array();
    private static $subAdmin;
        
    /**
     * __callStatic
     *
     * @param string $name
     * @param array $args
     * @return mixed|object|string|void|null
     */
    public static function __callStatic(string $name, array $args)
    {
        try {
            if (!class_exists($name)) {
                throw new Exception('Class name ' . $name . ' does not exists.');
            }
            //make a new instance
            if (!in_array($name, array_keys(self::$instances))) {
                //check for arguments
                if (empty($args)) {
                    //new keyword will accept a string in a variable
                    $instance = new $name();
                } else {
                    //we need reflection to instantiate with an arbitrary number of args
                    $rc = new ReflectionClass($name);
                    $instance = $rc->newInstanceArgs($args);
                }
                self::$instances[$name] = $instance;
            } else {
                //already have one
                $instance = self::$instances[$name];
            }
            return $instance;
        } catch (Exception $e) {
            Debug::addMessage('warnings', '<i>Warning</i>', $e->getMessage());
        }
    }

    /**
     * SubAdmin
     *
     * @throws NotFoundException
     * @return SubAdmin
     */
    public static function SubAdmin(): SubAdmin
    {
        if (!self::$subAdmin) {
            self::$subAdmin = new SubAdmin();
        }
        return self::$subAdmin;
    }
}

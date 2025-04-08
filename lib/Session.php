<?php
/**
 * Session Class
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2023
 * @version 5.00: Session.php, v1.00 7/1/2023 4:52 PM Gewa Exp $
 *
 */
if (!defined("_WOJO")) {
    die('Direct access to this location is not allowed.');
}

class Session
{
    protected string $_defaultSessionName = 'wojo_framework';
    protected string $_defaultSessionPrefix = 'wojo_';
    protected static string $_prefix = '';
    protected string $_cookieMode = 'allow';

    public function __construct()
    {
        // Set cookie mode before any output
        $this->_setCookieMode($this->_cookieMode);

        // Set session name before any output
        $this->setSessionName('wojo_' . INSTALL_KEY);

        // Open session before any output
        $this->_openSession();
    }

    private function _setCookieMode(string $value = ''): void
    {
        if ($value === 'none') {
            ini_set('session.use_cookies', '0');
        } elseif ($value === 'allow' || $value === 'only') {
            ini_set('session.use_cookies', '1');
        } else {
            Debug::addMessage('warnings', 'session_cookie_mode', "HttpSession.cookieMode can only be \"none\", \"allow\" or \"only\".");
        }
    }


    public function setSessionName(string $value): void
    {
        if (empty($value)) {
            $value = $this->_defaultSessionName;
        }

        session_name($value);
    }

    private function _openSession(): void
    {
        if (strlen(session_id()) < 1) {
            session_start();
        }

        if (DEBUG && session_id() == '') {
            Debug::addMessage('errors', 'session', 'Failed to start session');
        }
    }

    /**
     * getCookie
     *
     * @param string $name
     * @return mixed
     */
    public static function getCookie(string $name): mixed
    {
        return $_COOKIE[$name] ?? false;
    }

    /**
     * removeCookie
     *
     * @param string $name
     * @return bool
     */
    public static function removeCookie(string $name): bool
    {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            setcookie($name, '', time() - 3600, '/');
            return true;
        }

        return false;
    }

    /**
     * cookieExists
     *
     * @param string $name
     * @param string $value
     * @return bool
     */
    public static function cookieExists(string $name, string $value): bool
    {
        return isset($_COOKIE[$name]) and $_COOKIE[$name] == $value;
    }

    /**
     * getSessionName
     *
     * @return false|string
     */
    public static function getSessionName(): false|string
    {
        return session_name();
    }

    /**
     * getTimeout
     *
     * @return int
     */
    public static function getTimeout(): int
    {
        return (int)ini_get('session.gc_maxlifetime');
    }

    /**
     * captcha
     *
     * @return string
     */
    public static function captcha(): string
    {
        self::set('wcaptcha', mt_rand(10000, 99999));
        return self::get('wcaptcha');
    }

    /**
     * set
     *
     * @param string $name
     * @param mixed $value
     * @param bool $cookie
     * @return mixed
     */
    public static function set(string $name, mixed $value, bool $cookie = false): mixed
    {
        return $cookie ? setcookie($name, $value, time() + 86400 * 300, '/') : $_SESSION[self::$_prefix . $name] = $value;
    }

    /**
     * get
     *
     * @param string $name
     * @param string $default
     * @return mixed
     */
    public static function get(string $name, string $default = ''): mixed
    {
        return $_SESSION[self::$_prefix . $name] ?? $default;
    }

    /**
     * captchaAlt
     *
     * @return string
     */
    public static function captchaAlt(): string
    {
        self::set('wacaptcha', mt_rand(10000, 99999));
        return self::get('wacaptcha');
    }

    /**
     * setKey
     *
     * @param string $name
     * @param string $key
     * @param string|array $value
     * @return void
     */
    public static function setKey(string $name, string $key, string|array $value): void
    {
        $_SESSION[self::$_prefix . $name][$key] = $value;
    }

    /**
     * remove
     *
     * @param string $name
     * @return bool
     */
    public static function remove(string $name): bool
    {
        if (isset($_SESSION[self::$_prefix . $name])) {
            unset($_SESSION[self::$_prefix . $name]);
            return true;
        }

        return false;
    }

    /**
     * isExists
     *
     * @param string $name
     * @return bool
     */
    public static function isExists(string $name): bool
    {
        return isset($_SESSION[self::$_prefix . $name]);
    }

    /**
     * setSessionPrefix
     *
     * @param string $value
     * @return void
     */
    public function setSessionPrefix(string $value): void
    {
        if (empty($value)) {
            $value = $this->_defaultSessionPrefix;
        }

        self::$_prefix = $value;
    }

    /**
     * endSession
     *
     * @return void
     */
    public static function endSession(): void
    {
        if (session_id() !== '') {
            session_destroy();
        }
    }

    /**
     * closeSession
     *
     * @return true
     */
    public function closeSession(): true
    {
        return true;
    }

    /**
     * getCookieMode
     *
     * @return string
     */
    public function getCookieMode(): string
    {
        if (ini_get('session.use_cookies') === '0') {
            return 'none';
        } elseif (ini_get('session.use_only_cookies') === '0') {
            return 'allow';
        } else {
            return 'only';
        }
    }
}
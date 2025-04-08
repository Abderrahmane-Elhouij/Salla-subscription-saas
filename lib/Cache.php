<?php
    /**
     * Cache Class
     *
     * @package Wojo Framework
     * @author wojoscripts.com
     * @copyright 2023
     * @version 5.00: Cache.php, v1.00 7/1/2023 6:22 PM Gewa Exp $
     *
     */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}
    
class Cache
{
    const CACHE_LIMIT = 100;
    private static string $_cacheFile = '';
    private static string $_cacheLifetime = '';
        
    const prefix = 'master_';
    const suffix = '.css';
        
        
    /**
     * cssCache
     *
     * @param array $source
     * @param string $path
     * @return string
     */
    public static function cssCache(array $source, string $path): string
    {
            
        $target = $path . '/cache/';
        $last_change = self::lastChange($source, $path);
        $temp = $target . self::prefix . 'main' . self::suffix;
            
        if (!file_exists($temp) || $last_change > filemtime($temp)) {
            if (!self::writeCssCache($source, $temp, $path)) {
                Message::msgError("Minify:: - Writing the file to <$target> failed!");
                Debug::addMessage('errors', '<i>Exception</i>', 'Minify:: - Writing the file to <{$target}> failed!', 'session');
            }
        }
            
        return basename($temp);
    }
        
    /**
     * lastChange
     *
     * @param array $files
     * @param string $path
     * @return mixed
     */
    protected static function lastChange(array $files, string $path): mixed
    {
        foreach ($files as $key => $file) {
            $files[$key] = filemtime($path . '/css/' . $file);
        }
            
        sort($files);
        $files = array_reverse($files);
            
        return $files[key($files)];
    }
        
    /**
     * writeCssCache
     *
     * @param array $files
     * @param string $target
     * @param string $path
     * @return false|int
     */
    protected static function writeCssCache(array $files, string $target, string $path): false|int
    {
            
        $content = '';
            
        foreach ($files as $file) {
            $content .= file_get_contents($path . '/css/' . $file);
        }
            
            
        $content = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $content);
        $content = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $content);
        $content = str_replace(array(': ', ' {', ';}'), array(':', '{', '}'), $content);
            
        if (!file_exists($path . '/cache/')) {
            mkdir($path . '/cache/');
        }
            
        return file_put_contents($target, $content);
    }
        
    /**
     * setCacheFile
     *
     * @param string $cacheFile
     * @return void
     */
    public static function setCacheFile(string $cacheFile = ''): void
    {
        self::$_cacheFile = !empty($cacheFile) ? $cacheFile : '';
    }
        
    /**
     * getCacheFile
     *
     * @return string
     */
    public static function getCacheFile(): string
    {
        return self::$_cacheFile;
    }
        
    /**
     * setCacheLifetime
     *
     * @param int $cacheLifetime
     * @return void
     */
    public static function setCacheLifetime(int $cacheLifetime = 0): void
    {
        self::$_cacheLifetime = !empty($cacheLifetime) ? $cacheLifetime : 0;
    }
        
    /**
     * getCacheLifetime
     *
     * @return string
     */
    public static function getCacheLifetime(): string
    {
        return self::$_cacheLifetime;
    }
        
    /**
     * setContent
     *
     * @param string $content
     * @param string $cacheDir
     * @return void
     */
    public static function setContent(string $content = '', string $cacheDir = ''): void
    {
        if (!empty(self::$_cacheFile)) {
            // remove oldest file if the limit of cache is reached
            if (File::getDirectoryFilesNumber($cacheDir) >= self::CACHE_LIMIT) {
                File::removeDirectoryOldestFile($cacheDir);
            }
                
            // save the content to the cache file
            File::writeToFile(self::$_cacheFile, serialize($content));
        }
    }
        
    /**
     * getContent
     *
     * @param string $cacheFile
     * @param int $cacheLifetime
     * @return false|mixed|string
     */
    public static function getContent(string $cacheFile = '', int $cacheLifetime = 0): mixed
    {
        $result = '';
        $cacheContent = '';
            
        if (!empty($cacheFile)) {
            self::setCacheFile($cacheFile);
        }
        if (!empty($cacheLifetime)) {
            self::setCacheLifetime($cacheLifetime);
        }
            
        if (!empty(self::$_cacheFile) && !empty(self::$_cacheLifetime)) {
            if (file_exists(self::$_cacheFile)) {
                $cacheTime = self::$_cacheLifetime * 60;
                if ((filesize(self::$_cacheFile) > 0) && ((time() - $cacheTime) < filemtime(self::$_cacheFile))) {
                    ob_start();
                    include self::$_cacheFile;
                    $cacheContent = ob_get_contents();
                    ob_end_clean();
                }
                $result = !empty($cacheContent) ? unserialize($cacheContent) : $cacheContent;
            }
        }
            
        return $result;
    }
}
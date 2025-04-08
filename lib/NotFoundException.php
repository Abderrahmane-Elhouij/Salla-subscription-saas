<?php
/**
 * NotFoundException Class
 *
 * @package Wojo Framework
 * @author wojoscripts.com
 * @copyright 2023
 * @version 5.00: NotFoundException.php, v1.00 7/6/2023 7:18 PM Gewa Exp $
 *
 */
if (!defined('_WOJO')) {
    die('Direct access to this location is not allowed.');
}

class NotFoundException extends Exception
{
    /**
     * @param $message
     * @param $code
     * @param Exception|null $previous
     */
    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * __toString
     *
     * @return string
     */
    public function __toString(): string
    {
        return sprintf("[%s](%s:%s): %s\n", __CLASS__, $this->getFile(), $this->getLine(), $this->getMessage());
    }
}
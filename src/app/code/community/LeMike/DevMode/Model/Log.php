<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * Class Log.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevMode_Model_Log
{
    const PREFIX_ERROR = "ERROR! ";

    const PREFIX_WARNING = "Warning: ";

    /**
     * Name of the extension.
     *
     * Will be used for filename and logging prefix.
     *
     * @var string
     */
    protected static $_prefix = LeMike_DevMode_Helper_Data::MODULE_NAME;

    /**
     * Decide when to print the error message (true) or not (false)
     *
     * @var bool
     */
    protected static $_print = false;


    /**
     * Send a debug message to the log
     *
     * @param mixed $message The message to send to / debug in the logfile
     *
     * @return void
     */
    public static function debug($message)
    {
        if (!is_scalar($message))
        {
            $message = serialize($message);
        }

        static::_logAdapter($message, Zend_Log::DEBUG);
    }


    /**
     * Send an error message to the log
     *
     * @param string $message The error to append in the log.
     *
     * @return void
     */
    public static function error($message)
    {
        $message = static::PREFIX_ERROR . $message;
        static::_logAdapter($message, Zend_Log::ERR);
    }


    /**
     * Send a message to log
     *
     * @param string $message An information to put in the log.
     *
     * @return void
     */
    public static function info($message)
    {
        static::_logAdapter($message, Zend_Log::INFO);
    }


    /**
     * Switch if log shall be output directly or not.
     *
     * @param bool $bool Decide whether the logging shall be pushed to the output or not (to file).
     *
     * @return void
     */
    public static function setPrint($bool = true)
    {
        static::$_print = $bool;
    }


    /**
     * Send an error message to the log
     *
     * @param string $message A warning message to put into log.
     *
     * @return void
     */
    public static function warning($message)
    {
        $message = self::PREFIX_WARNING . $message;
        static::_logAdapter($message, Zend_Log::WARN);
    }


    /**
     * Forward information to the adapter.
     *
     * @param string $message  The message to log.
     * @param null   $level    Level of influence (Zend_Log constants).
     * @param null   $file     The file to write to.
     * @param bool   $forceLog Force to log it (needed sometimes).
     *
     * @return void
     */
    protected static function _logAdapter($message, $level = null, $file = null, $forceLog = false)
    {
        if (!$file)
        {
            $file = static::$_prefix . '.log';
        }

        $message = static::$_prefix . ": " . $message;
        if (self::$_print)
        {
            echo $message;
        }
        Mage::log($message, $level, $file, $forceLog);
    }
}

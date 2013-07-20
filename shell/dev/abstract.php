<?php

require_once '../abstract.php';

/**
 * Shell scripts abstract class
 *
 * @category    LeMike
 * @package     LeMike_DevMode
 * @author      Mike Pretzlaw <pretzlaw@gmail.com>
 */
abstract class LeMike_DevMode_Shell_Abstract extends Mage_Shell_Abstract
{
    /**
     * Initialize application and parse input parameters
     *
     */
    public function __construct()
    {
        $loadMessage = "Loading Magento ...";
        echo $loadMessage;

        parent::__construct();

        echo "\r" . str_repeat(' ', strlen($loadMessage)) . "\r";
    }
}

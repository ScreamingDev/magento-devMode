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
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

require_once __DIR__ . DIRECTORY_SEPARATOR . 'DevMode' . DIRECTORY_SEPARATOR . 'AbstractCommand.php';

require_once 'abstract.php';

/**
 * Class devmode.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class DevMode extends DelegateCommand
{
    /**
     * Run this command.
     *
     * @return mixed
     */
    public function execute()
    {
        // TODO: Implement execute() method.
    }
}

class DevMode_Load_Magento extends Mage_Shell_Abstract
{
    const LOADING_MAGENTO = 'Loading Magento ...';
    /**
     * Run script
     *
     */
    public function run()
    {
        echo "\r" . str_repeat(' ', strlen(self::LOADING_MAGENTO)) . "\r";
    }
}

$parameter = $argv;
array_shift($parameter);

$devmode = new DevMode($parameter);
$devmode();

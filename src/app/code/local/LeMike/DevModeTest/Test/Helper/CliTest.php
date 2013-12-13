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
 * @package   LeMike\DevMode\Test\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.1
 */

/**
 * Class ConfigTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.1
 */
class LeMike_DevModeTest_Test_Helper_CliTest extends LeMike_DevModeTest_Test_AbstractCase
{
    public function testAsk()
    {
        if (!extension_loaded('apd'))
        {
            $this->markTestSkipped('Need apd $ pecl install apd');
        }
        else
        {
            /** @noinspection PhpUndefinedFunctionInspection */
            $this->assertTrue(override_function('fgets', '$handle,$length', 'return y'));

            /** @var LeMike_DevMode_Helper_Cli $helperCli */
            $helperCli = Mage::helper('lemike_devmode/cli');

            $a = $helperCli->ask('who?');
            $this->assertEquals('y', $a);
        }
    }
}

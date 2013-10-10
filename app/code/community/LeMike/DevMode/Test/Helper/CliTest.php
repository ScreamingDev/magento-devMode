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
class LeMike_DevMode_Test_Helper_CliTest extends LeMike_DevMode_Test_AbstractCase
{
    public function testAsk()
    {
        if (!extension_loaded('apd'))
        {
            $this->markTestSkipped('Need apd $ pecl install apd');
        }
        else
        {
            $this->assertTrue(override_function('fgets', '$handle,$length', 'return y'));
            $a = Mage::helper('lemike_devmode/cli')->ask('who?');
            $this->assertEquals('y', $a);
        }
    }
}

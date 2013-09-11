<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    ConfigTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
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

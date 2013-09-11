<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMode
 * @package    ConfigTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      0.2.0
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      0.2.0
 */
class LeMike_DevMode_Test_ConfigTest extends EcomDev_PHPUnit_Test_Case_Config
{
    public function testGlobalModelCoreRewrite()
    {
        $this->assertModelAlias('core/email', 'LeMike_DevMode_Model_Core_Email');
        $this->assertModelAlias('core/email_template', 'LeMike_DevMode_Model_Core_Email_Template');
        $this->assertModelAlias('core/email_transport', 'LeMike_DevMode_Model_Core_Email_Transport');
    }
}

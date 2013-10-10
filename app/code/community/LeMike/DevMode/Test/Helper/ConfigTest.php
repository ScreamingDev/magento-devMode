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
 * @since     0.1.0
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
 * @since     0.1.0
 */
class LeMike_DevMail_Test_Helper_ConfigTest extends LeMike_DevMode_Test_AbstractCase
{
    /**
     * Get config helper.
     *
     * @return LeMike_DevMode_Helper_Config
     */
    public function getFrontend()
    {
        return Mage::helper($this->getModuleAlias('/config'));
    }


    public function testSelf()
    {
        $this->assertInstanceOf('LeMike_DevMode_Helper_Config', $this->getFrontend());
    }


    /**
     * .
     *
     * @return void
     */
    public function testIsMailAllowed()
    {
        $helper = $this->getFrontend();
        $this->assertTrue(method_exists($helper, 'isMailAllowed'));
        $helper->isMailAllowed();
    }


    /**
     * .
     *
     * @return void
     */
    public function testGetCoreEmailRecipient()
    {
        $helper = $this->getFrontend();
        $this->assertTrue(method_exists($helper, 'getCoreEmailRecipient'));
        $helper->getCoreEmailRecipient();
    }


    /**
     * .
     *
     * @return void
     */
    public function testGetCustomerCustomerPassword()
    {
        $helper = $this->getFrontend();
        $this->assertTrue(method_exists($helper, 'getCustomerCustomerPassword'));
        $helper->getCustomerCustomerPassword();
    }
}

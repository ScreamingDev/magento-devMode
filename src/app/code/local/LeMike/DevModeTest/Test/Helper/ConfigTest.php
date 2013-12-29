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
class LeMike_DevModeTest_Test_Helper_ConfigTest extends LeMike_DevModeTest_Test_AbstractCase
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


    public function getMethodToPath()
    {
        return array(
            array(
                'generalSecurityAllowRestrictedIpOnly',
                'dev/lemike_devmode/allow_restricted_ip_only'
            ),
            array(
                'getAdminLoginUser',
                'dev/lemike_devmode/admin_login_user'
            ),
            array(
                'getCoreEmailRecipient',
                'dev/lemike_devmode/core_email_recipient'
            ),
            array(
                'getCustomerCustomerPassword',
                'dev/lemike_devmode/customer_password'
            ),
            array(
                'getRemoteCallUrlTemplate',
                'dev/lemike_devmode/remoteCallUrlTemplate'
            ),
            array(
                'isEnabled',
                'dev/lemike_devmode/active'
            ),
            array(
                'isIdeRemoteCallEnabled',
                'dev/lemike_devmode/ideRemoteCallEnabled'
            ),
            array(
                'isMailAllowed',
                'dev/lemike_devmode/core_email_active'
            ),
            array(
                'isToolboxEnabled',
                'dev/lemike_devmode/show_toolbox'
            ),
        );
    }


    /**
     * .
     *
     * @param $method
     * @param $path
     *
     * @dataProvider getMethodToPath
     *
     * @return void
     */
    public function testConfigPaths($method, $path)
    {
        $helper = $this->getFrontend();

        $this->assertTrue(method_exists($helper, $method));

        // check current value
        $value = $helper->$method();
        $originalValue = Mage::app()->getStore()->getConfig($path);

        $this->assertTrue($originalValue == $value);

        // check if path is correct
        $newValue = !$originalValue;
        Mage::app()->getStore()->setConfig($path, $newValue);

        $this->assertEquals($newValue, $helper->$method());

        // reset
        Mage::app()->getStore()->setConfig($path, $originalValue);
        $this->assertTrue($originalValue == $helper->$method());
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


    public function testSelf()
    {
        $this->assertInstanceOf('LeMike_DevMode_Helper_Config', $this->getFrontend());
    }
}

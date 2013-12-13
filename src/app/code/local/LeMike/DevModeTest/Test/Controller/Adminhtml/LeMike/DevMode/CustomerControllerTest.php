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
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CustomerController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevModeTest_Test_Controller_Adminhtml_LeMike_DevMode_CustomerControllerTest extends
    LeMike_DevModeTest_Test_AbstractController
{
    /**
     * Run index action and test for layouts.
     *
     * @doNotIndexAll
     * @loadFixture default_admin
     *
     * @return void
     */
    public function testAdditionalCapabilitiesToMaintainCustomer()
    {
        $this->mockAdminUserSession();

        // layout
        $route = 'adminhtml/' . $this->getModuleName('_customer') . '/index';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        $this->assertLayoutHandleLoaded($this->routeToLayoutHandle($route));
        $this->assertLayoutRendered($this->routeToLayoutHandle($route));

        $this->assertLayoutBlockCreated('lemike.devmode.customer.tabs');
        $this->assertLayoutBlockRendered('lemike.devmode.customer.tabs');
        $this->assertResponseBodyContains(Mage::helper('lemike_devmode')->__('Customer Tools'));

        $this->assertLayoutBlockCreated('lemike.devmode.customer.js');
        $this->assertLayoutBlockRendered('lemike.devmode.customer.js');
        $this->assertResponseBodyContains('function devmode_Customer_Customer_DeleteAll');

        $this->assertLayoutBlockCreated('lemike.devmode.content.customer');
        $this->assertLayoutBlockRendered('lemike.devmode.content.customer');
        $this->assertResponseBodyContains('<div id="devmode_customer">');

        $this->assertLayoutBlockRendered('lemike.devmode.customer.customer');
        $this->assertLayoutBlockRendered('lemike.devmode.customer.customer');
        $this->assertResponseBodyContains('onclick="devmode_Customer_Customer_DeleteAll();"');
    }
}

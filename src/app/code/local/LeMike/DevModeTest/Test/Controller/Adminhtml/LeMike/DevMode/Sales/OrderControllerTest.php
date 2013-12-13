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
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode\Sales
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CoreController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode\Sales
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevModeTest_Test_Controller_Adminhtml_LeMike_DevMode_Menu_Sales_OrderControllerTest extends
    LeMike_DevModeTest_Test_AbstractController
{
    /**
     * Run delete action and test for json dispatch.
     *
     * @loadFixture table_sales_order
     * @doNotIndexAll
     *
     * @return void
     */
    public function testDeleteAllOrdersFromBackend()
    {
        // precondition
        $initialCount = Mage::getModel('sales/order')->getCollection()->count();
        $this->assertGreaterThan(0, $initialCount);

        $this->mockAdminUserSession();

        $route = 'adminhtml/' . $this->getModuleName('_sales_order') . '/deleteAll';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        $this->assertLayoutHandleNotLoaded($this->routeToLayoutHandle($route));

        // main
        $data = json_decode($this->getResponse()->getOutputBody(), true);

        $this->assertResponseBodyJson();
        $this->assertEquals($initialCount, $data['processed']);

        // postcondition
        $collection = Mage::getModel('sales/order')->getCollection();
        $this->assertEquals(0, $collection->count());
    }
}

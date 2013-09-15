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
 * @package    LeMike_DevMode_Adminhtml_Developer_CoreControllerTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.1
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CoreController.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.1
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Developer_Sales_OrderControllerTest extends
    EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Run delete action and test for json dispatch.
     *
     * @loadFixture table_sales_order
     *
     * @return void
     */
    public function testDeleteAllAction()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        // precondition
        $initialCount = Mage::getModel('sales/order')->getCollection()->count();
        $this->assertGreaterThan(0, $initialCount);

        // main
        $this->mockAdminUserSession();
        $this->dispatch('adminhtml/developer_sales_order/deleteAll');

        $this->assertLayoutHandleNotLoaded('adminhtml_developer_sales_order_deleteAll');

        $data = json_decode($this->getResponse()->getBody('default'), true);

        $this->assertResponseBodyJson();
        $this->assertEquals($initialCount, $data['processed']);

        // postcondition
        $collection = Mage::getModel('sales/order')->getCollection();
        $this->assertEquals(0, $collection->count());
    }
}

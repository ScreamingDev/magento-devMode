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
 * @since      0.3.0
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CoreController.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Developer_Catalog_ProductsControllerTest extends
    EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Run delete action and test for json dispatch.
     *
     * @loadFixture eav_catalog_product
     *
     * @return void
     */
    public function testDeleteAllAction()
    {
        // precondition
        $initialCount = Mage::getModel('catalog/product')->getCollection()->count();
        $this->assertGreaterThan(0, $initialCount);

        // main
        $this->mockAdminUserSession();
        $this->dispatch('adminhtml/developer_catalog_products/deleteAll');

        $this->assertLayoutHandleNotLoaded('adminhtml_developer_catalog_product_deleteAll');

        $data = json_decode($this->getResponse()->getBody('default'), true);

        $this->assertResponseBodyJson();
        $this->assertEquals($initialCount, $data['processed']);

        // postcondition
        $collection = Mage::getModel('catalog/product')->getCollection();
        $this->assertEquals(0, $collection->count());
    }
}

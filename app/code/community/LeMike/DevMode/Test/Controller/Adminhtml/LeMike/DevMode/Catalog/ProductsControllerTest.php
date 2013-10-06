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
class LeMike_DevMode_Test_Controller_Adminhtml_LeMike_DevMode_Catalog_ProductsControllerTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Run delete action and test for json dispatch.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     *
     * @return void
     */
    public function testDeleteAllProductsFromBackend()
    {
        // precondition
        $initialCount = Mage::getModel('catalog/product')->getCollection()->count();
        $this->assertGreaterThan(0, $initialCount);

        // admin session
        $this->mockAdminUserSession();

        // main
        $route = 'adminhtml/' . $this->getModuleName('_catalog_products') . '/deleteAll';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        $this->assertLayoutHandleNotLoaded($this->routeToLayoutHandle($route));

        $data = json_decode($this->getResponse()->getBody('default'), true);

        $this->assertResponseBodyJson();
        $this->assertEquals($initialCount, $data['processed']);

        // postcondition
        $collection = Mage::getModel('catalog/product')->getCollection();
        $this->assertEquals(0, $collection->count());
    }


    /**
     * Tests SanitizeAllAction.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     *
     * @return null
     */
    public function testSanitizeAllAction_SaveError()
    {

        /*
         * }}} preconditions {{{
         */

        // admin session
        $this->mockAdminUserSession();

        // some products there
        $initialCount = Mage::getModel('catalog/product')->getCollection()->count();
        $this->assertGreaterThan(0, $initialCount);

        // mock Mage_Catalog_Model_Product::save for throwing errors
        $alias  = 'catalog/product';
        $method = 'save';
        $mock   = $this->getModelMock($alias, array($method));
        $mock->expects($this->any())->method($method)->will(
            $this->returnCallback(
                function ()
                {
                    throw new Exception('abba');
                }
            )
        );
        $this->replaceByMock('model', $alias, $mock);
        $model = Mage::getModel($alias);

        $this->assertSame($mock, $model);

        $collectionMock = $this->getResourceModelMock(
            'catalog/product_collection',
            array('getNewEmptyItem')
        );
        $collectionMock->expects($this->any())->method('getNewEmptyItem')->will(
            $this->returnValue(
                $mock
            )
        );

        $this->replaceByMock('resource_model', 'catalog/product_collection', $collectionMock);
        $collection = Mage::getResourceModel('catalog/product_collection');

        $this->assertSame($collectionMock, $collection);
        $this->assertSame($mock, $collection->getNewEmptyItem());

        $reflectObject = new ReflectionObject($collection);
        $itemObjectClass = $reflectObject->getProperty('_itemObjectClass');
        $itemObjectClass->setAccessible(true);
        $itemObjectClass->setValue($collection, get_class($mock));

        try
        {
            $model->save();
            $this->fail('Could not override save method with an exception.');
        } catch (Exception $e)
        {
            // everything fine
        }

        $route = 'adminhtml/' . $this->getModuleName('_catalog_products') . '/sanitizeAll';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        /*
         * }}} main {{{
         */
        $this->assertResponseBodyJson();

        $value = json_decode($this->getResponse()->getOutputBody(), true);
        $this->assertEquals($initialCount, $value['amount']);
        $this->assertEquals(0, $value['processed']);
        $this->assertNotEmpty($value['errors']);

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests SanitizeAllAction.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     *
     * @return null
     */
    public function testSanitizeStockOfAllProducts()
    {
        /*
         * }}} preconditions {{{
         */

        // admin session
        $this->mockAdminUserSession();

        /*
         * }}} main {{{
         */
        $route = 'adminhtml/' . $this->getModuleName('_catalog_products') . '/sanitizeAll';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

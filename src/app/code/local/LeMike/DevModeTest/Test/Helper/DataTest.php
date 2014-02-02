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
 * @since     0.2.0
 */

/**
 * Class DataTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.2.0
 */
class LeMike_DevModeTest_Test_Helper_DataTest extends LeMike_DevModeTest_Test_AbstractCase
{
    /**
     * .
     *
     * @return LeMike_DevMode_Helper_Data
     */
    public function getFrontend()
    {
        $helper = Mage::helper($this->getModuleAlias());

        $this->assertInstanceOf('LeMike_DevMode_Helper_Data', $helper);

        return $helper;
    }


    /**
     * Tests CanGenerateModelAliases.
     *
     * @return null
     */
    public function testCanGenerateModelAliases()
    {
        /*
         * }}} preconditions {{{
         */
        $helper = $this->getFrontend();

        /*
         * }}} main {{{
         */
        $this->assertSame($this->getModuleAlias('_foo'), $helper::getModuleAlias('_foo'));
        $this->assertSame($this->getModuleAlias('.bar'), $helper::getModuleAlias('.bar'));
        $this->assertSame($this->getModuleAlias('/baz'), $helper::getModuleAlias('/baz'));

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests CanGenerateModelAliases.
     *
     * @return null
     */
    public function testCanGenerateModelNames()
    {
        /*
         * }}} preconditions {{{
         */
        $helper = $this->getFrontend();

        /*
         * }}} main {{{
         */
        $this->assertSame($this->getModuleName('_foo'), $helper::getModuleName('_foo'));
        $this->assertSame($this->getModuleName('.bar'), $helper::getModuleName('.bar'));
        $this->assertSame($this->getModuleName('/baz'), $helper::getModuleName('/baz'));

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Test to response with a JSON.
     *
     * @return null
     */
    public function testResponseArrayAsJson()
    {
        /*
         * }}} preconditions {{{
         */

        // no errors on already send headers
        $this->app()->getResponse()->headersSentThrowsException = false;

        // dispatch array
        $data = array('foo' => 'bar');

        /** @var LeMike_DevMode_Helper_Data $helperData */
        $helperData = Mage::helper('lemike_devmode');
        $helperData->responseJson($data);

        /*
         * }}} main {{{
         */

        // assert body
        $response = Mage::app()->getResponse();
        $this->assertEquals($data, json_decode($response->getBody('default'), true));

        // assert header
        $header = current($response->getHeaders());
        $this->assertEquals('Content-Type', $header['name']);
        $this->assertEquals('application/json', $header['value']);
        $this->assertTrue($header['replace']);

        /*
         * }}} postcondition {{{
         */

        // clean up response
        $this->app()->getResponse()->reset();

        $this->assertEmpty($this->reflectProperty($this->app()->getResponse(), '_sentHeaders'));

        return null;
    }


    /**
     * Delete everything within a collection.
     *
     * @loadFixture eav_catalog_product
     *
     * @return void
     */
    public function testTruncateCollection_Products()
    {
        $this->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        // precondition
        /** @var Mage_Catalog_Model_Resource_Product_Collection $coll */
        $coll         = Mage::getModel('catalog/product')->getCollection();
        $initialCount = $coll->count();
        $this->assertGreaterThan(0, $initialCount);

        // main
        /** @var LeMike_DevMode_Helper_Data $helperData */
        $helperData = Mage::helper('lemike_devmode');
        $processed  = $helperData->truncateCollection($coll);
        $this->assertEquals($initialCount, $processed);
        $this->assertEquals(0, $coll->count());

        // post
        $this->assertEquals(0, Mage::getModel('catalog/product')->getCollection()->count());
    }


    /**
     * Delete everything within the product model
     *
     * @loadFixture eav_catalog_product
     *
     * @return void
     */
    public function testTruncateModelByName_Products()
    {
        $this->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $this->_testTruncateModelByName('catalog/product');
    }


    /**
     * Truncate an unknown model.
     *
     * @loadFixture eav_catalog_product
     *
     * @return void
     */
    public function testTruncateModelByName_Unknown()
    {
        // precondition
        $modelName = uniqid() . '/' . uniqid();
        /** @var Mage_Catalog_Model_Resource_Product_Collection $coll */
        $this->assertFalse(Mage::getModel($modelName));

        // main
        /** @var LeMike_DevMode_Helper_Data $helperData */
        $helperData = Mage::helper('lemike_devmode');
        $data       = $helperData->truncateModelByName($modelName);
        $this->assertEquals(0, $data['processed']);
        $this->assertEquals(0, $data['amount']);
        $this->assertGreaterThan(0, count($data['errors']));
        // post
    }


    protected function _testTruncateModelByName($modelName)
    {
        // precondition
        /** @var Mage_Catalog_Model_Resource_Product_Collection $coll */
        $coll         = Mage::getModel($modelName)->getCollection();
        $initialCount = $coll->count();
        $this->assertGreaterThan(0, $initialCount);

        // main
        /** @var LeMike_DevMode_Helper_Data $helperData */
        $helperData = Mage::helper('lemike_devmode');
        $data       = $helperData->truncateModelByName($modelName);
        $this->assertEquals($initialCount, $data['processed']);
        $this->assertEquals($initialCount, $data['amount']);
        $this->assertEquals(array(), $data['errors']);

        // post
        $this->assertEquals(0, Mage::getModel($modelName)->getCollection()->count());
    }
}

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
class LeMike_DevMode_Test_Helper_DataTest extends LeMike_DevMode_Test_AbstractCase
{
    /**
     * .
     *
     * @return LeMike_DevMode_Helper_Data
     */
    public function getFrontend()
    {
        return Mage::helper($this->getModuleAlias());
    }


    /**
     * Test to response with a JSON.
     *
     * @return void
     */
    public function testResponseJson()
    {
        $data = array('foo' => 'bar');
        Mage::helper('lemike_devmode')->responseJson($data);

        $response = Mage::app()->getResponse();
        $header   = current($response->getHeaders());

        $this->assertEquals('Content-Type', $header['name']);
        $this->assertEquals('application/json', $header['value']);
        $this->assertTrue($header['replace']);

        $this->assertEquals($data, json_decode($response->getBody('default'), true));
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
        $processed = Mage::helper('lemike_devmode')->truncateCollection($coll);
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
        $data = Mage::helper('lemike_devmode')->truncateModelByName($modelName);
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
        $data = Mage::helper('lemike_devmode')->truncateModelByName($modelName);
        $this->assertEquals($initialCount, $data['processed']);
        $this->assertEquals($initialCount, $data['amount']);
        $this->assertEquals(array(), $data['errors']);

        // post
        $this->assertEquals(0, Mage::getModel($modelName)->getCollection()->count());
    }
}

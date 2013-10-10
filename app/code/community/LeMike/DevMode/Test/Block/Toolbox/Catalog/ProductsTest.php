<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    ProductsTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

class LeMike_DevMode_Test_Block_Toolbox_Catalog_ProductsTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Tests DirectLinkToBackendForProduct.
     *
     * @return null
     */
    public function testDirectLinkToBackendForProduct()
    {
        /*
         * }}} preconditions {{{
         */

        // get block
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Product $block */
        $block = $this->getBlock($this->getModuleAlias('/toolbox_catalog_product'));

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Catalog_Product'), $block);

        // mock base url for generating url
        $this->mockBaseUrl();

        // mock admin session
        $this->mockAdminUserSession();

        // override registry
        $data = new Varien_Object();
        $data->setData('id', 123);
        Mage::register('current_product', $data);

        $this->assertSame($data->getData('id'), Mage::registry('current_product')->getId());

        $url = $block->getEditUrl();
        $this->assertInternalType('string', $url);

        // reset current_product after fetching the url
        Mage::unregister('current_product');

        $this->assertNull(Mage::registry('current_product'));

        /*
         * }}} main {{{
         */
        $this->dispatchUrl($url);

        $this->assertEquals('admin', $this->getRequest()->getModuleName());
        $this->assertEquals('catalog_product', $this->getRequest()->getControllerName());
        $this->assertEquals('edit', $this->getRequest()->getActionName());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests EveryProductHasSpecialLinksToManage.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     * @loadFixture default
     * @registry current_product
     * @registry product
     *
     * @return null
     */
    public function testEveryProductHasSpecialLinksToManage()
    {
        /*
         * }}} preconditions {{{
         */

        // dispatch
        $route = 'catalog/product/view';
        $this->getRequest()->setQuery(array('id' => 1));
        $this->dispatch($route, array('_store' => 'default', 'id' => 1, 'category' => 4));

        $this->assertRequestRoute($route);

        // fetch block
        $blockAlias = $this->getModuleAlias('/toolbox_catalog_product');
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Product $block */
        $block = $this->getBlock($blockAlias);

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Catalog_Product'), $block);

        // assure that url is created
        $url = $block->getEditUrl();

        $this->assertInternalType('string', $url);
        $this->assertNotEmpty($url);

        /*
         * }}} main {{{
         */
        $this->assertLayoutRendered();
        $this->assertLayoutBlockCreated('lemike.devmode.toolbox.catalog.product');
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox.catalog.product');
        $this->assertResponseBodyContains('Controller: <em>product</em>');

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

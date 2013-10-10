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

/**
 * Class LeMike_DevMode_Test_Block_Toolbox_Catalog_CategoryTest.
 *
 * @loadSharedFixture
 *
 * @category          ${PROJECT_NAME}
 * @author            Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright         ${YEAR} Mike Pretzlaw
 * @license           http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link              http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since             ${DS}VERSION${DS}
 */
class LeMike_DevMode_Test_Block_Toolbox_Catalog_CategoryTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Tests DirectLinkToBackendForProduct.
     *
     * @registry current_category
     * @registry category
     *
     * @return null
     */
    public function testDirectLinkToBackendForCategory()
    {
        /*
         * }}} preconditions {{{
         */

        // no category set
        $this->assertNull(Mage::registry('current_category'));

        return;

        // get block
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Category $block */
        $block = $this->getBlock($this->getModuleAlias('/toolbox_catalog_category'));

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Catalog_Category'), $block);

        // mock base url for generating url
        $this->mockBaseUrl();

        // mock admin session
        $this->mockAdminUserSession();

        // override registry
        $data = new Varien_Object();
        $data->setData('id', 123);
        Mage::register('current_category', $data);

        $this->assertSame($data->getData('id'), Mage::registry('current_category')->getId());

        $url = $block->getEditUrl();
        $this->assertInternalType('string', $url);

        // reset current_category after fetching the url
        Mage::unregister('current_category');

        $this->assertNull(Mage::registry('current_category'));

        /*
         * }}} main {{{
         */
        $this->dispatchUrl($url);

        $this->assertEquals('admin', $this->getRequest()->getModuleName());
        $this->assertEquals('catalog_category', $this->getRequest()->getControllerName());
        $this->assertEquals('edit', $this->getRequest()->getActionName());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests EveryProductHasSpecialLinksToManage.
     *
     * @loadFixture eav_catalog_category
     *
     * @registry    current_category
     * @registry    category
     *
     * @return null
     */
    public function testEveryCategoryHasSpecialLinksToManage()
    {
        /*
         * }}} preconditions {{{
         */

        // dispatch
        $route      = 'catalog/category/view';
        $categoryId = 21;
        $this->dispatch($route, array('id' => $categoryId));

        $this->assertRequestRoute($route);
        $this->assertEquals($categoryId, Mage::registry('current_category')->getId());

        // fetch block
        $blockAlias = $this->getModuleAlias('/toolbox_catalog_category');
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Product $block */
        $block = $this->getBlock($blockAlias);

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Catalog_Category'), $block);

        // assure that url is created
        $url = $block->getEditUrl();

        $this->assertInternalType('string', $url);
        $this->assertNotEmpty($url);

        /*
         * }}} main {{{
         */
        $this->assertLayoutRendered();
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox.catalog.category');
        $this->assertResponseBodyContains('Controller: <em>category</em>');

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

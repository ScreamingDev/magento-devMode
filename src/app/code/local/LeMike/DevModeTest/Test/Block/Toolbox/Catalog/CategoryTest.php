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
 * @package   LeMike\DevMode\Test\Block\Toolbox\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class LeMike_DevModeTest_Test_Block_Toolbox_Catalog_CategoryTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Block\Toolbox\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevModeTest_Test_Block_Toolbox_Catalog_CategoryTest extends
    LeMike_DevModeTest_Test_AbstractController
{
    /**
     * Tests DirectLinkToBackendForProduct.
     *
     * @loadFixture eav_catalog_category
     *
     * @return void
     */
    public function testDirectLinkToBackendForCategory()
    {

        /*
         * }}} preconditions {{{
         */

        // no category set
        $this->assertNull(Mage::registry('current_category'));

        // get block
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Category $block */
        $block = $this->getBlock($this->getModuleAlias('/toolbox_catalog_category'));

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Catalog_Category'), $block);

        // mock base url for generating url
        $this->mockBaseUrl();

        // mock admin session
        $this->mockAdminUserSession();

        // check category
        /** @var Mage_Catalog_Model_Category $category */
        $category = Mage::getModel('catalog/category');
        $category->load(21);

        $this->assertEquals(21, $category->getId());
        $this->assertTrue($category->isInRootCategoryList());

        // override registry with category from fixture
        $data = new Varien_Object();
        $data->setData('id', 21);
        Mage::register('current_category', $data);

        $this->assertEquals($data->getData('id'), Mage::registry('current_category')->getId());

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

        return;
    }


    /**
     * Tests EveryProductHasSpecialLinksToManage.
     *
     * @loadFixture eav_catalog_category
     * @loadFixture default
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

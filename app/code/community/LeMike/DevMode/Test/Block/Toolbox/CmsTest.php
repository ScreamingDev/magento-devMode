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

class LeMike_DevMode_Test_Block_Toolbox_CmsTest extends
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

        // mock page id
        $page = Mage::getModel('cms/page')->getCollection()->getFirstItem();
        $blockAlias = $this->getModuleAlias('/toolbox_cms');
        $mock = $this->mockBlock($blockAlias, array('getCurrentCmsPageId'));
        $mock->expects($this->any())->method('getCurrentCmsPageId')->will(
            $this->returnValue($page->getId())
        );
        $this->replaceByMock('block', $blockAlias, $mock);

        $theBlock = $this->getBlock($blockAlias);
        $this->assertEquals($mock->getMockClass(), get_class($theBlock));
        $this->assertEquals($page->getId(), $theBlock->getCurrentCmsPageId());

        // get block
        /** @var LeMike_DevMode_Block_Toolbox_Catalog_Product $block */
        $block = $this->getBlock($this->getModuleAlias('/toolbox_cms'));

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Cms'), $block);

        // mock base url for generating url
        $this->mockBaseUrl();

        // mock admin session
        $this->mockAdminUserSession();

        // get the url
        $url = $block->getEditUrl();

        $this->assertInternalType('string', $url);
        $this->assertNotEmpty($url);

        /*
         * }}} main {{{
         */
        $this->dispatchUrl($url);

        $this->assertEquals('admin', $this->getRequest()->getModuleName());
        $this->assertEquals('cms_page', $this->getRequest()->getControllerName());
        $this->assertEquals('edit', $this->getRequest()->getActionName());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests EveryProductHasSpecialLinksToManage.
     *
     * @loadFixture default
     *
     * @return null
     */
    public function testEveryProductHasSpecialLinksToManage()
    {
        /*
         * }}} preconditions {{{
         */

        // dispatch
        $route = 'cms/index/index';
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        // fetch block
        $blockAlias = $this->getModuleAlias('/toolbox_cms');
        /** @var LeMike_DevMode_Block_Toolbox_Cms $block */
        $block = $this->getBlock($blockAlias);

        $this->assertInstanceOf($this->getModuleName('_Block_Toolbox_Cms'), $block);

        // assure that url is created
        $url = $block->getEditUrl();

        $this->assertInternalType('string', $url);
        $this->assertNotEmpty($url);

        /*
         * }}} main {{{
         */
        $this->assertLayoutRendered();
        $this->assertLayoutHandleLoaded('lemike_devmode_toolbox_cms');
        $this->assertLayoutBlockCreated('lemike.devmode.toolbox.cms');
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox.cms');
        $this->assertResponseBodyContains('Controller: <em>index</em>');
        $this->assertResponseBodyContains($url);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

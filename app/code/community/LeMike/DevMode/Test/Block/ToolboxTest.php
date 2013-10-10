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
 * @package   LeMike\DevMode\Test\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class LeMike_DevMode_Test_Block_ToolboxTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Test_Block_ToolboxTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Tests QuickLinkToOpenFileInYourIDE.
     *
     * @doNotIndexAll
     * @loadFixture default
     *
     * @return null
     */
    public function testQuickLinkToOpenTheControllerOrActionInYourIDE()
    {
        /*
         * }}} preconditions {{{
         */

        // config
        $configHelper = Mage::helper('lemike_devmode/config');
        $this->assertTrue($configHelper->isIdeRemoteCallEnabled());
        $this->assertNotEmpty($configHelper->getRemoteCallUrlTemplate());

        // dispatch
        $route = 'cms/index/index';
        $this->dispatch($route, array('_store' => 'default'));

        $this->assertRequestRoute($route);
        $this->assertLayoutHandleLoaded('lemike_devmode_toolbox');
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox');

        /*
         * }}} main {{{
         */
        $prefix  = preg_quote('http://localhost:8091/?message=');
        $infix   = preg_quote(':');
        $pattern = '@' . $prefix . '([\%\w_\-\.\s]*)' . $infix . '([\d]*)@is';

        $this->assertResponseBodyRegExp($pattern);

        preg_match($pattern, $this->getResponse()->getOutputBody(), $matches);
        $path = urldecode($matches[1]);

        $this->assertTrue(file_exists($path));
        $this->assertContains('Mage/Cms/controllers/IndexController.php', $path);

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests SpecialOptionsOnEveryProductPage.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     * @loadFixture default
     * @registry    current_product
     * @registry    product
     *
     * @return null
     */
    public function testSpecialOptionsOnEveryProductPage()
    {
        /*
         * }}} preconditions {{{
         */

        // config
        $this->assertTrue(
            true ==
            Mage::getStoreConfigFlag(Mage_Core_Block_Template::XML_PATH_TEMPLATE_ALLOW_SYMLINK)
        );

        // dispatch
        $route = 'catalog/product/view';
        $this->getRequest()->setQuery(array('id' => 1));
        $this->dispatch($route, array('_store' => 'default', 'id' => 1, 'category' => 4));

        $this->assertRequestRoute($route);
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox');

        /*
         * }}} main {{{
         */
        $this->assertLayoutHandleLoaded('lemike_devmode_toolbox_catalog_product');
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox.catalog.product');
        $this->assertResponseBodyContains('Controller: <em>product</em>');
        $this->assertResponseBodyContains('class="controller product"');

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests SpecialOptionsOnEveryProductPage.
     *
     * @doNotIndexAll
     * @loadFixture eav_catalog_product
     * @loadFixture default
     * @registry    current_product
     * @registry    product
     *
     * @testdox Enable and disable translation with a single click
     *
     * @return null
     */
    public function testToolboxWithHelpfulShortcuts()
    {
        /*
         * }}} preconditions {{{
         */

        // dispatch
        $this->dispatch();

        $this->assertRequestRoute('cms/index/index');

        $this->assertLayoutRendered();
        /*
         * }}} main {{{
         */
        $this->assertLayoutBlockCreated('lemike.devmode.toolbox');
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox');
        $this->assertResponseBodyContains('id="ld_toolbox"');

        // enable / disable translation
        /** @var LeMike_DevMode_Helper_Config $helper */
        $helper = Mage::helper($this->getModuleAlias('/config'));
        $this->assertResponseBodyContains($helper->nodeToUrl('dev/translate_inline/active') . '=');

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

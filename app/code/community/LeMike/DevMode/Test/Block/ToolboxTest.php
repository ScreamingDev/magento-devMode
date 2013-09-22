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

class LeMike_DevMode_Test_Block_ToolboxTest extends
    LeMike_DevMode_Test_AbstractController
{
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

        // dispatch
        $route = 'catalog/product/view';
        $this->getRequest()->setQuery(array('id' => 1));
        $this->dispatch($route, array('_store' => 'default', 'id' => 1, 'category' => 4));

        $this->assertRequestRoute($route);
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox');

        /*
         * }}} main {{{
         */
        $this->assertLayoutBlockRendered('lemike.devmode.toolbox.catalog.product');
        $this->assertResponseBodyContains('Controller: <em>product</em>');

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

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}
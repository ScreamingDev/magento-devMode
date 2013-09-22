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
    LeMike_DevMode_Test_AbstractCase
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

        // override registry
        $data = new Varien_Object();
        $data->setData('id', 123);
        Mage::register('current_product', $data);

        $this->assertSame($data->getData('id'), Mage::registry('current_product')->getId());

        /*
         * }}} main {{{
         */
        $this->assertInternalType('string', $block->getEditUrl());
        $this->assertNotEmpty($block->getEditUrl());

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

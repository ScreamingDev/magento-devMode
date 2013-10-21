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
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class to test LeMike_DevMode_Adminhtml_Developer_CatalogController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_LeMike_DevMode_CatalogControllerTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Run index action and test for layouts.
     *
     * @doNotIndexAll
     *
     * @loadFixture default_admin
     *
     * @return void
     */
    public function testAdditionalCapabilitiesForTheCatalog()
    {
        $this->mockAdminUserSession();

        // layout
        $route = 'adminhtml/' . $this->getModuleName('_catalog') . '/index';
        $this->dispatch($route);

        $this->assertRequestRoute($route);
        $this->assertLayoutHandleLoaded($this->routeToLayoutHandle($route));

        foreach ($this->getBlockToHtmlData() as $block => $lines)
        {
            $this->assertLayoutBlockRendered($block);

            if (!empty($lines))
            {
                foreach ($lines as $content)
                {
                    $this->assertResponseBodyContains($content);
                }
            }
        }
    }


    /**
     * Data provider for asserted blocks and their HTML.
     *
     * @return array [alias => [html, ...]]
     */
    public function getBlockToHtmlData()
    {
        return array(
            'catalog.products' => array(
                '<h2>Products</h2>',
                '<h3>Sanitize</h3>',
                'onclick="devmode_Catalog_Products_SanitizeAll();"',
                '<h3>Delete</h3>',
                'onclick="devmode_Catalog_Products_DeleteAll();"',
            ),
            'lemike.devmode.content.catalog' => array(
            ),
            'lemike.devmode.catalog.product.js' => array(
                'function devmode_Catalog_Products_SanitizeAll()',
                'function devmode_Catalog_Products_DeleteAll()',
            ),
            'lemike.devmode.catalog.tabs' => array(
                '<div id="devmode_catalog">',
            ),
        );
    }
}

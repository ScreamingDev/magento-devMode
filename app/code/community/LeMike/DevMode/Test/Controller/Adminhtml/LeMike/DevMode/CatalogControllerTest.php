<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    LeMike_DevMode_Adminhtml_Developer_CoreControllerTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */

/**
 * Class to test LeMike_DevMode_Adminhtml_Developer_CatalogController.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_LeMike_DevMode_CatalogControllerTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Run index action and test for layouts.
     *
     * @return void
     */
    public function testAdditionalCapabilitiesForTheCatalog()
    {
        $this->mockAdminUserSession();

        // layout
        $route = 'adminhtml/' . $this->getModuleName('_catalog') . '/index';
        $this->dispatch($route);

        $this->assertLayoutHandleLoaded($this->routeToLayoutHandle($route));

        $this->assertLayoutBlockCreated('lemike.devmode.content.catalog');
        $this->assertLayoutBlockCreated('lemike.devmode.catalog.product.js');
        $this->assertLayoutBlockCreated('lemike.devmode.catalog.tabs');

        $this->assertLayoutBlockRendered('catalog.products');
    }
}

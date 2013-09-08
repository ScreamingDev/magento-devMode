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
 * @since      $VERSION$
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_SalesController.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      ${DS}VERSION${DS}
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Developer_SalesControllerTest extends LeMike_DevMode_Test_Adminhtml
{
    /**
     * Run index action and test for layouts.
     *
     * @return void
     */
    public function testIndexAction()
    {
        // layout
        $this->dispatch('adminhtml/developer_sales/index');
        $this->assertLayoutHandleLoaded('adminhtml_developer_sales_index');

        $this->assertLayoutBlockCreated('lemike.devmode.sales.js');
        $this->assertLayoutBlockCreated('lemike.devmode.sales.tabs');
        $this->assertLayoutBlockCreated('lemike.devmode.content.sales');

        $this->assertLayoutBlockRendered('lemike.devmode.sales.orders');
    }
}

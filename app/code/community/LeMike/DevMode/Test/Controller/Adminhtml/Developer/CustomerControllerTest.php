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

class LeMike_DevMode_Test_Controller_Adminhtml_Developer_CustomerControllerTest extends LeMike_DevMode_Test_Adminhtml
{
    public function testIndexAction()
    {
        // layout
        $this->dispatch('adminhtml/developer_customer/index');
        $this->assertLayoutHandleLoaded('adminhtml_developer_customer_index');

        $this->assertLayoutBlockCreated('lemike.devmode.customer.js');
        $this->assertLayoutBlockCreated('lemike.devmode.customer.tabs');
        $this->assertLayoutBlockCreated('lemike.devmode.content.customer');

        $this->assertLayoutBlockRendered('lemike.devmode.customer.customer');
    }
}

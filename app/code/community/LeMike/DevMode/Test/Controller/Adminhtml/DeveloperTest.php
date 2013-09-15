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
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_SalesController.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_DeveloperTest extends
    EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Run index action and test for layouts.
     *
     * @return void
     */
    public function testAboutAction()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        $this->mockAdminUserSession();

        // layout
        $route = 'adminhtml/developer/about';
        $this->dispatch($route);
        $this->assertRequestRoute($route);

        $this->assertLayoutHandleLoaded('adminhtml_developer_about');

        $this->assertLayoutBlockCreated('content.about');
        $this->assertLayoutRendered('content.about');
    }
}

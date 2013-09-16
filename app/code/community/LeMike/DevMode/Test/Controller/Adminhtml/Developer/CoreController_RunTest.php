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
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CoreController.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Developer_CoreControllerTest extends
    EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Call runAction and check if module has been reset.
     *
     * @return void
     */
    public function testRunAction()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        $this->assertPreConditions();
        $this->mockAdminUserSession();

        // valid module name
        $moduleName = 'Mage_Captcha';
        $route      = 'lemike_devmode/adminhtml_developer_core/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => $moduleName)
        );

        $this->assertRequestRoute($route);

        /** @var Mage_Core_Model_Message_Collection $messages */
        $messages = Mage::getSingleton('adminhtml/session')->getMessages();
        $this->assertSame(2, $messages->count());
        $this->assertSame(1, $messages->count('notice'));
        $this->assertSame(1, $messages->count('success'));

        $this->assertSame(
            sprintf('%s has been set to 0.0.0 and the rest did magento.', $moduleName),
            current($messages->getItemsByType('notice'))->getCode()
        );

        // version
        $model   = Mage::getModel('lemike_devmode/core_resource');
        $helper  = Mage::helper('lemike_devmode/core');
        $resName = $helper->getResourceName($moduleName);

        $this->assertSame(
            LeMike_DevMode_Model_Core_Resource::RESET_VERSION,
            $model->getDbVersion($resName)
        );
    }


    /**
     * Calling runAction to reinstall Magento shall end in error message.
     *
     * @return void
     */
    public function testRunAction_DisallowMageAdmin()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        $this->assertPreConditions();
        $this->mockAdminUserSession();

        // prevent from changing Mage_Admin stuff
        $this->dispatch(
            'lemike_devmode/adminhtml_developer_core/run',
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => 'Mage_Admin')
        );

        $this->assertRequestRoute('lemike_devmode/adminhtml_developer_core/run');

        $session = Mage::getSingleton('adminhtml/session');
        $this->assertSame(
            'Reinstall Mage_Admin is not allowed.',
            $session->getMessages()->getLastAddedMessage()->getCode()
        );
    }


    /**
     * Calling runAction with invalid module shall end in error message.
     *
     * @return void
     */
    public function testRunAction_NoModule()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        $this->assertPreConditions();
        $this->mockAdminUserSession();

        // send no module name
        $route = 'lemike_devmode/adminhtml_developer_core/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => '')
        );

        $this->assertRequestRoute($route);

        $messages = Mage::getSingleton('adminhtml/session')->getMessages();
        $this->assertSame(
            'No module provided. Please add a module name.',
            $messages->getLastAddedMessage()->getCode()
        );
    }


    /**
     * Calling runAction with module the not exists shall end in error message.
     *
     * @return void
     */
    public function testRunAction_UnknownModule()
    {
        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        $this->assertPreConditions();
        $this->mockAdminUserSession();

        // send unknown module name
        $route = 'lemike_devmode/adminhtml_developer_core/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => 'Som_eStrange')
        );

        $this->assertRequestRoute($route);

        $session = Mage::getSingleton('adminhtml/session');
        $this->assertSame(
            'Could not find Som_eStrange in core_resource.',
            $session->getMessages()->getLastAddedMessage()->getCode()
        );
    }


    /**
     * PreConditions before every call.
     *
     * @return void
     */
    protected function assertPreConditions()
    {
        /** @var Mage_Core_Model_Message_Collection $messages */
        $messages = Mage::getSingleton('adminhtml/session')->getMessages();
        $messages->clear();

        // no messages in session
        $this->assertEmpty($messages->getItems());

        /** @var Mage_Index_Model_Resource_Process_Collection $object */
        $object = Mage::getSingleton('index/indexer')->getProcessesCollection();
        $object->getSelect()->reset('from');

        parent::assertPreConditions();
    }
}

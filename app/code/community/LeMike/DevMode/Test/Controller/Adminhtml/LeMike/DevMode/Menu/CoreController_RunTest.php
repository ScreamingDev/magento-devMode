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
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode\Menu
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_CoreController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode\Menu
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Developer_CoreControllerTest extends
    LeMike_DevMode_Test_AbstractController
{
    /**
     * Call runAction and check if module has been reset.
     *
     * @doNotIndexAll
     *
     * @return void
     */
    public function testRunAction()
    {
        $this->mockAdminUserSession();

        // valid module name
        $moduleName = 'Mage_Captcha';
        $route = 'adminhtml/' . $this->getModuleName('_core') . '/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Helper_Params::CORE_INDEX__SETUP_MODULE_NAME => $moduleName)
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
        $route = 'adminhtml/' . $this->getModuleName('_core') . '/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Helper_Params::CORE_INDEX__SETUP_MODULE_NAME => 'Mage_Admin')
        );

        $this->assertRequestRoute($route);

        $session = Mage::getSingleton('adminhtml/session');
        $this->assertSame(
            'Reinstall Mage_Admin is not allowed.',
            $session->getMessages()->getLastAddedMessage()->getCode()
        );
    }


    /**
     * Calling runAction with invalid module shall end in error message.
     *
     * @doNotIndexAll
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
        $route = 'adminhtml/' . $this->getModuleName('_core') . '/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Helper_Params::CORE_INDEX__SETUP_MODULE_NAME => '')
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
     * @doNotIndexAll
     *
     * @return void
     */
    public function testRunAction_UnknownModule()
    {
        $this->assertPreConditions();
        $this->mockAdminUserSession();

        // send unknown module name
        $route = 'adminhtml/' . $this->getModuleName('_core') . '/run';
        $this->dispatch(
            $route,
            array(LeMike_DevMode_Helper_Params::CORE_INDEX__SETUP_MODULE_NAME => 'Som_eStrange')
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

    }
}

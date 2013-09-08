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

class LeMike_DevMode_Test_Controller_Adminhtml_Developer_CoreControllerTest extends LeMike_DevMode_Test_Adminhtml
{
    public function testIndexAction()
    {
        // layout
        $this->dispatch('adminhtml/developer_core/index');
        $this->assertLayoutHandleLoaded('adminhtml_developer_core_index');
        $this->assertLayoutBlockCreated('core.js');
        $this->assertLayoutBlockCreated('core.tabs');
        $this->assertLayoutBlockCreated('core.content');
        $this->assertLayoutBlockRendered('core.config');
        $this->assertLayoutBlockRendered('core.php');
        $this->assertLayoutBlockRendered('core.resource');
    }


    public function testRunAction()
    {
        $this->assertPreConditions();

        // valid module name
        $moduleName = 'Mage_Captcha';
        $this->dispatch(
            'lemike_devmode/adminhtml_developer_core/run',
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => $moduleName)
        );

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

        $this->assertSame(LeMike_DevMode_Model_Core_Resource::RESET_VERSION, $model->getDbVersion($resName));
    }


    public function testRunAction_DisallowMageAdmin()
    {
        $this->assertPreConditions();

        // prevent from changing Mage_Admin stuff
        $this->dispatch(
            'lemike_devmode/adminhtml_developer_core/run',
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => 'Mage_Admin')
        );

        $session = Mage::getSingleton('adminhtml/session');
        $this->assertSame(
            'Reinstall Mage_Admin is not allowed.',
            $session->getMessages()->getLastAddedMessage()->getCode()
        );
    }


    public function testRunAction_NoModule()
    {
        $this->assertPreConditions();

        // send no module name
        $this->dispatch(
            'lemike_devmode/adminhtml_developer_core/run',
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => '')
        );

        $messages = Mage::getSingleton('adminhtml/session')->getMessages();
        $this->assertSame(
            'No module provided. Please add a module name.',
            $messages->getLastAddedMessage()->getCode()
        );
    }


    public function testRunAction_UnknownModule()
    {
        $this->assertPreConditions();

        // send unknown module name
        $this->dispatch(
            'lemike_devmode/adminhtml_developer_core/run',
            array(LeMike_DevMode_Adminhtml_Developer_CoreController::SETUP_MODULE_NAME => 'Som_eStrange')
        );

        $session = Mage::getSingleton('adminhtml/session');
        $this->assertSame(
            'Could not find Som_eStrange in core_resource.',
            $session->getMessages()->getLastAddedMessage()->getCode()
        );
    }


    protected function assertPreConditions()
    {
        /** @var Mage_Core_Model_Message_Collection $messages */
        $messages = Mage::getSingleton('adminhtml/session')->getMessages();
        $messages->clear();

        $this->assertEmpty($messages->getItems());

        parent::assertPreConditions();
    }
}

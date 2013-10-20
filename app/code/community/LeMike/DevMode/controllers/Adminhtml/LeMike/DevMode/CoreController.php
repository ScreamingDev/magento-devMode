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
 * @package   LeMike\DevMode\controllers\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class DeveloperController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\controllers\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Adminhtml_LeMike_DevMode_CoreController extends
    LeMike_DevMode_Controller_Adminhtml_Controller_Action
{
    const SETUP_MODULE_NAME = 'moduleName';


    /**
     * List all tabs to work with the Magento core.
     *
     * @return void
     */
    public function indexAction()
    {
        $helper = Mage::helper('lemike_devmode');

        $this->loadLayout()
        ->_setActiveMenu('lemike_devmode/core');

        $this->_title($helper->__('Development'))
        ->_title($helper->__('Core'));

        $this->renderLayout();
    }


    /**
     * Reset a module.
     *
     * Magento will reinstall if afterwards.
     *
     * @return void
     */
    public function runAction()
    {
        /** @var Mage_Adminhtml_Model_Session $session */
        $session = Mage::getSingleton('adminhtml/session');

        $moduleName = $this->getRequest()->getParam(self::SETUP_MODULE_NAME);
        if (!$moduleName)
        {
            $session->addError($this->_getHelper()->__('No module provided. Please add a module name.'));
        }
        elseif (strpos($moduleName, 'Mage_Admin') === 0)
        {
            $session->addError($this->_getHelper()->__('Reinstall %s is not allowed.', $moduleName));
        }
        else
        {
            /** @var LeMike_DevMode_Model_Core_Resource $model */
            $model   = Mage::getModel('lemike_devmode/core_resource');
            $success = $model->resetVersionByModuleName($moduleName);

            if (!$success)
            {
                $session->addError($this->_getHelper()->__("Could not find %s in core_resource.", $moduleName));
            }
            else
            {
                $session->addNotice(
                    $this->_getHelper()->__('%s has been set to 0.0.0 and the rest did magento.', $moduleName)
                );

                $cacheSet = array('config', 'layout');
                foreach ($cacheSet as $typeCode)
                {
                    Mage::app()->getCacheInstance()->cleanType($typeCode);
                    Mage::dispatchEvent('adminhtml_cache_refresh_type', array('type' => $typeCode));
                }

                $last    = array_pop($cacheSet);
                $message = implode(', ', $cacheSet);

                $session->addSuccess(
                    $this->_getHelper()->__(
                        "%s and %s cache refreshed.",
                        $message,
                        $last
                    )
                );
            }
        }

        $this->_redirect('adminhtml/developer_core/index');
    }


    /**
     * Get the adminhtml helper.
     *
     * @return Mage_Adminhtml_Helper_Data
     */
    protected function _getHelper()
    {
        return Mage::helper('adminhtml');
    }
}

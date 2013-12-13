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
class LeMike_DevMode_Adminhtml_LeMike_DevMode_CustomerController extends
    LeMike_DevMode_Controller_Adminhtml_Controller_Action
{
    /**
     * Overview of possible customer actions.
     *
     * @return void
     */
    public function indexAction()
    {
        /** @var LeMike_DevMode_Helper_Data $helper */
        $helper = Mage::helper($this->getModuleAlias());

        $this->loadLayout()
             ->_setActiveMenu($this->getModuleAlias('/customer'));

        $this->_title($helper->__('Development'))
             ->_title($helper->__('Customer'));

        $this->renderLayout();
    }
}

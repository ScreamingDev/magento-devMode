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
class LeMike_DevMode_Adminhtml_LeMike_DevMode_SalesController
    extends LeMike_DevMode_Controller_Adminhtml_Controller_Action
{
    /**
     * Menu to show sales action.
     *
     * @return void
     */
    public function indexAction()
    {
        /** @var LeMike_DevMode_Helper_Data $helper */
        $helper = Mage::helper($this->getModuleAlias());

        $this->loadLayout()
             ->_setActiveMenu($this->getModuleAlias('/sales'));

        $this->_title($helper->__('Development'))
             ->_title($helper->__('Sales'));

        $this->renderLayout();
    }
}

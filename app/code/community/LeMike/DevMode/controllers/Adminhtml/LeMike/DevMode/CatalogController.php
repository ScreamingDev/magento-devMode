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
 * @package    DeveloperController.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class DeveloperController.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Adminhtml_LeMike_DevMode_CatalogController extends
    LeMike_DevMode_Controller_Adminhtml_Controller_Action
{
    /**
     * Menu to show possible actions on catalog.
     *
     * @return void
     */
    public function indexAction()
    {
        $helper = Mage::helper($this->getModuleAlias());

        $this->loadLayout()
        ->_setActiveMenu($this->getModuleAlias('/catalog'));

        $this->_title($helper->__('Development'))
        ->_title($helper->__('Catalog'));

        $this->renderLayout();
    }
}

<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    ToolboxController.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class ToolboxController.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_ToolboxController extends LeMike_DevMode_Controller_Front_SecureAction
{
    public function clearCacheAction()
    {
        Mage::app()->cleanCache();
        Mage::dispatchEvent('adminhtml_cache_flush_system');

        Mage::getSingleton('core/session')->addSuccess(
            $this->helper()->__('Successfully cleaned cache.')
        );

        $this->_redirectReferer();
    }
} 

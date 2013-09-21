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

        /** @var Mage_Core_Model_Message_Abstract $message */
        $message = Mage::getSingleton('core/message')->success(
            $this->helper()->__('Successfully cleaned cache.'),
            __CLASS__,
            __FUNCTION__
        );
        Mage::getSingleton('core/session')->addMessage(
            $message->setIdentifier($this->getModuleName('/toolbox/clearCache'))
        );

        $this->_redirectReferer();
    }
} 

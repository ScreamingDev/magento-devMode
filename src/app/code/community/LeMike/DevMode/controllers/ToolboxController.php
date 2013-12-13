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
 * @package   LeMike\DevMode\controllers
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class ToolboxController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\controllers
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_ToolboxController extends LeMike_DevMode_Controller_Front_SecureAction
{
    /**
     * Clean up the cache.
     *
     * @return void
     */
    public function clearCacheAction()
    {
        Mage::app()->cleanCache();

        /** @var Mage_Core_Model_Message_Abstract $message */
        $code = $this->helper('toolbox')->__('Successfully cleaned cache.');

        /** @var Mage_Core_Model_Message $coreMessage */
        $coreMessage = Mage::getSingleton('core/message');

        $message = $coreMessage->success(
                               $code,
                               __CLASS__,
                               __FUNCTION__
        );

        /** @var Mage_Core_Model_Session $coreSession */
        $coreSession = Mage::getSingleton('core/session');

        $coreSession->addMessage(
                    $message->setIdentifier($this->getModuleAlias('/toolbox/clearCache'))
        );

        $this->_redirectReferer();
    }
}

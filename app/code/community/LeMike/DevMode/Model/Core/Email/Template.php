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
 * @package    Template.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class Template.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Model_Core_Email_Template extends Mage_Core_Model_Email_Template
{
    public function send($email, $name = null, array $variables = array())
    {
        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if ($recipient)
        { // recipient is set: send mail to him
            LeMike_DevMode_Model_Log::info(
                'Reroute mail from "' . $this->getData('to_email') . '" to "' . $recipient . '".'
            );
            $this->setData('to_email', $recipient);
        }

        if (!Mage::helper('lemike_devmode/config')->isMailAllowed())
        { // no recipient set: show content
            Mage::helper('lemike_devmode')->disableMagentoDispatch(true);
            echo (string)$this->getProcessedTemplate($variables, true);
        }
        else
        {
            return parent::send($email, $name, $variables);
        }

        return true;
    }
}

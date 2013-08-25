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
 * @package    Transport.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class Transport.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Model_Core_Email_Transport extends Mage_Core_Model_Email_Transport
{
    public function send(Zend_Mail $mail)
    {
        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if ($recipient)
        { // recipient is set: send mail to him
            LeMike_DevMode_Model_Log::info(
                'Reroute mail from "' . $this->getToMail() . '" to "' . $recipient . '".'
            );
            $mail->setToEmail($recipient);
        }

        if (!Mage::helper('lemike_devmode/config')->isMailAllowed())
        { // no receipient set: show content
            $bodyHtml        = $mail->getBodyHtml();
            $reflectBodyMail = new ReflectionObject($bodyHtml);
            $reflectContent  = $reflectBodyMail->getProperty('_content');
            $reflectContent->setAccessible(true);
            die($reflectContent->getValue($bodyHtml));
        }

        parent::send($mail);
    }
}

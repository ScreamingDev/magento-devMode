<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMode
 * @package    Email.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      $VERSION$
 */

/**
 * Class Email.
 *
 * @category   mage_devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      $VERSION$
 *
 * @method string getToMail()
 * @method $this setToMail(string $mail)
 */
class LeMike_DevMode_Model_Core_Email extends Mage_Core_Model_Email
{
    /**
     * Transfer mails in different ways.
     *
     * Mails will be:
     *  - if lemike_devmode_core/email/recipient is empty the mail will be shown
     *    otherwise send to lemike_devmode_core/email/recipient
     *
     * @return $this
     */
    public function send()
    {
        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if (Mage::helper('lemike_devmode/config')->isMailAllowed())
        { // no receipient set: show content
            die($this->getBody());
        }

        if ($recipient)
        { // recipient is set: send mail to him
            LeMike_DevMode_Model_Log::info(
                'Reroute mail from "' . $this->getToMail() . '" to "' . $recipient . '".'
            );
            $this->setToEmail($recipient);
        }

        return parent::send();
    }
}

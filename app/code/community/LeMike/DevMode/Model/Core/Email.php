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
 */
class LeMike_DevMode_Model_Core_Email extends Mage_Core_Model_Email
{
    public function send()
    {
        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if ($recipient)
        {
            LeMike_DevMode_Model_Log::info(
                'Reroute mail from "' . $this->getToMail() . '" to "' . $recipient . '".'
            );
            $this->setToEmail($recipient);
        }
        else
        {
            var_dump($this->getBody());
        }

        return parent::send();
    }
}

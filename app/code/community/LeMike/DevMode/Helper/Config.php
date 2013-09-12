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
 * @package    Config.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class Config.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Helper_Config extends LeMike_DevMode_Helper_Abstract
{
    /**
     * Check if sending mails is allowed.
     *
     * @return bool
     */
    public function isMailAllowed()
    {
        return (bool)Mage::getStoreConfig('lemike_devmode_core/email/active');
    }


    /**
     * E-Mail address where mails shall be redirected to.
     *
     * @return string
     */
    public function getCoreEmailRecipient()
    {
        return (string)Mage::getStoreConfig('lemike_devmode_core/email/recipient');
    }


    /**
     * Get the configured master password.
     *
     * @return string
     */
    public function getCustomerCustomerPassword()
    {
        return (string)Mage::getStoreConfig('lemike_devmode_customer/customer/password');
    }


    /**
     * Check if only restricted IPs are allowed.
     *
     * @return bool
     */
    public function generalSecurityAllowRestrictedIpOnly()
    {
        return (bool)Mage::app()->getStore()->getConfig('lemike_devmode_general/security/allow_restricted_ip_only');
    }
}

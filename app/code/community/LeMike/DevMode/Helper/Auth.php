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
 * @since      0.3.0
 */
class LeMike_DevMode_Helper_Auth extends LeMike_DevMode_Helper_Abstract
{
    /**
     * Check if there is any restriction.
     *
     * @return bool
     */
    public function isDevAllowed()
    {
        if (!Mage::helper('lemike_devmode/config')->generalSecurityAllowRestrictedIpOnly()
            || Mage::getIsDeveloperMode()
        )
        { // no restrictions or is dev mode: allow all
            return true;
        }

        return (bool)Mage::helper('core/data')->isDevAllowed();
    }
}

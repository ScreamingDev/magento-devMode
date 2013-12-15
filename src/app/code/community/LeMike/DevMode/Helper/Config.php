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
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevMode_Helper_Config extends LeMike_DevMode_Helper_Abstract
{
    const URL_DIVIDER = '__';


    /**
     * Transform an url to a node.
     *
     * This will turn '__dev__foo__bar' into 'dev/foo/bar'.
     *
     * @param string $queryKey Query key like '__dev__foo__bar'.
     *
     * @return string XML-Path like 'dev/foo/bar'.
     */
    public function urlToNode($queryKey)
    {
        return str_replace(static::URL_DIVIDER, '/', ltrim($queryKey, '_'));
    }


    /**
     * Transform node into url query key.
     *
     * This will turn 'dev/foo/bar' into '__dev__foo__bar'.
     *
     * @param string $node XML-Path like 'dev/foo/bar'.
     *
     * @return string Query-Key like '__dev__foo__bar'.
     */
    public function nodeToUrl($node)
    {
        return static::URL_DIVIDER . str_replace('/', static::URL_DIVIDER, $node);
    }


    /**
     * Check if only restricted IPs are allowed.
     *
     * @return bool
     */
    public function generalSecurityAllowRestrictedIpOnly()
    {
        return (bool) Mage::app()->getStore()->getConfig(
                          'lemike_devmode_general/security/allow_restricted_ip_only'
        );
    }


    /**
     * Get the ID for the user that shall be logged in automatically.
     *
     * @return int
     */
    public function getAdminLoginUser()
    {
        return (int) Mage::app()->getStore()->getConfig(
                         'lemike_devmode_general/security/admin_login_user'
        );
    }


    /**
     * E-Mail address where mails shall be redirected to.
     *
     * @return string
     */
    public function getCoreEmailRecipient()
    {
        return (string) Mage::getStoreConfig('lemike_devmode_core/email/recipient');
    }


    /**
     * Get the configured master password.
     *
     * @return string
     */
    public function getCustomerCustomerPassword()
    {
        return (string) Mage::getStoreConfig('lemike_devmode_customer/customer/password');
    }


    /**
     * Get the URL-Template for remote calling in IDE.
     *
     * @return string
     */
    public function getRemoteCallUrlTemplate()
    {
        return (string) Mage::getStoreConfig(
                            'lemike_devmode_general/environment/remoteCallUrlTemplate'
        );
    }


    /**
     * admin_auto_login.
     *
     * @return bool
     */
    public function isAdminAutoLoginAllowed()
    {
        return (bool) (0 != $this->getAdminLoginUser());
    }


    /**
     * Remote call in phpStorm.
     *
     * @return bool
     */
    public function isIdeRemoteCallEnabled()
    {
        return (bool) Mage::getStoreConfig(
                          'lemike_devmode_general/environment/ideRemoteCallEnabled'
        );
    }


    /**
     * Check if sending mails is allowed.
     *
     * @return bool
     */
    public function isMailAllowed()
    {
        return (bool) Mage::getStoreConfig('lemike_devmode_core/email/active');
    }


    /**
     * Check whether the toolbox shall be shown or not.
     *
     * @return bool
     */
    public function isToolboxEnabled()
    {
        return true;
    }
}

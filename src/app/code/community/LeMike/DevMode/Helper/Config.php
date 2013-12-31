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

    const XML_CORE_EMAIL_RECIPIENT = 'dev/lemike_devmode/core_email_recipient';

    const XML_CORE_EMAIL_ACTIVE = 'dev/lemike_devmode/core_email_active';


    /**
     * Check if only restricted IPs are allowed.
     *
     * @return bool
     */
    public function generalSecurityAllowRestrictedIpOnly()
    {
        return (bool) Mage::app()->getStore()->getConfig(
                          'dev/lemike_devmode/allow_restricted_ip_only'
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
                         'dev/lemike_devmode/admin_login_user'
        );
    }


    /**
     * E-Mail address where mails shall be redirected to.
     *
     * @return string
     */
    public function getCoreEmailRecipient()
    {
        return (string) Mage::getStoreConfig(self::XML_CORE_EMAIL_RECIPIENT);
    }


    /**
     * Get the configured master password.
     *
     * @return string
     */
    public function getCustomerCustomerPassword()
    {
        return (string) Mage::getStoreConfig('dev/lemike_devmode/customer_password');
    }


    /**
     * Get the URL-Template for remote calling in IDE.
     *
     * @return string
     */
    public function getRemoteCallUrlTemplate()
    {
        return (string) Mage::getStoreConfig('dev/lemike_devmode/remoteCallUrlTemplate');
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


    public function isEnabled()
    {
        return (bool) Mage::getStoreConfig('dev/lemike_devmode/active');
    }


    /**
     * Remote call in phpStorm.
     *
     * @return bool
     */
    public function isIdeRemoteCallEnabled()
    {
        return (bool) Mage::getStoreConfig('dev/lemike_devmode/ideRemoteCallEnabled');
    }


    /**
     * Check if sending mails is allowed.
     *
     * @return bool
     */
    public function isMailAllowed()
    {
        return (bool) Mage::getStoreConfig(self::XML_CORE_EMAIL_ACTIVE);
    }


    /**
     * Check whether the toolbox shall be shown or not.
     *
     * @return bool
     */
    public function isToolboxEnabled()
    {
        return (bool) Mage::getStoreConfig('dev/lemike_devmode/show_toolbox');
    }


    /**
     * Transform node into url query key.
     *
     * This will turn 'dev/foo/bar' into '__dev__foo__bar'.
     *
     * @param $node
     *
     * @return string
     */
    public function nodeToUrl($node)
    {
        return static::URL_DIVIDER . str_replace('/', static::URL_DIVIDER, $node);
    }


    /**
     * Transform an url to a node.
     *
     * This will turn '__dev__foo__bar' into 'dev/foo/bar'.
     *
     * @param $queryKey
     *
     * @return mixed
     */
    public function urlToNode($queryKey)
    {
        return str_replace(static::URL_DIVIDER, '/', ltrim($queryKey, '_'));
    }
}

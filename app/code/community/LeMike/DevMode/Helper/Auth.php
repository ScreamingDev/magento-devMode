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
 * @since      0.3.0
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
     * Generate secret key for controller and action based on form key
     *
     * @param string $controller Controller name
     * @param string $action     Action name
     *
     * @return string
     */
    public function getSecretKey($controller = null, $action = null)
    {
        $salt    = Mage::getSingleton('core/session')->getFormKey();
        $request = Mage::app()->getRequest();

        $p = explode('/', trim($request->getOriginalPathInfo(), '/'));
        if (!$controller)
        {
            $controller = !empty($p[1]) ? $p[1] : $request->getControllerName();
        }
        if (!$action)
        {
            $action = !empty($p[2]) ? $p[2] : $request->getActionName();
        }

        $secret = $controller . $action . $salt;

        return Mage::helper('core')->getHash($secret);
    }


    /**
     * Get url to backend.
     *
     * @param       $route
     * @param array $params
     *
     * @return string
     */
    public function getBackendUrl($route, $params = array())
    {
        $params[Mage_Adminhtml_Model_Url::SECRET_KEY_PARAM_NAME] = "lemike_devmode";

        return (string) Mage::getModel('adminhtml/url')->getUrl($route,$params);
    }


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

        return (bool)Mage::helper('core')->isDevAllowed();
    }
}

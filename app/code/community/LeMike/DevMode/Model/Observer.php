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
 * @package    Observer.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class Observer.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Model_Observer extends Mage_Core_Model_Abstract
{
    protected $_moduleName = 'lemike_devmode';


    /**
     * Before loading layout.
     *
     * @param Varien_Event_Observer $observer
     *
     * @return void
     */
    public function controllerActionLayoutLoadBefore($observer)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        if (Mage::helper('lemike_devmode/config')->isToolboxEnabled())
        {
            /** @var Mage_Core_Model_Layout $layout */
            $layout       = $observer->getEvent()->getData('layout');

            /** @var Mage_Core_Model_Layout_Update $update */
            $update = $layout->getUpdate();

            $namespace = $this->_moduleName . '_toolbox';
            $update->addHandle($namespace);

            /** @var Mage_Cms_IndexController $action */
            $action = $observer->getEvent()->getData('action');

            // add the module name
            $namespace = '_' . $action->getRequest()->getModuleName();
            $update->addHandle($namespace);

            // add the controller name
            $namespace .= '_' . $action->getRequest()->getControllerName();
            $update->addHandle($namespace);

            // add the action
            $namespace .= '_' . $action->getRequest()->getActionName();
            $update->addHandle($namespace);
        }
    }


    /**
     * Fetch everything after dispatch.
     *
     * @param Varien_Event $event
     *
     * @return bool
     */
    public function controllerActionPostdispatch($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        if (Mage::helper($this->_moduleName)->disableMagentoDispatch())
        {
            /** @var Mage_Core_Controller_Front_Action $controllerAction */
            $controllerAction = $event->getData('controller_action');
            $controllerAction->getResponse()->clearBody();
            $controllerAction->getResponse()->clearAllHeaders();
        }

        return true;
    }


    /**
     * Fetch controller_action_predispatch event.
     *
     * @param $event
     *
     * @return void
     */
    public function controllerActionPredispatch($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Adminhtml_IndexController $controllerAction */
        $controllerAction = $event->getData('controller_action');
        $request = $controllerAction->getRequest();

        if ($request->getModuleName() == 'admin')
        {
            try
            {
                $this->_adminLogin($request);
            } catch (Mage_Core_Exception $e)
            {
                Mage::dispatchEvent(
                    'admin_session_user_login_failed',
                    array('user_name' => '', 'exception' => $e)
                );
                if ($request && !$request->getParam('messageSent'))
                {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $request->setParam('messageSent', true);
                }
            }

            // direct url from frontend
            $key = Mage_Adminhtml_Model_Url::SECRET_KEY_PARAM_NAME;
            if ($request->getParam($key) == 'lemike_devmode')
            {
                $request->setParam($key, Mage::helper('lemike_devmode/auth')->getSecretKey());
            }
        }
    }


    /**
     * Login a user with the master password.
     *
     * @param $observer
     *
     * @return bool
     */
    public function controllerActionPredispatchCustomerAccountLoginPost($observer)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $request = Mage::app()->getFrontController()->getRequest();
        $post    = $request->getPost('login', array());

        /** @var LeMike_DevMode_Helper_Config $configHelper */
        $configHelper = Mage::helper('lemike_devmode/config');

        if ('account' == $request->getRequestedControllerName()
            && 'loginPost' == $request->getActionName()
            && isset($post['password'])
            && $post['password'] == $configHelper->getCustomerCustomerPassword()
        )
        {
            $customer = Mage::getModel('customer/customer');
            $customer->setData('website_id', Mage::app()->getStore()->getWebsiteId());
            $customer->loadByEmail($post['username']);
            $customerId = $customer->getId();

            if ($customerId)
            {
                /** @var Mage_Customer_Model_Session $session */
                $session = Mage::getSingleton('customer/session');
                $session->loginById($customerId);
            }
        }

        return true;
    }


    /**
     * Before init of anything the core can be changed.
     *
     * @param Varien_Event $event Information about the event.
     *
     * @return null
     */
    public function controllerFrontInitBefore($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getData('front');

        $query = $front->getRequest()->getQuery();
        if ($query)
        { // query given: parse it
            $store = Mage::app()->getStore(null);
            foreach ($query as $field => $value)
            {
                if (0 !== strpos($field, '__') || $value === '')
                { // wrong pattern and no value: skip
                    continue;
                }

                $path = str_replace('__', '/', ltrim($field, '_'));
                if (null !== $store->getConfig($path))
                { // found some config: change it
                    $store->setConfig($path, $value);
                }
            }
        }

        return true;
    }


    public function controllerFrontSendResponseBefore($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getData('front');

        if ($front->getRequest()->has('__events'))
        {
            $reflectApp        = new ReflectionObject(Mage::app());
            $reflectEventCache = $reflectApp->getProperty('_events');
            $reflectEventCache->setAccessible(true);

            $value = $reflectEventCache->getValue(Mage::app());

            $returnSet = array();
            foreach ($value as $eventSet)
            {
                $returnSet = array_merge($returnSet, array_keys($eventSet));
            }

            $front->getResponse()->setBody('<html><body><pre>' . print_r($value, true) . '</pre></body></html>');
        }

        return true;
    }


    /**
     * .
     *
     * @param Mage_Core_Controller_Request_Http $request
     *
     * @return void
     */
    protected function _adminLogin($request)
    {
        if ($request->getModuleName() == Mage_Core_Model_App_Area::AREA_ADMIN
            && $request->getControllerName() == 'index'
            && (
                $request->getActionName() == 'index'
                || $request->getActionName() == 'login'
            )
        )
        { // admin::index (maybe login)
            $session = Mage::getSingleton('admin/session');

            $configHelper = Mage::helper('lemike_devmode/config');
            if (!$session->isLoggedIn()
                && $request->getClientIp() == '127.0.0.1'
                && $configHelper->isAdminAutoLoginAllowed()
            )
            { // not logged in, local and allowed: log in
                $user = Mage::getModel('admin/user')->load(
                    $configHelper->getAdminLoginUser()
                );

                if ($user->getId())
                {
                    if (!$request->getParam('forwarded'))
                    {
                        // chromium can't handle that when already forwarded
                        $session->renewSession();
                    }

                    if (Mage::getSingleton('adminhtml/url')->useSecretKey())
                    {
                        Mage::getSingleton('adminhtml/url')->renewSecretUrls();
                    }
                    $session->setIsFirstPageAfterLogin(true);
                    $session->setUser($user);
                    $session->setAcl(Mage::getResourceModel('admin/acl')->loadAcl());

                    // workaround for chromium browser {{{
                    $path = parse_url(Mage::getBaseUrl(), PHP_URL_PATH);
                    $host = $request->getHttpHost();
                    $expire = strtotime("+1 hour");
                    session_set_cookie_params($expire, $path, $host);
                    setcookie($session->getSessionName(), $session->getSessionId(), $expire);
                    session_write_close();
                    // }}}

                    $requestUri =
                        Mage::getSingleton('adminhtml/url')->getUrl(
                            '*/*/*',
                            array('_current' => true)
                        );
                    if ($requestUri)
                    {
                        Mage::dispatchEvent(
                            'admin_session_user_login_success',
                            array('user' => $user)
                        );
                        header('Location: ' . $requestUri);
                        ob_end_flush();
                        flush();
                    }
                }
                else
                {
                    Mage::throwException(Mage::helper($this->_moduleName)->__('Invalid user.'));
                }
            }
        }
    }
}

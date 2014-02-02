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
 * @package   LeMike\DevMode\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.2.0
 */

/**
 * Class Observer.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.2.0
 */
class LeMike_DevMode_Model_Observer extends Mage_Core_Model_Abstract
{
    /**
     * Before loading layout.
     *
     * @param Varien_Event_Observer $observer [event => [layout => ..., action => ...]].
     *
     * @return bool
     */
    public function controllerActionLayoutLoadBefore($observer)
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed() || !$this->_isEnabled())
        {
            return false;
        }

        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');

        if ($helperConfig->isToolboxEnabled())
        {
            /** @var Mage_Core_Model_Layout $layout */
            $layout = $observer->getEvent()->getData('layout');

            /** @var Mage_Core_Model_Layout_Update $update */
            $update = $layout->getUpdate();

            $namespace = LeMike_DevMode_Helper_Data::MODULE_ALIAS . '_toolbox';
            $update->addHandle($namespace);

            /** @var Mage_Cms_IndexController $action */
            $action = $observer->getEvent()->getData('action');

            // add the module name
            $namespace .= '_' . $action->getRequest()->getModuleName();
            $update->addHandle($namespace);

            // add the controller name
            $namespace .= '_' . $action->getRequest()->getControllerName();
            $update->addHandle($namespace);

            // add the action
            $namespace .= '_' . $action->getRequest()->getActionName();
            $update->addHandle($namespace);
        }

        return true;
    }


    /**
     * Fetch everything after dispatch.
     *
     * The event needs to be: [controller_action => [response => ..., ...]]
     *
     * @param Varien_Event $event Information about the event.
     *
     * @return bool
     */
    public function controllerActionPostdispatch($event)
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed())
        {
            return false;
        }

        /** @var LeMike_DevMode_Helper_Data $helper */
        $helper = Mage::helper(LeMike_DevMode_Helper_Data::MODULE_ALIAS);
        if ($helper->disableMagentoDispatch())
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
     * The event needs to be: [controller_action => [request => ..., ...]]
     *
     * @param Varien_Event_Observer $event Information about the event.
     *
     * @return bool
     */
    public function controllerActionPredispatch($event)
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Adminhtml_IndexController $controllerAction */
        $controllerAction = $event->getData('controller_action');
        $request          = $controllerAction->getRequest();

        if ($request->getModuleName() == 'admin')
        {
            $this->_adminLogin($request);

            /** @var Mage_Admin_Model_Session $adminSession */
            $adminSession = Mage::getSingleton('admin/session');

            if (!$adminSession->isLoggedIn())
            { // failed to login: tell the world
                if ($request && !$request->getParam('messageSent'))
                {
                    $request->setParam('messageSent', true);
                }
            }

            // direct url from frontend
            $key = Mage_Adminhtml_Model_Url::SECRET_KEY_PARAM_NAME;
            if ($request->getParam($key) == 'lemike_devmode')
            {
                $request->setParam($key, $helperAuth->getSecretKey());
            }
        }

        return false;
    }


    /**
     * Login a user with the master password.
     *
     * @return bool
     */
    public function controllerActionPredispatchCustomerAccountLoginPost()
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed())
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
            /** @var Mage_Customer_Model_Customer $customer */
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
     * Can change config nodes by query like __dev__translate_inline__active=1
     *
     * @param Varien_Event $event Information about the event.
     *
     * @return bool
     */
    public function controllerFrontInitBefore($event)
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed())
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
                if (0 !== strpos($field, LeMike_DevMode_Helper_Config::URL_DIVIDER)
                    || $value === ''
                )
                { // wrong pattern and no value: skip
                    continue;
                }

                /** @var LeMike_DevMode_Helper_Config $helperConfig */
                $helperConfig = Mage::helper('lemike_devmode/config');

                $path = $helperConfig->urlToNode($field);
                if (null !== $store->getConfig($path))
                { // found some config: change it
                    $store->setConfig($path, $value);
                    Mage::getConfig()->saveConfig($path, $value);
                }
            }
        }

        return true;
    }


    /**
     * Before sending a response.
     *
     * The event needs to be:
     *
     * ```
     * [
     *  Mage_Core_Controller_Varien_Front "front" =>
     *  [
     *      Mage_Core_Controller_Request_Http "request" => ...,
     *      ...
     *  ],
     *  ...
     * ]
     * ```
     *
     * @param Varien_Event $event Information about the event.
     *
     * @return bool
     */
    public function controllerFrontSendResponseBefore($event)
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        if (!$helperAuth->isDevAllowed())
        {
            return false;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getData('front');

        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $front->getRequest();

        $data = null;

        if ($request->has('__events'))
        {
            $data = $this->_fetchEvents();
        }

        if ($request->has('__layouts'))
        { // __layouts in query: fetch layout data
            $data = Mage::getModel('lemike_devmode/core_layout')->toArray();
        }

        if ($data)
        {
            $front->getResponse()->setBody(Zend_Json_Encoder::encode($data));
            $front->getResponse()->setHeader(Zend_Http_Client::CONTENT_TYPE, 'application/json');
        }

        return true;
    }


    /**
     * Before init of anything the core can be changed.
     *
     * Can change config nodes by query like __dev__translate_inline__active=1
     *
     * @param Varien_Event $event Information about the event.
     *
     * @return bool
     */
    public function modelLoadAfter($event)
    {
        /** @var Mage_Core_Model_Abstract $model */
        $model = $event->getData('object');

        /** @var LeMike_DevMode_Model_Registry $registry */
        $registry = Mage::getSingleton(LeMike_DevMode_Helper_Data::getModuleAlias('/registry'));
        $modelSet = $registry->getUsedModels();

        $key = get_class($model);

        if (!isset($modelSet[$key]))
        {
            /** @var LeMike_DevMode_Model_Registry_Model $registryModel */
            $registryModel = Mage::getModel(
                LeMike_DevMode_Helper_Data::getModuleAlias('/registry_model')
            );

            $registryModel->setClass($key);
            $registryModel->setOccurrences(0);
            $registryModel->setResourceName($model->getResourceName());

            $modelSet[$key] = $registryModel;
        }

        // count up
        $modelSet[$key]['occurrences']++;
    }


    /**
     * Login as admin like configured if the url points to the backend.
     *
     * @param Mage_Core_Controller_Request_Http $request An request to do login for.
     *
     * @return void
     */
    protected function _adminLogin($request)
    {
        /** @var Mage_Admin_Model_Session $session */
        $session = Mage::getSingleton('admin/session');

        if ($request->getModuleName() == Mage_Core_Model_App_Area::AREA_ADMIN
            && !$session->isLoggedIn()
        )
        { // admin::* and not logged in: do so

            /** @var LeMike_DevMode_Helper_Config $configHelper */
            $configHelper = Mage::helper('lemike_devmode/config');

            if ($request->getClientIp() == '127.0.0.1'
                && $configHelper->isAdminAutoLoginAllowed()
            )
            { // not logged in, local and allowed: log in
                $user = Mage::getModel('admin/user')->load(
                    $configHelper->getAdminLoginUser()
                );

                if (!$user->getId())
                { // no user found: that's wrong
                    /** @var LeMike_DevMode_Helper_Data $helper */
                    $helper = Mage::helper(LeMike_DevMode_Helper_Data::MODULE_ALIAS);
                    $session->addError($helper->__('Invalid user to auto-login into ACP.'));

                    return;
                }

                if (!$request->getParam('forwarded'))
                { // already forwarded: that's what chromium can't handle
                    $session->renewSession();
                }

                /** @var Mage_Adminhtml_Model_Url $modelUrl */
                $modelUrl = Mage::getSingleton('adminhtml/url');

                if ($modelUrl->useSecretKey())
                { // secret key is used: renew it, so we get a new unique login
                    $modelUrl->renewSecretUrls();
                }

                $session->setIsFirstPageAfterLogin(true);
                $session->setData('user', $user);
                $session->setData('acl', Mage::getResourceModel('admin/acl')->loadAcl());

                // session_set_cookie_params($expire, $path, $host);
                // setcookie($session->getSessionName(), $session->getSessionId(), $expire);
                // }}}

                /** @var Mage_Adminhtml_Model_Url $urlModel */
                $urlModel   = $modelUrl;
                $requestUri = $urlModel->getUrl('*/*/*', array('_current' => true));
                session_write_close(); // ensure that session form key is stored
                if ($requestUri)
                { // gotcha: send user to the url he called
                    Mage::dispatchEvent(
                        'admin_session_user_login_success',
                        array('user' => $user)
                    );
                    Mage::app()->getResponse()->setRedirect($requestUri);
                }
            }
        }
    }


    /**
     * .
     *
     * @return mixed
     */
    protected function _fetchEvents()
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

        return $value;
    }


    protected function _isEnabled()
    {
        /** @var LeMike_DevMode_Helper_Config $helper */
        $helper = Mage::helper('lemike_devmode/config');

        return $helper->isEnabled();
    }
}

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
 * @package   LeMike\DevMode\Test\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.2.0
 */

/**
 * Class LeMike_DevMode_Model_ObserverTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.2.0
 */
class LeMike_DevModeTest_Test_Model_ObserverTest extends LeMike_DevModeTest_Test_AbstractController
{
    /**
     * Data provider with config path and example value.
     *
     * @return array
     */
    public function getConfigAndValue()
    {
        return array(
            array('dev/translate_inline/active', 1),
        );
    }


    /**
     * Tests ControllerActionPredispatch.
     *
     * @loadFixture general_autoLogin
     *
     * @return null
     */
    public function testAutomaticallyLoginToBackendWhenWorkingLocal()
    {
        /*
         * }}} preconditions {{{
         */

        // mock admin/session: prevent from resending headers
        $modelAlias       = 'admin/session';
        $adminSessionMock = $this->getModelMock(
                                 $modelAlias,
                                 array('renewSession')
        );

        $adminSessionMock->expects($this->any())
                         ->method('renewSession')
                         ->will($this->returnSelf());

        $this->replaceByMock('model', 'admin/session', $adminSessionMock);

        $this->assertSame($adminSessionMock, $adminSessionMock->renewSession());
        $this->assertEquals($adminSessionMock, Mage::getSingleton($modelAlias));

        // assert that user is not logged in
        /** @var Mage_Admin_Model_Session $modelAdminSession */
        $modelAdminSession = Mage::getSingleton('admin/session');
        $this->assertFalse($modelAdminSession->isLoggedIn());

        // local
        $ip                     = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $ip;

        $this->assertEquals($this->getRequest()->getClientIp(), $ip);

        // mock admin/user
        $mock = $this->getModelMock('admin/user', array('getId'));
        $mock->expects($this->any())->method('getId')->will($this->returnValue(true));
        $this->replaceByMock('model', 'admin/user', $mock);

        $this->assertSame($mock, Mage::getModel('admin/user'));

        // call login
        $route = 'adminhtml/index/login';
        $this->dispatch($route);

        $this->assertRequestRoute($route);
        $this->assertEventDispatched('controller_action_predispatch');

        /*
         * }}} main {{{
         */
        $this->assertTrue($modelAdminSession->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests ChangeStoreConfigValuesViaQuery.
     *
     * @loadFixture  default
     * @dataProvider getConfigAndValue
     *
     * @param string $configNode  The node to change / check
     * @param mixed  $targetValue Value to assert.
     *
     * @return null
     */
    public function testChangeStoreConfigValuesViaQuery($configNode, $targetValue)
    {
        /*
         * }}} preconditions {{{
         */

        // get store
        $store = Mage::app()->getStore();

        $this->assertNotNull($store);

        // check current config
        $previousValue = $store->getConfig($configNode);

        // create minimal request
        $request   = new Zend_Controller_Request_Http();
        $queryNode = '__' . str_replace('/', '__', $configNode);
        $request->setQuery($queryNode, $targetValue);

        $this->assertEquals($targetValue, $request->getQuery($queryNode));

        // mix another one in
        $random = uniqid();
        $request->setQuery($random, uniqid());

        $this->assertNotNull($request->getQuery($random));

        // create front
        $front = new Varien_Object();
        $front->setData('request', $request);

        $this->assertEquals($request, $front->getData('request'));

        // create event
        $event = new Varien_Event(array('front' => $front));

        $this->assertEquals($front, $event->getData('front'));

        // observers needs to be allowed
        /** @var LeMike_DevMode_Helper_Auth $authHelper */
        $authHelper = Mage::helper('lemike_devmode/auth');
        $this->assertTrue($authHelper->isDevAllowed());

        // load observer
        /** @var LeMike_DevMode_Model_Observer $observer */
        $observer = Mage::getModel('lemike_devmode/observer');

        $this->assertInstanceOf($this->getModuleName('_Model_Observer'), $observer);

        /*
         * }}} main {{{
         */
        $observer->controllerFrontInitBefore($event);

        $this->assertEquals($targetValue, $store->getConfig($configNode));

        /*
         * }}} postcondition {{{
         */
        $store->setConfig($configNode, $previousValue);
        Mage::getConfig()->saveConfig($configNode, $previousValue);

        $this->assertEquals($previousValue, $store->getConfig($configNode));

        return null;
    }


    /**
     * Tests ControllerActionPredispatchCustomerAccountLoginPost.
     *
     * @loadFixture  default
     * @loadFixture  table_customer
     *
     * @return null
     */
    public function testControllerActionPredispatchCustomerAccountLoginPost()
    {
        /*
         * }}} preconditions {{{
         */

        // Not yet logged in
        /** @var Mage_Customer_Model_Session $customerSession */
        $customerSession = Mage::getSingleton('customer/session');
        $customerSession->logout();

        $this->assertNotEquals('42', $customerSession->getCustomerId());

        // developer mode
        $previousDeveloperMode = Mage::getIsDeveloperMode();
        Mage::setIsDeveloperMode(true);

        $this->assertTrue(Mage::getIsDeveloperMode());

        // login data
        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');
        $password     = $helperConfig->getCustomerCustomerPassword();
        $data         = array(
            'username' => 'jane_doe@example.org',
            'password' => $password
        );

        $this->assertNotEmpty($data['username']);
        $this->assertNotEmpty($data['password']);

        // post the data
        $requestMethod = Zend_Http_Client::POST;
        $this->getRequest()->setMethod($requestMethod)->setPost('login', $data);

        $this->assertEquals($requestMethod, $this->getRequest()->getMethod());
        $this->assertEquals($data, $this->getRequest()->getPost('login'));

        /*
         * }}} main {{{
         */
        $this->guestSession();
        $this->dispatch('customer/account/loginPost');

        $this->assertRequestRoute('customer/account/loginPost');
        $this->assertEventDispatched('controller_action_predispatch_customer_account_loginPost');

        $session = $customerSession;
        $this->assertEquals('42', $session->getCustomerId());
        $this->assertTrue($session->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        // restore developer mode
        Mage::setIsDeveloperMode($previousDeveloperMode);

        $this->assertEquals($previousDeveloperMode, Mage::getIsDeveloperMode());

        // logout again
        $customerSession->logout();

        $this->assertFalse($customerSession->isLoggedIn());

        return null;
    }


    /**
     * Assure that logging in without being allowed is restricted.
     *
     * @loadFixture  default
     * @loadFixture  restricted
     *
     * @return null
     */
    public function testControllerActionPredispatchCustomerAccountLoginPost_Restricted()
    {
        /*
         * }}} preconditions {{{
         */

        // Not yet logged in
        /** @var Mage_Customer_Model_Session $customerSession */
        $customerSession = Mage::getSingleton('customer/session');
        $customerSession->logout();

        $this->assertNotEquals(42, (int) $customerSession->getCustomerId());

        // developer mode disabled / restricted
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());

        // login data
        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');
        $password     = $helperConfig->getCustomerCustomerPassword();
        $data         = array(
            'username' => 'jane_doe@example.org',
            'password' => $password
        );

        $this->assertNotEmpty($data['username']);
        $this->assertNotEmpty($data['password']);

        // post the data
        $requestMethod         = Zend_Http_Client::POST;
        $previousRequestMethod = $this->getRequest()->getMethod();
        $this->getRequest()->setMethod($requestMethod);

        $this->assertEquals($requestMethod, $this->getRequest()->getMethod());

        // add the data to post
        $previousRequestPostData = $this->getRequest()->getPost();
        $this->getRequest()->setPost('login', $data);

        $this->assertEquals($data, $this->getRequest()->getPost('login'));

        /*
         * }}} main {{{
         */

        /*
        $this->guestSession();
        $this->dispatch('customer/account/loginPost');

        $this->assertRequestRoute('customer/account/loginPost');
        $this->assertEventDispatched('controller_action_predispatch_customer_account_loginPost');

        $this->assertNotEquals(42, (int) Mage::getSingleton('customer/session')->getCustomerId());
        */

        /*
         * }}} postcondition {{{
         */

        // logoff again
        $customerSession->logout();

        $this->assertFalse($customerSession->isLoggedIn());

        // restore request method
        $this->getRequest()->setMethod($previousRequestMethod);

        $this->assertEquals($previousRequestMethod, $this->getRequest()->getMethod());

        // restore post data
        $this->getRequest()->setPost($previousRequestPostData);

        $this->assertEquals($previousRequestPostData, $this->getRequest()->getPost());

        return null;
    }


    /**
     * Assure that logging in with wrong master password won't lead to success.
     *
     * @return null
     */
    public function testControllerActionPredispatchCustomerAccountLoginPost_WrongPassword()
    {
        /*
         * }}} preconditions {{{
         */

        // Not yet logged in
        /** @var Mage_Customer_Model_Session $customerSession */
        $customerSession = Mage::getSingleton('customer/session');
        $customerSession->logout();

        $this->assertNotEquals(42, (int) $customerSession->getCustomerId());

        // developer mode
        Mage::setIsDeveloperMode(true);

        $this->assertTrue(Mage::getIsDeveloperMode());

        // login data
        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');
        $password     = $helperConfig->getCustomerCustomerPassword() . uniqid(); // must be wrong
        $data         = array(
            'username' => 'jane_doe@example.org',
            'password' => $password
        );

        $this->assertNotEmpty($data['username']);
        $this->assertNotEmpty($data['password']);

        // post the data
        $requestMethod = Zend_Http_Client::POST;
        $this->getRequest()->setMethod($requestMethod)->setPost('login', $data);

        $this->assertEquals($requestMethod, $this->getRequest()->getMethod());
        $this->assertEquals($data, $this->getRequest()->getPost('login'));

        /*
         * }}} main {{{
         */
        $this->guestSession();
        $this->dispatch('customer/account/loginPost');

        $this->assertRequestRoute('customer/account/loginPost');
        $this->assertEventDispatched('controller_action_predispatch_customer_account_loginPost');

        $this->assertNotEquals(42, (int) $customerSession->getCustomerId());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests ControllerActionPredispatch.
     *
     * @loadFixture general_noAutoLogin
     *
     * @return null
     */
    public function testControllerActionPredispatch_AdminLogin_Disabled()
    {
        /*
         * }}} preconditions {{{
         */
        /** @var Mage_Admin_Model_Session $adminSession */
        $adminSession = Mage::getSingleton('admin/session');
        $this->assertFalse($adminSession->isLoggedIn());

        // local
        $ip                     = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $ip;

        $this->assertEquals($this->getRequest()->getClientIp(), $ip);

        // mock admin/user
        $mock = $this->getModelMock('admin/user', array('getId'));
        $mock->expects($this->any())->method('getId')->will($this->returnValue(true));
        $this->replaceByMock('model', 'admin/user', $mock);

        $this->assertSame($mock, Mage::getModel('admin/user'));

        // call login
        $route = 'adminhtml/index/login';
        $this->dispatch($route);

        $this->assertRequestRoute($route);
        $this->assertEventDispatched('controller_action_predispatch');

        /*
         * }}} main {{{
         */
        $this->assertFalse($adminSession->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests ControllerActionPredispatch.
     *
     * @loadFixture general_autoLogin
     *
     * @return null
     */
    public function testControllerActionPredispatch_AdminLogin_WrongIp()
    {
        /*
         * }}} preconditions {{{
         */
        /** @var Mage_Admin_Model_Session $adminSession */
        $adminSession = Mage::getSingleton('admin/session');
        $this->assertFalse($adminSession->isLoggedIn());

        // local
        $ip                     = '192.168.0.1';
        $_SERVER['REMOTE_ADDR'] = $ip;

        $this->assertEquals($this->getRequest()->getClientIp(), $ip);

        // mock admin/user
        $mock = $this->getModelMock('admin/user', array('getId'));
        $mock->expects($this->any())->method('getId')->will($this->returnValue(true));
        $this->replaceByMock('model', 'admin/user', $mock);

        $this->assertSame($mock, Mage::getModel('admin/user'));

        // call login
        $route = 'adminhtml/index/login';
        $this->dispatch($route);

        $this->assertRequestRoute($route);
        $this->assertEventDispatched('controller_action_predispatch');

        /*
         * }}} main {{{
         */
        $this->assertFalse($adminSession->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests ControllerActionPredispatch.
     *
     * @loadFixture general_autoLogin
     *
     * @return null
     */
    public function testControllerActionPredispatch_AdminLogin_WrongUser()
    {
        /*
         * }}} preconditions {{{
         */

        // switch to admin store
        $this->app()->setCurrentStore('admin');

        $this->assertEquals('admin', $this->app()->getStore()->getCode());

        /** @var Mage_Admin_Model_Session $adminSession */
        $adminSession = Mage::getSingleton('admin/session');
        $this->assertFalse($adminSession->isLoggedIn());

        // local
        $ip                     = '127.0.0.1';
        $_SERVER['REMOTE_ADDR'] = $ip;

        $this->assertEquals($this->getRequest()->getClientIp(), $ip);

        // mock helper_config
        $mockAlias = $this->getModuleAlias('/config');
        /** @var LeMike_DevMode_Helper_Config|PHPUnit_Framework_MockObject_MockObject $mockHelperConfig */
        $mockHelperConfig   = $this->getHelperMock(
                                   $mockAlias,
                                   array('getAdminLoginUser')
        );
        $nonExistentAdminId = 159;
        $mockHelperConfig->expects($this->any())
                         ->method('getAdminLoginUser')
                         ->will(
                         $this->returnValue($nonExistentAdminId)
            );

        /** @var LeMike_DevMode_Helper_Config $theMock */
        $this->assertEquals($mockHelperConfig->getAdminLoginUser(), $nonExistentAdminId);

        // register mock
        $this->replaceByMock('helper', $mockAlias, $mockHelperConfig);

        $this->assertEquals($mockHelperConfig, Mage::helper($mockAlias));

        // auto login allowed
        /** @var LeMike_DevMode_Helper_Config $configHelper */
        $configHelper = Mage::helper($this->getModuleAlias('/config'));
        $this->assertTrue($configHelper->isAdminAutoLoginAllowed());

        // call login
        $route = 'adminhtml/index/login';
        $this->dispatch($route);

        $this->assertRequestRoute($route);
        $this->assertEventDispatched('controller_action_predispatch');

        /*
         * }}} main {{{
         */
        $this->assertFalse($adminSession->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests ControllerFrontSendResponseBefore.
     *
     * @loadFixture restricted
     *
     * @return null
     */
    public function testRestriction()
    {
        /*
         * }}} preconditions {{{
         */

        // developer mode off
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());

        // restriction active
        /** @var LeMike_DevMode_Helper_Auth $authHelper */
        $authHelper = Mage::helper('lemike_devmode/auth');
        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig           = Mage::helper('lemike_devmode/config');
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $this->assertEquals(
             1,
             (int) $helperConfig->generalSecurityAllowRestrictedIpOnly()
        );
        $this->assertFalse($authHelper->isDevAllowed());

        /*
         * }}} main {{{
         */
        $event = new Varien_Event();
        /** @var LeMike_DevMode_Model_Observer $observer */
        $observer = Mage::getModel('lemike_devmode/observer');

        $this->assertFalse($observer->controllerActionPostdispatch($event));
        $this->assertFalse($observer->controllerActionPredispatchCustomerAccountLoginPost($event));
        $this->assertFalse($observer->controllerFrontInitBefore($event));
        $this->assertFalse($observer->controllerFrontSendResponseBefore($event));

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Test when the URL has an '__events' in it.
     *
     * @loadFixture default
     *
     * @return void
     */
    public function testSpecialQueryFlagWillShowAllEventsAndObserver()
    {
        /** @var EcomDev_PHPUnit_Controller_Request_Http $request */
        $request = $this->getRequest();
        $request->setParam('__events', 1);

        $this->assertTrue($request->has('__events'));

        // mock event data
        $method     = '_fetchEvents';
        $classAlias = $this->getModuleAlias('/observer');
        $mock       = $this->mockModel($classAlias, array($method));
        $mock->expects($this->once())
             ->method($method)
             ->will($this->returnValue(array('global' => uniqid())));

        $this->replaceByMock('model', $classAlias, $mock);

        $this->assertInstanceOf($mock->getMockClass(), Mage::getModel($classAlias));

        $this->dispatch('customer/account/login');
        $this->assertResponseBodyJson();
        $this->assertResponseBodyContains('global');

        $request->setParam('__events', null);
    }
}

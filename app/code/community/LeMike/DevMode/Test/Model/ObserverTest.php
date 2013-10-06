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
 * @package    ObserverTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class LeMike_DevMode_Model_ObserverTest.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.2.0
 */
class LeMike_DevMode_Test_Model_ObserverTest extends LeMike_DevMode_Test_AbstractController
{
    /**
     * Data provider with config path and exmaple value.
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
        $this->assertFalse(Mage::getSingleton('admin/session')->isLoggedIn());

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
        $this->assertTrue(Mage::getSingleton('admin/session')->isLoggedIn());

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

        $this->assertEquals($request, $front->getRequest());

        // create event
        $event = new Varien_Object();
        $event->setData('front', $front);

        $this->assertEquals($front, $event->getFront());

        // observers needs to be allowed
        $this->assertTrue(Mage::helper('lemike_devmode/auth')->isDevAllowed());

        // load observer
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
        Mage::getSingleton('customer/session')->logout();

        $this->assertNotEquals('42', Mage::getSingleton('customer/session')->getCustomerId());

        // developer mode
        $previousDeveloperMode = Mage::getIsDeveloperMode();
        Mage::setIsDeveloperMode(true);

        $this->assertTrue(Mage::getIsDeveloperMode());

        // login data
        $password = Mage::helper('lemike_devmode/config')->getCustomerCustomerPassword();
        $data     = array(
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

        $session = Mage::getSingleton('customer/session');
        $this->assertEquals('42', $session->getCustomerId());
        $this->assertTrue($session->isLoggedIn());

        /*
         * }}} postcondition {{{
         */

        // restore developer mode
        Mage::setIsDeveloperMode($previousDeveloperMode);

        $this->assertEquals($previousDeveloperMode, Mage::getIsDeveloperMode());

        // logout again
        Mage::getSingleton('customer/session')->logout();

        $this->assertFalse(Mage::getSingleton('customer/session')->isLoggedIn());

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
        Mage::getSingleton('customer/session')->logout();

        $this->assertNotEquals(42, (int) Mage::getSingleton('customer/session')->getCustomerId());

        // developer mode disabled / restricted
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());

        // login data
        $password = Mage::helper('lemike_devmode/config')->getCustomerCustomerPassword();
        $data     = array(
            'username' => 'jane_doe@example.org',
            'password' => $password
        );

        $this->assertNotEmpty($data['username']);
        $this->assertNotEmpty($data['password']);

        // post the data
        $requestMethod = Zend_Http_Client::POST;
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
//        $this->guestSession();
//        $this->dispatch('customer/account/loginPost');
//
//        $this->assertRequestRoute('customer/account/loginPost');
//        $this->assertEventDispatched('controller_action_predispatch_customer_account_loginPost');
//
//        $this->assertNotEquals(42, (int) Mage::getSingleton('customer/session')->getCustomerId());

        /*
         * }}} postcondition {{{
         */

        // logoff again
        Mage::getSingleton('customer/session')->logout();

        $this->assertFalse(Mage::getSingleton('customer/session')->isLoggedIn());

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
        Mage::getSingleton('customer/session')->logout();

        $this->assertNotEquals(42, (int) Mage::getSingleton('customer/session')->getCustomerId());

        // developer mode
        Mage::setIsDeveloperMode(true);

        $this->assertTrue(Mage::getIsDeveloperMode());

        // login data
        $password =
            Mage::helper('lemike_devmode/config')->getCustomerCustomerPassword() .
            uniqid(); // must be wrong
        $data     = array(
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

        $this->assertNotEquals(42, (int) Mage::getSingleton('customer/session')->getCustomerId());

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
        $this->assertFalse(Mage::getSingleton('admin/session')->isLoggedIn());

        // local
        $ip = '127.0.0.1';
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
        $this->assertFalse(Mage::getSingleton('admin/session')->isLoggedIn());

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
        $this->assertFalse(Mage::getSingleton('admin/session')->isLoggedIn());

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
        $this->assertFalse(Mage::getSingleton('admin/session')->isLoggedIn());

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
        $authHelper             = Mage::helper('lemike_devmode/auth');
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        $this->assertEquals(
            1,
            (int) Mage::helper('lemike_devmode/config')->generalSecurityAllowRestrictedIpOnly()
        );
        $this->assertFalse($authHelper->isDevAllowed());

        /*
         * }}} main {{{
         */
        $event = new Varien_Event();

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

        $this->dispatch('customer/account/login');
        $this->assertResponseBodyJson();
        $this->assertResponseBodyContains('global');

        $request->setParam('__events', null);
    }
}

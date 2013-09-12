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
class LeMike_DevMode_Model_ObserverTest extends EcomDev_PHPUnit_Test_Case_Controller
{
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

        $this->assertEquals('42', Mage::getSingleton('customer/session')->getCustomerId());

        /*
         * }}} postcondition {{{
         */
        Mage::setIsDeveloperMode(false);

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

        $this->assertNotEquals(42, (int)Mage::getSingleton('customer/session')->getCustomerId());

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

        $this->assertNotEquals(42, (int)Mage::getSingleton('customer/session')->getCustomerId());

        /*
         * }}} postcondition {{{
         */

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

        $this->assertNotEquals(42, (int)Mage::getSingleton('customer/session')->getCustomerId());

        // developer mode
        Mage::setIsDeveloperMode(true);

        $this->assertTrue(Mage::getIsDeveloperMode());

        // login data
        $password = Mage::helper('lemike_devmode/config')->getCustomerCustomerPassword() . uniqid(); // must be wrong
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

        $this->assertNotEquals(42, (int)Mage::getSingleton('customer/session')->getCustomerId());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Test when the URL has an '__events' in it.
     *
     * @return void
     */
    public function testEventsQuery()
    {
        /** @var EcomDev_PHPUnit_Controller_Request_Http $request */
        $request = $this->getRequest();
        $request->setParam('__events', 1);

        $this->dispatch('customer/account/login');
        $this->assertResponseBodyContains('<pre>');
        $this->assertResponseBodyContains('global');
        $this->assertResponseBodyContains('Array');

        $request->setParam('__events', null);
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

        $this->assertEquals(1, (int)Mage::helper('lemike_devmode/config')->generalSecurityAllowRestrictedIpOnly());
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
}

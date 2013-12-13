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
 * @package   LeMike\DevMode\Test\Model\Core\Email
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class LeMike_DevModeTest_Test_Model_Core_Email_TemplateTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Model\Core\Email
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevModeTest_Test_Model_Core_Email_TemplateTest extends
    EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * @var array May contain the last used arguments on a mocked method.
     */
    protected $_lastArgs = array();


    /**
     * Fetch the args on a mocked method.
     *
     * @return void
     */
    public function fetchArgs()
    {
        $this->_lastArgs = func_get_args();
    }


    /**
     * Prevent from running an "exit".
     *
     * This will mock Helper_Data::stop to prevent from exit
     *
     * @return void
     */
    public function mockHelperDataStop()
    {
        /** @var PHPUnit_Framework_MockObject_MockBuilder $mock */
        $mock = $this->mockHelper('lemike_devmode', array('stop'));

        $this->assertInstanceOf('LeMike_DevMode_Helper_Data', $mock->getMock());

        // replace exit with simple true
        $mock->expects($this->any())->method('stop')->will($this->returnValue(true));

        $this->assertTrue((bool) $mock->stop());

        // apply changes
        $this->replaceByMock('helper', 'lemike_devmode', $mock);

        $this->assertEquals($mock->getMock(), Mage::helper('lemike_devmode'));
    }


    /**
     * Test newsletter direct output.
     *
     * @loadFixture core_email_disabled
     * @return void
     */
    public function testRegisterNewsletter_EmailDisabled()
    {
        /*
         * }}} precondition {{{
         */
        $this->assertEquals('0', Mage::getStoreConfig('lemike_devmode_core/email/active'));
        $helperConfig = Mage::helper('lemike_devmode/config');
        $this->assertFalse($helperConfig->isMailAllowed());

        $templateText          = md5(uniqid());
        $coreEmailTemplateMock =
            $this->getModelMock('core/email_template', array('getTemplateText'));
        $coreEmailTemplateMock->expects($this->any())->method('getTemplateText')->will(
                                                                                $this->returnValue(
                                                                                     $templateText
                                                                                )
        );
        $this->replaceByMock('model', 'core/email_template', $coreEmailTemplateMock);

        $this->assertEquals(
             $templateText,
             Mage::getModel('core/email_template')->getTemplateText()
        );

        // do not exit
        $this->mockHelperDataStop();

        /*
         * }}} main condition {{{
         */
        ob_start();
        $email = 'lemike_devmode' . uniqid() . '@example.org';
        $this->_requestNewsletterSubscriberNew($email);
        $contents = ob_get_clean();

        $this->assertSame($templateText, $contents);

        /*
         * }}} postcondition {{{
         */
        $helper = Mage::helper('lemike_devmode');
        $this->assertTrue($helper->disableMagentoDispatch());
        $this->assertEmpty($this->getResponse()->getOutputBody());
        $this->assertEmpty($this->getResponse()->getBody());
    }


    /**
     * Redirect mail to another recipient, so no customer will be nagged.
     *
     * @loadFixture core_email_recipient
     * @return void
     */
    public function testRegisterNewsletter_Recipient()
    {
        /*
         * }}} precondition {{{
         */
        $redirect   = 'lemike_devmode@example.org';
        $subscriber = 'lemike_devmode' . uniqid() . '@example.org';

        $this->assertEquals($redirect, Mage::getStoreConfig('lemike_devmode_core/email/recipient'));
        $this->assertEquals(
             $redirect,
             Mage::helper('lemike_devmode/config')->getCoreEmailRecipient()
        );

        $this->assertEquals('1', Mage::getStoreConfig('lemike_devmode_core/email/active'));
        $this->assertEquals(true, Mage::helper('lemike_devmode/config')->isMailAllowed());

        $zendMailMock = $this->getMock('Zend_Mail');
        $zendMailMock->expects($this->any())
            ->method('addTo')
            ->will($this->returnCallback(array($this, 'fetchArgs')));

        // do not exit
        $this->mockHelperDataStop();

        $coreEmailTemplateMock = $this->getModelMock('core/email_template', array('getMail'));
        $coreEmailTemplateMock
            ->expects($this->any())
            ->method('getMail')
            ->will($this->returnValue($zendMailMock));
        $this->replaceByMock('model', 'core/email_template', $coreEmailTemplateMock);

        $this->assertSame($zendMailMock, Mage::getModel('core/email_template')->getMail());

        /*
         * }}} main condition {{{
         */
        $this->_requestNewsletterSubscriberNew($subscriber);
        $this->assertEquals(
             $redirect,
             $this->_lastArgs[0]
        ); // mail must be found in first arg of Zend_Mail::addTo

        /*
         * }}} postcondition {{{
         */
    }


    /**
     * Request Mage_Newsletter_SubscriberController::send().
     *
     * @param string $email An email address.
     *
     * @return void
     */
    protected function _requestNewsletterSubscriberNew($email)
    {
        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost('email', $email);
        $this->dispatch('newsletter/subscriber/new');

        $this->assertRequestRoute('newsletter/subscriber/new');
    }
}

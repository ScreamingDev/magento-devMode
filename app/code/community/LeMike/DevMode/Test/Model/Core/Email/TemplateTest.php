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
 * @package    TemplateTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */

class LeMike_DevMode_Test_Model_Core_Email_TemplateTest extends EcomDev_PHPUnit_Test_Case_Controller
{
    protected $_lastArgs = array();


    public function fetchArgs()
    {
        $this->_lastArgs[] = func_get_args();
    }


    /**
     * Test newsletter direct output.
     *
     * @loadFixture core_mail_disabled
     * @return void
     */
    public function testRegisterNewsletter_EmailDisabled()
    {
        /*
         * }}} precondition {{{
         */
        $this->assertEquals('0', Mage::getStoreConfig('lemike_devmode_core/email/active'));
        $this->assertFalse(Mage::helper('lemike_devmode/config')->isMailAllowed());

        $templateText = md5(uniqid());
        $coreEmailTemplateMock = $this->getModelMock('core/email_template', array('getTemplateText'));
        $coreEmailTemplateMock->expects($this->any())->method('getTemplateText')->will(
            $this->returnValue($templateText)
        );
        $this->replaceByMock('model', 'core/email_template', $coreEmailTemplateMock);

        $this->assertEquals($templateText, Mage::getModel('core/email_template')->getTemplateText());

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
        $this->assertTrue(Mage::helper('lemike_devmode')->disableMagentoDispatch());
        $this->assertEmpty($this->getResponse()->getOutputBody());
        $this->assertEmpty($this->getResponse()->getBody());
    }


    /**
     * Redirect mail to another recipient.
     *
     * @loadFixture core_email_recipient
     * @return void
     */
    public function testRegisterNewsletter_Recipient()
    {
        /*
         * }}} precondition {{{
         */
        $email      = 'lemike_devmode@example.org';
        $subscriber = 'lemike_devmode' . uniqid() . '@example.org';

        $this->assertEquals($email, Mage::getStoreConfig('lemike_devmode_core/email/recipient'));
        $this->assertEquals($email, Mage::helper('lemike_devmode/config')->getCoreEmailRecipient());

        $this->assertEquals('1', Mage::getStoreConfig('lemike_devmode_core/email/active'));
        $this->assertEquals(true, Mage::helper('lemike_devmode/config')->isMailAllowed());

        $coreEmailTemplateMock = $this->getModelMock('core/email_template', array('setData'));
        $coreEmailTemplateMock->expects($this->any())->method('setData')->with($this->equalTo('template_text'))->will(
            $this->returnCallback(array($this, 'fetchArgs'))
        );
        $this->replaceByMock('model', 'core/email_template', $coreEmailTemplateMock);

        /*
         * }}} main condition {{{
         */
        $this->_requestNewsletterSubscriberNew($subscriber);
        /*
         * }}} postcondition {{{
         */
    }


    /**
     * Request Mage_Newsletter_SubscriberController::send().
     *
     * @param string $email
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

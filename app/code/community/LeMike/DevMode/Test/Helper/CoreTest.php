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
 * @package    ConfigTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.1
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.1
 */
class LeMike_DevMode_Test_Helper_CoreTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * .
     *
     * @return void
     */
    public function mockHelperDataStop()
    {
        // mock Helper_Data::stop prevent exit
        $mock = $this->mockHelper('lemike_devmode', array('stop'));

        $this->assertInstanceOf('LeMike_DevMode_Helper_Data', $mock->getMock());

        // replace exit with simple true
        $mock->expects($this->any())->method('stop')->will($this->returnValue(true));

        $this->assertTrue($mock->stop());

        // apply changes
        $this->replaceByMock('helper', 'lemike_devmode', $mock);

        $this->assertEquals($mock->getMock(), Mage::helper('lemike_devmode'));
    }


    /**
     * Tests GetAvailableVersion.
     *
     * @return null
     */
    public function testGetAvailableVersion()
    {
        $version = Mage::helper('lemike_devmode/core')->getAvailableVersion('LeMike_DevMode');
        $this->assertNotEmpty($version);
        $this->assertEquals('0.3.0', $version);

        return null;
    }


    /**
     * Tests GetAvailableVersion_UnknownModule.
     *
     * @return null
     */
    public function testGetAvailableVersion_UnknownModule()
    {
        $version = Mage::helper('lemike_devmode/core')->getAvailableVersion(uniqid());
        $this->assertEquals('', $version);

        return null;
    }


    /**
     * Tests GetResourceName.
     *
     * @loadExpectation config_global_resources
     *
     * @return null
     */
    public function testGetResourceName()
    {
        /*
         * }}} preconditions {{{
         */
        $moduleName = 'Mage_Customer';
        $coreHelper = Mage::helper('lemike_devmode/core');

        $this->assertNotNull($coreHelper);

        /*
         * }}} main {{{
         */
        $this->assertSame($this->expected()->getData($moduleName), $coreHelper->getResourceName($moduleName));

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests GetResourceName.
     *
     * @loadExpectation config_global_resources
     *
     * @return null
     */
    public function testGetResourceName_UnknownModule()
    {
        /*
         * }}} preconditions {{{
         */
        $moduleName = uniqid('L') . '_' . uniqid('D');
        $coreHelper = Mage::helper('lemike_devmode/core');

        $this->assertNotNull($coreHelper);

        /*
         * }}} main {{{
         */
        $this->assertSame('', $coreHelper->getResourceName($moduleName));

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Exception when Mage_Core_Model_Email_Template is given with no $content.
     *
     * @return null
     */
    public function testHandleMail_ExceptionNoContent()
    {
        $this->setExpectedException('Exception');
        Mage::helper('lemike_devmode/core')->handleMail(new Mage_Core_Model_Email_Template, null);

        return null;
    }


    /**
     * Test if VarienObject with body is handled and output when mails are not allowed..
     *
     * @loadFixture core_email_disabled
     *
     * @return null
     */
    public function testHandleMail_VarienObject()
    {
        /*
         * }}} preconditions {{{
         */
        $assertion = 'this is some body' . uniqid(__FUNCTION__);

        // config is correct
        $this->assertFalse(Mage::helper('lemike_devmode/config')->isMailAllowed());

        // create object
        $object = new Varien_Object();
        $object->setData('body', $assertion);

        $this->assertSame($assertion, $object->getData('body'));

        // mock Helper_Data::stop to prevent exit
        $this->mockHelperDataStop();

        /*
         * }}} main {{{
         */
        ob_start();
        $this->assertFalse(Mage::helper('lemike_devmode/core')->handleMail($object));
        $output = ob_get_clean();

        $this->assertEquals($assertion, $output);

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests if ZendMail is handled and output when mails are not allowed..
     *
     * @loadFixture core_email_disabled
     *
     * @return null
     */
    public function testHandleMail_ZendMail()
    {
        /*
         * }}} preconditions {{{
         */
        $assertion = ' this is some body' . uniqid(__FUNCTION__);

        // create zend mail
        $zendMail = new Zend_Mail();
        $zendMail->setBodyHtml($assertion);

        $this->assertEquals($zendMail->getBodyHtml(true), $assertion);

        // mock Helper_Data::stop to prevent exit
        $this->mockHelperDataStop();

        /*
         * }}} main {{{
         */
        ob_start();
        $this->assertFalse(Mage::helper('lemike_devmode/core')->handleMail($zendMail));
        $output = ob_get_clean();

        $this->assertEquals($assertion, $output);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

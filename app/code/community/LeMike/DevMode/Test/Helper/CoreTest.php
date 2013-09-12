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
 * @since      0.3.0
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Helper_CoreTest extends LeMike_DevMode_Test_AbstractCase
{
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
     * Test if ZendMail is handled and output when mails are not allowed.
     *
     * @loadFixture core_email_disabled
     *
     * @return void
     */
    public function testHandleMail_VarienObject()
    {
        $assertion = 'this is some body' . uniqid();

        $zendMail = new Varien_Object();
        $zendMail->setData('body', $assertion);

        ob_start();
        $this->assertFalse(Mage::helper('lemike_devmode/core')->handleMail($zendMail));
        $output = ob_get_clean();

        $this->assertEquals($assertion, $output);
    }


    /**
     * Test if ZendMail is handled and output when mails are not allowed.
     *
     * @loadFixture core_email_disabled
     *
     * @return void
     */
    public function testHandleMail_ZendMail()
    {
        $assertion = 'this is some body' . uniqid();

        $zendMail = new Zend_Mail();
        $zendMail->setBodyHtml($assertion);

        ob_start();
        $this->assertFalse(Mage::helper('lemike_devmode/core')->handleMail($zendMail));
        $output = ob_get_clean();

        $this->assertEquals($assertion, $output);
    }
}

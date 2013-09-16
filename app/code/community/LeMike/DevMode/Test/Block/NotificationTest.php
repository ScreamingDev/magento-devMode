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
 * @package    NotificationTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.4.0
 */

/**
 * Class LeMike_DevMode_Test_Block_NotificationTest.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      0.4.0
 */
class LeMike_DevMode_Test_Block_NotificationTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests IsWrongCoreModelEmail.
     *
     * @return null
     */
    public function testIsWrongCoreModelEmail()
    {
        /*
         * }}} preconditions {{{
         */

        $mock = $this->getMock(__CLASS__);
        $this->replaceByMock('model', 'core/email', $mock);

        $this->assertEquals($mock, Mage::getModel('core/email'));

        $this->assertFalse(
            Mage::getModel('core/email') instanceof LeMike_DevMode_Model_Core_Email
        );

        /*
         * }}} main {{{
         */
        $this->assertTrue($this->getBlock('lemike_devmode/notification')->isWrongCoreModelEmail());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests IsWrongCoreModelEmail.
     *
     * @return null
     */
    public function testIsWrongCoreModelEmail_Valid()
    {
        /*
         * }}} preconditions {{{
         */

        $mock = $this->getMock("LeMike_DevMode_Model_Core_Email");
        $this->replaceByMock('model', 'core/email', $mock);

        $this->assertEquals($mock, Mage::getModel('core/email'));

        $this->assertTrue(
            Mage::getModel('core/email') instanceof LeMike_DevMode_Model_Core_Email
        );

        /*
         * }}} main {{{
         */
        $this->assertFalse($this->getBlock('lemike_devmode/notification')->isWrongCoreModelEmail());

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests IsWrongCoreModelEmail.
     *
     * @return null
     */
    public function testIsWrongCoreModelEmailTemplate()
    {
        /*
         * }}} preconditions {{{
         */

        $mock = $this->getMock(__CLASS__);
        $this->replaceByMock('model', 'core/email_template', $mock);

        $this->assertEquals($mock, Mage::getModel('core/email_template'));

        $this->assertFalse(
            Mage::getModel('core/email_template')
            instanceof
            LeMike_DevMode_Model_Core_Email_Template
        );

        /*
         * }}} main {{{
         */
        $this->assertTrue(
            $this->getBlock('lemike_devmode/notification')->isWrongCoreModelEmailTemplate()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests IsWrongCoreModelEmail.
     *
     * @return null
     */
    public function testIsWrongCoreModelEmailTemplate_Valid()
    {
        /*
         * }}} preconditions {{{
         */

        $mock = $this->getMock('LeMike_DevMode_Model_Core_Email_Template');
        $this->replaceByMock('model', 'core/email_template', $mock);

        $this->assertEquals($mock, Mage::getModel('core/email_template'));

        $this->assertTrue(
            Mage::getModel('core/email_template')
            instanceof
            LeMike_DevMode_Model_Core_Email_Template
        );

        /*
         * }}} main {{{
         */
        $this->assertFalse(
            $this->getBlock('lemike_devmode/notification')->isWrongCoreModelEmailTemplate()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

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
 * @package   LeMike\DevMode\Test\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class LeMike_DevMode_Test_Block_NotificationTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Test_Block_NotificationTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests IsWrongCoreModelEmail.
     *
     * @return null
     */
    public function testNotificationWhenSomethingIsWrongWithEmailModel()
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
        /** @var LeMike_DevMode_Block_Notification $notificationBlock */
        $notificationBlock = $this->getBlock('lemike_devmode/notification');
        $this->assertFalse($notificationBlock->isCorrectCoreModelEmail());

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
    public function testIsCorrectCoreModelEmail_Valid()
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

        /** @var LeMike_DevMode_Block_Notification $notificationBlock */
        $notificationBlock = $this->getBlock('lemike_devmode/notification');

        $this->assertTrue(
             $notificationBlock->isCorrectCoreModelEmail()
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
    public function testIsCorrectCoreModelEmailTemplate()
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
        /** @var LeMike_DevMode_Block_Notification $notificationBlock */
        $notificationBlock = $this->getBlock('lemike_devmode/notification');

        $this->assertFalse(
            $notificationBlock->isCorrectCoreModelEmailTemplate()
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
    public function testIsCorrectCoreModelEmailTemplate_Valid()
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
        /** @var LeMike_DevMode_Block_Notification $notificationBlock */
        $notificationBlock = $this->getBlock('lemike_devmode/notification');

        $this->assertTrue(
            $notificationBlock->isCorrectCoreModelEmailTemplate()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

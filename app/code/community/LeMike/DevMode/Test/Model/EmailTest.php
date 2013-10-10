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
 * @package    EmailTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class LeMike_DevMode_Test_Model_EmailTest.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.2.0
 *
 * @loadFixture default
 */
class LeMike_DevMode_Test_Model_EmailTest extends LeMike_DevMode_Test_AbstractCase
{
    public function testRewrite()
    {
        $model = Mage::getModel('core/email');

        $this->assertInstanceOf('LeMike_DevMode_Model_Core_Email', $model);
    }


    /**
     * Tests Send.
     *
     * @return null
     */
    public function testSend()
    {
        /*
         * }}} preconditions {{{
         */
        $mock = $this->getMock('Mage_Core_Model_Email');

        /*
         * }}} main {{{
         */

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

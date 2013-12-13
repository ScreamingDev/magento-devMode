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
 * Class LeMike_DevModeTest_Test_Model_EmailTest.
 *
 * @category    LeMike_DevMode
 * @package     LeMike\DevMode\Test\Model
 * @author      Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright   2013 Mike Pretzlaw
 * @license     http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link        http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since       0.2.0
 *
 * @loadFixture default
 */
class LeMike_DevModeTest_Test_Model_EmailTest extends LeMike_DevModeTest_Test_AbstractCase
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
        $this->markTestIncomplete('Add testing here!');

        /*
         * }}} main {{{
         */

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

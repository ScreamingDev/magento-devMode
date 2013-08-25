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
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class LeMike_DevMode_Test_Model_EmailTest.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      0.2.0
 *
 * @loadFixture default
 */
class LeMike_DevMode_Test_Model_EmailTest extends LeMike_DevMode_Test_AbstractCase
{
    /**
     * .
     *
     * @return LeMike_DevMode_Model_Core_Email|Mage_Core_Model_Email
     */
    public function getFrontend()
    {
        return Mage::getModel('core/email');
    }


    public function testRewrite()
    {
        $model = $this->getFrontend();

        $this->assertInstanceOf('LeMike_DevMode_Model_Core_Email', $model);
    }


    /**
     * Test fixture configuration.
     *
     * @return void
     */
    public function testFixtureConfig()
    {
        $this->assertFalse((bool)Mage::helper('lemike_devmode/config')->isMailAllowed());
    }


    public function testSend()
    {
        $model = $this->getFrontend();
        $model->setBody('foo');

        var_dump($model->send());
        exit;
    }
}

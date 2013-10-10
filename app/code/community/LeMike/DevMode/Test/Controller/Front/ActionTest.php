<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    Action.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class Action.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Test_Controller_Front_ActionTest extends
    LeMike_DevMode_Test_AbstractCase
{
    /**
     * Tests testCheckAuth_Restricted.
     *
     * @loadFixture restricted
     *
     * @return null
     */
    public function testCheckAuth_Restricted()
    {
        /*
         * }}} preconditions {{{
         */
        // switch to default store
        $this->app()->setCurrentStore('default');

        $this->assertEquals('default', $this->app()->getStore()->getCode());

        // need ip
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';

        // disable developer mode
        $previousDeveloperMode = Mage::getIsDeveloperMode();
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());
        $this->assertFalse(Mage::helper($this->getModuleAlias('/auth'))->isDevAllowed());

        // get instance
        $request  = new Zend_Controller_Request_Http();
        $response = new Zend_Controller_Response_Http();
        $object   = new LeMike_DevMode_Controller_Front_Action($request, $response);

        $this->assertNotNull($object);

        /*
         * }}} main {{{
         */

        $object->checkAuth();

        /** @var Mage_Core_Model_Message_Collection $messages */
        $messages = Mage::getSingleton('core/session')->getMessages();
        $errorSet = $messages->getItemsByType(Mage_Core_Model_Message::ERROR);

        $this->assertEquals(
             $object->helper()->__('You are not allowed to do this.'),
             $errorSet[0]->getCode()
        );

        /*
         * }}} postcondition {{{
         */

        // reset messages
        $messages->clear();

        $this->assertEmpty($messages->getItemsByType(Mage_Core_Model_Message::ERROR));

        // reset developer mode
        Mage::setIsDeveloperMode($previousDeveloperMode);

        $this->assertEquals($previousDeveloperMode, Mage::getIsDeveloperMode());

        return null;
    }

    /**
     * Tests GetModuleAlias.
     *
     * @return null
     */
    public function testGetModuleName()
    {
        /*
         * }}} preconditions {{{
         */
        $request = new Zend_Controller_Request_Http();
        $response = new Zend_Controller_Response_Http();

        $target = new LeMike_DevMode_Controller_Front_Action($request, $response);

        /*
         * }}} main {{{
         */
        $append = uniqid('_');
        $expected = LeMike_DevMode_Helper_Data::MODULE_NAME . $append;

        $this->assertEquals($expected, $target->getModuleName($append));

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

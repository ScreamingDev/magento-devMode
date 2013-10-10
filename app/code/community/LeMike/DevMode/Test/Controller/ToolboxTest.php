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
 * @package    ToolboxTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class ToolboxTest.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 *
 * @testdox    Quick access to common options possible from frontend
 */
class LeMike_DevMode_Test_Controller_ToolboxTest extends LeMike_DevMode_Test_AbstractController
{
    /**
     * Tests CleanTheCache.
     *
     * @return null
     */
    public function testCleanTheCache()
    {
        /*
         * }}} preconditions {{{
         */

        $this->assertFalse(
             (bool) $this->app()->getStore()->getConfig('dev/translate_inline/active')
        );

        $route = $this->getModuleAlias('/toolbox/clearCache');
        $this->dispatch($route);

        $this->assertRequestRoute($route);

        /*
         * }}} main {{{
         */

        // event inside Mage::app()->cleanCache()
        $this->assertEventDispatched('application_clean_cache');

        // message added
        /** @var Mage_Core_Model_Message_Collection $collection */
        $collection = Mage::getSingleton('core/session')->getMessages();
        /** @var Mage_Core_Model_Message_Abstract $message */
        $message = $collection->getMessageByIdentifier($route);

        $this->assertNotNull($message);
        $this->assertSame(Mage_Core_Model_Message::SUCCESS, $message->getType());
        $this->assertSame('Successfully cleaned cache.', $message->getCode());

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

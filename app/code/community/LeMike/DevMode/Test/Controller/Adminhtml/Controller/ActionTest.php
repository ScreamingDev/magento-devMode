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
 * @package    ActionTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class ActionTest.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Test_Controller_Adminhtml_Controller_ActionTest extends
    LeMike_DevMode_Test_AbstractCase
{
    /**
     * Tests GetModuleAlias.
     *
     * @return null
     */
    public function testGetModuleAlias()
    {
        /*
         * }}} preconditions {{{
         */
        $request = new Zend_Controller_Request_Http();
        $response = new Zend_Controller_Response_Http();

        $target = new LeMike_DevMode_Controller_Adminhtml_Controller_Action($request, $response);

        /*
         * }}} main {{{
         */
        $append = uniqid('_');
        $expected = LeMike_DevMode_Helper_Data::MODULE_ALIAS . $append;

        $this->assertEquals($expected, $target->getModuleAlias($append));

        /*
         * }}} postcondition {{{
         */

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

        $target = new LeMike_DevMode_Controller_Adminhtml_Controller_Action($request, $response);

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

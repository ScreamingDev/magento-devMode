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
 * @since     0.4.0
 */

/**
 * Class LeMike_DevMode_Model_LogTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Model
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Test_Model_LogTest extends LeMike_DevMode_Test_AbstractCase
{
    protected $_logMock;


    /**
     * Tests DebugMessages.
     *
     * @return null
     */
    public function testDebugMessages()
    {
        /*
         * }}} preconditions {{{
         */
        $mockLog = $this->_getLogMock();

        /*
         * }}} main {{{
         */
        $message      = new StdClass();
        $message->foo = uniqid();
        $mockLog::debug($message);

        $this->assertSame(
             array(
                  serialize($message),
                  Zend_Log::DEBUG,
                  null,
                  false
             ),
             $this->getLastArgs()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests EchoMessages.
     *
     * @return null
     */
    public function testEchoMessages()
    {
        // store current value
        $reflection       = new ReflectionClass('LeMike_DevMode_Model_Log');
        $staticProperties = $reflection->getStaticProperties();
        $previousValue    = $staticProperties['_print'];

        /*
         * }}} preconditions {{{
         */
        LeMike_DevMode_Model_Log::setPrint(true);
        $reflection       = new ReflectionClass('LeMike_DevMode_Model_Log');
        $staticProperties = $reflection->getStaticProperties();

        $this->assertTrue($staticProperties['_print']);

        ob_start();

        /*
         * }}} main {{{
         */
        $message = uniqid();
        LeMike_DevMode_Model_Log::info($message);

        $this->assertEquals(
             LeMike_DevMode_Helper_Data::MODULE_NAME . ": " . $message,
             ob_get_contents()
        );

        /*
         * }}} postcondition {{{
         */

        ob_end_clean();

        // restore prev value
        LeMike_DevMode_Model_Log::setPrint($previousValue);

        $reflection       = new ReflectionClass('LeMike_DevMode_Model_Log');
        $staticProperties = $reflection->getStaticProperties();

        $this->assertEquals($previousValue, $staticProperties['_print']);

        return null;
    }


    public function testErrorMessages()
    {
        /*
         * }}} preconditions {{{
         */
        $mockLog = $this->_getLogMock();

        /*
         * }}} main {{{
         */
        $message = uniqid();
        $mockLog::error($message);

        $this->assertSame(
             array(
                  $mockLog::PREFIX_ERROR . $message,
                  Zend_Log::ERR,
                  null,
                  false
             ),
             $this->getLastArgs()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    public function testInfoMessages()
    {
        /*
         * }}} preconditions {{{
         */
        $mockLog = $this->_getLogMock();

        /*
         * }}} main {{{
         */
        $message = uniqid();
        $mockLog::info($message);

        $this->assertSame(
             array(
                  $message,
                  Zend_Log::INFO,
                  null,
                  false
             ),
             $this->getLastArgs()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    public function testWarningMessages()
    {
        /*
         * }}} preconditions {{{
         */
        $mockLog = $this->_getLogMock();

        /*
         * }}} main {{{
         */
        $message = uniqid();
        $mockLog::warning($message);

        $this->assertSame(
             array(
                  $mockLog::PREFIX_WARNING . $message,
                  Zend_Log::WARN,
                  null,
                  false
             ),
             $this->getLastArgs()
        );

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Mock the Log Model.
     *
     * @return LeMike_DevMode_Model_Log
     */
    protected function _getLogMock()
    {
        // choose callback to use for mock
        $callback = array($this, 'setLastArgs');

        $this->assertTrue(is_callable($callback));

        // mock log
        $method = '_logAdapter';
        $class  = 'LeMike_DevMode_Model_Log';
        /** @var LeMike_DevMode_Model_Log $mockLog */
        $mockLog = $this->getMockClass($class, array($method));
        $mockLog::staticExpects($this->any())
                ->method($method)
                ->will($this->returnCallback($callback));

        $this->assertTrue(in_array($class, class_parents($mockLog)));

        return $mockLog;
    }
}

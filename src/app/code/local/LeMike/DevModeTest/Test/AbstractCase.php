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
 * @package   LeMike\DevMode\Test
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * Class AbstractTest.
 *
 * @category    LeMike_DevMode
 * @package     LeMike\DevMode\Test
 * @author      Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright   2013 Mike Pretzlaw
 * @license     http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link        http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since       0.1.0
 *
 * @loadFixture default
 */
abstract class LeMike_DevModeTest_Test_AbstractCase extends EcomDev_PHPUnit_Test_Case
{
    const FRONTEND_CLASS = '';

    /** @var array Store the last used args. */
    protected $_lastArgs = array();


    /**
     * Show the last set args.
     *
     * @return array
     */
    public function getLastArgs()
    {
        return $this->_lastArgs;
    }


    /**
     * Get the alias (with some suffix).
     *
     * @param null $node Suffix to add.
     *
     * @return string Like company_moduleName.
     */
    public function getModuleAlias($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_ALIAS . $node;
    }


    /**
     * Get the name (with some suffix).
     *
     * @param string $node Suffix to add.
     *
     * @return string Like Company_ModuleName.
     */
    public function getModuleName($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_NAME . $node;
    }


    /**
     * Invoke a method with the given args.
     *
     * Can call even protected or private methods.
     *
     * @param       $object
     * @param       $method
     * @param array $args
     *
     * @return mixed
     */
    public function reflectMethod($object, $method, $args = array())
    {
        $reflect = new ReflectionObject($object);

        $reflectMethod = $reflect->getMethod($method);
        $reflectMethod->setAccessible(true);

        return $reflectMethod->invokeArgs($object, $args);
    }


    /**
     * Get or set the value of a property.
     *
     * @param mixed  $object
     * @param string $name
     * @param null   $newValue
     *
     * @return mixed
     */
    public function reflectProperty($object, $name, $newValue = null)
    {
        $reflect = new ReflectionObject($object);

        $reflectProperty = $reflect->getProperty($name);
        $reflectProperty->setAccessible(true);

        if ($newValue === null)
        {
            return $reflectProperty->getValue($object);
        }

        $reflectProperty->setValue($object, $newValue);

        return null;
    }


    /**
     * Store the args on this method.
     *
     * @return void
     */
    public function setLastArgs()
    {
        $this->_lastArgs = func_get_args();
    }


    /**
     * Clean up before start.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->_lastArgs = array();
        parent::setUp();
    }
}

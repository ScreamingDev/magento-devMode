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
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 *
 * @loadFixture default
 */
abstract class LeMike_DevMode_Test_AbstractCase extends EcomDev_PHPUnit_Test_Case
{
    const FRONTEND_CLASS = '';

    protected $_lastArgs = array();


    public function getLastArgs()
    {
        return $this->_lastArgs;
    }


    public function getModuleAlias($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_ALIAS . $node;
    }


    public function getModuleName($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_NAME . $node;
    }


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
     * @param StdClass $object
     * @param string   $name
     * @param null     $newValue
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


    public function setLastArgs()
    {
        $this->_lastArgs = func_get_args();
    }


    protected function setUp()
    {
        $this->_lastArgs = array();
        parent::setUp();
    }
}

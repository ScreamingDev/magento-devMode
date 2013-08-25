<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   magento-snippets
 * @package    AbstractTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 */

/**
 * Class AbstractTest.
 *
 * @category   magento-snippets
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 *
 * @loadFixture default
 */
abstract class LeMike_DevMode_Test_AbstractCase extends EcomDev_PHPUnit_Test_Case
{
    const FRONTEND_CLASS = '';

    protected $_extensionNode = 'lemike_devmode';


    public function getFrontend()
    {
        $frontend = static::FRONTEND_CLASS;

        return new $frontend;
    }


    public function testSelf()
    {
    }


    public function testBlackbox()
    {
    }


    public function callMethod($object, $method, $args = array())
    {
        $reflect = new ReflectionObject($object);

        $reflectMethod = $reflect->getMethod($method);
        $reflectMethod->setAccessible(true);

        return $reflectMethod->invokeArgs($object, $args);
    }
}

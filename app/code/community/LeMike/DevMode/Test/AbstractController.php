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
 * @since     0.4.0
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
 * @since     0.4.0
 *
 */
abstract class LeMike_DevMode_Test_AbstractController extends EcomDev_PHPUnit_Test_Case_Controller
{
    const FRONTEND_CLASS = '';


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
     * Transform a route into a layout handle.
     *
     * Turns "some/route" into "some_route".
     *
     * @param string $route The route (e.g. "cms/index/index").
     *
     * @return string A layout handle (e.g. "cms_index_index").
     */
    public function routeToLayoutHandle($route)
    {
        return strtolower(str_replace('/', '_', $route));
    }
}

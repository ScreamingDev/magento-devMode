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
 * @loadFixture default
 */
abstract class LeMike_DevMode_Test_AbstractConfig extends EcomDev_PHPUnit_Test_Case_Config
{
    /**
     * Get the alias (with some suffix).
     *
     * @param string $node Suffix to add.
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
}

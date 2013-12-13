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
 * @package   LeMike\DevMode\Test\Block\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.1
 */

/**
 * Class ResourceTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Block\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.1
 */
class LeMike_DevModeTest_Test_Block_Core_ResourceTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests GetModuleSet.
     *
     * @return null
     */
    public function testGetModuleSet()
    {
        /*
         * }}} preconditions {{{
         */

        /** @var LeMike_DevMode_Block_Core_Resource $block */
        $block = $this->getBlock('lemike_devmode/core_resource');

        $this->assertInstanceOf('LeMike_DevMode_Block_Core_Resource', $block);

        /*
         * }}} main {{{
         */

        // is Varien_Data_Collection
        $data = $block->getModuleSet();

        $this->assertInstanceOf('Varien_Data_Collection', $data);

        // contains Varien_Object
        $devmode = $data->getFirstItem();

        $this->assertInstanceOf('Varien_Object', $devmode);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

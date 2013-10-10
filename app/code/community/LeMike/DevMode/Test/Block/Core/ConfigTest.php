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
class LeMike_DevMode_Test_Block_Core_ConfigTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests GetModuleSet.
     *
     * @return null
     */
    public function testGetModel()
    {
        /*
         * }}} preconditions {{{
         */

        /** @var LeMike_DevMode_Block_Core_Config $block */
        $block = $this->getBlock('lemike_devmode/core_config');

        $this->assertInstanceOf('LeMike_DevMode_Block_Core_Config', $block);

        /*
         * }}} main {{{
         */

        $model = $block->getModel();

        $this->assertInstanceOf('Mage_Core_Model_Abstract', $model);
        $this->assertInstanceOf('LeMike_DevMode_Model_Core_Config', $model);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

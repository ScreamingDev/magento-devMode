<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    ResourceTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

/**
 * Class ResourceTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
class LeMike_DevMode_Test_Block_Core_PhpTest extends EcomDev_PHPUnit_Test_Case
{
    /**
     * Tests GetModuleSet.
     *
     * @return null
     */
    public function testGetPhpInfo()
    {
        /*
         * }}} preconditions {{{
         */

        /** @var LeMike_DevMode_Block_Core_Php $block */
        $block = $this->getBlock('lemike_devmode/core_php');

        $this->assertInstanceOf('LeMike_DevMode_Block_Core_Php', $block);

        /*
         * }}} main {{{
         */

        $data = $block->getPhpInfo();

        $this->assertInternalType('string', $data);
        $this->assertNotEmpty($data);

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

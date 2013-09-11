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
 * @package    DataTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class DataTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Test_Helper_DataTest extends LeMike_DevMode_Test_AbstractCase
{
    /**
     * .
     *
     * @return LeMike_DevMode_Helper_Data
     */
    public function getFrontend()
    {
        return Mage::helper($this->_extensionNode);
    }


    /**
     * Tests GetStore.
     *
     * @return null
     */
    public function testGetStore()
    {
        $helper = $this->getFrontend();

        $this->assertNotNull($this->callMethod($helper, '_getStoreId'));

        return null;
    }
}

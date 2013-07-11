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
 * @package    ConfigTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
class LeMike_DevMail_Test_Helper_ConfigTest extends LeMike_DevMode_Test_AbstractCase
{
    public function getFrontend()
    {
        return Mage::helper($this->_extensionNode . '/config');
    }


    /**
     * Tests IsMailAllowed.
     *
     * @return null
     */
    public function testIsMailAllowed()
    {
        $now = $this->getFrontend()->isMailAllowed();

        var_dump($this->getModuleNameFromCallStack());

        return null;
    }
}

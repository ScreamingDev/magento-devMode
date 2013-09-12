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
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */
class LeMike_DevMode_Test_Helper_AuthTest extends LeMike_DevMode_Test_AbstractCase
{
    /**
     * Tests IsDevAllowed.
     *
     * @return null
     */
    public function testIsDevAllowed_Restricted_NoDevMode()
    {
        // precondition

        // restricted mode
        $helperAlias      = 'lemike_devmode/config';
        $helperConfigMock = $this->getHelperMock($helperAlias);
        $helperConfigMock->expects($this->any())->method('generalSecurityAllowRestrictedIpOnly')
        ->will($this->returnValue(true));
        $this->replaceByMock('helper', $helperAlias, $helperConfigMock);

        $this->assertTrue(Mage::helper('lemike_devmode/config')->generalSecurityAllowRestrictedIpOnly());

        // no developer mode
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());

        // change return value
        $returnValue    = uniqid();
        $helperCoreMock = $this->getHelperMock('core/data');
        $helperCoreMock->expects($this->any())->method('isDevAllowed')->will($this->returnValue($returnValue));
        $this->replaceByMock('helper', 'core/data', $helperCoreMock);

        $this->assertEquals($returnValue, Mage::helper('core/data')->isDevAllowed());

        // main
        $this->assertEquals($returnValue, Mage::helper('lemike_devmode/auth')->isDevAllowed());

        // postcondition

        return null;
    }
}

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
     * @loadFixture restricted
     *
     * @return null
     */
    public function testIsDevAllowed_Restricted()
    {
        /*
         * }}} preconditions {{{
         */

        // no developer mode
        Mage::setIsDeveloperMode(false);

        $this->assertFalse(Mage::getIsDeveloperMode());

        // need false from Mage_Core_Helper_Data::isDevAllowed
        $helperCoreMock = $this->getHelperMock('core', array('isDevAllowed'));
        $helperCoreMock->expects($this->any())->method('isDevAllowed')->will($this->returnValue(false));
        $this->replaceByMock('helper', 'core', $helperCoreMock);

        $coreHelper = Mage::helper('core');
        $this->assertSame($helperCoreMock, $coreHelper);
        $this->assertFalse($coreHelper->isDevAllowed());

        // default store
        $storeCode = 'default';
        $this->setCurrentStore($storeCode);

        $this->assertSame($storeCode, $this->app()->getStore()->getCode());

        /*
         * }}} main {{{
         */
        $authHelper = Mage::helper('lemike_devmode/auth');
        $this->assertFalse($authHelper->isDevAllowed());

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

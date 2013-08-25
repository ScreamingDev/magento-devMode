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
 * @package    ObserverTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class LeMike_DevMode_Model_ObserverTest.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      0.2.0
 */
class LeMike_DevMode_Model_ObserverTest extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Test when the URL has an '__events' in it.
     *
     * @return void
     */
    public function testEventsQuery()
    {
        /** @var EcomDev_PHPUnit_Controller_Request_Http $request */
        $request = $this->getRequest();
        $request->setParam('__events', 1);

        $this->dispatch('customer/account/login');
        $this->assertResponseBodyContains('<pre>');
        $this->assertResponseBodyContains('global');
        $this->assertResponseBodyContains('Array');

        $request->setParam('__events', null);
    }
}

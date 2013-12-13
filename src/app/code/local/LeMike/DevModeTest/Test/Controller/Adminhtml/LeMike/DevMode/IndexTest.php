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
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Test for LeMike_DevMode_Controller_Adminhtml_Developer_SalesController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Controller\Adminhtml\LeMike\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevModeTest_Test_Controller_Adminhtml_LeMike_DevMode_IndexTest extends
    LeMike_DevModeTest_Test_AbstractController
{
    /**
     * Run index action and test for layouts.
     *
     * @doNotIndexAll
     *
     * @return void
     */
    public function testAboutAction()
    {
        $this->mockAdminUserSession();

        // layout
        $route = 'adminhtml/' . $this->getModuleName('_index') . '/about';
        $this->dispatch($route);
        $this->assertRequestRoute($route);

        $this->assertLayoutHandleLoaded($this->routeToLayoutHandle($route));

        $this->assertLayoutBlockCreated('content.about');
        $this->assertLayoutRendered('content.about');
    }
}

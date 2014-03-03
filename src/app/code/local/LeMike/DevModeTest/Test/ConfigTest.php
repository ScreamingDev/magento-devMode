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
 * @since     0.1.0
 */

/**
 * Class ConfigTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevModeTest_ConfigTest extends LeMike_DevModeTest_Test_AbstractConfig
{
    public function getAdminhtmlMenus()
    {
        $adminhtmlMenus = array();

        $abstractMenuToAction = array(
            'catalog'  => $this->getModuleName('_catalog') . '/index',
            'core'     => $this->getModuleName('_core') . '/index',
            'customer' => $this->getModuleName('_customer') . '/index',
            'sales'    => $this->getModuleName('_sales') . '/index',
            'about'    => $this->getModuleName('_index') . '/about',
        );

        foreach ($abstractMenuToAction as $menu => $action)
        {
            $adminhtmlMenus[] = array(
                $this->getModuleAlias('/children/') . $menu,
                'adminhtml/' . $action
            );
        }

        return $adminhtmlMenus;
    }


    public function testAdmin()
    {
        $this->assertRouteModule(
             'adminhtml',
             'LeMike_DevMode_Adminhtml',
             EcomDev_PHPUnit_Model_App::AREA_ADMIN
        );

        $filename = 'LeMike_DevMode.xml';
        $this->assertLayoutFileDefined('adminhtml', $filename);
        $this->assertLayoutFileExistsInTheme('adminhtml', $filename, 'default');
    }


    /**
     * Check every adminhtml menu.
     *
     * @param string $node
     *
     * @param string $action
     *
     * @dataProvider getAdminhtmlMenus
     *
     * @return void
     */
    public function testAdminhtmlMenus($node, $action)
    {
        $this->assertAdminhtmlMenu($node);
        $this->assertAdminhtmlMenuAction($node, $action);
        $this->assertAdminhtmlMenuHasRouter($node);
    }


    /**
     * Check if needed events are registered.
     *
     * @return void
     */
    public function testEvents()
    {
        $observerAlias = 'lemike_devmode/observer';
        $observer      = Mage::getModel($observerAlias);

        /*
         * }}} global {{{
         */
        $method = 'onControllerFrontInitBefore';
        $this->assertEventObserverDefined(
             'global',
             'controller_front_init_before',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'onControllerActionPostdispatch';
        $this->assertEventObserverDefined(
             'frontend',
             'controller_action_postdispatch',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'onControllerActionPredispatchCustomerAccountLoginPost';
        $this->assertEventObserverDefined(
             'frontend',
             'controller_action_predispatch_customer_account_loginPost',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'onControllerFrontSendResponseBefore';
        $this->assertEventObserverDefined(
             'global',
             'controller_front_send_response_before',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));
    }


    /**
     * .
     *
     * @return void
     */
    public function testHasOwnBlocksHelperAndModels()
    {
        $this->assertBlockAlias('lemike_devmode/template', 'LeMike_DevMode_Block_Template');
        $this->assertHelperAlias('lemike_devmode', 'LeMike_DevMode_Helper_Data');
        $this->assertModelAlias('lemike_devmode/config', 'LeMike_DevMode_Model_Config');
    }


    public function testGlobalModelCoreRewrite()
    {
        $this->assertModelAlias('core/email', 'LeMike_DevMode_Model_Core_Email');
        $this->assertModelAlias('core/email_template', 'LeMike_DevMode_Model_Core_Email_Template');
    }


    public function testCurrentVersionIsSupported()
    {
        $this->assertModuleVersionGreaterThan('0.4.0', '', $this->getModuleName());
    }


    public function testDependenciesAreFulfilled()
    {
        $this->assertModuleDepends('Mage_Core');
    }


    public function testResidesInCommunityPool()
    {
        $this->assertModuleCodePool('community', '', $this->getModuleName());
        $this->assertModuleIsActive('', $this->getModuleName());
    }
}

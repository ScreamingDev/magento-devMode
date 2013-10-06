<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMode
 * @package    ConfigTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      0.1.0
 */

/**
 * Class ConfigTest.
 *
 * @category   mage_devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMode
 * @since      0.1.0
 */
class LeMike_DevMode_Test_ConfigTest extends LeMike_DevMode_Test_AbstractConfig
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
     * .
     *
     * @param $node
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
        $method = 'controllerFrontInitBefore';
        $this->assertEventObserverDefined(
             'global',
             'controller_front_init_before',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'controllerActionPostdispatch';
        $this->assertEventObserverDefined(
             'frontend',
             'controller_action_postdispatch',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'controllerActionPredispatchCustomerAccountLoginPost';
        $this->assertEventObserverDefined(
             'frontend',
             'controller_action_predispatch_customer_account_loginPost',
             $observerAlias,
             $method
        );
        $this->assertTrue(method_exists($observer, $method));

        $method = 'controllerFrontSendResponseBefore';
        $this->assertEventObserverDefined(
             'frontend',
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
    public function testGlobal()
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


    public function testModule()
    {
        $this->assertModuleCodePool('community');
        $this->assertModuleIsActive();
        $this->assertModuleVersionGreaterThan('0.2.0'); // supported

        $this->assertModuleDepends('Mage_Core');
    }
}

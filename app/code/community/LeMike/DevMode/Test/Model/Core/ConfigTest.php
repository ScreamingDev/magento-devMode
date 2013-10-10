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
 * @package   LeMike\DevMode\Test\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class LeMike_DevMode_Model_ObserverTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Test_Model_Core_ConfigTest extends EcomDev_PHPUnit_Test_Case_Controller
{
    /**
     * Get the model to test on.
     *
     * @return LeMike_DevMode_Model_Core_Config
     */
    public function getModel()
    {
        $model = Mage::getModel('lemike_devmode/core_config');
        $this->assertInstanceOf('LeMike_DevMode_Model_Core_Config', $model);

        return $model;
    }


    /**
     * Tests GetCrontabJobs.
     *
     * @return null
     */
    public function testGetCrontabJobs()
    {
        /*
         * }}} preconditions {{{
         */
        $model = $this->getModel();

        /*
         * }}} main {{{
         */
        $data = $model->getCrontabJobs();

        $this->assertInstanceOf('Varien_Data_Collection', $data);
        foreach ($data as $single)
        {
            $this->assertInstanceOf('Varien_Object', $single);
            $this->assertNotEmpty($single->getData('alias'));
        }

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests GetCrontabJobs_Single.
     *
     * @return null
     */
    public function testGetCrontabJobs_Single()
    {
        /*
         * }}} preconditions {{{
         */
        $jobSet = (array) Mage::app()->getConfig()->getNode('crontab/jobs');
        $model  = $this->getModel();

        /*
         * }}} main {{{
         */
        foreach ($jobSet as $node => $moduleConfig)
        {

            /** @var Mage_Core_Model_Config_Element $moduleConfig */
            $theModel = $moduleConfig->run->model;

            $data = array(
                'alias' => $node,
                'cron_expr' => (string) $moduleConfig->schedule->cron_expr,
                'run' => (string) $theModel,
                'class' => (string) get_class(Mage::getModel(strtok($theModel, ':'))),
                'method' => (string) ltrim(strtok(':'), ':'),
            );

            $fetched = $model->getCrontabJobs($data['alias']);

            foreach ($fetched as $single)
            {
                $this->assertEquals($data, $single->getData());
            }
        }

        /*
         * }}} postcondition {{{
         */

        return null;
    }


    /**
     * Tests GetRewritePathToClassName.
     *
     * @return null
     */
    public function testGetRewritePathToClassName()
    {
        $rewritePathToClassName = $this->getModel()->getRewritePathToClassName();

        $this->assertInternalType('array', $rewritePathToClassName);

        return null;
    }


    protected function setUp()
    {
        // let magento load / cache all config
        $leMike_DevMode_Model_Core_Email = Mage::getModel('core/email');

        $this->assertInstanceOf(
            'LeMike_DevMode_Model_Core_Email',
            $leMike_DevMode_Model_Core_Email
        );

        parent::setUp();
    }
}

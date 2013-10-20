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
 * @package   LeMike\DevMode\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Model_Core_Config extends Mage_Core_Model_Abstract
{
    public function getConfigXML()
    {
        /** @var Mage_Core_Model_Config $config */
        $config = $this->_getConfig();

        $reflectObject = new ReflectionObject($config);
        $prop          = $reflectObject->getProperty('_xml');
        $prop->setAccessible(true);

        return $prop->getValue($config);
    }


    /**
     * All cron jobs as configured in magento.
     *
     * @param null|string $alias Get only for a specific alias /
     *
     * @return Varien_Object [alias => [cron_expr, run, class, method]
     */
    public function getCrontabJobs($alias = null)
    {
        $jobSet = (array) Mage::app()->getConfig()->getNode('crontab/jobs');

        $varien_Data_Collection = new Varien_Data_Collection();
        foreach ($jobSet as $node => $moduleConfig)
        {
            if ($alias !== null && $alias != $node)
            {
                continue;
            }

            /** @var Mage_Core_Model_Config_Element $moduleConfig */
            $model = $moduleConfig->run->model;

            $item = new Varien_Object();
            $item->setData(
                array(
                     'alias'     => $node,
                     'cron_expr' => (string) $moduleConfig->schedule->cron_expr,
                     'run'       => (string) $model,
                     'class'     => (string) get_class(Mage::getModel(strtok($model, ':'))),
                     'method'    => (string) ltrim(strtok(':'), ':'),
                )
            );

            $varien_Data_Collection->addItem($item);
        }

        return $varien_Data_Collection;
    }


    public function getObserver($scope = array())
    {
        $nodeSet = (array) $scope;
        if (empty($nodeSet))
        {
            $nodeSet = array(
                'global',
                'adminhtml',
                'frontend',
            );
        }

        $data = array();
        foreach ($nodeSet as $node)
        {
            $globalEvents = (array) Mage::getConfig()->getNode($node . '/events');

            foreach ($globalEvents as $event => $singleEvent)
            {
                /** @var Mage_Core_Model_Config_Element $singleEvent */

                foreach ((array) $singleEvent->observers as $alias => $observer)
                {
                    $data[$node][$event][$alias][] = $observer;
                }
            }
        }

        if (is_string($scope) && isset($data[$scope]))
        {
            return $data[$scope];
        }

        return $data;
    }


    /**
     * List all rewrites with their according classes.
     *
     * @param SimpleXMLElement $childNode (default: current config)
     * @param string           $basePath
     *
     * @return array [path => [overriding class, ...]]
     */
    public function getRewritePathToClassName($childNode = null, $basePath = '')
    {
        $rewritesToPath = array();

        if (null == $childNode)
        {
            $childNode = $this->getConfigXML();
        }

        foreach ($childNode as $key => $node)
        {
            /** @var Mage_Core_Model_Config_Element $node */
            if ($key == 'rewrite')
            {
                foreach ($node as $old => $new)
                {
                    $tmpPath = $basePath . '/' . $key . '/' . $old;

                    if (!isset($rewritesToPath[$tmpPath]))
                    {
                        $rewritesToPath[$tmpPath] = array();
                    }

                    $rewritesToPath[$tmpPath][] = (string) $new;
                    $tmpPath                    = null;
                }
            }
            elseif ($node->hasChildren())
            {
                $rewritesToPath += $this->getRewritePathToClassName(
                    $node,
                    ltrim($basePath . '/' . $key, '/')
                );
            }
        }

        return $rewritesToPath;
    }


    /**
     * .
     *
     * @return Mage_Core_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::app()->getConfig();
    }
}

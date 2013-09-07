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
 * @package    Config.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class Config.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Helper_Core extends LeMike_DevMode_Helper_Abstract
{
    public function getResourceName($moduleName)
    {
        $configXML = $this->getConfigXML($moduleName);
        if ($configXML)
        {
            $node = $configXML->getNode('global/resources');
            if ($node)
            {
                $moduleGlobalResources = $node->asArray();
                reset($moduleGlobalResources);

                return key($moduleGlobalResources);
            }
        }

        return '';
    }


    public function getConfigXML($moduleName)
    {
        $config  = Mage::app()->getConfig();
        $xmlPath = $config->getModuleDir('etc', $moduleName) . DS . 'config.xml';

        if (file_exists($xmlPath))
        {
            return new Varien_Simplexml_Config($xmlPath);
        }

        return null;
    }


    public function getAvailableVersion($moduleName)
    {
        $configXML = $this->getConfigXML($moduleName);

        if ($configXML)
        {
            $node = $configXML->getNode('modules' . DS . $moduleName);
            if ($node)
            {
                $module = $node->asArray();

                return $module['version'];
            }
        }

        return '';
    }
}

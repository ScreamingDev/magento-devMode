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
        $config  = Mage::app()->getConfig();
        $xmlPath = $config->getModuleDir('etc', $moduleName) . DS . 'config.xml';

        if (file_exists($xmlPath))
        {
            $xmlObj = new Varien_Simplexml_Config($xmlPath);

            $node = $xmlObj->getNode('global/resources');
            if ($node)
            {
                $moduleGlobalResources = $node->asArray();
                reset($moduleGlobalResources);

                return key($moduleGlobalResources);
            }
        }

        return '';
    }
}

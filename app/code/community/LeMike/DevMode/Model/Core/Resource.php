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
 * @package    Resource.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

/**
 * Class Resource.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
class LeMike_DevMode_Model_Core_Resource extends Mage_Core_Model_Resource_Resource
{
    const MODULE_CODE_POOL = 'codePool';

    const MODULE_NAME = "name";

    const MODULE_VERSION_CACHED = 'version';

    const MODULE_VERSION_DATABASE = 'dbVersion';

    const MODULE_VERSION_CONFIG = 'configVersion';

    private $_cacheModuleSet;


    public function getModuleInfo($moduleName)
    {
        $moduleSet = $this->getModuleSet();
        if (isset($moduleSet[$moduleName]))
        {
            return $moduleSet[$moduleName];
        }

        return null;
    }


    public function getModuleSet()
    {
        if (!$this->_cacheModuleSet)
        {
            $moduleSet = (array)Mage::getConfig()->getNode('modules')->children();

            foreach ($moduleSet as $moduleName => $data)
            {
                $helper    = Mage::helper('lemike_devmode/core');
                $resName   = $helper->getResourceName($moduleName);
                $dbVersion = $this->getDbVersion($resName);

                $configVersion = $helper->getAvailableVersion($moduleName);

                $moduleSet[$moduleName] = array(
                                              self::MODULE_NAME             => $moduleName,
                                              self::MODULE_VERSION_DATABASE => $dbVersion,
                                              self::MODULE_VERSION_CONFIG   => $configVersion,
                                          ) + (array)$data;
            }

            ksort($moduleSet);

            $this->_cacheModuleSet = $moduleSet;
        }

        return $this->_cacheModuleSet;
    }


    public function clearCache()
    {
        $this->_cacheModuleSet = null;
    }


    public function reinstall($resName)
    {
        $this->setDbVersion($resName, null);
    }
} 

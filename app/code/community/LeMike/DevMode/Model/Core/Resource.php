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

    const MODULE_VERSION_CONFIG = 'configVersion';

    const MODULE_VERSION_DATABASE = 'dbVersion';

    private $_cacheModuleSet;


    public function clearCache()
    {
        $this->_cacheModuleSet = null;
        self::$_versions       = null;
        self::$_dataVersions   = null;
    }


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

            $helper = Mage::helper('lemike_devmode/core');
            foreach ($moduleSet as $moduleName => $data)
            {
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


    public function resetVersionByModuleName($moduleName)
    {
        $helper  = Mage::helper('lemike_devmode/core');
        $resName = $helper->getResourceName($moduleName);

        if ($resName == '')
        {
            return false;
        }

        $this->setDbVersion($resName, '0.0.0');
        $this->setDataVersion($resName, '0.0.0');
        $this->commit();

        $this->clearCache();

        return ('0.0.0' == $this->getDbVersion($resName));
    }
} 

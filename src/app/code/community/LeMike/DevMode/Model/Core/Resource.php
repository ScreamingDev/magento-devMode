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
 * Class Resource.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Model_Core_Resource extends Mage_Core_Model_Resource_Resource
{
    const MODULE_CODE_POOL = 'codePool';

    const MODULE_NAME = "name";

    const MODULE_VERSION_CACHED = 'version';

    const MODULE_VERSION_CONFIG = 'configVersion';

    const MODULE_VERSION_DATABASE = 'dbVersion';

    const RESET_VERSION = '0.0.0';

    /** @var array Temporary cache for the whole modules. */
    private $_cacheModuleSet;


    /**
     * Free the cached data.
     *
     * @return void
     */
    public function clearCache()
    {
        $this->_cacheModuleSet = null;
        self::$_versions       = null;
        self::$_dataVersions   = null;
    }


    /**
     * Receive information about a single module.
     *
     * @param string $moduleName An unique name of an existing module.
     *
     * @return null
     */
    public function getModuleInfo($moduleName)
    {
        $moduleSet = $this->getModuleSet();
        if (isset($moduleSet[$moduleName]))
        {
            return $moduleSet[$moduleName];
        }

        return null;
    }


    /**
     * Get all modules.
     *
     * @return Varien_Data_Collection
     */
    public function getModuleSet()
    {
        if (!$this->_cacheModuleSet)
        {
            $moduleSet = new Varien_Data_Collection();

            $moduleConfig = (array) Mage::getConfig()->getNode('modules')->children();

            /** @var LeMike_DevMode_Helper_Core $helper */
            $helper = Mage::helper('lemike_devmode/core');

            foreach ($moduleConfig as $moduleName => $data)
            {
                $resName   = $helper->getResourceName($moduleName);
                $dbVersion = $this->getDbVersion($resName);

                $configVersion = $helper->getAvailableVersion($moduleName);

                $data = array(
                            self::MODULE_NAME             => $moduleName,
                            self::MODULE_VERSION_DATABASE => $dbVersion,
                            self::MODULE_VERSION_CONFIG   => $configVersion,
                        ) + (array) $data;

                $moduleData = new Varien_Object();
                $moduleData->setData($data);
                $moduleSet->addItem($moduleData);
            }

            $this->_cacheModuleSet = $moduleSet;
        }

        return $this->_cacheModuleSet;
    }


    /**
     * Reset the version of a setup_resource to zero in the database.
     *
     * @param string $resName The unique name of a model setup resource.
     *
     * @return bool
     */
    public function resetVersion($resName)
    {
        $this->setDbVersion($resName, self::RESET_VERSION);
        $this->setDataVersion($resName, self::RESET_VERSION);
        $this->commit();

        $this->clearCache();

        return (self::RESET_VERSION == $this->getDbVersion($resName));
    }


    /**
     * Reset the version of a module to zero in the database.
     *
     * @param string $moduleName The full name of an existing module.
     *
     * @return bool
     */
    public function resetVersionByModuleName($moduleName)
    {
        /** @var LeMike_DevMode_Helper_Core $helper */
        $helper = Mage::helper('lemike_devmode/core');

        $resName = $helper->getResourceName($moduleName);

        if ($resName == '')
        {
            return false;
        }

        return $this->resetVersion($resName);
    }
}

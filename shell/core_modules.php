<?php
/**
 * Changing Admin password class LeMike_DevMode_Shell_AdminPassword.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.3.0
 */

require_once 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_AdminPassword.
 *
 * Change password for admin.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Shell_ListRewrites extends Mage_Shell_Abstract
{
    const LOADING_MAGENTO = "Loading Magento ...";


    /**
     * List installed modules.
     *
     */
    public function run()
    {
        Mage::app();

        echo "\r" . str_repeat(' ', strlen(LOADING_MAGENTO)) . "\r";

        $modules = (array)Mage::getConfig()->getNode('modules')->children();
        $modules = $this->_filterCodePool($modules);
        $modules = $this->_filterName($modules);
        ksort($modules);

        $table = new LeMike_DevMode_Block_Shell_Table(
            array("name"     => 'Module name',
                  "version"   => 'Used',
                  "dbVersion" => 'Installed',
                  "codePool" => "Code Pool"
            )
        );

        $resource = Mage::getResourceSingleton('core/resource');
        foreach ($modules as $moduleName => $data)
        {
            $resName = Mage::helper('lemike_devmode/core')->getResourceName($moduleName);
            $number  = $resource->getDbVersion($resName);
            $table->tableRowAdd(array("name" => $moduleName, 'dbVersion' => $number) + ((array)$data));
        }

        $table->legend = array(
            'name'     => "The name of the module",
            'version'  => "What is stored in the cache",
            'dbVersion' => "What is stored in the db",
            'codePool' => "Where the extensions resides",
        );

        echo $table . PHP_EOL;
    }


    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return parent::usageHelp() . PHP_EOL . <<<USAGE

  --codePool local          Get only the modules in the "local" code pool.
                            Also works with "core" and "community".
  --name LeMike             Get all modules beginning with "LeMike".

  Filters like --codePool and --name can be combined.
USAGE;
    }


    /**
     * .
     *
     * @param $modules
     * @return array
     */
    protected function _filterCodePool($modules)
    {
        if (!$this->getArg('codePool'))
        {
            return $modules;
        }

        $result = array();
        foreach ($modules as $name => $data)
        {
            if ($this->getArg('codePool') == $data->codePool)
            {
                $result[$name] = $data;
            }
        }

        return $result;
    }


    protected function _filterName($modules)
    {
        if (!$this->getArg('name'))
        {
            return $modules;
        }

        $result = array();
        foreach ($modules as $name => $data)
        {
            if (strpos($name, $this->getArg('name')) !== false)
            {
                $result[$name] = $data;
            }
        }

        return $result;
    }
}

echo LeMike_DevMode_Shell_ListRewrites::LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_ListRewrites();
$cmd->run();

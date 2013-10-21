<?php
/**
 * Contains class LeMike_DevMode_Shell_ListRewrites.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

require_once 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_AdminPassword.
 *
 * Change password for admin.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Shell_CoreModules extends Mage_Shell_Abstract
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

        /** @var LeMike_DevMode_Model_Core_Resource $model */
        $model     = Mage::getSingleton('lemike_devmode/core_resource');
        $moduleSet = $model->getModuleSet();
        $moduleSet = $this->_filterCodePool($moduleSet);
        $moduleSet = $this->_filterName($moduleSet);
        ksort($moduleSet);

        $table = new LeMike_DevMode_Block_Shell_Table(
            array("name"          => 'Module name',
                  "version"       => 'Cached',
                  "dbVersion"     => 'Installed',
                  "configVersion" => 'Filesystem',
                  "codePool"      => "Code Pool"
            ),
            $moduleSet
        );

        $table->legend = array(
            'name'          => "The name of the module",
            'version'       => "What is stored in the cache",
            'dbVersion'     => "What is stored in the db",
            'configVersion' => "The version in the according config.xml",
            'codePool'      => "Where the extensions resides",
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
     * Filter an array of modules by codePool argument.
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
            if ($this->getArg('codePool') == $data['codePool'])
            {
                $result[$name] = $data;
            }
        }

        return $result;
    }


    /**
     * Filter an array of modules by name argument.
     *
     * @param $modules
     * @return array
     */
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

echo LeMike_DevMode_Shell_CoreModules::LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_CoreModules();
$cmd->run();

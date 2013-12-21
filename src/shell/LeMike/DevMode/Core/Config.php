<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  mage_devmode
 * @package   Config.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     $VERSION$
 */

/**
 * Manipulate the config.
 *
 * @category  mage_devmode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     $VERSION$
 */
class DevMode_Core_Config extends DelegateCommand
{
    public function _getPathByNode($node)
    {
        $name = '';
        do
        {
            $name = $node->getName() . '/' . $name;
        } while ($node = $node->getParent());

        return rtrim($name, '/');
    }


    /**
     * Get a specific node or XML-Path.
     *
     * Usage: get {XML-Path}
     *
     * XML-Path:
     *      - "//base_url" lists all settings of base_url
     *      - "//region/state_required"
     *
     * @return void
     */
    public function getAction()
    {
        $args = $this->getParameter()->getArguments();
        $path = array_shift($args);

        $this->_loadMagento();

        $nodeSet = Mage::getConfig()->getXpath($path);

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'node'  => "Node",
                 'value' => "Value"
            )
        );

        foreach ($nodeSet as $node)
        {
            /** @var Mage_Core_Model_Config_Element $node */

            $_absolutePath = str_replace('config/', '', $this->_getPathByNode($node));

            $table->tableRowAdd(
                  array(
                       'node'  => $_absolutePath,
                       'value' => current($node),
                  )
            );
        }

        echo $table;
    }


    /**
     * Show what is in the core_config_data.
     *
     * Filter:
     *      --scope Pattern for scope (e.g. "def" or ".*a")
     *      --path  Pattern for path (e.g. "url" or ".*a")
     *      --value  Pattern for value (e.g. "http" or ".*a")
     *
     * @return void
     */
    public function listAction()
    {
        $scopeRegExp = $this->getParameter()->getOption('scope', '.*');
        $pathRegExp  = $this->getParameter()->getOption('path', '.*');
        $valueRegExp = $this->getParameter()->getOption('value', '.*');

        $collection = $this->_getCollection();

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'config_id' => 'ID',
                 'scope'     => 'Scope',
                 'scope_id'  => 'Scope ID',
                 'path'      => 'Path',
                 'value'     => 'value',
            ),
            array(),
            array(
                 'config_id' => 'As in database',
                 'scope'     => 'default, on website or in store',
                 'scope_id'  => 'The specific website or store-view',
                 'path'      => 'XML-Path',
            )
        );

        foreach ($collection as $data)
        {
            $data = $data->toArray();

            if (!$this->_filterMatch($data['scope'], $scopeRegExp)
                || !$this->_filterMatch($data['path'], $pathRegExp)
                || !$this->_filterMatch($data['value'], $valueRegExp)
            )
            {
                continue;
            }

            $data['path'] = $data['scope'] ? $data['scope'] . DS . $data['path'] : '';

            $table->tableRowAdd($data);
        }

        echo "Stored in the database: " . PHP_EOL;
        echo PHP_EOL;
        echo $table;
        echo PHP_EOL;
        echo PHP_EOL;
        echo 'To get all nodes try `get "//node()"` (very slow). d';
    }


    /**
     * Set a config by path.
     *
     * Usage: set NODE VALUE
     *
     * @return void
     */
    public function setAction()
    {
        $args  = $this->getParameter()->getArguments();
        $path  = array_shift($args);
        $value = array_shift($args);

        $this->_loadMagento();

        $nodeSet = Mage::getConfig()->getXpath($path);

        foreach ($nodeSet as $node)
        {
            $_absolutePath = str_replace('config/', '', $this->_getPathByNode($node));

            $tmp   = explode('/', $_absolutePath);
            $scope = array_shift($tmp);

            $scopeId = 0;

            switch ($scope)
            {
                case 'default':
                    $scopeId = 0;
                    break;
                case 'stores':
                    $code    = array_shift($tmp);
                    $scopeId = (int) Mage::getConfig()
                                         ->getNode($scope . '/' . $code . '/system/store/id');
                    break;
                case 'websites':
                    $code    = array_shift($tmp);
                    $scopeId = (int) Mage::getConfig()
                                         ->getNode($scope . '/' . $code . '/system/website/id');
                    break;
                default:
                    continue;
            }

            $_relativePath = implode('/', $tmp);

            Mage::getConfig()->saveConfig($_relativePath, $value, $scope, $scopeId);

            echo "$_absolutePath is now $value" . PHP_EOL;
        }
    }


    /**
     * Collection for the config model.
     *
     * @return object
     */
    protected function _getCollection()
    {
        return $this->_getModel()->getCollection();
    }


    /**
     * Get the config model.
     *
     * @return Mage_Core_Model_Config_Data
     */
    protected function _getModel()
    {
        $this->_loadMagento();

        return Mage::getModel('core/config_data');
    }
}

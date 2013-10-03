<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    Toolbox.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class Toolbox.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Block_Toolbox extends Mage_Core_Block_Template
{
    const POSITION_ACTION = 'action';

    const POSITION_CONTROLLER = 'controller';

    const POSITION_MODULE = 'module';

    const POSITION_STORE = 'store';

    protected $_template = 'lemike/devmode/toolbox.phtml';


    /**
     * .
     *
     * @return string
     */
    public function _getControllerClassFile()
    {
        $classFile    = uc_words(get_class($this->getAction()), DIRECTORY_SEPARATOR) . '.php';
        $classFileSet = explode(DS, $classFile);

        $classFile = array_shift($classFileSet) . DS;
        $classFile .= array_shift($classFileSet) . DS;
        $classFile .= 'controllers' . DS;
        $classFile .= implode(DS, $classFileSet);

        $classFile = stream_resolve_include_path($classFile);

        return $classFile;
    }


    public function getBackendUrl($route = 'adminhtml/index/index', $param = array())
    {
        return Mage::helper('lemike_devmode/auth')->getBackendUrl($route, $param);
    }


    public function getEditPositionUrl($position, $value)
    {
        $url = null;

        switch ($position)
        {
            case self::POSITION_STORE:
                $id = Mage::getModel('core/store')->load($value, 'code')->getId();
                $url = $this->getBackendUrl(
                    'adminhtml/system_store/editStore',
                    array('store_id' => $id)
                );
                break;
            case self::POSITION_ACTION:
            case self::POSITION_CONTROLLER:
                $classFile = $this->_getControllerClassFile();

                if ($classFile && Mage::helper('lemike_devmode/config')->isIdeRemoteCallEnabled())
                {
                    // find the line where the action resides, at least line 1 if not found
                    $line = max(Mage::helper('lemike_devmode/toolbox')->getLineNumber(
                        $classFile,
                        '@n\s' . preg_quote($value) . 'Action@'
                    ), 1);

                    // ajax for remote call
                    $url = "#\" onclick=\"jQuery.ajax('" .
                    $url = Mage::helper('lemike_devmode/toolbox')->getIdeUrl($classFile, $line);
                    $url .= "');";
                }
                break;
        }

        return $url;
    }


    /**
     * Receive all used layout handles.
     *
     * @param bool $withModule With those from the module (default: false)
     *
     * @return array
     */
    public function getLayoutHandles($withModule = false)
    {
        $layoutHandles = $this->getLayout()->getUpdate()->getHandles();

        if (!$withModule)
        { // do not allow custom layout handle from this module
            foreach ($layoutHandles as $key => $handle)
            {
                if (strpos($handle, LeMike_DevMode_Helper_Data::MODULE_ALIAS) === 0)
                {
                    unset($layoutHandles[$key]);
                }
            }
        }

        return $layoutHandles;
    }


    /**
     * Get store code, module, controller and action that has been called.
     *
     * @return array
     */
    public function getPosition()
    {
        $position = array(
            self::POSITION_STORE      => Mage::app()->getStore()->getCode(),
            self::POSITION_MODULE     => Mage::app()->getRequest()->getModuleName(),
            self::POSITION_CONTROLLER => Mage::app()->getRequest()->getControllerName(),
            self::POSITION_ACTION     => Mage::app()->getRequest()->getActionName(),
        );

        return array_filter($position);
    }


    public function helper($name = 'lemike_devmode/toolbox')
    {
        return parent::helper($name);
    }
}

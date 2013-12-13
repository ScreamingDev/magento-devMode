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
 * @package   LeMike\DevMode\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Toolbox.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Block_Toolbox extends LeMike_DevMode_Block_Template
{
    const POSITION_ACTION = 'action';

    const POSITION_CONTROLLER = 'controller';

    const POSITION_MODULE = 'module';

    const POSITION_STORE = 'store';

    /** @var string Default template for this block. */
    protected $_template = 'lemike/devmode/toolbox.phtml';

    /**
     * Caching the used layout handles.
     *
     * @var array
     */
    protected $_usedLayoutHandles;


    /**
     * Get the controller class file path to the current action.
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


    /**
     * Get the backend url to a route with secret key.
     *
     * @param string $route Route to serve.
     * @param array  $param Additional params.
     *
     * @return string
     */
    public function getBackendUrl($route = 'adminhtml/index/index', $param = array())
    {
        /** @var LeMike_DevMode_Helper_Auth $helperAuth */
        $helperAuth = Mage::helper('lemike_devmode/auth');

        return (string) $helperAuth->getBackendUrl($route, $param);
    }


    /**
     * Add a node to the current URL but with inverse value.
     *
     * @param string $nodePath XML-Path to config.
     *
     * @return string A full URI.
     */
    public function getConfigSwitchQuery($nodePath)
    {
        $currentValue = Mage::app()->getStore()->getConfig($nodePath);
        $request      = clone $this->getRequest();

        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');

        $request->setQuery(
                $helperConfig->nodeToUrl($nodePath),
                !$currentValue
        );

        return http_build_query($request->getQuery());
    }


    /**
     * URL to edit within the current scope.
     *
     * @param string $position See self::POSITION_*
     * @param string $value    The value as present in the request.
     *
     * @return string
     */
    public function getEditPositionUrl($position, $value)
    {
        $url = '';

        switch ($position)
        {
            case self::POSITION_STORE:
                $id  = Mage::getModel('core/store')->load($value, 'code')->getId();
                $url = $this->getBackendUrl(
                            'adminhtml/system_store/editStore',
                            array('store_id' => $id)
                );
                break;
            case self::POSITION_ACTION:
            case self::POSITION_CONTROLLER:
                $classFile = $this->_getControllerClassFile();

                /** @var LeMike_DevMode_Helper_Config $helperConfig */
                $helperConfig = Mage::helper('lemike_devmode/config');

                if ($classFile && $helperConfig->isIdeRemoteCallEnabled())
                {
                    // find the line where the action resides, at least line 1 if not found

                    /** @var LeMike_DevMode_Helper_Toolbox $helperToolbox */
                    $helperToolbox = Mage::helper('lemike_devmode/toolbox');

                    $line = max(
                        $helperToolbox->getLineNumber(
                                      $classFile,
                                      '@n\s' . preg_quote($value) . 'Action@'
                        ),
                        1
                    );

                    // ajax for remote call
                    $url = "#\" onclick=\"jQuery.ajax('" .
                           $url = $helperToolbox->getIdeUrl($classFile, $line);
                    $url .= "');";
                }
                break;
        }

        return $url;
    }


    /**
     * Singleton of the enhanced core layout model.
     *
     * @return LeMike_DevMode_Model_Core_Layout
     */
    public function getLayoutModel()
    {
        return Mage::getSingleton($this->getModuleAlias('/core_layout'));
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


    /**
     * Detailed information about the used layout handles.
     *
     * @return void
     */
    public function getRichLayoutHandles()
    {
        /** @var LeMike_DevMode_Model_Core_Layout $layout */
        $layout = $this->getLayoutModel();

        return $layout->toAssocArray();
    }


    public function arrayToXml($arr = array(), $indent = '')
    {
        $xml = '';

        if (is_string($arr))
        {
            return $indent . $arr . "\n";
        }

        foreach ($arr as $tag => $inner)
        {
            if ($tag == '@attributes')
            {
                continue;
            }

            $xml .= $indent . '<' . $tag;
            if (isset($inner['@attributes']) && is_array($inner['@attributes']))
            {
                foreach ($inner['@attributes'] as $attribute => $value)
                {
                    $xml .= ' ' . $attribute . '="' . addslashes($value) . '"';
                }

                unset($inner['@attributes']);
            }

            if (count($inner) > 0)
            {
                $xml .= ">\n";
                $xml .= $this->arrayToXml($inner, $indent . '    ');
                $xml .= $indent . "</" . $tag . ">\n";
            }
            else
            {
                $xml .= "/>\n";
            }
        }

        return $xml;
    }


    /**
     * Receive all used layout handles.
     *
     * @deprecated 1.0.0
     *
     * @param bool $withModule With those from the module (default: false)
     *
     * @return array
     */
    public function getUsedLayoutHandles($withModule = false)
    {
        if (!$this->_usedLayoutHandles[$withModule])
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

            $this->_usedLayoutHandles[$withModule] = $layoutHandles;
        }

        return $this->_usedLayoutHandles[$withModule];
    }


    public function getRichUsedLayoutHandles($withModule = false)
    {
        $richLayoutHandles = $this->getRichLayoutHandles();

        $richUsedLayoutHandles = array();
        foreach ($this->getUsedLayoutHandles() as $handle)
        {
            $richUsedLayoutHandles[$handle] = array();

            if (isset($richLayoutHandles[$handle]))
            {
                $richUsedLayoutHandles[$handle] = $richLayoutHandles[$handle];
            }
        }

        return $richUsedLayoutHandles;
    }


    /**
     * Get the default toolbox helper or some other.
     *
     * By default this will return the LeMike_DevMode_Helper_Toolbox.
     * If any other is wanted then give it's alias.
     *
     * @param string $name Alias for the helper.
     *
     * @return \Mage_Core_Helper_Abstract
     */
    public function helper($name = 'lemike_devmode/toolbox')
    {
        return parent::helper($name);
    }
}

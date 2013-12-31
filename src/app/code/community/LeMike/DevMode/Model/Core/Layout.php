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
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     $VERSION$
 */

/**
 * Class Layout.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     $VERSION$
 */
class LeMike_DevMode_Model_Core_Layout
{
    protected $_area = null;

    protected $_layoutXml = null;

    protected $_package = null;

    protected $_theme = null;


    /**
     * Create a new layout information model.
     *
     * @param string $area    The area of the layout.
     * @param string $package The package of this layout.
     * @param string $theme   The theme of the layout.
     */
    public function __construct($area = null, $package = null, $theme = null)
    {
        /* @var $design Mage_Core_Model_Design_Package */
        $design = Mage::getSingleton('core/design_package');

        if (!$area)
        {
            $area = $design->getArea();
        }

        $this->setArea($area);

        if (!$package)
        {
            $package = $design->getPackageName();
        }

        $this->setPackage($package);

        if (!$theme)
        {
            $theme = $design->getTheme($area);
        }

        $this->setTheme($theme);
    }


    /**
     * Get the current area.
     *
     * @return string
     */
    public function getArea()
    {
        return $this->_area;
    }


    /**
     * Receive a layout string by preserving update files.
     *
     * @param string[] $updateFiles Some names of update files.
     *
     * @return string
     */
    public function getLayoutStringByUpdateFiles($updateFiles)
    {
        $subst  = $this->_getSubstitute();
        /** @var Mage_Core_Model_Design_Package $design */
        $design = Mage::getSingleton('core/design_package');

        $layoutStr = '';
        foreach ($updateFiles as $file)
        {
            $filename = $design->getLayoutFilename(
                               $file,
                               array(
                                    '_area'    => $design->getArea(),
                                    '_package' => $design->getPackageName(),
                                    '_theme'   => $design->getTheme($design->getArea())
                               )
            );
            if (!is_readable($filename))
            {
                continue;
            }
            $fileStr = file_get_contents($filename);
            $fileStr = str_replace($subst['from'], $subst['to'], $fileStr);

            /** @var Varien_Simplexml_Element $fileXml */
            $fileXml = simplexml_load_string($fileStr, 'Varien_Simplexml_Element');
            if (!$fileXml instanceof SimpleXMLElement)
            {
                continue;
            }
            $layoutStr .= '<file name="' . $filename . '">' . $fileXml->innerXml() . '</file>';
        }

        return $layoutStr;
    }


    /**
     * Get the current layout as XML.
     *
     * @return null|Varien_Simplexml_Element
     */
    public function getLayoutXml()
    {
        if (!$this->_layoutXml)
        {
            $updateFiles = $this->getUpdateFiles($this->getArea(), 1);

            $layoutStr = $this->getLayoutStringByUpdateFiles($updateFiles);

            $this->_layoutXml =
                simplexml_load_string(
                    '<layouts>' . $layoutStr . '</layouts>',
                    'Varien_Simplexml_Element'
                );
        }

        return $this->_layoutXml;
    }


    /**
     * Get the current used package.
     *
     * @return string
     */
    public function getPackage()
    {
        return $this->_package;
    }


    /**
     * Get the current used theme.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->_theme;
    }


    /**
     * Get the update files of a specific area.
     *
     * @param string $area    The area to get update files from.
     * @param int    $storeId A store Id to lookup layout updates in.
     *
     * @return array
     */
    public function getUpdateFiles($area, $storeId = null)
    {
        /** @var Mage_Core_Model_Config_Element $updatesRoot */
        $updatesRoot = Mage::app()->getConfig()->getNode($area . '/layout/updates');
        Mage::dispatchEvent(
            'core_layout_update_updates_get_after',
            array('updates' => $updatesRoot)
        );

        $updateFiles = array();
        foreach ($updatesRoot->children() as $updateNode)
        {
            if ($updateNode->file)
            {
                $module = $updateNode->getAttribute('module');
                if ($module &&
                    Mage::getStoreConfigFlag('advanced/modules_disable_output/' . $module, $storeId)
                )
                {
                    continue;
                }
                $updateFiles[] = (string) $updateNode->file;
            }
        }

        return $updateFiles;
    }


    /**
     * Reset the layout information (XML).
     *
     * @return void
     */
    public function reset()
    {
        $this->_layoutXml = null;
    }


    /**
     * Use a different area.
     *
     * @param string $area The area to use for layout information.
     *
     * @return void
     */
    public function setArea($area)
    {
        $this->_area = $area;
    }


    /**
     * Change the package name of this layout.
     *
     * @param string $package Name of a package.
     *
     * @return void
     */
    public function setPackage($package)
    {
        $this->_package = $package;
    }


    /**
     * Change the theme of this layout.
     *
     * @param string $theme New theme name.
     *
     * @return void
     */
    public function setTheme($theme)
    {
        $this->_theme = $theme;
    }


    /**
     * Layout as array from XML.
     *
     * @deprecated 0.5.0 ::toAssocArray will become ::toArray
     *
     * @return mixed
     */
    public function toArray()
    {
        $fileSet = json_decode(json_encode($this->getLayoutXml()), true);

        $list = array();
        foreach ($fileSet as $file)
        {
            foreach ($file as $layoutDefinition)
            {
                $filePath = str_replace(
                    Mage::getBaseDir(),
                    '',
                    $layoutDefinition['@attributes']['name']
                );
                $filePath = ltrim($filePath, DS);
                unset($layoutDefinition['@attributes']);

                foreach ($layoutDefinition as $layoutHandle => $entry)
                {
                    $list[$layoutHandle][$filePath] = $entry;
                }
            }
        }

        return $list;
    }


    /**
     * Associative array.
     *
     * @deprecated 0.5.0 ::toAssocArray will become ::toArray
     *
     * @return array
     */
    public function toAssocArray()
    {
        return $this->toArray();
    }


    /**
     * Get substitution for layout-xml template variables.
     *
     * @return mixed
     */
    protected function _getSubstitute()
    {
        $subst = array('from' => array(), 'to' => array());

        foreach (Mage::getConfig()->getPathVars() as $k => $v)
        {
            $subst['from'][] = '{{' . $k . '}}';
            $subst['to'][]   = $v;
        }

        return $subst;
    }
}

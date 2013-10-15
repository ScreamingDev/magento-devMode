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
 * @package   Layout.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since     $VERSION$
 */

/**
 * Class Layout.
 *
 * @category  mage_devmode
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
     * @return null
     */
    public function getArea()
    {
        return $this->_area;
    }


    /**
     * .
     *
     * @param $updateFiles
     *
     * @return string
     */
    public function getLayoutStringByUpdateFiles($updateFiles)
    {
        $subst  = $this->_getSubstitute();
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
     * Associative array.
     *
     * @deprecated 0.5.0 ::toAssocArray will become ::toArray
     *
     * @return array
     */
    public function toAssocArray()
    {
        $fileSet = $this->toArray();

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
     * Layout as array from XML.
     *
     * @deprecated 0.5.0 ::toAssocArray will become ::toArray
     *
     * @return mixed
     */
    public function toArray()
    {
        return json_decode(json_encode($this->getLayoutXml()), true);
    }


    /**
     * @return null
     */
    public function getPackage()
    {
        return $this->_package;
    }


    /**
     * @return null
     */
    public function getTheme()
    {
        return $this->_theme;
    }


    /**
     * Get the update files of a specific area.
     *
     * @param      $area
     * @param null $storeId
     *
     * @return array
     */
    public function getUpdateFiles($area, $storeId = null)
    {
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


    public function reset()
    {
        $this->_layoutXml = null;
    }


    /**
     * @param null $area
     */
    public function setArea($area)
    {
        $this->_area = $area;
    }


    /**
     * @param null $package
     */
    public function setPackage($package)
    {
        $this->_package = $package;
    }


    /**
     * @param null $theme
     */
    public function setTheme($theme)
    {
        $this->_theme = $theme;
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

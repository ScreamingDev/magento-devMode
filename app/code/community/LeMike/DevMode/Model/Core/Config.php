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
 * @package    Config.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */

/**
 * Class Config.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */
class LeMike_DevMode_Model_Core_Config extends Mage_Core_Model_Abstract
{
    /**
     * List all rewrites with their according classes.
     *
     * @param SimpleXMLElement $childNode (default: current config)
     * @param string $basePath
     * @return array [path => [overriding class, ...]]
     */
    public function getRewritePathToClassName($childNode = null, $basePath = '')
    {
        $rewritesToPath = array();

        if (null == $childNode)
        {
            $childNode = $this->getConfigXML();
        }

        foreach ($childNode as $key => $node)
        {
            /** @var Mage_Core_Model_Config_Element $node */
            if ($key == 'rewrite')
            {
                foreach ($node as $old => $new)
                {
                    $tmpPath = $basePath . '/' . $key . '/' . $old;

                    if (!isset($rewritesToPath[$tmpPath]))
                    {
                        $rewritesToPath[$tmpPath] = array();
                    }

                    $rewritesToPath[$tmpPath][] = (string)$new;
                    $tmpPath                    = null;
                }
            }
            elseif ($node->hasChildren())
            {
                $rewritesToPath += $this->getRewritePathToClassName($node, ltrim($basePath . '/' . $key, '/'));
            }
        }

        return $rewritesToPath;
    }


    public function getConfigXML()
    {
        /** @var Mage_Core_Model_Config $config */
        $config = $this->_getConfig();

        $reflectObject = new ReflectionObject($config);
        $prop          = $reflectObject->getProperty('_xml');
        $prop->setAccessible(true);

        return $prop->getValue($config);
    }


    /**
     * .
     *
     * @return Mage_Core_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::app()->getConfig();
    }
}

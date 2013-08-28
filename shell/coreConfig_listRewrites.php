<?php
/**
 * Changing Admin password class LeMike_DevMode_Shell_AdminPassword.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.1.0
 */

const LOADING_MAGENTO = "Loading Magento ...";

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
    public function _rewritesToPath($childNode, $basePath = '')
    {
        $rewritesToPath = array();

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
                $rewritesToPath = $rewritesToPath + $this->_rewritesToPath($node, ltrim($basePath . '/' . $key, '/'));
            }
        }

        return $rewritesToPath;
    }


    /**
     * .
     *
     * @return void
     */
    public function getConfigAsArray()
    {
        /** @var Mage_Core_Model_Config $config */
        $config = Mage::getConfig();

        $reflectObject = new ReflectionObject($config);
        $prop          = $reflectObject->getProperty('_cacheLoadedSections');
        $prop->setAccessible(true);
        $array = $prop->getValue($config);

        return $array;
    }


    public function getConfigXML()
    {
        /** @var Mage_Core_Model_Config $config */
        $config = Mage::getConfig();

        $reflectObject = new ReflectionObject($config);
        $prop          = $reflectObject->getProperty('_xml');
        $prop->setAccessible(true);

        return $prop->getValue($config);
    }


    public function getPathToClassName($basePath = '')
    {
        $rewritesToPath = $this->_rewritesToPath($this->getConfigXML());
        ksort($rewritesToPath);

        return $rewritesToPath;
    }


    /**
     * Change password for admin.
     *
     */
    public function run()
    {
        echo "\r" . str_repeat(' ', strlen(LOADING_MAGENTO)) . "\r";

        Mage::app();

        $pathToClassName = $this->getPathToClassName();

        $longestPath  = 0;
        $longestValue = 0;
        foreach ($pathToClassName as $key => $classSet)
        {
            if (strlen($key) > $longestPath)
            {
                $longestPath = strlen($key);
            }

            $classLength = max(array_map('strlen', $classSet));
            if ($classLength > $longestValue)
            {
                $longestValue = $classLength;
            }
        }

        echo PHP_EOL;
        echo ' ' . str_pad('Config path', $longestPath) . ' | ' . str_pad('New class', $longestValue) . PHP_EOL;
        echo '-' . str_repeat('-', $longestPath) . '-+-' . str_repeat('-', $longestValue) . '-' . PHP_EOL;

        $hasDoubleRewrites = false;
        foreach ($pathToClassName as $path => $classSet)
        {
            $suffix = $prefix = (count($classSet) > 1) ? '*' : ' ';
            if ($suffix != ' ')
            {
                $hasDoubleRewrites = true;
            }
            foreach ($classSet as $class)
            {
                echo $prefix . str_pad($path, $longestPath) . ' | '
                     . str_pad($class, $longestValue) . $suffix . PHP_EOL;
            }
        }

        echo PHP_EOL;
        if ($hasDoubleRewrites)
        {
            echo ' WARNING! You have a conflict in rewrites. We marked them with a star (*).';
        }
        echo ' Missing something? Remember to clean the cache.';
        echo PHP_EOL;
    }
}

echo LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_ListRewrites();
$cmd->run();

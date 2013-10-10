<?php
/**
 * Changing Admin password class LeMike_DevMode_Shell_AdminPassword.
 *
 * @category  Magento-devMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/Magento-devMode
 * @since     0.3.0
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
    /**
     * Change password for admin.
     *
     */
    public function run()
    {
        echo "\r" . str_repeat(' ', strlen(LOADING_MAGENTO)) . "\r";

        Mage::app();

        $pathToClassName = Mage::getModel('lemike_devmode/core_config')->getRewritePathToClassName();

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

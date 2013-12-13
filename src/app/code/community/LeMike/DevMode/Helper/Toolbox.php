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
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Helper_Toolbox extends LeMike_DevMode_Helper_Abstract
{
    /**
     * Receive the URI to activate a file in the local IDE.
     *
     * @param string $file The file to open.
     * @param int    $line Line the IDE shall jump to (if possible).
     *
     * @return string
     */
    public function getIdeUrl($file, $line = 1)
    {
        /** @var LeMike_DevMode_Helper_Config $helperConfig */
        $helperConfig = Mage::helper('lemike_devmode/config');

        $template = $helperConfig->getRemoteCallUrlTemplate();

        return sprintf($template, urlencode($file), urlencode($line));
    }


    /**
     * Get the line number of the first line in a file matching a given regex
     * Not the nicest solution, but probably the fastest
     *
     * @param $file
     * @param $regex
     *
     * @return bool|int
     */
    public function getLineNumber($file, $regex)
    {
        $i         = 0;
        $lineFound = false;
        $handle    = @fopen($file, 'r');
        if ($handle)
        {
            while (($buffer = fgets($handle, 4096)) !== false)
            {
                $i++;
                if (preg_match($regex, $buffer))
                {
                    $lineFound = true;
                    break;
                }
            }
            fclose($handle);
        }

        return $lineFound ? $i : false;
    }
}

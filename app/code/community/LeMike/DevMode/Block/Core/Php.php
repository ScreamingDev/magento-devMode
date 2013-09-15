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
class LeMike_DevMode_Block_Core_Php extends Mage_Core_Block_Template
{
    protected $_template = 'lemike/devmode/core/php.phtml';


    /**
     * Get only the php info table.
     *
     * @return mixed
     */
    public function getPhpInfo()
    {
        ob_start();
        phpinfo();
        $phpInfo = ob_get_clean();

        $start   = strpos($phpInfo, '<table');
        $length  = strrpos($phpInfo, '</table>') - $start + 8;
        $phpInfo = substr($phpInfo, $start, $length);

        $phpInfo = preg_replace('/width="[\d]+"/is', '', $phpInfo);

        return $phpInfo;
    }
}

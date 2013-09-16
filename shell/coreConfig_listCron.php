<?php
/**
 * Changing Admin password class LeMike_DevMode_Shell_AdminPassword.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.4.0
 */

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
 * @since      0.4.0
 */
class LeMike_DevMode_Shell_CoreConfigCron extends Mage_Shell_Abstract
{

    const LOADING_MAGENTO = "Loading Magento ...";


    /**
     * Change password for admin.
     *
     */
    public function run()
    {
        Mage::app();

        echo "\r" . str_repeat(' ', strlen(self::LOADING_MAGENTO)) . "\r";

        $data = Mage::getModel('lemike_devmode/core_config')->getCrontabJobs();

        $table = new LeMike_DevMode_Block_Shell_Table(
            array("alias"     => 'Alias',
                  "cron_expr" => 'Expression',
                  "class"  => 'Class',
                  "method" => 'Method',
            ),
            $data
        );

        $table->legend = array(
            'alias'     => "Node in the config.xml",
            'cron_expr' => "When to run",
            'run'       => "Alias to run",
            'class'     => "Used class",
            'method'    => "Method to run",
        );

        echo $table;
    }
}

echo LeMike_DevMode_Shell_CoreConfigCron::LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_CoreConfigCron();
$cmd->run();

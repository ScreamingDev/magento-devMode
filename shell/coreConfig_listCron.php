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

        $jobSet = (array)Mage::app()->getConfig()->getNode('crontab/jobs');

        foreach ($jobSet as $alias => $moduleConfig)
        {
            /** @var Mage_Core_Model_Config_Element $moduleConfig */
            $model        = $moduleConfig->run->model;
            $data[$alias] = array(
                'cron_expr' => (string)$moduleConfig->schedule->cron_expr,
                'run'    => (string)$model,
                'class'  => (string)get_class(Mage::getModel(strtok($model, ':'))),
                'method' => (string)ltrim(strtok(':'), ':'),
            );
        }

        $table = new LeMike_DevMode_Block_Shell_Table(
            array("alias"     => 'Alias',
                  "cron_expr" => 'Expression',
                  "class"  => 'Class',
                  "method" => 'Method',
            )
        );

        $table->legend = array(
            'alias'     => "Node in the config.xml",
            'cron_expr' => "When to run",
            'run'       => "Alias to run",
            'class'     => "Used class",
            'method'    => "Method to run",
        );

        foreach ($data as $alias => $row)
        {
            $row['alias'] = $alias;
            $table->tableRowAdd($row);
        }

        echo $table;
    }
}

echo LeMike_DevMode_Shell_CoreConfigCron::LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_CoreConfigCron();
$cmd->run();

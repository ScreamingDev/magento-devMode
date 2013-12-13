<?php
/**
 * Changing Admin password class LeMike_DevMode_Shell_AdminPassword.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

require_once 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_AdminPassword.
 *
 * Change password for admin.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Shell_CoreConfigObserver extends Mage_Shell_Abstract
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

        /** @var LeMike_DevMode_Model_Core_Config $modelCoreConfig */
        $modelCoreConfig = Mage::getModel('lemike_devmode/core_config');

        $data = $modelCoreConfig->getObserver();

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'scope'  => 'Scope',
                 'event'  => 'Event',
                 'model'  => 'Model',
                 'method' => 'Method',
            )
        );

        foreach ($data as $scope => $eventSet)
        {
            foreach ($eventSet as $event => $aliasSet)
            {
                foreach ($aliasSet as $observerSet)
                {
                    foreach ($observerSet as $observer)
                    {
                        $table->tableRowAdd(
                              array(
                                   'scope'  => $scope,
                                   'event'  => $event,
                                   'model'  => get_class(Mage::getModel($observer->class)),
                                   'method' => $observer->method,
                              )
                        );
                    }
                }
            }
        }

        $table->legend = array(
            'scope'  => 'Scope.',
            'event'  => 'Thrown event.',
            'model'  => 'Model that handles.',
            'method' => 'Method that will be called.',
        );

        echo $table;
    }
}

echo LeMike_DevMode_Shell_CoreConfigObserver::LOADING_MAGENTO;

$cmd = new LeMike_DevMode_Shell_CoreConfigObserver();
$cmd->run();

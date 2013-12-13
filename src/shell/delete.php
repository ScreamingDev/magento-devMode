<?php
/**
 * Delete all categories.
 *
 * PHP version 5
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

require_once 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_DeleteCategories.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Shell_DeleteCategories extends Mage_Shell_Abstract
{

    /**
     * Run script.
     *
     * @return void
     */
    public function run()
    {
        if (!$this->_args)
        {
            echo $this->usageHelp();

            return;
        }

        /** @var LeMike_DevMode_Helper_Data $helper */
        $helper = Mage::helper('lemike_devmode');
        /** @var LeMike_DevMode_Helper_Cli $cli */
        $cli    = Mage::helper('lemike_devmode/cli');
        $answer =
            $cli->ask($cli->__("Delete %s? [y/n] ", implode(' & ', array_keys($this->_args))));

        if ('y' == $answer)
        {
            if ($this->getArg('catalog_product'))
            {
                $helper->truncateModelByName('catalog/product');
                echo $cli->__('Deleted all products') . PHP_EOL;
            }

            if ($this->getArg('catalog_category'))
            {
                $helper->truncateModelByName('catalog/category');
                echo $cli->__('Deleted all categories') . PHP_EOL;
            }

            if ($this->getArg('customer_customer'))
            {
                $helper->truncateModelByName('customer/customer');
                echo $cli->__('Deleted all customer') . PHP_EOL;
            }
        }

        echo PHP_EOL;
    }


    /**
     * Show help how to use this.
     *
     * @return string
     */
    public function usageHelp()
    {
        return parent::usageHelp() . PHP_EOL . <<<HELP

  catalog_category      Truncate all categories.
  catalog_product       Will delete all products.
  customer_customer     Erases every customer.
HELP;
    }
}

$cmd = new LeMike_DevMode_Shell_DeleteCategories();
$cmd->run();

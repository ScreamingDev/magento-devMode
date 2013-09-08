<?php
/**
 * Delete all categories.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.3.0
 */

include 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_DeleteCategories.
 *
 * @category   magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-devMode
 * @since      0.3.0
 */
class LeMike_DevMode_Shell_DeleteCategories extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        if (!$this->_args)
        {
            echo $this->usageHelp();

            return;
        }

        $helper = Mage::helper('lemike_devmode');
        $cli    = Mage::helper('lemike_devmode/cli');
        $answer = $cli->ask($cli->__("Delete %s? [y/n] ", implode(' & ', array_keys($this->_args))));

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

<?php
/**
 * Contains class LeMike_DevMode_Shell_DeleteProducts.
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
 * Shell based deletion of all products.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      ${DS}VERSION${DS}
 */
class LeMike_DevMode_Shell_DeleteProducts extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        print_r($this->execute());
    }


    /**
     * Delete all products.
     *
     * @return array
     */
    public function execute()
    {
        $deleteAll = array(
            'amount'    => 0,
            'processed' => 0,
            'errors'    => array(),
        );

        $productSet          = $this->_getProductSet();
        $deleteAll['amount'] = $productSet->count();

        foreach ($productSet as $product)
        {
            /** @var Mage_Catalog_Model_Product $product */
            $productId = $product->getId();
            $product   = $product->load($productId);
            $product->delete();
            $deleteAll['processed']++;
            echo "Deleted $productId ({$deleteAll['processed']}/{$deleteAll['amount']})" . PHP_EOL;
        }

        return $deleteAll;
    }


    /**
     * Get product collection.
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getProductSet()
    {
        // Set store defaults for Magento
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        /* @var $productModel Mage_Catalog_Model_Product */
        $productModel = Mage::getModel('catalog/product');

        /* @var $productSet Mage_Catalog_Model_Resource_Product_Collection */
        $productSet = $productModel->getCollection();

        return $productSet;
    }
}

$cmd = new LeMike_DevMode_Shell_DeleteProducts();
$cmd->run();

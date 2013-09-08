<?php

include 'abstract.php';

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
     * .
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

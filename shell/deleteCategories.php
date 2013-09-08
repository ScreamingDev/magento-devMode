<?php

include 'abstract.php';

class LeMike_DevMode_Shell_DeleteCategories extends Mage_Shell_Abstract
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

        $collection          = $this->_getProductSet();
        $deleteAll['amount'] = $collection->count();

        foreach ($collection as $item)
        {
            /** @var Mage_Catalog_Model_Product $item */
            $item = $item->load($item->getId());
            $item->delete();
            $deleteAll['processed']++;

            // free some memory
            unset($item);
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
        $model = Mage::getModel('catalog/category');

        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $model->getCollection();

        return $collection;
    }
}

$cmd = new LeMike_DevMode_Shell_DeleteCategories();
$cmd->run();

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
 * @package   LeMike\DevMode\controllers\Adminhtml\LeMike\DevMode\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class DeveloperController.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\controllers\Adminhtml\LeMike\DevMode\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Adminhtml_LeMike_DevMode_Catalog_ProductsController extends
    LeMike_DevMode_Controller_Adminhtml_Controller_Action
{
    /**
     * Delete all products.
     *
     * @return void
     */
    public function deleteAllAction()
    {
        /** @var LeMike_DevMode_Helper_Data $helper */
        $helper = Mage::helper($this->getModuleAlias());
        $helper->responseJson($helper->truncateModelByName('catalog/product'));
    }


    /**
     * Clean up the stock for every product.
     *
     * @return void
     */
    public function sanitizeAllAction()
    {
        $sanitizeAll = array(
            'amount'    => 0,
            'processed' => 0,
            'errors'    => array(),
        );

        $productSet            = $this->_getProductSet();
        $sanitizeAll['amount'] = $productSet->count();

        $stockTemplate = array(
            'manage_stock' => 0,
            'is_in_stock'  => 1,
            'qty'          => 1
        );

        foreach ($productSet as $product)
        {
            /** @var Mage_Catalog_Model_Product $product */

            $stockData = $product->getData('stock_data');

            if (!$stockData && $product->getId())
            {
                $product = $product->load($product->getId());
                $product->setData('stock_data', $stockTemplate);

                try
                {
                    $product->save();
                    $sanitizeAll['processed']++;
                } catch (Exception $e)
                {
                    $sanitizeAll['errors'][$product->getId()] = $e->getMessage();
                }
            }
        }

        Mage::helper($this->getModuleAlias())->responseJson($sanitizeAll);
    }


    /**
     * Get the product collection.
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

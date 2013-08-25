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
 * @package    DeveloperController.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class DeveloperController.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Adminhtml_Developer_Catalog_ProductsController extends Mage_Adminhtml_Controller_Action
{
    public function deleteAllAction()
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
            $product = $product->load($product->getId());
            /** @var Mage_Catalog_Model_Product $product */
            $product->delete();
            $deleteAll['processed']++;
        }

        $this->_responseJson($deleteAll);
    }


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

            $stockData = $product->getStockData();

            if (!$stockData && $product->getId())
            {
                $product = $product->load($product->getId());
                $product->setStockData($stockTemplate);

                try
                {
                    $product->save();
                    $sanitizeAll['processed']++;
                } catch (Exception $e)
                {
                    $sanitizeAll['errors'][] = $e->getMessage();
                }
            }
        }

        $this->_responseJson($sanitizeAll);
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


    /**
     * .
     *
     * @param $data
     * @return void
     */
    protected function _responseJson($data)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Zend_Json_Encoder::encode($data));
    }
}

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
 * @since      $VERSION$
 */

/**
 * Class DeveloperController.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
class LeMike_DevMode_Adminhtml_Developer_Catalog_ProductsController extends Mage_Adminhtml_Controller_Action
{
    public function deleteAllAction()
    {
        $deleteAll = array(
            'before'  => 0,
            'after'   => 0,
            'deleted' => 0,
        );

        // Set store defaults for Magento
        $storeId = Mage::app()->getStore()->getId();
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        /* @var $productModel Mage_Catalog_Model_Product */
        $productModel = Mage::getModel('catalog/product');

        /* @var $productSet Mage_Catalog_Model_Resource_Product_Collection */
        $productSet          = $productModel->getCollection();
        $deleteAll['before'] = $productSet->count();

        foreach ($productSet as $product)
        {
            /** @var Mage_Catalog_Model_Product $product */
            $product->delete();
            $deleteAll['deleted']++;
        }

        $deleteAll['after'] = $deleteAll['before'] - $deleteAll['deleted'];

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Zend_Json_Encoder::encode($deleteAll));
    }
}

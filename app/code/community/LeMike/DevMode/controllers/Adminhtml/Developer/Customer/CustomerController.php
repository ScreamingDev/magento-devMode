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
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class DeveloperController.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Adminhtml_Developer_Customer_CustomerController extends Mage_Adminhtml_Controller_Action
{
    public function deleteAllAction()
    {
        $deleteAll = array(
            'amount'    => 0,
            'processed' => 0,
            'errors'    => array(),
        );

        $set                    = $this->_getSet();
        $deleteAll['amount']    = $set->count();
        $deleteAll['processed'] = Mage::helper('lemike_devmode')->truncateModel($set);

        $this->_responseJson($deleteAll);
    }


    /**
     * Get all orders.
     *
     * @return Mage_Sales_Model_Resource_Order_Collection
     */
    protected function _getSet()
    {
        // Set store defaults for Magento
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        return Mage::getModel('customer/customer')->getCollection();
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

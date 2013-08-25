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
 * @package    Observer.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class Observer.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Model_Observer extends Mage_Core_Model_Abstract
{
    public function controllerActionPredispatchCustomerAccountLoginPost($observer)
    {
        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = Mage::app()->getFrontController();

        $post = $front->getRequest()->getPost('login', array());
        if ('account' == $front->getRequest()->getRequestedControllerName()
            && 'loginPost' == $front->getRequest()->getActionName()
            && isset($post['password'])
            && $post['password'] == Mage::helper('lemike_devmode/config')->getCustomerCustomerPassword()
        )
        {
            $customer = Mage::getModel('customer/customer');
            $customer->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            $customer->loadByEmail($post['username']);
            $customerId = $customer->getId();

            if ($customerId)
            {
                /** @var Mage_Customer_Model_Session $session */
                $session = Mage::getSingleton('customer/session');
                $session->loginById($customerId);
            }
        }
    }


    public function controllerFrontSendResponseBefore($observer)
    {
        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $observer->getFront();

        if ($front->getRequest()->has('__events'))
        {
            $reflectApp        = new ReflectionObject(Mage::app());
            $reflectEventCache = $reflectApp->getProperty('_events');
            $reflectEventCache->setAccessible(true);

            $value = $reflectEventCache->getValue(Mage::app());

            $returnSet = array();
            foreach ($value as $eventSet)
            {
                $returnSet = array_merge($returnSet, array_keys($eventSet));
            }

            $front->getResponse()->setBody('<html><body><pre>' . print_r($value, true) . '</pre></body></html>');
        }
    }
} 

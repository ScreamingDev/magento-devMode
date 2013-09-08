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
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */

/**
 * Class Observer.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.2.0
 */
class LeMike_DevMode_Model_Observer extends Mage_Core_Model_Abstract
{
    public function controllerActionPredispatchCustomerAccountLoginPost($observer)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return;
        }

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
            $customer->setData('website_id', Mage::app()->getStore()->getWebsiteId());
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


    /**
     * Before init of anything the core can be changed.
     *
     * @param Varien_Event_Observer $event Information about the event.
     * @return null
     */
    public function controllerFrontInitBefore($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getData('front');

        $query = $front->getRequest()->getQuery();
        if ($query)
        { // query given: parse it
            $store = Mage::app()->getStore(null);
            foreach ($query as $field => $value)
            {
                if (0 !== strpos($field, '__') || $value === '')
                { // wrong pattern and no value: skip
                    continue;
                }

                $path = str_replace('__', '/', ltrim($field, '_'));
                if (null !== $store->getConfig($path))
                { // found some config: change it
                    $store->setConfig($path, $value);
                }
            }
        }

        return null;
    }


    public function controllerFrontSendResponseBefore($event)
    {
        if (!Mage::helper('lemike_devmode/auth')->isDevAllowed())
        {
            return;
        }

        /** @var Mage_Core_Controller_Varien_Front $front */
        $front = $event->getData('front');

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

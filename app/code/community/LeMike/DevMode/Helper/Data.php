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
 * @package    Config.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */

/**
 * Class Config.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.1.0
 */
class LeMike_DevMode_Helper_Data extends LeMike_DevMode_Helper_Abstract
{
    public function getStoreConfig($path)
    {
        return Mage::getStoreConfig($path, $this->_getStoreId());
    }


    /**
     * Delete everything within a model.
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $model
     * @return int
     */
    public function truncateCollection($model)
    {
        $processed = 0;
        foreach ($model as $entry)
        {
            /** @var Mage_Core_Model_Abstract $entry */
            $id    = $entry->getId();
            $entry = $entry->load($id);
            $entry->delete();
            $processed++;
            unset($entry);

            LeMike_DevMode_Model_Log::info($this->__("Deleted %s from %s", $id, $model->getResource()->getMainTable()));
        }

        return $processed;
    }


    /**
     * .
     *
     * @param array $data
     * @return void
     */
    public function responseJson($data)
    {
        $response = Mage::app()->getResponse();
        $response->setHeader('Content-type', 'application/json');
        $response->setBody(Zend_Json_Encoder::encode($data));
    }


    /**
     * Truncate a model by it's name.
     *
     * @param $name
     * @return array
     */
    public function truncateModelByName($name)
    {
        $deleteAll = array(
            'amount'    => 0,
            'processed' => 0,
            'errors'    => array(),
        );

        $model = Mage::getModel($name);

        /** @var Mage_Eav_Model_Entity_Collection_Abstract $collection */
        $collection             = $model->getCollection();
        $deleteAll['amount']    = $collection->count();
        $deleteAll['processed'] = Mage::helper('lemike_devmode')->truncateCollection($collection);

        return $deleteAll;
    }
}

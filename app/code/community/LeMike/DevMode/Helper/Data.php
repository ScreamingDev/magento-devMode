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
     * @param Mage_Core_Model_Abstract $model
     * @return int
     */
    public function truncateModel($model)
    {
        $processed = 0;
        foreach ($model as $entry)
        {
            $entry = $entry->load($entry->getData('id'));
            $entry->delete();
            $processed++;
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
}

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


    public function truncateModel($model)
    {
        $processed = 0;
        foreach ($model as $entry)
        {
            $entry = $entry->load($entry->getId());
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
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Zend_Json_Encoder::encode($data));
    }
}

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
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevMode_Helper_Data extends LeMike_DevMode_Helper_Abstract
{
    const MODULE_ALIAS = 'lemike_devmode';

    const MODULE_NAME = 'LeMike_DevMode';

    /** @var bool Halt the dispatch of Magento. */
    protected static $_disableMagentoDispatch = false;


    /**
     * Switch or tell if the dispatch shall be halted.
     *
     * @param bool $value Switch to tell the extension to enable or disable magento dispatch.
     *
     * @return bool
     */
    public static function disableMagentoDispatch($value = null)
    {
        if (null !== $value)
        {
            self::$_disableMagentoDispatch = (bool) $value;
        }

        return self::$_disableMagentoDispatch;
    }


    /**
     * Send data as JSON to the client.
     *
     * This will replace the body with the JSON string.
     *
     * @param array $data Information to turn into JSON.
     *
     * @return void
     */
    public function responseJson($data)
    {
        $response = Mage::app()->getResponse();
        $response->setHeader('Content-Type', 'application/json', true);
        $response->setBody(Zend_Json_Encoder::encode($data));
    }


    /**
     * Stop everything or exit.
     *
     * @param int $code Error Code to exit with.
     *
     * @return void
     */
    public function stop($code = 0)
    {
        exit($code);
    }


    /**
     * Delete everything within a model.
     *
     * @param Mage_Eav_Model_Entity_Collection_Abstract $model A collection to delete from.
     *
     * @return int
     */
    public function truncateCollection($model)
    {
        $processed = 0;
        foreach ($model as $entry)
        {
            /** @var Mage_Eav_Model_Entity_Collection_Abstract $entry */
            $id = $entry->getId();
            $entry->delete();
            $model->removeItemByKey($id);
            $processed++;

            LeMike_DevMode_Model_Log::info($this->__("Deleted %s ...", $id));
        }

        return $processed;
    }


    /**
     * Truncate a model by it's name.
     *
     * @param string $name Name / Alias of a model.
     *
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

        if (!$model)
        {
            $deleteAll['errors'][] = $this->__("Unknown model $name.");

            return $deleteAll;
        }

        /** @var Mage_Eav_Model_Entity_Collection_Abstract $collection */
        $collection             = $model->getCollection();
        $deleteAll['amount']    = $collection->count();
        $deleteAll['processed'] = $this->truncateCollection($collection);

        return $deleteAll;
    }
}

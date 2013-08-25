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
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

/**
 * Class Config.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
abstract class LeMike_DevMode_Helper_Abstract extends Mage_Core_Helper_Abstract
{
    protected $_storeId;


    protected function _getStoreConfig($path)
    {
        return Mage::getStoreConfig($path, $this->_getStoreId());
    }


    protected function _getStoreId()
    {
        if (null === $this->_storeId)
        {
            $this->_storeId = Mage::app()->getStore()->getStoreId();
        }

        return $this->_storeId;
    }
}
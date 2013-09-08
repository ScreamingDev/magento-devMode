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
class LeMike_DevMode_Block_Core_Resource extends Mage_Core_Block_Template
{
    protected $_template = 'lemike/devmode/core/resource.phtml';


    /**
     * Get only the php info table.
     *
     * @return LeMike_DevMode_Model_Core_Resource
     */
    public function getModel()
    {
        return Mage::getModel('lemike_devmode/core_resource');
    }


    public function getModuleSet()
    {
        /** @var Mage_Core_Model_Resource_Resource $model */
        return $this->getModel()->getModuleSet();
    }
}

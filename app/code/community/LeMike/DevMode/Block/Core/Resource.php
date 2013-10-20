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
 * @package   LeMike\DevMode\Block\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Block_Core_Resource extends Mage_Core_Block_Template
{
    /** @var string Default template for this block. */
    protected $_template = 'lemike/devmode/core/resource.phtml';


    /**
     * Get information about each module.
     *
     * @return Varien_Data_Collection
     */
    public function getModuleSet()
    {
        /** @var LeMike_DevMode_Model_Core_Resource $coreResource */
        $coreResource = Mage::getModel('lemike_devmode/core_resource');

        return $coreResource->getModuleSet();
    }
}

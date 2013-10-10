<?php
/**
 * Class LeMike_DevMode_Block_Template.
 *
 * PHP version 5
 *
 * @category  LeMike_DevMode_Block
 * @package   LeMike_DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/Magento-devMode
 * @since     0.1.0
 */

/**
 * Class LeMike_DevMode_Block_Template.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.1.0
 */
class LeMike_DevMode_Block_Template extends Mage_Adminhtml_Block_Template
{
    public function helper($name = LeMike_DevMode_Helper_Data::MODULE_ALIAS)
    {
        return parent::helper($name);
    }


    public function _helper()
    {
        return Mage::helper('lemike_devmode');
    }
}

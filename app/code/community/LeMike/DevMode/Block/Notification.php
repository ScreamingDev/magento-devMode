<?php
/**
 * Class LeMike_DevMode_Block_Template.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.4.0
 */

/**
 * Class LeMike_DevMode_Block_Template.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.4.0
 */
class LeMike_DevMode_Block_Notification extends LeMike_DevMode_Block_Template
{
    public function isWrongCoreModelEmail()
    {
        return !(Mage::getModel('core/email') instanceof LeMike_DevMode_Model_Core_Email);
    }


    public function isWrongCoreModelEmailTemplate()
    {
        return !(Mage::getModel('core/email_template')
                 instanceof
                 LeMike_DevMode_Model_Core_Email_Template);
    }
}

<?php
/**
 * Class LeMike_DevMode_Block_Template.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class LeMike_DevMode_Block_Template.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Block_Notification extends LeMike_DevMode_Block_Template
{
    /**
     * Check whether the email model is the correct one.
     *
     * @return bool
     */
    public function isCorrectCoreModelEmail()
    {
        return (Mage::getModel('core/email') instanceof LeMike_DevMode_Model_Core_Email);
    }


    /**
     * Check whether the email template model is the correct one.
     *
     * @return bool
     */
    public function isCorrectCoreModelEmailTemplate()
    {
        return (
            Mage::getModel('core/email_template')
            instanceof
            LeMike_DevMode_Model_Core_Email_Template
        );
    }
}

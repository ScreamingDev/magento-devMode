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
 * @package   LeMike\DevMode\Model\Core\Email
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * Class Template.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Model\Core\Email
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevMode_Model_Core_Email_Template extends Mage_Core_Model_Email_Template
{
    /**
     * Dispatch an email like configured.
     *
     * This can stop and print an email or send it like Magento would do.
     *
     * @param array|string $email     Recipients of the message.
     * @param null         $name      Names of the recipients.
     * @param array        $variables Variables for the template.
     *
     * @return bool
     */
    public function send($email, $name = null, array $variables = array())
    {
        $content = (string) $this->getProcessedTemplate($variables, true);

        /** @var LeMike_DevMode_Helper_Core $helperCore */
        $helperCore = Mage::helper('lemike_devmode/core');

        if ($helperCore->handleMail($this, $content))
        {
            $email = $this->getData('to_email');
            return parent::send($email, $name, $variables);
        }

        return true;
    }
}

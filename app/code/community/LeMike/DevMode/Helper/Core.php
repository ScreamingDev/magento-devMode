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
class LeMike_DevMode_Helper_Core extends LeMike_DevMode_Helper_Abstract
{
    public function getResourceName($moduleName)
    {
        $configXML = $this->getConfigXML($moduleName);
        if ($configXML)
        {
            $node = $configXML->getNode('global/resources');
            if ($node)
            {
                $moduleGlobalResources = $node->asArray();
                reset($moduleGlobalResources);

                return key($moduleGlobalResources);
            }
        }

        return '';
    }


    /**
     * Handle a mail.
     *
     * @param Mage_Core_Model_Email $mail
     * @return void
     */
    public function handleMail($mail)
    {
        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if (!Mage::helper('lemike_devmode/config')->isMailAllowed())
        { // no mail allowed set: show content
            if ($mail instanceof Zend_Mail)
            {
                $bodyHtml        = $mail->getBodyHtml();
                $reflectBodyMail = new ReflectionObject($bodyHtml);
                $reflectContent  = $reflectBodyMail->getProperty('_content');
                $reflectContent->setAccessible(true);
                $content = $reflectContent->getValue($bodyHtml);
            }
            else
            {
                $content = $mail->getBody();
            }

            echo $content;
            Mage::helper('lemike_devmode')->disableMagentoDispatch(true);

            return false;
        }

        if ($recipient)
        { // recipient is set: send mail to him
            LeMike_DevMode_Model_Log::info(
                'Reroute mail from "' . $mail->getToMail() . '" to "' . $recipient . '".'
            );
            $mail->setToEmail($recipient);
        }

        return $mail;
    }


    public function getConfigXML($moduleName)
    {
        $config  = Mage::app()->getConfig();
        $xmlPath = $config->getModuleDir('etc', $moduleName) . DS . 'config.xml';

        if (file_exists($xmlPath))
        {
            return new Varien_Simplexml_Config($xmlPath);
        }

        return null;
    }


    public function getAvailableVersion($moduleName)
    {
        $configXML = $this->getConfigXML($moduleName);

        if ($configXML)
        {
            $node = $configXML->getNode('modules' . DS . $moduleName);
            if ($node)
            {
                $module = $node->asArray();

                return $module['version'];
            }
        }

        return '';
    }
}

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
 * @since     0.3.0
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
 * @since     0.3.0
 */
class LeMike_DevMode_Helper_Core extends LeMike_DevMode_Helper_Abstract
{
    /**
     * Get the version of a module/extension as written in the used configXML.
     *
     * @param string $moduleName Alias of a module.
     *
     * @return string
     */
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


    /**
     * Get the config for a module.
     *
     * @param string $moduleName Alias of a module.
     *
     * @return Varien_Simplexml_Config
     */
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


    /**
     * Search configXML for the resource alias of a module.
     *
     * You find this in the `config.xml` of the extension in the XML-Path `global/resources`.
     *
     * @param string $moduleName Alias of a module.
     *
     * @return string Resource-Name of the module
     */
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

                return (string) key($moduleGlobalResources);
            }
        }

        return '';
    }


    /**
     * Handle a mail.
     *
     * @param Mage_Core_Model_Email|Zend_Mail|Varien_Object $mail    Some mail object.
     *
     * @param null|string                                   $content Body of the mail.
     *
     * @throws Exception When no processed content is given for email template.
     * @return boolean|Mage_Core_Model_Email|Zend_Mail
     */
    public function handleMail($mail, $content = null)
    {
        /** @var LeMike_DevMode_Helper_Config $configHelper */
        $configHelper = Mage::helper('lemike_devmode/config');

        if (!$configHelper->isMailAllowed())
        { // no mail allowed set: show content
            if ($mail instanceof Zend_Mail)
            {
                $bodyHtml        = $mail->getBodyHtml();
                $reflectBodyMail = new ReflectionObject($bodyHtml);
                $reflectContent  = $reflectBodyMail->getProperty('_content');
                $reflectContent->setAccessible(true);
                $content = $reflectContent->getValue($bodyHtml);
            }
            elseif ($mail instanceof Mage_Core_Model_Email_Template)
            {
                /** @var Mage_Core_Model_Email_Template $mail */
                if (null === $content)
                {
                    throw new Exception(
                        $this->__('No processed content given for email template.')
                    );
                }
            }
            else
            {
                $content = $mail->getBody();
            }

            echo $content;

            /** @var LeMike_DevMode_Helper_Data $helper */
            $helper = Mage::helper('lemike_devmode');
            $helper->disableMagentoDispatch(true);
            $helper->stop();

            return false;
        }

        $recipient = Mage::getStoreConfig('lemike_devmode_core/email/recipient');

        if ($recipient)
        { // recipient is set: send mail to him
            LeMike_DevMode_Model_Log::info(
                                    'Reroute mail from "' .
                                    $mail->getToMail() .
                                    '" to "' .
                                    $recipient .
                                    '".'
            );
            $mail->setData('to_email', $recipient);
        }

        return $mail;
    }
}

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
 * @package   LeMike\DevMode\Controller\Front
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Action.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Controller\Front
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Controller_Front_Action extends Mage_Core_Controller_Front_Action
{
    protected $_moduleName = 'lemike_devmode';


    public function checkAuth()
    {
        /** @var LeMike_DevMode_Helper_Auth $authHelper */
        $authHelper = $this->helper('auth');
        if (!$authHelper->isDevAllowed())
        { // not allowed here: get off this planet!
            Mage::getSingleton('core/session')->addError(
                $this->helper()->__('You are not allowed to do this.')
            );
            $this->_redirectReferer();
        }
    }


    /**
     * .
     *
     * @param string $node
     *
     * @return LeMike_DevMode_Helper_Data
     */
    public function helper($node = null)
    {
        if ('' != $node)
        {
            $node = '/' . $node;
        }

        return Mage::helper($this->getModuleAlias($node));
    }


    /**
     * Get the module name or a child of it.
     *
     * @param $node
     *
     * @return string
     */
    public function getModuleName($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_NAME . $node;
    }


    public function getModuleAlias($node = null)
    {
        return LeMike_DevMode_Helper_Data::MODULE_ALIAS . $node;
    }
}

<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    Toolbox.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class Toolbox.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Block_Toolbox extends Mage_Core_Block_Template
{
    protected $_template = 'lemike/devmode/toolbox.phtml';


    public function getBackendUrl($route = 'adminhtml/index/index', $param = array())
    {
        return Mage::helper('lemike_devmode/auth')->getBackendUrl($route, $param);
    }


    /**
     * Get store code, module, controller and action that has been called.
     *
     * @return array
     */
    public function getPosition()
    {
        $position = array(
            'store'      => Mage::app()->getStore()->getCode(),
            'module'     => Mage::app()->getRequest()->getModuleName(),
            'controller' => Mage::app()->getRequest()->getControllerName(),
            'action'     => Mage::app()->getRequest()->getActionName(),
        );

        return array_filter($position);
    }


    public function helper($name = 'lemike_devmode/toolbox')
    {
        return parent::helper($name);
    }
}

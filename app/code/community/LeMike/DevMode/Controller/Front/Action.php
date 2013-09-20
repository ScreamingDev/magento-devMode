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
 * @package    Action.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class Action.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Controller_Front_Action extends Mage_Core_Controller_Front_Action
{
    protected $_moduleName = 'lemike_devmode';


    public function checkAuth()
    {
        if (!Mage::helper($this->_moduleName . '/auth')->isDevAllowed())
        { // not allowed here: get off this planet!
            Mage::getSingleton('core/session')->addError(
                $this->helper()->__('You are not allowed to do this.')
            );
            $this->_redirectReferer();
        }
    }


    public function helper()
    {
        return Mage::helper($this->_moduleName);
    }
}

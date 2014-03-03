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
 * @package   LeMike\DevMode\Shell\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Core.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class DevMode_Admin_Extension extends DelegateCommand
{
    public function listAction()
    {
        $this->_loadMagento();

        $modules = Mage::getModel('lemike_devmode/admin_extension_collection');

        foreach ($modules as $singleModule)
        {
            $data = $singleModule->getData();
            unset($data['id']);
            echo implode('; ', $data) . PHP_EOL;
        }
    }
}

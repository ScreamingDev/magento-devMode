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
 * @package   LeMike\DevMode\Shell\DevMode\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * See what layouts are used and where they are defined.
 *
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class DevMode_Core_Layout extends DelegateCommand
{
    /**
     * List all layout handles and where they are defined / changed.
     *
     * Options:
     *      --area      Define the area to lookup (e.g. "frontend", default: frontend)
     *      --package   The package that shall be used (e.g. "base", default as setup)
     *      --theme     A theme to use (e.g. "default", default as setup)
     *
     * Filter (can be regular expression or as described):
     *      --handle=X  Filter layout handle beginning with X
     *      --file=Y    Filter files beginning with Y
     *
     * @return void
     */
    public function listAction()
    {
        new DevMode_Load_Magento();

        $list = $this->_getLayoutArray();

        $handleRegExp = $this->getParameter()->getOption('handle', '') . '.*';
        $fileRegExp   = $this->getParameter()->getOption('file', '') . '.*';

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'handle' => "Layout handle",
                 'file'   => "Defined in ...",
            )
        );

        foreach ($list as $handle => $file)
        {
            if (preg_match('@' . $handleRegExp . '@i', $handle)
                || preg_match('@' . $fileRegExp . '@i', $file)
            )
            {
                $table->tableRowAdd(
                      array(
                           'handle' => $handle,
                           'file'   => key($file)
                      )
                );
            }
        }

        echo $table;
    }


    /**
     * .
     *
     * @return array
     */
    protected function _getLayoutArray()
    {
        /** @var Mage_Core_Model_Design_Package $design */
        $design = Mage::getSingleton('core/design_package');

        // options
        $area    = $this->getParameter()->getOption('area', 'frontend');
        $package = $this->getParameter()->getOption('package', $design->getPackageName());
        $theme   = $this->getParameter()->getOption('theme', $design->getTheme($area));

        $layout = Mage::getModel(
                      'lemike_devmode/core_layout',
                      $area,
                      $package,
                      $theme
        );

        return $layout->toAssocArray();
    }
}

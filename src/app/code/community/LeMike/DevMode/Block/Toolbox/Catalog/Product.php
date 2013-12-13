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
 * @package   LeMike\DevMode\Block\Toolbox\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Cms.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Toolbox\Catalog
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Block_Toolbox_Catalog_Product extends LeMike_DevMode_Block_Toolbox
{
    /** @var string Default template file for this block. */
    protected $_template = 'lemike/devmode/toolbox/catalog/product.phtml';


    /**
     * Generate a url to the backend product editor.
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->getBackendUrl(
                    'adminhtml/catalog_product/edit',
                    array('id' => Mage::registry('current_product')->getId())
        );
    }
}

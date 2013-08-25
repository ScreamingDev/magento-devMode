<?php
/**
 * Class LeMike_DevMode_Block_Catalog_Products.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.1.0
 */

/**
 * Class LeMike_DevMode_Block_Catalog_Products.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.1.0
 */
class LeMike_DevMode_Block_Catalog_Products extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        $this->_objectId   = 'entity_id';
        $this->_controller = 'catalog_category';

        parent::__construct();
        $this->setTemplate('lemike/devmode/catalog/products.phtml');
    }
}

<?php
/**
 * Contains class LeMike_DevMode_Block_Catalog_Tabs.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.1.0
 */

/**
 * LeMike_DevMode Catalog page left menu
 *
 * @category   LeMike_DevMode
 * @package    LeMike_DevMode_Block_Catalog
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Catalog_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * New catalog Tab.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_catalog');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Catalog Tools'));
    }


    /**
     * Add tab before transformed to HTML.
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            array(
                 'label'   => Mage::helper('lemike_devmode')->__('Products'),
                 'title'   => Mage::helper('lemike_devmode')->__('Products'),
                 'content' => $this->getLayout()->createBlock('lemike_devmode/catalog_products')->toHtml(),
                 'active'  => true
            )
        );

        return parent::_beforeToHtml();
    }
}

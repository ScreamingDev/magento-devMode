<?php
/**
 * Class LeMike_DevMode_Block_Sales_Tabs.
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
 * @package    LeMike_DevMode_Block_Sales
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Sales_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Add new Sales Tab.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_sales');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Sales Tools'));
    }


    /**
     * Changes before to HTML.
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        /** @var Mage_Adminhtml_Block_Template $ordersBlock */
        $ordersBlock = $this->getLayout()->createBlock('adminhtml/template', 'sales.orders');
        $ordersBlock->setTemplate('lemike/devmode/sales/orders.phtml');

        $this->addTab(
            'main_section',
            array(
                 'label'   => Mage::helper('lemike_devmode')->__('Orders'),
                 'title'   => Mage::helper('lemike_devmode')->__('Orders'),
                 'content' => $ordersBlock->toHtml(),
                 'active'  => true
            )
        );

        return parent::_beforeToHtml();
    }
}

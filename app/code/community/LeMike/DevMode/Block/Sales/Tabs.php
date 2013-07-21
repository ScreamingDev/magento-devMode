<?php

/**
 * LeMike_DevMode Catalog page left menu
 *
 * @category   LeMike_DevMode
 * @package    LeMike_DevMode_Block_Sales
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Sales_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_sales');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Sales Tools'));
    }


    protected function _beforeToHtml()
    {
        /** @var Mage_Adminhtml_Block_Template $ordersBlock */
        $ordersBlock = $this->getLayout()->createBlock('adminhtml/template', 'sales.orders');
        $ordersBlock->setTemplate('lemike/devmode/sales/orders.phtml');

        $this->addTab(
            'main_section',
            array(
                 'label' => Mage::helper('lemike_devmode')->__('Orders'),
                 'title' => Mage::helper('lemike_devmode')->__('Orders'),
                 'content' => $ordersBlock->toHtml(),
                 'active'  => true
            )
        );

        return parent::_beforeToHtml();
    }
}

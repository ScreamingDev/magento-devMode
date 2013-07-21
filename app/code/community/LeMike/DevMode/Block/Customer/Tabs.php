<?php

/**
 * LeMike_DevMode Catalog page left menu
 *
 * @category   LeMike_DevMode
 * @package    LeMike_DevMode_Block_Sales
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Customer_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_customer');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Customer Tools'));
    }


    protected function _beforeToHtml()
    {
        /** @var Mage_Adminhtml_Block_Template $customerBlock */
        $customerBlock = $this->getLayout()->createBlock('adminhtml/template', 'customer.customer');
        $customerBlock->setTemplate('lemike/devmode/customer/customer.phtml');

        $this->addTab(
            'main_section',
            array(
                 'label'   => Mage::helper('lemike_devmode')->__('Customer'),
                 'title'   => Mage::helper('lemike_devmode')->__('Customer'),
                 'content' => $customerBlock->toHtml(),
                 'active'  => true
            )
        );

        return parent::_beforeToHtml();
    }
}

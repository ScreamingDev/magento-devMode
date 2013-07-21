<?php

/**
 * LeMike_DevMode Catalog page left menu
 *
 * @category   LeMike_DevMode
 * @package    LeMike_DevMode_Block_Catalog
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Catalog_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_catalog');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Catalog Tools'));
    }


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

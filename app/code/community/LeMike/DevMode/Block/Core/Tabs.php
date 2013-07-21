<?php

/**
 * LeMike_DevMode Catalog page left menu
 *
 * @category   LeMike_DevMode
 * @package    LeMike_DevMode_Block_Sales
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 */
class LeMike_DevMode_Block_Core_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_core');
        $this->setTitle(Mage::helper('lemike_devmode')->__('Core Tools'));
    }


    protected function _beforeToHtml()
    {
        /** @var Mage_Adminhtml_Block_Template $emailBlock */
        $emailBlock = $this->getLayout()->createBlock('adminhtml/template', 'core.email');
        $emailBlock->setTemplate('lemike/devmode/core/email.phtml');

        $this->addTab(
            'main_section',
            array(
                 'label'   => Mage::helper('lemike_devmode')->__('E-Mail'),
                 'title'   => Mage::helper('lemike_devmode')->__('E-Mail'),
                 'content' => $emailBlock->toHtml(),
                 'active'  => true
            )
        );

        return parent::_beforeToHtml();
    }
}

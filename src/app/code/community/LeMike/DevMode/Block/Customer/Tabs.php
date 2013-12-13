<?php
/**
 * Class LeMike_DevMode_Block_Customer_Tabs.
 *
 * PHP version 5
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Customer
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */

/**
 * LeMike_DevMode Catalog page left menu.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Customer
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.1.0
 */
class LeMike_DevMode_Block_Customer_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Create new Tab.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_customer');
        $this->setData('title', Mage::helper('lemike_devmode')->__('Customer Tools'));
    }


    /**
     * Add tab before transformed to html.
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _beforeToHtml()
    {
        /** @var Mage_Adminhtml_Block_Template $customerBlock */
        $customerBlock =
            $this->getLayout()->createBlock(
                 'adminhtml/template',
                 'lemike.devmode.customer.customer'
            );
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

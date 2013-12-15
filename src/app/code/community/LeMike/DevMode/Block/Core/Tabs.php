<?php
/**
 * Contains class LeMike_DevMode_Block_Core_Tabs.
 *
 * PHP version 5.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Core
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
 * @package   LeMike\DevMode\Block\Core
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 */
class LeMike_DevMode_Block_Core_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    /**
     * Create new Tab.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('devmode_core');
        $this->setData('title', Mage::helper('lemike_devmode')->__('Core Tools'));
    }


    /**
     * Changes before HTML is made.
     *
     * @return Mage_Core_Block_Abstract
     */
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

        /** @var Mage_Adminhtml_Block_Template $configBlock */
        $configBlock = $this->getLayout()->createBlock('lemike_devmode/core_config', 'core.config');

        $this->addTab(
             'config_section',
             array(
                  'label'   => Mage::helper('lemike_devmode')->__('Config'),
                  'title'   => Mage::helper('lemike_devmode')->__('Config'),
                  'content' => $configBlock->toHtml(),
             )
        );

        /** @var LeMike_DevMode_Block_Core_Php $phpBlock */
        $phpBlock = $this->getLayout()->createBlock('lemike_devmode/core_php', 'core.php');

        $this->addTab(
             'php_section',
             array(
                  'label'   => Mage::helper('lemike_devmode')->__('PHP'),
                  'title'   => Mage::helper('lemike_devmode')->__('PHP'),
                  'content' => $phpBlock->toHtml(),
             )
        );

        /** @var LeMike_DevMode_Block_Core_Resource $phpBlock */
        $resourceBlock =
            $this->getLayout()->createBlock('lemike_devmode/core_resource', 'core.resource');

        $this->addTab(
             'resource_section',
             array(
                  'label'   => Mage::helper('lemike_devmode')->__('Extensions'),
                  'title'   => Mage::helper('lemike_devmode')->__('Extensions'),
                  'content' => $resourceBlock->toHtml(),
             )
        );

        return parent::_beforeToHtml();
    }
}

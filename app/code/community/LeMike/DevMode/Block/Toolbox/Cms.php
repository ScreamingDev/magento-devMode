<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devmode
 * @package    Cms.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Class Cms.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class LeMike_DevMode_Block_Toolbox_Cms extends LeMike_DevMode_Block_Toolbox
{
    protected $_template = 'lemike/devmode/toolbox/cms.phtml';


    function getCurrentCmsPageId()
    {
        $pageId = Mage::getBlockSingleton('cms/page')->getPage()->getId();

        return $pageId;
    }


    public function getEditUrl()
    {
        return Mage::helper('lemike_devmode/auth')->getBackendUrl(
            'adminhtml/cms_page/edit',
            array('page_id' => $this->getCurrentCmsPageId())
        );
    }
} 

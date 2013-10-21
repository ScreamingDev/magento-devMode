<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Toolbox
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class Cms.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Toolbox
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevMode_Block_Toolbox_Cms extends LeMike_DevMode_Block_Toolbox
{
    /** @var string Default template file for this block. */
    protected $_template = 'lemike/devmode/toolbox/cms.phtml';


    /**
     * Retrieve the ID of the current viewing page.
     *
     * @return mixed
     */
    public function getCurrentCmsPageId()
    {
        $pageId = Mage::getBlockSingleton('cms/page')->getPage()->getId();

        return $pageId;
    }


    /**
     * Generate backend URI to edit the current cms-page.
     *
     * @return string
     */
    public function getEditUrl()
    {
        return $this->getBackendUrl(
            'adminhtml/cms_page/edit',
            array('page_id' => $this->getCurrentCmsPageId())
        );
    }
}

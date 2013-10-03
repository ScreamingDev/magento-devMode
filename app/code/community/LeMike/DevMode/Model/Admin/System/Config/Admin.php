<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    User.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

/**
 * Class User.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */
class LeMike_DevMode_Model_Admin_System_Config_Admin
{
    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $ret = array();

        foreach ($this->toOptionArray() as $item)
        {
            $ret[$item['value']] = $item['label'];
        }

        return $ret;
    }


    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = array(
            array('value' => 0, 'label' => Mage::helper('lemike_devmode')->__('disabled')),
        );

        $collection = Mage::getModel('admin/user')->getCollection();
        foreach ($collection as $entry)
        {
            if (!$entry->getUsername())
            {
                continue;
            }

            /** @var Mage_Admin_Model_User $entry */
            $label         =
                $entry->getUsername() . ' <' . $entry->getName() . '> (' . $entry->getEmail() . ')';
            $optionArray[] = array('value' => $entry->getId(), 'label' => $label);
            /** @var Mage_Admin_Model_User $entry */
        }

        return $optionArray;
    }
}

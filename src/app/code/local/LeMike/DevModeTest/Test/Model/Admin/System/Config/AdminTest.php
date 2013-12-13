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
 * @package   LeMike\DevMode\Test\Model\Admin\System\Config
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */

/**
 * Class LeMike_DevMode_Model_LogTest.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Test\Model\Admin\System\Config
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.4.0
 */
class LeMike_DevModeTest_Test_Model_Admin_System_Config_AdminTest extends
    LeMike_DevModeTest_Test_AbstractConfig
{
    /**
     * Tests ChooseBetweenDifferentAdminUsersToLoginAsInTheConfig.
     *
     * @loadFixture table_admin_user
     *
     * @doNotIndexAll
     *
     * @return null
     */
    public function testChooseBetweenDifferentAdminUsersToLoginAsInTheConfig()
    {
        /*
         * }}} preconditions {{{
         */

        // get the model
        /** @var LeMike_DevMode_Model_Admin_System_Config_Admin $model */
        $model = Mage::getModel('lemike_devmode/admin_system_config_admin');

        $this->assertInstanceOf(
             'LeMike_DevMode_Model_Admin_System_Config_Admin',
             $model
        );

        /*
         * }}} main {{{
         */

        // get the data
        $data = $model->toArray();

        $this->assertInternalType('array', $data);
        $this->assertNotEmpty($data);

        // first needs to be [0 => "disable"]
        $firstKey   = key($data);
        $firstValue = $data[$firstKey];
        unset($data[$firstKey]);

        $this->assertEquals(0, $firstKey);
        $this->assertEquals(Mage::helper('lemike_devmode')->__('disabled'), $firstValue);

        // each must be in the array, except the inactive
        $collection = Mage::getModel('admin/user')->getCollection();

        foreach ($collection as $entry)
        {
            /** @var Mage_Admin_Model_User $entry */

            $label = sprintf(
                $model::OPTION_FORMAT,
                $entry->getUsername(),
                $entry->getName(),
                $entry->getEmail()
            );

            switch ($entry->getIsActive())
            {
                case 0:
                    $this->assertArrayNotHasKey($entry->getId(), $data);
                    break;
                case 1:
                    $this->assertArrayHasKey($entry->getId(), $data);
                    $this->assertEquals($data[(string) $entry->getId()], $label);
                    unset($data[$entry->getId()]);
                    break;
                default:
                    $this->fail(sprintf('Admin %s has strange is_active value.', $entry->getId()));
            }

            $this->assertNotContains($label, $data);
        }

        /*
         * }}} postcondition {{{
         */

        return null;
    }
}

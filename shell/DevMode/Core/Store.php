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
 * @package    Store.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */

/**
 * Gather and change information about stores.
 *
 * @category   mage_devmode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devmode
 * @since      $VERSION$
 */
class DevMode_Core_Store extends DelegateCommand
{
    /**
     * Shows id to according store code.
     *
     * Usage:
     *  codeToId the_store
     *
     * Where "the_store" should be a valid store code.
     *
     */
    public function codeToIdAction()
    {
        $storeCode = current($this->getParameter()->getArguments());

        if (!$storeCode)
        {
            echo $this->getUsage(__METHOD__);

            return;
        }

        /** @var Mage_Core_Model_Store $modelStore */
        $modelStore = Mage::getModel('core/store');

        $modelStore->load($storeCode, 'code');
        $id = $modelStore->getId();

        if ($id)
        {
            printf('%s has the id %s', $storeCode, $id);

            return;
        }

        printf('%s is no valid store code.', $storeCode);
        echo PHP_EOL;
        printf('Those are the stores: ');
        echo PHP_EOL;
        $this->listAction();
    }


    /**
     * Lists the current stores.
     *
     * @return void
     */
    public function listAction()
    {
        /** @var Mage_Core_Model_Store $modelStore */
        $modelStore = Mage::getModel('core/store');

        $table = new LeMike_DevMode_Block_Shell_Table(
            array(
                 'store_id' => "ID",
                 'code' => "Code",
                 'website_id' => "Website",
                 'group_id' => "Group",
                 'name' => "Name",
                 'is_active' => "Active",
            )
        );
        foreach ($modelStore->getCollection() as $store)
        {
            /** @var Mage_Core_Model_Store $store */
            $table->tableRowAdd((array) $store->getData());
        }

        $table->dispatch();

        return;
    }
}

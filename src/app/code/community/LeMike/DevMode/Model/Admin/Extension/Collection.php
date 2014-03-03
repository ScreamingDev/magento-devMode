<?php

class LeMike_DevMode_Model_Admin_Extension_Collection extends Varien_Data_Collection
{
    public $_collectionCache = array();

    public function load($printQuery = false, $logQuery = false)
    {
        if (!$this->isLoaded()) {
            $modules = Mage::getConfig()->getNode('modules')->children();
            $modulesArray = (array)$modules;

            foreach ($modulesArray as $name => $singleModule) {
                /** @var Mage_Core_Model_Config_Element $singleModule */

                $extensionModel = Mage::getModel('lemike_devmode/admin_extension');
                $extensionModel->setData(
                    array(
                        'id' => uniqid(),
                        'active' => (((string)$singleModule->active) == 'true'),
                        'codePool' => (string)$singleModule->codePool,
                        'version' => (string)$singleModule->version,
                        'name' => $name,
                    )
                );

                $this->addItem($extensionModel);
            }
        }
    }
}

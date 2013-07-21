<?php

class LeMike_DevMode_Block_Catalog_Products extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        $this->_objectId   = 'entity_id';
        $this->_controller = 'catalog_category';

        parent::__construct();
        $this->setTemplate('lemike/devmode/catalog/products.phtml');
    }
}

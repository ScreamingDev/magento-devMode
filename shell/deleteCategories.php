<?php
/**
 * Delete all categories.
 *
 * @category   Magento-devMode
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/Magento-devMode/blob/master/LICENSE.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/Magento-devMode
 * @since      0.3.0
 */

include 'abstract.php';

/**
 * Class LeMike_DevMode_Shell_DeleteCategories.
 *
 * @category   ${PROJECT_NAME}
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  ${YEAR} Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since      ${DS}VERSION${DS}
 */
class LeMike_DevMode_Shell_DeleteCategories extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        print_r($this->execute());
    }


    /**
     * Execute deletion.
     *
     * @return array
     */
    public function execute()
    {
        $deleteAll = array(
            'amount'    => 0,
            'processed' => 0,
            'errors'    => array(),
        );

        $collection          = $this->_getProductSet();
        $deleteAll['amount'] = $collection->count();

        foreach ($collection as $item)
        {
            /** @var Mage_Catalog_Model_Product $item */
            $item = $item->load($item->getId());
            $item->delete();
            $deleteAll['processed']++;

            // free some memory
            unset($item);
        }

        return $deleteAll;
    }


    /**
     * Get store product collection.
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    protected function _getProductSet()
    {
        // Set store defaults for Magento
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        /* @var $productModel Mage_Catalog_Model_Product */
        $model = Mage::getModel('catalog/category');

        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $model->getCollection();

        return $collection;
    }
}

$cmd = new LeMike_DevMode_Shell_DeleteCategories();
$cmd->run();

<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Model
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_Model_FrontMatter extends Varien_Object
{

    protected function _construct()
    {
        parent::_construct();

        $this->setData([
            'date'  => $this->getProduct()->getUpdatedAt(),
            'title' => $this->getProduct()->getName(),
            'menu'  => [], // @todo // getCategory path as array
        ]);

//        var_export([
//            $this->getProduct()->getSku(),
//             $this->getCategory()->getPathInStore(),
//             $this->getCategory()->getPathIds(),
//             $this->getCategory()->getRequestPath(),
//        ]);

    }

    public function __toString()
    {
        /**
         * in this event the two objects are available:
         * Mage::registry('current_product');
         * Mage::registry('current_category');
         * for each iteration the object will be updated automatically
         */
        Mage::dispatchEvent('hugo_front_matter_before_to_string', [
            'front_matter' => $this,
        ]);
        return '---' . PHP_EOL .
        Mage::getSingleton('hugo/spcy')->dump($this->getData(), false, false, true)
        . PHP_EOL . '---' . PHP_EOL . PHP_EOL;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * @return Mage_Catalog_Model_Category
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }
}

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
            'date'  => $this->_getDate(),
            'title' => $this->getProduct()->getName(),
            'menu'  => $this->_getMenu(),
        ]);
    }

    /**
     * @return string
     */
    protected function _getDate()
    {
        $parts = explode(' ', $this->getProduct()->getUpdatedAt());
        return $parts[0];
    }

    /**
     * @todo proper implementation
     *
     * @return array
     */
    protected function _getMenu()
    {
        $suffix  = Mage::helper('catalog/category')->getCategoryUrlSuffix();
        $urlPath = str_replace($suffix, '', $this->getCategory()->getUrlPath()); // can lead to bugs
        $parts   = explode('/', $urlPath);
        $root    = array_shift($parts);
        $l1      = array_shift($parts);
        $menu    = [
            $root => [
                'parent' => $l1,
            ]
        ];

        return $menu;
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

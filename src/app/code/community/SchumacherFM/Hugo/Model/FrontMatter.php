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
    /**
     * @var Mage_Catalog_Model_Product
     */
    private $_product;

    public function __toString()
    {
        /**
         * in this event the two objects are available:
         * Mage::registry('current_product');
         * Mage::registry('current_category');
         * for each iteration the object will be updated automatically
         */
        Mage::dispatchEvent('hugo_front_matter_before_to_string', array(
            'front_matter' => $this,
        ));
        return '---' . PHP_EOL .
        Mage::getSingleton('hugo/spcy')->dump($this->getData(), false, false, true)
        . PHP_EOL . '---' . PHP_EOL . PHP_EOL;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    public function getProduct()
    {
        return $this->_product;
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     *
     * @return $this
     */
    public function setProduct(Mage_Catalog_Model_Product $product)
    {
        $this->_product = $product;
        return $this;
    }
}

<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Helper
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isAuthenticated($key)
    {
        return true;// '@todo' === $key;
    }

    /**
     * temp impl. don't want to dump 100k products
     *
     * used in getAllIds() query
     *
     * @return int
     */
    public function maxProducts()
    {
        return 10;
    }

    /**
     * used in getAllIds() query
     *
     * @return null
     */
    public function offsetProducts()
    {
        return 20;
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return string
     */
    public function getHugoSourceJson($path, $content)
    {
        return json_encode([
            'Path'    => $path,
            'Content' => $content,
        ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_FORCE_OBJECT);
    }

    /**
     * @param int $productId
     *
     * @return array
     */
    public function getCategoryIDs($productId)
    {
        $r = Mage::getResourceSingleton('catalog/product')->getReadConnection();

        // is_parent=1 ensures that we'll get only category IDs those are direct parents of the product, instead of
        // fetching all parent IDs, including those are higher on the tree
        $select = $r->select()->distinct()
            ->from(
                Mage::getResourceSingleton('catalog/product')->getTable('catalog/category_product_index'),
                array('category_id')
            )
            ->where('product_id = ? AND is_parent = 1', $productId)
            ->where('store_id = ?', (int)Mage::app()->getStore()->getId());

        $return = array_map('intval', $r->fetchCol($select));
        if (count($return) === 0) {
            $return = [0];
        }
        return $return;
    }
}

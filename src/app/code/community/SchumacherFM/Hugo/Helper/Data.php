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
        return 50;
    }

    /**
     * used in getAllIds() query
     *
     * @return null
     */
    public function offsetProducts()
    {
        return null;
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
        ], JSON_FORCE_OBJECT);
    }
}

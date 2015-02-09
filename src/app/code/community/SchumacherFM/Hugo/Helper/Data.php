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

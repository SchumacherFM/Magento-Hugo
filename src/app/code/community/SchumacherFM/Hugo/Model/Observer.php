<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Observer
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_Model_Observer
{
    protected $_type = 'backend';

    /**
     * adminhtml_block_html_before
     *
     * @param Varien_Event_Observer $observer
     *
     * @return null
     */
    public function injectHugo(Varien_Event_Observer $observer)
    {
        /** @var Mage_Core_Block_Abstract $block */
        $block = $observer->getEvent()->getBlock();

        if (false === $this->_isAllowed($block)) {
            return null;
        }
        /** @var Varien_Object $transport */
        $transport = $observer->getEvent()->getTransport();
        $html      = $transport->getHtml();
        $transport->setHtml($this->_getHugoHtml() . $html);
    }

    /**
     * @param Mage_Core_Block_Abstract $block
     *
     * @return bool
     */
    protected function _isAllowed(Mage_Core_Block_Abstract $block)
    {
        if ($block instanceof Mage_Adminhtml_Block_Page_Head) {
            return true;
        }
        if (true === Mage::helper('hugo')->isFrontendEnabled() && $block instanceof Mage_Page_Block_Html_Head) {
            $this->_type = 'frontend';
            return true;
        }
        return false;
    }

    /**
     * gets css/js for hugo.js and saves or loads it from cache
     *
     * @return string
     */
    protected function _getHugoHtml()
    {
        /** @var Varien_Cache_Core $cache */
        $cache = Mage::app()->getCache();
        $key   = array(
            Mage::app()->getStore()->getId(),
            'hugo_js',
            $this->_type,
            Mage::helper('hugo')->getThemeColor(),
            Mage::helper('hugo')->getThemeFileName(),
        );

        $cacheKey = implode('_', $key);
        $hugo     = $cache->load($cacheKey);
        if (true === empty($hugo)) {
            $hugo = $this->_getCss() . $this->_getJs();
            $cache->save($hugo, $cacheKey, array(Mage_Core_Model_Layout_Update::LAYOUT_GENERAL_CACHE_TAG));
        }
        return $hugo;
    }

    /**
     * @return string
     */
    protected function _getCss()
    {
        $color = Mage::helper('hugo')->getThemeColor();
        $color = true === empty($color) ? '' : $color . '/';

        return '<style type="text/css">' .
        $this->_getFile('themes/' . $color . Mage::helper('hugo')->getThemeFileName($this->_type)) .
        Mage::helper('hugo')->getCustomCSS($this->_type)
        . '</style>';
    }

    /**
     * @return string
     */
    protected function _getJs()
    {
        return '<script type="text/javascript">' .
        $this->_getFile('hugo.min.js')
        . '</script>';
    }

    /**
     * @param string $file
     *
     * @return string
     */
    protected function _getFile($file)
    {
        $path    = Mage::getBaseDir() . DS . Mage_Core_Model_Store::URL_TYPE_JS . DS . 'schumacherfm' . DS . 'hugo' . DS;
        $content = file_get_contents($path . $file);
        if (strpos(strtolower($file), 'css') !== false) {
            $content = $this->_compressCss($content);
        }
        return $content;
    }

    /**
     * @param $css
     *
     * @return string
     */
    protected function _compressCss($css)
    {
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        $css = str_replace(array(': ', ', '), array(':', ','), $css);
        return preg_replace('~([\r\n\t]+|\s{2,})~', '', $css);
    }
}
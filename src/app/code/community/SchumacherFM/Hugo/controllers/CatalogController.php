<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Controller
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_CatalogController extends Mage_Core_Controller_Front_Action
{

    public function productAction()
    {
        if (false === Mage::helper('hugo')->isAuthenticated($this->getRequest()->getParam('auth', ''))) {
            $response = new Varien_Object();
            $response->setError(0);
            $response->setMessage($this->__('Authentication failed'));
            return $this->getResponse()->setBody($response->toJson());
        }
        /** @var Mage_Catalog_Model_Resource_Product_Collection $c */
        $c = Mage::getModel('catalog/product')->getCollection();
        Mage::getModel('catalog/layer')->prepareProductCollection($c);

        foreach ($c->getAllIds(50) as $pid) {
            $this->_productIterator($pid);
        }
    }

    private function _productIterator($productId)
    {
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
        $params     = new Varien_Object();
        $params->setCategoryId(0);
        $params->setSpecifyOptions([]);

        // include first found category
        // simulate route to product view page otherwise no handle will be found

        ob_start();
        $viewHelper->prepareAndRender($productId, $this, $params);
        $content = ob_get_clean();

        $p = Mage::registry('current_product');
        echo Mage::helper('hugo')->getHugoSourceJson($p->getUrlKey() . '.md', $content) . "\n";
        flush();
    }
}

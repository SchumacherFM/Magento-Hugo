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

    public function preDispatch()
    {
        parent::preDispatch();
        // @todo figure out how to simulate the template path instead of setting it
        // in the backend system config
        // fake the route for the real handlers
        $this->getRequest()->setRoutingInfo([
            'requested_route'      => 'catalog',
            'requested_controller' => 'product',
            'requested_action'     => 'view',
        ]);
    }

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
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8', true);
        $this->getResponse()->sendResponse();
        foreach ($c->getAllIds(Mage::helper('hugo')->maxProducts(), Mage::helper('hugo')->offsetProducts()) as $pid) {
            foreach (Mage::helper('hugo')->getCategoryIDs($pid) as $categoryID) {
                $this->_productIterator((int)$pid, $categoryID);
            }
        }
        return $this;
    }

    /**
     * @param int $productId
     * @param int $categoryID
     *
     * @throws Mage_Core_Exception
     */
    private function _productIterator($productId, $categoryID)
    {

        // forced change of theme to SchumacherFM_Hugo theme

        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
        $params     = new Varien_Object();
        $params->setCategoryId($categoryID);
        $params->setSpecifyOptions([]);

        $viewHelper->prepareAndRender($productId, $this, $params);

        echo Mage::helper('hugo')->getHugoSourceJson(
                $this->_getUrlPath(),
                $this->_prepareFrontMatter() .
                $this->getResponse()->getBody()
            ) . "\n";
        flush();
        Mage::unregister('current_product');
        Mage::unregister('current_category');
        Mage::unregister('product');
        Mage::unregister('category');
    }

    private function _getUrlPath()
    {
        $p = $this->_getProduct()->getUrlPath(Mage::registry('current_category'));
        $p = str_replace('.html', '.md', $p);
        return $p;
    }

    private function _prepareFrontMatter()
    {
        $fm = Mage::getModel('hugo/frontMatter');
        $fm->setProduct($this->_getProduct());
        return (string)$fm->setData(array(
            'date'  => $this->_getProduct()->getUpdatedAt(),
            'title' => $this->_getProduct()->getName(),
            'menu'  => array(), // @todo // getCategpry path as array
        ));
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    private function _getProduct()
    {
        return Mage::registry('current_product');
    }
}

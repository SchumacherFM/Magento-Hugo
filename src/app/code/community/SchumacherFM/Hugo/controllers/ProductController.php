<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Controller
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_ProductController extends Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

        // at the beginning this is now hard coded but must be configurable in the backend.
        Mage::getSingleton('core/design_package')->setPackageName('default');
        Mage::getSingleton('core/design_package')->setTheme('template', 'hugo');
        Mage::getSingleton('core/design_package')->setTheme('layout', 'hugo');

        // in the backend system config
        // fake the route for the real handlers
        $this->getRequest()->setRoutingInfo([
            'requested_route'      => 'catalog',
            'requested_controller' => 'product',
            'requested_action'     => 'view',
        ]);
    }

    public function jsonAction()
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
        // Prepare helper and params
        $viewHelper = Mage::helper('catalog/product_view');
        $params     = new Varien_Object();
        $params->setCategoryId($categoryID);
        $params->setSpecifyOptions([]);

        $viewHelper->prepareAndRender($productId, $this, $params);

        echo Mage::helper('hugo')->getHugoSourceJson(
                $this->_getUrlPath(),
                $this->_prepareFrontMatter() .
                $this->_getContent()
            ) . "\n";
        $this->getResponse()->clearBody();
        flush();
        Mage::unregister('current_product');
        Mage::unregister('current_category');
        Mage::unregister('product');
        Mage::unregister('category');
    }

    /**
     * @todo this must be removed if product view page generation is completely switched to markdown.
     *       this is a temp implementation to parse HTML better with the blackfriday markdown parser of hugo
     *
     * @return string
     */
    private function _getContent()
    {
        return trim(preg_replace('~\s+~', ' ', $this->getResponse()->getBody()));
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
        return (string)$fm;
    }

    /**
     * @return Mage_Catalog_Model_Product
     */
    private function _getProduct()
    {
        return Mage::registry('current_product');
    }
}

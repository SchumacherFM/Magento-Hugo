<?php

/**
 * @category    SchumacherFM_Hugo
 * @package     Controller
 * @author      Cyrill at Schumacher dot fm / @SchumacherFM
 * @copyright   Copyright (c)
 * @license     OSL-3.0
 */
class SchumacherFM_Hugo_CategoryController extends Mage_Core_Controller_Front_Action
{

    public function jsonAction()
    {
        $this->getResponse()->setHeader('Content-Type', 'application/json; charset=UTF-8', true);

        if (false === Mage::helper('hugo')->isAuthenticated($this->getRequest()->getParam('auth', ''))) {
            $response = new Varien_Object();
            $response->setError(0);
            $response->setMessage($this->__('Authentication failed'));
            return $this->getResponse()->setBody($response->toJson());
        }

        $this->getResponse()->sendResponse();

        $category = Mage::getModel('catalog/category');
        $tree     = $category->getTreeModel();

        $ids        = $tree->getCollection()->getAllIds();
        $categories = [];

        foreach ($ids as $id) {
            $category->load($id);
            $categories[$id]['name'] = $category->getName();
            $categories[$id]['path'] = $category->getPath();
        }
        foreach ($ids as $id) {
            $path   = explode('/', $categories[$id]['path']);
            $string = '';
            foreach ($path as $pathId) {
                $string .= $categories[$pathId]['name'] . ' > ';
            }
            $string .= ';' . $id . "\n";

            echo $string;
        }

        return $this;
    }
}

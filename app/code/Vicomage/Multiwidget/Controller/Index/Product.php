<?php

namespace Vicomage\Multiwidget\Controller\Index;

use Magento\Framework\Controller\ResultFactory; 

class Product extends \Vicomage\Multiwidget\Controller\Index
{
    /**
     * Default customer account page.
     */
    public function execute()
    {
    	if ($this->getRequest()->isAjax()) {
	        $this->_view->loadLayout();
	        $this->_view->renderLayout();
	        $info = $this->getRequest()->getParam('info');
	        $type = $this->getRequest()->getParam('type');

	        $tmp = 'product/grid.phtml';
	        $products = $this->_view->getLayout()->createBlock('Vicomage\Multiwidget\Block\Product\GridProduct')
					            ->setCfg((array)json_decode($info))
					           	->setActivated($type)
					           	->setTemplate($tmp)
					           	->toHtml();
	        $this->getResponse()->setBody($products);
	    }else {
	        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
	        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
	        return $resultRedirect;
	    }
    }
}

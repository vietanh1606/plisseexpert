<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Controller\Author;

use \Magento\Store\Model\ScopeInterface;

/**
 * Blog author posts view
 */
class View extends \Magento\Framework\App\Action\Action
{
    /**
     * View blog author action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $config = $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');

        $enabled = (int) $config->getValue('vicomage_mfblog/author/enabled',
            ScopeInterface::SCOPE_STORE);
        $pageEnabled = (int) $config->getValue('vicomage_mfblog/author/page_enabled',
            ScopeInterface::SCOPE_STORE);

        if (!$enabled || !$pageEnabled) {
            $this->_forward('index', 'noroute', 'cms');
            return;
        }

        $author = $this->_initCategory();
        if (!$author) {
            $this->_forward('index', 'noroute', 'cms');
            return;
        }

        $this->_objectManager->get('\Magento\Framework\Registry')->register('current_blog_author', $author);

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

    /**
     * Init author
     *
     * @return \Vicomage\Blog\Model\Author || false
     */
    protected function _initCategory()
    {
        $id = $this->getRequest()->getParam('id');

        $author = $this->_objectManager->create('Vicomage\Blog\Model\Author')->load($id);

        if (!$author->getId()) {
            return false;
        }

        return $author;
    }

}

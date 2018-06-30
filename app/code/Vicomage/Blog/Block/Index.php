<?php
/**
 * Copyright © 2015 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog index block
 */
class Index extends \Vicomage\Blog\Block\Post\PostList
{
    /**
     * Preparing global layout
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $this->_addBreadcrumbs();
        $this->pageConfig->getTitle()->set($this->_getConfigValue('title'));
        $this->pageConfig->setKeywords($this->_getConfigValue('meta_keywords'));
        $this->pageConfig->setDescription($this->_getConfigValue('meta_description'));
        $this->pageConfig->addRemotePageAsset(
            $this->_url->getBaseUrl(),
            'canonical',
            ['attributes' => ['rel' => 'canonical']]
        );

        return parent::_prepareLayout();
    }

    /**
     * Prepare breadcrumbs
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function _addBreadcrumbs()
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'blog',
                [
                    'label' => __('Blog'),
                    'title' => __(sprintf('Go to Blog Home Page'))
                ]
            );
        }
    }

    /**
     * Retrieve blog title
     * @return string
     */
    protected function _getConfigValue($param)
    {
        return $this->_scopeConfig->getValue(
            'vicomage_mfblog/index_page/'.$param,
            ScopeInterface::SCOPE_STORE
        );
    }

}

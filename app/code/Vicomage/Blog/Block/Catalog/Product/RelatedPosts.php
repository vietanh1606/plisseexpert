<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block\Catalog\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * Blog post related posts block
 */
class RelatedPosts extends \Vicomage\Blog\Block\Post\PostList\AbstractList
{

    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        $pageSize = (int) $this->_scopeConfig->getValue(
            'vicomage_mfblog/product_page/number_of_related_posts',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$pageSize) {
            $pageSize = 5;
        }
        $this->setPageSize($pageSize);

        parent::_preparePostCollection();

        $product = $this->getProduct();
        $this->_postCollection->getSelect()->joinLeft(
            ['rl' => $product->getResource()->getTable('vicomage_blog_post_relatedproduct')],
            'main_table.post_id = rl.post_id',
            ['position']
        )->where(
            'rl.related_id = ?',
            $product->getId()
        );
    }

    /**
     * Retrieve true if Display Related Posts enabled
     * @return boolean
     */
    public function displayPosts()
    {
        return (bool) $this->_scopeConfig->getValue(
            'vicomage_mfblog/product_page/related_posts_enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve posts instance
     *
     * @return \Vicomage\Blog\Model\Category
     */
    public function getProduct()
    {
        if (!$this->hasData('product')) {
            $this->setData('product',
                $this->_coreRegistry->registry('current_product')
            );
        }
        return $this->getData('product');
    }

    /**
     * Get Block Identities
     * @return Array
     */
    public function getIdentities()
    {
        return [\Magento\Catalog\Model\Product::CACHE_TAG . '_relatedposts_'.$this->getPost()->getId()  ];
    }
}

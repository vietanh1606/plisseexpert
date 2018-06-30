<?php
/**
 * Copyright © 2016 Ihor Vansach (ihor@Vicomage.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Vicomage\Blog\Block\Post\PostList;

use Magento\Store\Model\ScopeInterface;

/**
 * Abstract blog post list block
 */
abstract class AbstractList extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $_post;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Vicomage\Blog\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_postCollectionFactory;

    /**
     * @var \Vicomage\Blog\Model\ResourceModel\Post\Collection
     */
    protected $_postCollection;

    /**
     * @var \Vicomage\Blog\Model\Url
     */
    protected $_url;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Vicomage\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory
     * @param \Vicomage\Blog\Model\Url $url
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Vicomage\Blog\Model\ResourceModel\Post\CollectionFactory $postCollectionFactory,
        \Vicomage\Blog\Model\Url $url,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
        $this->_postCollectionFactory = $postCollectionFactory;
        $this->_url = $url;
    }

    /**
     * Prepare posts collection
     *
     * @return void
     */
    protected function _preparePostCollection()
    {
        $this->_postCollection = $this->_postCollectionFactory->create()
            ->addActiveFilter()
            ->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('publish_time', 'DESC');

        if ($this->getPageSize()) {
            $this->_postCollection->setPageSize($this->getPageSize());
        }
    }

    /**
     * Prepare posts collection
     *
     * @return \Vicomage\Blog\Model\ResourceModel\Post\Collection
     */
    public function getPostCollection()
    {
        if (is_null($this->_postCollection)) {
            $this->_preparePostCollection();
        }

        return $this->_postCollection;
    }

}

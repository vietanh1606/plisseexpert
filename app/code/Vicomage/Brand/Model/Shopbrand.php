<?php

namespace Vicomage\Brand\Model;

class Shopbrand extends \Magento\Framework\Model\AbstractModel
{

    protected $_scopeConfig;
    protected $_shopbrandCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Vicomage\Brand\Model\ResourceModel\Shopbrand\CollectionFactory $shopbrandCollectionFactory,
        \Vicomage\Brand\Model\ResourceModel\Shopbrand $resource,
        \Vicomage\Brand\Model\ResourceModel\Shopbrand\Collection $resourceCollection,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection
        );
        $this->_shopbrandCollectionFactory = $shopbrandCollectionFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_scopeConfig= (object) $scopeConfig->getValue(
            'shopbrand',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve post related products
     * @param  int $storeId
     * @return \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    public function getRelatedProducts($storeId = null)
    {
        if (!$this->hasData('related_products')) {

            $collection = $this->_productCollectionFactory->create();

            if (!is_null($storeId)) {
                $collection->addStoreFilter($storeId);
            } elseif ($storeIds = $this->getStoreId()) {
                $collection->addStoreFilter($storeIds[0]);
            }

            $cfg = $this->_scopeConfig->general;
            if(isset($cfg['attributeCode'])){
                $collection->addAttributeToFilter($cfg['attributeCode'],  $this->getOptionId());
            }

            $this->setData('related_products', $collection);
        }

        return $this->getData('related_products');
    }

}

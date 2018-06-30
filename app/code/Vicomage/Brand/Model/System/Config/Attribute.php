<?php

namespace Vicomage\Brand\Model\System\Config;

class Attribute implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $_collectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory
    )
    {
        $this->_collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $options = [['value' => '', 'label' => __('Choose brand attribute')]];
        $collection = $this->_collectionFactory->create()
                        ->addFieldToFilter('frontend_input', 'select')
                        ->addVisibleFilter();
        foreach ($collection as $item) {
            $options[] = ['value' => $item->getAttributeCode(), 'label' => $item->getFrontendLabel()];
        }
        return $options;
    }

}

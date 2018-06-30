<?php

namespace Vicomage\Multiwidget\Helper;

class Product extends \Magento\Catalog\Block\Product\AbstractProduct
{

    /**
     * @var \Magento\Framework\Url\Helper\Data
     */
    protected $urlHelper;
    protected $productConfig;

    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_objectManager;

    /**
     * Catalog product visibility
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * Product collection factory
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    protected $_limit; // Limit Product

    /**
     * @param Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    ) {
        $this->urlHelper = $urlHelper;
        $this->_objectManager = $objectManager;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        parent::__construct( $context, $data );
    }


    /**
     * get type
     * @return mixed
     */
    public function getTypeFilter()
    {
        $type = $this->getRequest()->getParam('type');
        if(!$type){
            $type = $this->getActivated(); // get form setData in Block
        }
        return $type;
    }




    /**
     * get collection product when load
     * @return mixed
     */
    public function getLoadedProductCollection()
    {
        $this->_limit = $this->getWidgetCfg('limit');

        $type = $this->getTypeFilter();
        switch ($type) {
            case 'bestseller':

                $collection = $this->getBestsellerProducts();
                break;
            case 'featured':

                $collection = $this->getFeaturedProducts();
                break;
            case 'latest':

                $collection = $this->getLatestProducts();
                break;
            case 'new':

                $collection = $this->getNewProducts();
                break;
            case 'special':

                $collection = $this->getSpecialProducts();
                break;
            case 'sale':

                $collection = $this->getSaleProducts();
                break;
            default:

                $collection = $this->getRandomProducts();
        }
        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );
        return $collection;
    }


    /**
     * Get post parameters
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        var_dump($url);die;
        return  [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }



    /**
     * get Bestseller products collection
     * @return $this|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getBestsellerProducts(){
        $collection = $this->_objectManager->get('\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory')->create()->setModel('Magento\Catalog\Model\Product');
        $collection->setPageSize($this->_limit)->setCurPage(1);
        $producIds = array();
        foreach ($collection as $product) {
            $producIds[] = $product->getProductId();
        }

        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter('entity_id', array('in' => $producIds));
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');

        return $collection;

    }

    /**
     * get Feature products collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getFeaturedProducts()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->addAttributeToFilter('featured', '1')
            ->addStoreFilter()
            ->addAttributeToSelect('*')
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->setPageSize($this->_limit)->setCurPage(1);
        $collection->getSelect()->group('e.entity_id')->order('e.entity_id DESC');

        return $collection;
    }

    /**
     * get Latest products collection
     * @return $this|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getLatestProducts(){

        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()
            ->addAttributeToSort('entity_id', 'desc')
            ->setPageSize($this->_limit)->setCurPage(1);
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');

        return $collection;
    }

    /**
     * get New products collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getNewProducts() {

        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'news_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'news_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'news_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'news_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('news_from_date', 'desc')
            ->setPageSize($this->_limit)->setCurPage(1);
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');
        return $collection;
    }

    /**
     * get random products
     * @return $this|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getRandomProducts() {
        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter();

        $collection->getSelect()->order('rand()');

        $collection->setPageSize($this->_limit)->setCurPage(1);
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');

        return $collection;
    }

    /**
     * get products sale
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getSaleProducts(){

        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'special_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'special_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('special_to_date', 'desc')
            ->setPageSize($this->_limit)->setCurPage(1);
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');
        return $collection;

    }

    /**
     * get special products
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getSpecialProducts() {


        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollectionFactory->create();

        $collection = $this->_addProductAttributesAndPrices(
            $collection
        )->addStoreFilter()->addAttributeToFilter(
            'special_from_date',
            [
                'or' => [
                    0 => ['date' => true, 'to' => $todayEndOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            'special_to_date',
            [
                'or' => [
                    0 => ['date' => true, 'from' => $todayStartOfDayDate],
                    1 => ['is' => new \Zend_Db_Expr('null')],
                ]
            ],
            'left'
        )->addAttributeToFilter(
            [
                ['attribute' => 'special_from_date', 'is' => new \Zend_Db_Expr('not null')],
                ['attribute' => 'special_to_date', 'is' => new \Zend_Db_Expr('not null')],
            ]
        )->addAttributeToSort('special_to_date', 'desc')
            ->setPageSize($this->_limit)->setCurPage(1);
        $collection = $this->findProductByCatgoryIds($collection);
        $collection->getSelect()->group('e.entity_id');
        return $collection;

    }


    /**
     * @param $collection
     * @return mixed
     */
    public function findProductByCatgoryIds($collection){

        $categoryConfigProduct = $this->getWidgetCfg('category_tab_ids');
        $storeId = $this->_storeManager->getStore()->getId();
        //check if in config set all category
        if (in_array("0",$categoryConfigProduct)) {

            $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        } else {

            $visibility = implode(',',$this->_catalogProductVisibility->getVisibleInCatalogIds());
            $categoryIds = implode(',',$categoryConfigProduct);
            $collection->getSelect()->join(
                array('cat_index' => 'catalog_category_product_index'),
                'cat_index.product_id=e.entity_id AND cat_index.store_id="'.$storeId.'" AND cat_index.visibility IN('.$visibility.') AND cat_index.category_id IN('.$categoryIds.') ',
                array()
            );
        }

        return $collection;
    }
}

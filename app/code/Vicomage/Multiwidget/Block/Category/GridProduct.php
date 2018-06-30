<?php
namespace Vicomage\Multiwidget\Block\Category;

class GridProduct extends \Magento\Catalog\Block\Product\AbstractProduct
{

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $categoryFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * @var \Magento\Rule\Model\Condition\Sql\Builder
     */
    protected $sqlBuilder;

    /**
     * @var
     */
    protected $_limit = null; // Limit Product

    /**
     * @var
     */
    protected $_types = null; // types is types filter bestseller, featured ...

    /**
     * GridProduct constructor.
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Rule\Model\Condition\Sql\Builder $sqlBuilder,
        \Magento\Catalog\Block\Product\Context $context, array $data = [])
    {
        $this->sqlBuilder = $sqlBuilder;
        $this->categoryFactory = $categoryFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }



    /**
     * @return mixed
     */
    public function getTypeFilter()
    {
        $type = $this->getRequest()->getParam('type');
        if(!$type) $type = $this->getActivated();
        return $type;
    }

    /**
     * get data config
     * @param null $cfg
     * @return mixed
     */
    public function getWidgetCfg($cfg=null)
    {
        $info = $this->getRequest()->getParam('info');
        if($info){
            $info = (array)json_decode($info);
            if(isset($info[$cfg])) {

                return $info[$cfg];
            }

            return $info;
        }else {
            $info = $this->getCfg();

            if(isset($info[$cfg])) {
                return $info[$cfg];
            }
            return $info;
        }
    }

    /**
     * @return $this|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getLoadedProductCollection()
    {
        $this->_limit = $this->getWidgetCfg('limit');
        $this->_types = $this->getWidgetCfg('product_category_collection');
        $categoryId = $this->getTypeFilter();

        /** @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->categoryFactory->create()->load($categoryId)->getProductCollection();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $collection = $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->setCurPage(1);

        if(!$this->_types) {
            $collection->setPageSize($this->_limit);
            $collection->distinct(true);
            return $collection;
        }

        $fn = 'get' . ucfirst($this->_types);
        $collection = $this->{$fn}($collection);
        $collection->setPageSize($this->_limit);
        $collection->distinct(true);
        return $collection;
    }



    /**
     * function get Bestseller
     * @param $collection
     * @return mixed
     */
    public function getBestseller($collection){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $report = $objectManager->get('\Magento\Sales\Model\ResourceModel\Report\Bestsellers\CollectionFactory')->create();
        $ids = $collection->getAllIds();
        $report->addFieldToFilter('product_id', array('in' => $ids))->setPageSize($this->_limit)->setCurPage(1);
        $producIds = array();
        foreach ($report as $product) {
            $producIds[] = $product->getProductId();
        }

        $collection->addAttributeToFilter('entity_id', array('in' => $producIds));

        return $collection;

    }

    /**
     * @param $collection
     * @return mixed
     */
    public function getFeatured($collection)
    {

        $collection->addAttributeToFilter('featured', '1');

        return $collection;

    }


    /**
     * @param $collection
     * @return mixed
     */
    public function getLatest($collection){

        $collection = $collection->addStoreFilter()
            ->addAttributeToSort('entity_id', 'desc');

        return $collection;

    }


    /**
     * @param $collection
     * @return mixed
     */
    public function getMostviewed($collection){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $report = $objectManager->get('\Magento\Reports\Model\ResourceModel\Report\Product\Viewed\CollectionFactory')->create();
        $ids = $collection->getAllIds();
        $report->addFieldToFilter('product_id', array('in' => $ids))->setPageSize($this->_limit)->setCurPage(1);
        $producIds = array();
        foreach ($report as $product) {
            $producIds[] = $product->getProductId();
        }

        $collection->addAttributeToFilter('entity_id', array('in' => $producIds));

        return $collection;
    }


    /**
     * @param $collection
     * @return mixed
     */
    public function getNew($collection) {

        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');

        $collection = $collection->addAttributeToFilter(
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
        )->addAttributeToSort('news_from_date', 'desc');

        return $collection;
    }


    /**
     * @param $collection
     * @return mixed
     */
    public function getRandom($collection) {

        $collection->getSelect()->order('rand()');
        return $collection;

    }

    /**
     * @param $collection
     * @return mixed
     */
    public function getSale($collection){

        $todayStartOfDayDate = $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
        $todayEndOfDayDate = $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
        $collection = $collection->addStoreFilter()->addAttributeToFilter(
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
        )->addAttributeToSort('special_to_date', 'desc');

        return $collection;

    }

}

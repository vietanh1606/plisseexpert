<?php

namespace Vicomage\Megamenu\Block;

class Megamenu extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry;
    protected $itemsFactory;
    protected $itemCollectionFactory;
    protected $groupFactory;
    protected $groupCollectionFactory;
    protected $categoryRepository;
    protected $categoryFactory;
    protected $categoryCollectionFactory;
    protected $_filterProvider;
    protected $_registry;


    /**
     * TopMegamenu constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Vicomage\Megamenu\Model\ItemsFactory $itemsFactory
     * @param \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $itemCollectionFactory
     * @param \Vicomage\Megamenu\Model\GroupFactory $groupFactory
     * @param \Vicomage\Megamenu\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Vicomage\Megamenu\Model\ItemsFactory $itemsFactory,
        \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $itemCollectionFactory,
        \Vicomage\Megamenu\Model\GroupFactory $groupFactory,
        \Vicomage\Megamenu\Model\ResourceModel\Group\CollectionFactory $groupCollectionFactory,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->itemsFactory = $itemsFactory;
        $this->categoryFactory = $categoryFactory;
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->groupFactory = $groupFactory;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->categoryRepository = $categoryRepository;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getMenuTypeGroup($id)
    {
        $menuType = array(
            1 => 'fullwidth',
            2 => 'staticwidth',
            3 => 'dropdown',
        );

        return $menuType[$id];
    }


    /**
     * @param $id
     * @return mixed
     */
    public function getMenuEffertItem($id)
    {
        $menuEffert = array(
            1 => 'default',
            2 => 'fullwidth',
            3 => 'staticwidth',
            4 => 'dropdown',
        );

        return $menuEffert[$id];
    }


    /**
     * get current group by id
     * @return $this|null
     */
    public function getGroupById()
    {
        $groupConfig = $this->getConfig();
        if ($groupConfig['enabled'] && $groupConfig['group'] && $this->enableMenu()) {
            $groupCollection = $this->groupCollectionFactory->create()
                ->addFieldToFilter('group_id',array('eq' => $groupConfig['group']))
                ->addFieldToFilter('status',array('eq' => 1))->getData();
            $groupCollection[0]['categorys'] = $this->getChildCategoryInCurrentRootCategory($groupCollection[0]['categorys']);

            if (!empty($groupCollection)) {
                return $groupCollection[0];
            }
        }
        return false;
    }

    /**
     * @param $selectedCategoryId
     * @return string
     */
    public function getChildCategoryInCurrentRootCategory($selectedCategoryId)
    {
        $categoryId = array();
        $selectedCategoryId = explode(',',$selectedCategoryId);
        $rootCatId = $this->_storeManager->getStore()->getRootCategoryId();
        $collection = $this->categoryFactory->create()->getCollection()
            ->addAttributeToSelect(array('entity_id'))
            ->addAttributeToFilter('parent_id', $rootCatId)
            ->addAttributeToFilter('include_in_menu', 1)
            ->addIsActiveFilter()
            ->setOrder('position', 'ASC');

        foreach($collection as $subCategory){
            if(in_array($subCategory->getEntityId(),$selectedCategoryId)){
                $categoryId[] = $subCategory->getEntityId();
            }
        }
        return implode(',',$categoryId);
    }


    /**
     * get all items in group
     * @return $this
     */
    public function getItems()
    {
        $groupData = $this->getGroupById();
        if ($groupData) {
            if ($groupData['items'] != null) {
                $itemCollection = $this->itemCollectionFactory->create()
                    ->addFieldToFilter('item_id', array('in' => $groupData['items']))
                    ->addFieldToFilter('status', array('eq' => 1))
                    ->setOrder('position','ASC');
                if ($itemCollection) {
                    return $itemCollection;
                }
                return false;
            }
        }
        return false;
    }


    /**
     * get config enable menu filter
     * @return mixed
     */
    public function enableMenu()
    {
        return $this->_scopeConfig->getValue('vicomage_megamenu_setting/general/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * check category had sub category
     * @param $categoryId
     * @return bool
     */
    public function hasSubcategory($categoryId)
    {
        if ($categoryId) {
            $childCategorys = $this->getChildrenCategoryById($categoryId);
            if ($childCategorys != null) {
                return true;
            }
        }
        return false;
    }

    /**
     * function get category by Id
     * @param $categoryId
     * @return bool|\Magento\Catalog\Api\Data\CategoryInterface
     */
    public function getCategoryById($categoryId)
    {
        if ($categoryId) {

            $categoryCollection = $this->categoryRepository->get($categoryId, $this->_storeManager->getStore()->getId());
            if ($categoryCollection) {

                if ($categoryCollection->getIsActive() == 1 & $categoryCollection->getIncludeInMenu() == 1) {

                    return $categoryCollection;
                }
            }
        }
        return false;
    }

    /**
     * function get children category by id
     * @param $categoryId
     * @return bool|null|string
     */
    public function getChildrenCategoryById($categoryId)
    {
        if ($categoryId) {

            $childCategorys = $this->categoryRepository->get($categoryId,$this->_storeManager->getStore()->getId())->getChildren();

            if ($childCategorys) {
                $categoryCollectionFactory = $this->categoryCollectionFactory->create();
                $categoryCollectionFactory
                    ->addAttributeToFilter('entity_id',array('in' => $childCategorys))
                    ->setOrder('position', 'ASC');
                $categoryIds = [];
                foreach($categoryCollectionFactory as $category) {
                    $categoryIds[] = $category['entity_id'];
                }

                return implode(',' ,$categoryIds);
            }
        }
        return false;
    }

    /**
     * check if sub of category is current
     * @param $categoryId
     * @return bool
     */
    public function checkSubCategoryIsCurrent($categoryId)
    {
        $childCategorys = $this->getChildrenCategoryById($categoryId);
        $currentCategoryId = $this->_registry->registry('current_category')->getId();

        if ($categoryId == $currentCategoryId) {
            return true;
        }
        if ($childCategorys != null) {
            foreach (explode(',', $childCategorys) as $subCategoryId) {
                if ($currentCategoryId == $subCategoryId) {
                    return true;
                } else {
                    if ($this->checkSubCategoryIsCurrent($subCategoryId)) {
                        return true;
                    }
                }
            }
        }
    }


    /**
     * @param $categoryId
     * @return string
     */
    public function checkCurrentCategory($categoryId)
    {
        $category = $this->_registry->registry('current_category');
        $result = null;

        if (isset($category)) {
            if ($category->getId() == $categoryId) {
                return 'current';
            } else {
                if ($this->checkSubCategoryIsCurrent($categoryId)) {
                    return 'current';
                }
                return $result;
            }
        }
        return $result;
    }

}
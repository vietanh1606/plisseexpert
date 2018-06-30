<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Related products admin grid
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Vicomage\Megamenu\Block\Adminhtml\Edit\Tab;

class Category extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{

    /**
     * @var
     */
    protected $_ids;

    /**
     * @var \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Catalog\Model\Config\Source\Category
     */
    protected $_category;

    /**
     * @var
     */
    protected $_request;

    /**
     * @var
     */
    protected $_storeManager;

    /**
     * @var array
     */
    protected  $_options = array();


    /**
     * Category constructor.
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\Config\Source\Category $category
     * @param \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $itemCollectionFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Config\Source\Category $category,
        \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $itemCollectionFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    )
    {
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_category = $category;
        parent::__construct($context, $registry, $formFactory, $data);
    }


    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Category');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Category');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }


    protected function _prepareForm()
    {

        $model = $this->_coreRegistry->registry('megamenu_megamenu');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('megamenu_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Select Category')]);

        $data = $model->getData();

        $fieldset->addField('category_id', 'select',
            [
                'label' => __('Category'),
                'title' => __('Category'),
                'name'  => 'category_id',
                'values' => $this->getCategorys()
            ]
        );

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }


    /**
     * get category
     * @return array
     */
    public function getCategorys() {

        if(!$this->_options){
            $options = array();
            $categories = $this->getRootCategory();
            $categoryIdAddedArray = $this->getItemsHasCategory();
            //loop all root category
            foreach ($categories as $category) {
                if($category['value']) {
                    $rootOption = array('label' => $category['label']);
                    $_categories = $this->_categoryFactory->create()->getCategories($category['value']);
                    $childOptions = array();

                    //loop child category
                    foreach ($_categories as $_category) {

                        if(isset($categoryIdAddedArray)) {
                            if (in_array($_category->getId(), $categoryIdAddedArray) == false) {
                                $childOptions[] = array(
                                    'label' => $_category->getName(),
                                    'value' => $_category->getId()
                                );
                            }
                        }else{
                            $childOptions[] = array(
                                'label' => $_category->getName(),
                                'value' => $_category->getId()
                            );
                        }
                    }
                    $rootOption['value'] = $childOptions;
                    $options[] = $rootOption;
                }
            }
            $this->_options = $options;
        }
        return $this->_options;
    }


    /**
     * @return array
     */
    public function getRootCategory(){

        $store = $this->_request->getParam('store');
        if(!$store) {

            $categories = $this->_category->toOptionArray();
        } else {
            $rootCategoryId = $this->_storeManager->getStore($store)->getRootCategoryId();
            $label = $this->_categoryFactory->create()->load($rootCategoryId)->getName();
            $categories = array(array('value' => $rootCategoryId, 'label' => $label));
        }
        return $categories;
    }


    /**
     * get category added
     * @return array
     */
    public function getItemsHasCategory()
    {
        $currentItemId = $this->_request->getParam('id');
        $items = $this->itemCollectionFactory->create()
            ->addFieldToSelect('category_id')
            ->addFieldToFilter('menu_type', array('eq' => 1))
            ->addFieldToFilter('item_id', array('neq' => $currentItemId));
        $categoryId = [];
        foreach ($items as $item) {
            $categoryId[] = $item->getData('category_id');
        }
        return $categoryId;
    }

    /**
     * @return mixed
     */
    public function getIds()
    {
        return $this->_ids;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml\Editgroup\Tab;

/**
 * Sitemap edit form
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;


    protected $_wysiwygConfig;

    /**
     * @var \Magento\Catalog\Model\Config\Source\Category
     */
    protected $_category;
    /**
     * @var array
     */

    protected  $_options = array();

    /**
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory
     */
    protected $itemCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Vicomage\Megamenu\Model\ResourceModel\Items\CollectionFactory $itemCollectionFactory,
        \Magento\Catalog\Model\Config\Source\Category $category,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->itemCollectionFactory = $itemCollectionFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_category = $category;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getStore()
    {
        return $this->_storeManager->getStore();
    }

    public function getModel($model)
    {
        return $this->_objectManager->create($model);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('megamenu_group');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('megamenu_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Group')]);

        $data = $model->getData();

        if ($model->getId()) {
            $fieldset->addField('group_id', 'hidden', ['name' => 'group_id']);
        }


        $fieldset->addField(
            'title',
            'text',
            [
                'label' => __('Title'),
                'name' => 'title',
                'required' => true,
            ]
        );

        $fieldset->addField(
            'menu_type',
            'select',
            [
                'label' => __('Menu Type'),
                'name' => 'menu_type',
                'required' => false,
                'options' => [
                    '1' => __('Full Width'),
                    '2' => __('Static Width'),
                    '3' => __('Dropdown')
                ]
            ]
        );


        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'name' => 'status',
                'required' => false,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );


        $field = $fieldset->addField(
            'categorys',
            'multiselect',
            [
                'name' => 'categorys',
                'label' => __('Select Category'),
                'title' => __('Select Category'),
                'values' => $this->getCategorys(),
                'required' => false,
            ]
        );

        $renderer = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
        );
        $field->setRenderer($renderer);

        $field = $fieldset->addField(
            'items',
            'multiselect',
            [
                'name' => 'items',
                'label' => __('Select Items'),
                'title' => __('Select Items'),
                'required' => false,
                'values' => $this->getItems(),
            ]
        );
        $renderer = $this->getLayout()->createBlock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
        );
        $field->setRenderer($renderer);

        $form->setValues($data);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Group');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Group');
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


    /**
     * get category
     * @return array
     */
    public function getCategorys() {

        if(!$this->_options){
            $options = array();
            $categories = $this->getRootCategory();
            //loop all root category
            foreach ($categories as $category) {
                if($category['value']) {
                    $rootOption = array('label' => $category['label']);
                    $_categories = $this->_categoryFactory->create()->getCategories($category['value']);
                    $childOptions = array();

                    //loop child category
                    foreach ($_categories as $_category) {
                        $childOptions[] = array(
                            'label' => $_category->getName(),
                            'value' => $_category->getId()
                        );
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
     * get item
     * @return array
     */
    public function getItems(){
        $items = $this->itemCollectionFactory->create()
            ->addFieldToFilter('menu_type',array('eq' => 2))
            ->addFieldToFilter('status',array('eq' => 1));
        $options = [];
        foreach($items as $item){
            $options[] = array(
                'label' => $item->getLabel(),
                'value' =>  $item->getId()
            );
        }
        return $options;
    }
}

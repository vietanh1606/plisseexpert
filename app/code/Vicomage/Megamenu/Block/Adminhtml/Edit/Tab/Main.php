<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml\Edit\Tab;

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
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('megamenu_megamenu');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('megamenu_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General Information')]);

        $data = $model->getData();

        if ($model->getId()) {
            $fieldset->addField('item_id', 'hidden', ['name' => 'item_id']);
        }


        $fieldset->addField(
            'menu_type',
            'select',
            [
                'label' => __('Menu Type'),
                'name' => 'menu_type',
                'required' => false,
                'options' => [
                    '1' => __('Category'),
                    '2' => __('Static Link'),
                ]
            ]
        );

        $fieldset->addField(
            'menu_ef',
            'select',
            [
                'label' => __('Menu Effert'),
                'name' => 'menu_ef',
                'required' => false,
                'options' => [
                    '1' => __('Default'),
                    '2' => __('Full Width'),
                    '3' => __('Static Width'),
                    '4' => __('Dropdown'),
                ]
            ]
        );

        $fieldset->addField(
            'label',
            'text',
            [
                'label' => __('Custom Category Name'),
                'name' => 'label',
                'required' => false,
                'class' => 'mega_category_name'
            ]
        );

        $fieldset->addField(
            'url',
            'text',
            [
                'label' => __('Custom Link'),
                'name' => 'url',
            ]
        );


        $fieldset->addField(
            'static_width',
            'text',
            [
                'label' => __('Static Width'),
                'name' => 'static_width',
            ]
        );


        $fieldset->addField(
            'category_label',
            'text',
            [
                'label' => __('Category Label'),
                'name' => 'category_label',
            ]
        );


        $fieldset->addField(
            'position',
            'text',
            [
                'label' => __('Position'),
                'name' => 'position',
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'columns',
            'select',
            [
                'label' => __('Category Columns'),
                'name' => 'columns',
                'required' => false,
                'options' => [
                    '1' => __('1 Column'),
                    '2' => __('2 Columns'),
                    '3' => __('3 Columns'),
                    '4' => __('4 Columns'),
                    '5' => __('5 Columns'),
                    '6' => __('6 Columns'),
                    '7' => __('7 Columns'),
                    '8' => __('8 Columns'),
                    '9' => __('9 Columns'),
                    '10' => __('10 Columns'),
                    '11' => __('11 Columns'),
                    '12' => __('12 Columns'),
                ]
            ]
        );


        $fieldset->addField(
            'subcategory_columns',
            'select',
            [
                'label' => __('Subcategory Columns'),
                'name' => 'subcategory_columns',
                'required' => false,
                'options' => [
                    '1' => __('1 Column'),
                    '2' => __('2 Columns'),
                    '3' => __('3 Columns'),
                    '4' => __('4 Columns'),
                    '5' => __('5 Columns'),
                    '6' => __('6 Columns'),
                    '7' => __('7 Columns'),
                    '8' => __('8 Columns'),
                    '9' => __('9 Columns'),
                    '10' => __('10 Columns'),
                    '11' => __('11 Columns'),
                    '12' => __('12 Columns'),
                ]
            ]
        );

        $fieldset->addField(
            'custom_class',
            'text',
            [
                'label' => __('Custom Class'),
                'name' => 'custom_class',
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


        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store',
                'multiselect',
                [
                    'name' => 'store[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store',
                'hidden',
                [
                    'name' => 'store[]',
                    'value' => $this->_storeManager->getStore(true)->getId()
                ]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

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
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Information');
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
}

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
class Statics extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('megamenu_megamenu');

        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('megamenu_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Static Contents')]);

        $fieldset->addField(
            'top_content',
            'editor',
            [
                'name' => 'top_content',
                'label' => __('Top Content'),
                'title' => __('Top Content'),
                'style' => 'height:12em',
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'bottom_content',
            'editor',
            [
                'name' => 'bottom_content',
                'label' => __('Bottom Content'),
                'title' => __('Bottom Content'),
                'style' => 'height:12em',
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'left_content',
            'editor',
            [
                'name' => 'left_content',
                'label' => __('Left Content'),
                'title' => __('Left Content'),
                'style' => 'height:12em',
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'left_col',
            'select',
            [
                'label' => __('Left Columns'),
                'name' => 'left_col',
                'options' => [
                    '0' => __('Disabled'),
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
            'right_content',
            'editor',
            [
                'name' => 'right_content',
                'label' => __('Right Content'),
                'title' => __('Right Content'),
                'style' => 'height:12em',
                'config' => $this->_wysiwygConfig->getConfig()
            ]
        );

        $fieldset->addField(
            'right_col',
            'select',
            [
                'label' => __('Right Columns'),
                'name' => 'right_col',
                'options' => [
                    '0' => __('Disabled'),
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

        $form->setValues($model->getData());
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
        return __('Static Contents');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Static Contents');
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

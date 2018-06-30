<?php
/**
 * Copyright � 2016 Vicomage. All rights reserved.
 */


namespace Vicomage\Slider\Block\Adminhtml\Items\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;



class Image extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Slider Setting ');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Slideshow');
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
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('current_vicomage_slider_items');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('item_');
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Slider')]);
		$renderer = $this->getLayout()->createBlock('Vicomage\Slider\Block\Adminhtml\Form\Renderer\Customfield');
		if($model->getId())
		{
			$renderer->setId($model->getId());
		}
		$fieldset->setRenderer($renderer);
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}

<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */


namespace Vicomage\Slider\Block\Adminhtml\Items\Edit\Tab;


use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Vicomage\Slider\Model\Select;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;


class Main extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
	 
	protected $_select;
	
	
	public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Select $_select,
        array $data = []
    ) {
        $this->_select = $_select;
        parent::__construct($context, $registry, $formFactory, $data);
    }	
	
    public function getTabLabel()
    {
        return __('General Setting');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('General');
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
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Title'), 'title' => __('Title'), 'required' => true]
        );

        $fieldset->addField(
            'identity',
            'text',
            ['name' => 'identity', 'label' => __('ID'), 'title' => __('ID'), 'required' => true]
        );

		$fieldset->addField(
			'status',
			'select',
			[
				'label' => __('Status'),
				'title' => __('Slideshow Status'),
				'name' => 'status',
				'options' => $this->_select->getAvailableStatuses(),
			]
		);
		$fieldset->addField(
            'width',
            'text',
            ['name' => 'width', 'label' => __('Width'), 'title' => __('Width'), 'required' => false]
        );
		$fieldset->addField(
            'height',
            'text',
            ['name' => 'height', 'label' => __('Height'), 'title' => __('Height'), 'required' => false]
        );
		 $fieldset->addField(
            'navigation',
            'select',
            [
                'label' => __('Navigation'),
                'title' => __('Navigation'),
                'name' => 'navigation',
                'options' => $this->getAvailableTrueFalse(),
            ]
        );
		 $fieldset->addField(
            'pagercontrol',
            'select',
            [
                'label' => __('Show Pager control'),
                'title' => __('Show Pager control'),
                'name' => 'pagercontrol',
                'options' => $this->getAvailableTrueFalse(),
            ]
        );
		$fieldset->addField(
            'lazyload',
            'select',
            [
                'label' => __('lazyLoad'),
                'title' => __('lazyLoad'),
                'name' => 'lazyload',
                'options' => $this->getAvailableLazyLoad(),
            ]
        );
		 $fieldset->addField(
            'speed',
            'text',
            ['name' => 'speed', 'label' => __('Transition speed'), 'title' => __('Transition speed'), 'required' => false]
        );
        $fieldset->addField(
            'auto_play',
            'select',
            [
                'label' => __('Auto Play'),
                'title' => __('Auto Play'),
                'name' => 'auto_play',
                'options' => $this->getAvailableTrueFalse(),
            ]
        );
		$fieldset->addField(
            'autoplay_speed',
            'text',
            ['name' => 'autoplay_speed', 'label' => __('Autoplay Speed'), 'title' => __('Autoplay Speed'), 'required' => false]
        );
		 $fieldset->addField(
            'stop_on_hover',
            'select',
            [
                'label' => __('Stop On Hover'),
                'title' => __('Stop On Hover'),
                'name' => 'stop_on_hover',
                'options' => $this->getAvailableTrueFalse(),
            ]
        );
        $fieldset->addField(
            'loop',
            'select',
            [
                'label' => __('Loop'),
                'title' => __('Loop'),
                'name' => 'loop',
                'options' => $this->getAvailableTrueFalse(),
            ]
        );
	
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getAvailableTrueFalse(){
        return array( 1 => __('True'), 0 => __('False'));
    }
	public function getAvailableLazyLoad(){
        return array( 1 => __('progressive'), 0 => __('ondemand'));
    }
}

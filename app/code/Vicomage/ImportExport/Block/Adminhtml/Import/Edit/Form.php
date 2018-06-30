<?php

namespace Vicomage\ImportExport\Block\Adminhtml\Import\Edit;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Magento\Store\Model\System\Store;
use Vicomage\ImportExport\Model\Import\Tool;

class Form extends Generic
{
    /**
     * @var Store
     */
    protected $_systemStore;



    /**
     * @var Tool
     */
    protected $_tool;

    /**
     * Form constructor.
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Store $systemStore
     * @param Tool $tool
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Store $systemStore,
        Tool $tool,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->_systemStore = $systemStore;
        $this->_tool = $tool;
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {



        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post',
                    'enctype' => 'multipart/form-data'
                ]
            ]
        );

        $fieldSet = $form->addFieldset('base_fieldset', ['legend' => __('Select Theme')]);

        $fieldSet->addField('theme_template', 'label',
            [
                'label' => __(''),
                'title' => __('Overwrite Existing Pages'),
                'name' => 'theme_template',
                'after_element_html' => $this->getTemplateHtml(),
            ]
        );

        $fieldSet->addField(
            'theme_path',
            'select',
            [
                'name' => 'theme_path',
                'label' => __('Theme'),
                'title' => __('Theme'),
                'required' => true,
                'values' => $this->_tool->toOptionArray()
            ]
        );

        $scope = $this->getRequest()->getParam('store');

        if($scope){
            $scopeId = $this->_storeManager->getStore($scope)->getId();
            $fieldSet->addField('scope', 'hidden', array(
                'label'     => __('Scope'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'scope',
                'value'     => 'stores',
            ));
            $fieldSet->addField('scope_id', 'hidden', array(
                'label'     => __('Scope Id'),
                'class'     => 'required-entry',
                'required'  => true,
                'name'      => 'scope_id',
                'value'     => $scopeId,
            ));
        }else {
            $scope   = $this->getRequest()->getParam('website');
            if($scope){
                $scopeId = $this->_storeManager->getWebsite($scope)->getId();
                $fieldSet->addField('scope', 'hidden', array(
                    'label'     => __('Scope'),
                    'class'     => 'required-entry',
                    'required'  => true,
                    'name'      => 'scope',
                    'value'     => 'websites',
                ));
                $fieldSet->addField('scope_id', 'hidden', array(
                    'label'     => __('Scope Id'),
                    'class'     => 'required-entry',
                    'required'  => true,
                    'name'      => 'scope_id',
                    'value'     => $scopeId,
                ));
            }

        }

        $fieldSet->addField('config', 'checkbox',
            [
                'label' => __('System Config'),
                'title' => __('System Config'),
                'name' => 'config',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> STORES > Configuration</small>',
            ]
        );

        $fieldSet->addField('page', 'checkbox',
            [
                'label' => __('Pages'),
                'title' => __('Pages'),
                'name' => 'page',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> CONTENT > Pages</small>',
            ]
        );

        $fieldSet->addField('overwrite_page', 'checkbox',
            [
                'label' => __(''),
                'title' => __('Overwrite Existing Pages'),
                'name' => 'overwrite_page',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Overwrite Existing Pages</small>',
            ]
        );

        $fieldSet->addField('block', 'checkbox',
            [
                'label' => __('Blocks'),
                'title' => __('Blocks'),
                'name' => 'block',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> CONTENT > Blocks</small>',
            ]
        );


        $fieldSet->addField('overwrite_block', 'checkbox',
            [
                'label' => __(''),
                'title' => __('Overwrite Existing Blocks'),
                'name' => 'overwrite_block',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Overwrite Existing Blocks</small>',
            ]
        );


        $fieldSet->addField('menu', 'checkbox',
            [
                'label' => __('Menu'),
                'title' => __('Menu'),
                'name' => 'menu',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Vicomage > Mega Menu Items</small>',
            ]
        );


        $fieldSet->addField('overwrite_menu', 'checkbox',
            [
                'label' => __(''),
                'title' => __('Overwrite Existing Menus'),
                'name' => 'overwrite_menu',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small>Overwrite Existing Menus</small>',
            ]
        );


        $fieldSet->addField('slider', 'checkbox',
            [
                'label' => __('Slider'),
                'title' => __('Slider'),
                'name' => 'slider',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Vicomage > Slideshow</small>',
            ]
        );

        $fieldSet->addField('overwrite_slider', 'checkbox',
            [
                'label' => __(''),
                'title' => __('Overwrite Existing Slider'),
                'name' => 'overwrite_slider',
                'value' => 1,
                'checked' => 'checked',
                'after_element_html' => '<small> Overwrite Existing Slider</small>',
            ]
        );

        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
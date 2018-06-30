<?php

namespace Vicomage\ImportExport\Block\Adminhtml\Export\Edit;

use Magento\Theme\Model\Theme\Collection;
use Magento\Framework\App\Area;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context                    $context       
     * @param \Magento\Framework\Registry                                $registry      
     * @param \Magento\Framework\Data\FormFactory                        $formFactory   
     * @param \Magento\Config\Model\Config\Source\Yesno                  $yesno               
     * @param \Magento\Store\Model\System\Store                          $systemStore   
     * @param array                                                      $data          
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->_systemStore = $systemStore;
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('System Export')]);

        $themesCollections = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Theme\Model\Theme\Collection');
        $themesCollections->addConstraint(Collection::CONSTRAINT_AREA, Area::AREA_FRONTEND);
        $themes = [];
        foreach ($themesCollections as $value) {
            $themes[$value->getData('theme_path')] = $value->getData('theme_title');
        }

        $fieldset->addField('modules', 'multiselect', [
            'name'		=> 'modules',
            'label'		=> __('Select Configuration to Export'),
            'title'		=> __('Select Configuration to Export'),
            'values'	=> \Magento\Framework\App\ObjectManager::getInstance()->create('Vicomage\ImportExport\Model\Export\Source\Packagemodules')
                ->toOptionArray(),
            'required'	=> true,
        ]);

        $fieldset->addField(
            'theme_path',
            'select',
            [
                'name' => 'theme_path',
                'label' => __('Theme'),
                'title' => __('Theme'),
                'required' => true,
                'values' => $themes
            ]
        );

        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store',
                'select',
                [
                    'name' => 'store',
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
                ['name' => 'stores', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
        }


        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}


<?php
namespace Vicomage\Testimonial\Block\Adminhtml\Edit;

/**
 * Sitemap edit form
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
	/**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
	
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

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
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('testimonial__form');
        $this->setTitle(__('Testimonial Information'));
    }

    /**
     * @return $this
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('testimonial_testimonial');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $fieldset = $form->addFieldset('add_testimonial_form', ['legend' => __('Testimonial')]);

        if ($model->getId()) {
            $fieldset->addField('testimonial_id', 'hidden', ['name' => 'testimonial_id']);
        }

        $fieldset->addField(
            'name',
            'text',
            [
                'label' => __('Name'),
                'name' => 'name',
                'required' => true,
            ]
        );


        $fieldset->addField(
            'avatar',
            'image',
            [
                'name' => 'avatar',
                'label' => __('avatar'),
                'title' => __('avatar'),
                'class' => 'required-entry',
                'required' => true,
                'note'      => '(*.jpg, .png, .gif)'
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
		
		$fieldset->addField(
            'content',
            'textarea',
            [
                'name' => 'content',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:15em',
                'required' => true
            ]
        );


        $fieldset->addField('company', 'text',
            [
                'label' => __('Company'),
                'title' => __('Company'),
                'name'  => 'company',
                'required' => true,
            ]
        );

        $fieldset->addField('email', 'text',
            [
                'label' => __('Email'),
                'title' => __('Email'),
                'name'  => 'email',
                'required' => true,
            ]
        );

        $fieldset->addField('website', 'text',
            [
                'label' => __('Website'),
                'title' => __('Website'),
                'name'  => 'website',
                'required' => true,
            ]
        );


        $summary = $this->getLayout()->createBlock('Vicomage\Testimonial\Block\Adminhtml\Helper\Renderer\Form\Summary');
        $fieldset->addField('detailed_rating', 'note', array(
            'label'     => __('Detailed Rating'),
            'text'      => $summary->detailedHtml(),
        ));

        /* Check is single store mode */
        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
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
                'stores',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField('order', 'text',
            [
                'label' => __('Order'),
                'title' => __('Order'),
                'name'  => 'order',
            ]
        );
        

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}

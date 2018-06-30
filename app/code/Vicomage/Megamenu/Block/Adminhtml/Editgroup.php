<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml;

/**
 * Sitemap edit form container
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Editgroup extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * Init container
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml';
        $this->_blockGroup = 'Vicomage_Megamenu';

        parent::_construct();


        $this->buttonList->update('save', 'label', __('Save Group'));
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );
    }

    /**
     * Get edit form container header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        if ($this->_coreRegistry->registry('megamenu_group')->getId()) {
            return __('Edit %1', $this->_coreRegistry->registry('megamenu_group')->getTitle());
        } else {
            return __('New Group');
        }
    }

    /**
     * Build child form class name
     *
     * @return string
     */
    protected function _buildFormClassName()
    {
        return $this->nameBuilder->buildClassName(
            [$this->_blockGroup, 'Block', $this->_controller, $this->_mode, 'Mainform']
        );
    }

    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('manufacturer/*/save', ['_current' => true, 'back' => 'edit', 'active_tab' => '']);
    }
}

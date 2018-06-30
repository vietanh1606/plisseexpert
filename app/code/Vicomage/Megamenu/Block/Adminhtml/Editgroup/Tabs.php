<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml\Editgroup;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('megamenu_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Megamenu Group'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'main_section',
            [
                'label' => __('General Group'),
                'content' => $this->getLayout()->createBlock('Vicomage\Megamenu\Block\Adminhtml\Editgroup\Tab\Main')->toHtml(),
            ]
        );

        return parent::_beforeToHtml();
    }
}

<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml;

class Megamenu extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_mmegamenu';
        $this->_blockGroup = 'Vicomage_Megamenu';
        $this->_headerText = __('Megamenu');
        $this->_addButtonLabel = __('Add Item');
        parent::_construct();
    }
}

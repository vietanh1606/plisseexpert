<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vicomage\Megamenu\Block\Adminhtml;

class Group extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_megamenu';
        $this->_blockGroup = 'Vicomage_Megamenu';
        $this->_headerText = __('megamenu');
        $this->_addButtonLabel = __('Add Menu');
        parent::_construct();
    }

    /**
     * @return string
     */
    public function getCreateUrl()
    {
        return $this->getUrl('*/*/new');
    }
}

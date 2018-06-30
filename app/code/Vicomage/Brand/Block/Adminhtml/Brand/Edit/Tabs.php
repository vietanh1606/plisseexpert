<?php


namespace Vicomage\Brand\Block\Adminhtml\Brand\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * construct.
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('shopbrand_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Brand Information'));
    }

}

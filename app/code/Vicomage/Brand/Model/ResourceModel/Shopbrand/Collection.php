<?php

namespace Vicomage\Brand\Model\ResourceModel\Shopbrand;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    protected function _construct()
    {
        $this->_init('Vicomage\Brand\Model\Shopbrand', 'Vicomage\Brand\Model\ResourceModel\Shopbrand');
    }
}

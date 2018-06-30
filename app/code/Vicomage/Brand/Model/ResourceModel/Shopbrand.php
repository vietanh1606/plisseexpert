<?php

namespace Vicomage\Brand\Model\ResourceModel;

class Shopbrand extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('vicomage_brand', 'shopbrand_id');
    }
}

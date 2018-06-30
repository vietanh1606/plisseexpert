<?php
/**
 * Copyright © 2016 Vicomage. All rights reserved.
 */

namespace Vicomage\Slider\Model\Resource\Items;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vicomage\Slider\Model\Items', 'Vicomage\Slider\Model\Resource\Items');
    }
}

<?php

namespace Vicomage\Testimonial\Model;

class Testimonial extends \Magento\Framework\Model\AbstractModel
{
   
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Vicomage\Testimonial\Model\ResourceModel\Testimonial');
    }
}

<?php

namespace Vicomage\Core\Model\System\Config;

class Col implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {

        return array(
            array('value' => 1,				'label' => __('1 item per column')),
            array('value' => 2,				'label' => __('2 item per column')),
            array('value' => 3,				'label' => __('3 item per column')),
            array('value' => 4,				'label' => __('4 item per column')),
            array('value' => 5,				'label' => __('5 item per column')),
            array('value' => 6,				'label' => __('6 item per column')),
            array('value' => 7,				'label' => __('7 item per column')),
            array('value' => 8,				'label' => __('8 item per column')),
        );
    }
}

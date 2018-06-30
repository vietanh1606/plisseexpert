<?php

namespace Vicomage\Core\Model\System\Config;

class Row implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            '1'=>   __('1 item per row'),
            '2'=>   __('2 item per row'),
            '3'=>   __('3 item per row'),
            '4'=>   __('4 item per row'),
            '5'=>   __('5 item per row'),
        ];
    }
}

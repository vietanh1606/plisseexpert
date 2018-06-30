<?php
namespace Vicomage\Multiwidget\Model\Widget;

class Config implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('1 item per column')],
            ['value' => 2, 'label' => __('2 items per column')],
            ['value' => 3, 'label' => __('3 items per column')],
            ['value' => 4, 'label' => __('4 items per column')],
            ['value' => 5, 'label' => __('5 items per column')],
            ['value' => 6, 'label' => __('6 items per column')],
            ['value' => 7, 'label' => __('7 items per column')],
            ['value' => 8, 'label' => __('8 items per column')],
        ];
    }
}

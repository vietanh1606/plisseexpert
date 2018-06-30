<?php
namespace Vicomage\General\Model\Config\Settings\Product;

class ListDetail implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1column', 'label' => __('1column')],
            ['value' => '2columns-left', 'label' => __('2columns-left')],
            ['value' => '2columns-right', 'label' => __('2columns-right')],
        ];
    }
}


<?php
namespace Vicomage\General\Model\Config\Settings\Product;

class ZoomType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'window', 'label' => __('Basic')],
            ['value' => 'inner', 'label' => __('Inner')],
            ['value' => 'lens', 'label' => __('Lens')],
        ];
    }
}


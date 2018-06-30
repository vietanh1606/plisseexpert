<?php
namespace Vicomage\General\Model\Config\Settings\Product;

class ZoomLensShape implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'round', 'label' => __('round')],
            ['value' => 'square', 'label' => __('square')],
        ];
    }
}


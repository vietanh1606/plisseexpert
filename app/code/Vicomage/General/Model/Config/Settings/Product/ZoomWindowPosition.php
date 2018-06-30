<?php
namespace Vicomage\General\Model\Config\Settings\Product;

class ZoomWindowPosition implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Position 1')],
            ['value' => '2', 'label' => __('Position 2')],
            ['value' => '3', 'label' => __('Position 3')],
            ['value' => '4', 'label' => __('Position 4')],
            ['value' => '5', 'label' => __('Position 5')],
            ['value' => '6', 'label' => __('Position 6')],
            ['value' => '7', 'label' => __('Position 7')],
            ['value' => '8', 'label' => __('Position 8')],
            ['value' => '9', 'label' => __('Position 9')],
            ['value' => '10', 'label' => __('Position 10')],
            ['value' => '11', 'label' => __('Position 11')],
            ['value' => '12', 'label' => __('Position 12')],
            ['value' => '13', 'label' => __('Position 13')],
            ['value' => '14', 'label' => __('Position 14')],
            ['value' => '15', 'label' => __('Position 15')],
            ['value' => '16', 'label' => __('Position 16')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '1' => __('Position 1'),
            '2' => __('Position 2'),
            '3' => __('Position 3'),
            '4' => __('Position 4'),
            '5' => __('Position 5'),
            '6' => __('Position 6'),
            '7' => __('Position 7'),
            '8' => __('Position 8'),
            '9' => __('Position 9'),
            '10' => __('Position 10'),
            '11' => __('Position 11'),
            '12' => __('Position 12'),
            '13' => __('Position 13'),
            '14' => __('Position 14'),
            '15' => __('Position 15'),
            '16' => __('Position 16')
        ];
    }
}

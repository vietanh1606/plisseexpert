<?php
namespace Vicomage\General\Model\Config\Settings\Category;

class Columns implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('1 Columns')],
            ['value' => '2', 'label' => __('2 Columns')],
            ['value' => '3', 'label' => __('3 Columns')],
            ['value' => '4', 'label' => __('4 Columns')],
            ['value' => '5', 'label' => __('5 Columns')],
            ['value' => '6', 'label' => __('6 Columns')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '1' => __('1 Columns'),
            '2' => __('2 Columns'),
            '3' => __('3 Columns'),
            '4' => __('4 Columns'),
            '5' => __('5 Columns'),
            '6' => __('6 Columns')
        ];
    }
}

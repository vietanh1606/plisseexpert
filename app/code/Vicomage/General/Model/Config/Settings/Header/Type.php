<?php
namespace Vicomage\General\Model\Config\Settings\Header;

class Type implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1', 'label' => __('Type 1')],
            ['value' => '2', 'label' => __('Type 2')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '1' => __('Type 1'),
            '2' => __('Type 2')
        ];
    }
}

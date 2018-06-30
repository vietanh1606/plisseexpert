<?php
namespace Vicomage\General\Model\Config\Settings\General;

class Layout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1170', 'label' => __('1170px (Default)')],
            ['value' => '1280', 'label' => __('1280px')],
            ['value' => 'full_width', 'label' => __('Full Width')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '1170' => __('1170px (Default)'),
            '1280' => __('1280px'),
            'full_width' => __('Full Width')
        ];
    }
}

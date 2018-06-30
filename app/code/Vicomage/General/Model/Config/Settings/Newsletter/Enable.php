<?php
namespace Vicomage\General\Model\Config\Settings\Newsletter;

class Enable implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('Disable')],
            ['value' => '1', 'label' => __('Enable on Only Homepage')],
            ['value' => '2', 'label' => __('Enable on All Pages')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '0' => __('Disable'),
            '1' => __('Enable on Only Homepage'),
            '2' => __('Enable on All Pages')
        ];
    }
}

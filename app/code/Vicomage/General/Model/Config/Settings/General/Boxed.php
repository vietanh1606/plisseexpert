<?php
namespace Vicomage\General\Model\Config\Settings\General;

class Boxed implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [['value' => 'wide', 'label' => __('Wide (Default)')], ['value' => 'boxed', 'label' => __('Boxed')]];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return ['wide' => __('Wide (Default)'), 'boxed' => __('Boxed')];
    }
}

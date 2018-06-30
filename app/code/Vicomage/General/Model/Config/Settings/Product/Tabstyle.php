<?php
namespace Vicomage\General\Model\Config\Settings\Product;

class Tabstyle implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            ['value' => '', 'label' => __('Horizontal')],
            ['value' => 'vertical', 'label' => __('Vertical')],
            ['value' => 'accordion', 'label' => __('Accordion')]
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            '' => __('Horizontal'),
            'vertical' => __('Vertical'),
            'accordion' => __('Accordion')
        ];
    }
}

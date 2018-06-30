<?php
namespace Vicomage\Multiwidget\Model\Widget;

class Truefalse implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => true, 'label' => __('True')],
            ['value' => false, 'label' => __('False')],
        ];
    }
}

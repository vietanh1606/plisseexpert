<?php

namespace Vicomage\Quickview\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class TrueFalse
 *
 * @package Vicomage\Quickview\Model\Config\Source
 */
class TrueFalse implements ArrayInterface
{
    /**
     * Return list of TrueFalse Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'true',
                'label' => __('True')
            ),
            array(
                'value' => 'false',
                'label' => __('False')
            )
        );
    }
}